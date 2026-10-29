<?php

namespace Tests\Feature;

use App\Mail\VerificationCode;
use App\Models\ChatThread;
use App\Models\ContactMessage;
use App\Models\Post;
use App\Models\Tag;
use App\Models\User;
use Filament\Panel;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;
use Laravel\Socialite\Facades\Socialite;
use Laravel\Socialite\Two\InvalidStateException;
use Laravel\Socialite\Two\User as GoogleUser;
use Tests\TestCase;

class TravelistaTest extends TestCase
{
    use RefreshDatabase;

    public function test_public_pages_render_with_an_empty_database(): void
    {
        foreach (['/', '/index', '/about', '/auth_page', '/blog-home', '/contact', '/elements', '/hotels', '/insurance', '/packages'] as $page) {
            $this->get($page)->assertOk()->assertDontSee('{%', false)->assertDontSee('request.session', false);
        }

        $this->get('/blog-single')->assertRedirect('/blog-home');
        $this->get('/blog-single/999')->assertNotFound();
    }

    public function test_members_pages_require_login(): void
    {
        foreach (['/profile', '/setup_profile', '/connect', '/add-blog'] as $page) {
            $this->get($page)->assertRedirect('/auth_page');
        }

        $this->getJson('/api/messages/1')->assertUnauthorized();
    }

    public function test_members_pages_render_with_real_user_data(): void
    {
        $this->actingAs(User::factory()->create());

        foreach (['/profile', '/setup_profile', '/connect', '/add-blog'] as $page) {
            $this->get($page)->assertOk();
        }
    }

    public function test_signup_and_verification_create_an_authenticated_user_without_storing_plaintext_secrets(): void
    {
        Mail::fake();
        $this->signup()->assertRedirect('/verify');
        $pending = session('pending_registration');
        $this->assertTrue(Hash::check('password123', $pending['password']));
        $this->assertArrayNotHasKey('code', $pending);
        $this->assertDatabaseCount('users', 0);

        Mail::assertSent(VerificationCode::class, function ($mail) use (&$code) {
            $code = $mail->code;

            return $mail->hasTo('traveler@example.com');
        });

        $this->get('/verify')->assertOk();
        $this->post('/verify', ['code' => $code])->assertRedirect('/setup_profile')->assertSessionMissing('pending_registration');
        $this->assertAuthenticated();
        $this->assertNotNull(User::first()->email_verified_at);
        $this->assertTrue(Hash::check('password123', User::first()->password));
        $this->post('/verify', ['code' => $code])->assertRedirect('/');
        $this->assertDatabaseCount('users', 1);
    }

    public function test_verification_expires_and_cannot_be_replayed(): void
    {
        Mail::fake();
        $this->signup();
        $this->travel(11)->minutes();
        $this->post('/verify', ['code' => '123456'])->assertRedirect('/auth_page')->assertSessionMissing('pending_registration');
        $this->assertDatabaseCount('users', 0);
    }

    public function test_verification_locks_after_five_incorrect_attempts(): void
    {
        Mail::fake();
        $this->signup();

        for ($i = 0; $i < 5; $i++) {
            $this->from('/verify')->post('/verify', ['code' => '000000'])->assertSessionHasErrors('code');
        }

        $this->post('/verify', ['code' => '000000'])->assertRedirect('/auth_page')->assertSessionMissing('pending_registration');
        $this->assertDatabaseCount('users', 0);
    }

    public function test_signup_rejects_duplicate_email_and_invalid_form_type(): void
    {
        Mail::fake();
        User::factory()->create(['email' => 'traveler@example.com']);
        $this->signup()->assertSessionHasErrors('email');
        $this->post('/auth_page', ['form_type' => 'anything'])->assertSessionHasErrors('form_type');
        Mail::assertNothingSent();
    }

    public function test_login_uses_laravel_auth_and_logout_invalidates_the_session(): void
    {
        $user = User::factory()->create(['email' => 'traveler@example.com', 'password' => 'password123']);
        $this->post('/auth_page', ['form_type' => 'login', 'email' => 'TRAVELER@example.com', 'password' => 'password123'])->assertRedirect('/');
        $this->assertAuthenticatedAs($user);
        $this->get('/logout')->assertStatus(405);
        $this->post('/logout')->assertRedirect('/auth_page');
        $this->assertGuest();
        $this->get('/profile')->assertRedirect('/auth_page');
    }

    public function test_invalid_login_preserves_form_selection_without_flashing_password(): void
    {
        $this->from('/auth_page')->post('/auth_page', ['form_type' => 'login', 'email' => 'nobody@example.com', 'password' => 'secret123'])
            ->assertSessionHasErrors('email')->assertSessionHasInput('form_type', 'login')->assertSessionMissing('_old_input.password');
        $this->get('/auth_page')->assertOk()->assertSee('Invalid email or password.');
        $this->assertGuest();
    }

    public function test_profile_saves_first_name_and_stores_and_replaces_images(): void
    {
        Storage::fake('public');
        $user = User::factory()->create();
        $profile = ['first_name' => 'Leyla', 'last_name' => 'Aliyeva', 'age' => 25, 'location' => 'Baku', 'about_me' => 'Traveler'];
        $this->actingAs($user)->post('/setup_profile', [...$profile, 'profile_img' => UploadedFile::fake()->image('portrait.jpg')])->assertRedirect('/profile');
        $oldImage = $user->fresh()->profile_img;
        Storage::disk('public')->assertExists($oldImage);
        $this->get('/profile')->assertOk()->assertSee('Leyla Aliyeva');
        $this->post('/setup_profile', [...$profile, 'profile_img' => UploadedFile::fake()->image('new.png')])->assertRedirect('/profile');
        Storage::disk('public')->assertMissing($oldImage);
        Storage::disk('public')->assertExists($user->fresh()->profile_img);
    }

    public function test_profile_rejects_non_images_and_does_not_change_another_user(): void
    {
        $user = User::factory()->create();
        $other = User::factory()->create();
        $this->actingAs($user)->post('/setup_profile', [
            'user_id' => $other->id, 'first_name' => 'Changed', 'last_name' => 'Name', 'age' => 25, 'location' => 'Baku',
            'profile_img' => UploadedFile::fake()->create('malicious.php', 1, 'application/x-php'),
        ])->assertSessionHasErrors('profile_img');
        $this->assertNull($other->fresh()->first_name);
    }

    public function test_blog_publication_persists_tags_image_and_escaped_content(): void
    {
        Storage::fake('public');
        $user = User::factory()->create();
        $tag = Tag::create(['name' => 'Travel']);
        $this->actingAs($user)->post('/add-blog', [
            'title' => 'Baku trip', 'content' => '<script>alert(1)</script>', 'tags' => [$tag->id],
            'new_tag' => 'Azerbaijan', 'blog_img' => UploadedFile::fake()->image('baku.jpg'),
        ])->assertRedirect('/blog-single/1');
        $post = Post::first();
        $this->assertEquals($user->id, $post->user_id);
        $this->assertCount(2, $post->tags);
        Storage::disk('public')->assertExists($post->blog_img);
        $this->get('/blog-single/'.$post->id)->assertOk()->assertSee('&lt;script&gt;alert(1)&lt;/script&gt;', false)->assertDontSee('<script>alert(1)</script>', false);
        $this->assertEquals(1, $post->fresh()->view_count);
        $this->get('/')->assertOk()->assertSee('Baku trip');
        $this->get('/blog-home')->assertOk()->assertSee('Baku trip');
    }

    public function test_blog_search_tag_filters_and_pagination_work_together(): void
    {
        $user = User::factory()->create();
        $tag = Tag::create(['name' => 'Mountains']);

        for ($i = 0; $i < 7; $i++) {
            $post = $this->postFor($user, 'Mountain journey '.$i);
            $post->tags()->attach($tag);
        }

        $this->postFor($user, 'Beach holiday');
        $this->get('/blog-home?q=Mountain&tag='.$tag->id)->assertOk()->assertSee('page=2', false)->assertDontSee('Beach holiday</h3>', false);
        $this->get('/blog-home?q=Mountain&tag='.$tag->id.'&page=2')->assertOk();
        $this->get('/blog-home?q=NoMatch')->assertOk()->assertSee('No posts found.');
    }

    public function test_guest_comments_are_escaped_and_authenticated_identity_cannot_be_spoofed(): void
    {
        $author = User::factory()->create();
        $post = $this->postFor($author);
        $this->post('/blog-single/'.$post->id, ['name' => 'Guest', 'email' => 'guest@example.com', 'message' => '<script>bad()</script>'])->assertRedirect('/blog-single/'.$post->id.'#comments-section');
        $this->get('/blog-single/'.$post->id)->assertOk()->assertDontSee('<script>bad()</script>', false);
        $this->actingAs($author)->post('/blog-single/'.$post->id, ['name' => 'Impersonator', 'email' => 'fake@example.com', 'message' => 'Hello'])->assertRedirect();
        $this->assertDatabaseHas('comments', ['user_id' => $author->id, 'email' => $author->email, 'message' => 'Hello']);
        $this->get('/profile')->assertOk()->assertSee('Hello');
    }

    public function test_contact_messages_are_saved_but_only_approved_testimonials_are_public(): void
    {
        $data = ['name' => 'Leyla', 'surname' => 'Aliyeva', 'email' => 'leyla@example.com', 'subject' => 'Feedback', 'stars' => 5, 'message' => 'A great journey!'];
        $this->post('/contact', $data)->assertRedirect('/contact');
        $this->get('/contact')->assertOk()->assertSee('Your message has been received.');
        $this->get('/about')->assertOk()->assertDontSee('A great journey!');
        $this->getJson('/api/user-contacts')->assertOk()->assertExactJson([]);
        ContactMessage::first()->forceFill(['is_published' => true])->save();
        $this->get('/about')->assertOk()->assertSee('A great journey!')->assertDontSee('leyla@example.com');
        $this->getJson('/api/user-contacts')->assertOk()->assertJsonPath('0.message', 'A great journey!')->assertJsonMissing(['email' => 'leyla@example.com']);
        $this->post('/contact', [...$data, 'stars' => 6])->assertSessionHasErrors('stars');
    }

    public function test_newsletter_deduplicates_normalized_email(): void
    {
        $this->from('/about')->post('/newsletter', ['EMAIL' => 'TRAVELER@example.com'])->assertRedirect('/about');
        $this->post('/newsletter', ['EMAIL' => 'traveler@example.com'])->assertRedirect();
        $this->assertDatabaseCount('newsletter_subscriptions', 1);
        $this->assertDatabaseHas('newsletter_subscriptions', ['email' => 'traveler@example.com']);
        $this->post('/newsletter', ['EMAIL' => 'invalid'])->assertSessionHasErrors('EMAIL');
    }

    public function test_chat_supports_private_threads_saved_messages_and_member_authorization(): void
    {
        [$alice, $bob, $eve] = User::factory()->count(3)->create()->all();
        $threadId = $this->actingAs($alice)->postJson('/api/get-or-create-thread/'.$bob->id)->assertOk()->json('thread_id');
        $this->postJson('/api/messages/send', ['thread_id' => $threadId, 'message' => 'Private hello'])->assertCreated();
        $this->actingAs($bob)->postJson('/api/get-or-create-thread/'.$alice->id)->assertJson(['thread_id' => $threadId]);
        $this->getJson('/api/messages/'.$threadId)->assertOk()->assertJsonPath('0.message', 'Private hello')->assertJsonPath('0.user_id', $alice->id);
        $this->actingAs($eve)->getJson('/api/messages/'.$threadId)->assertForbidden();
        $this->postJson('/api/messages/send', ['thread_id' => $threadId, 'message' => 'Intrusion'])->assertForbidden();
        $this->postJson('/api/get-or-create-thread/'.$eve->id)->assertOk();
        $this->assertDatabaseCount('chat_threads', 2);
        $this->assertDatabaseCount('chat_messages', 1);
    }

    public function test_chat_limits_message_length_and_returns_only_the_latest_200_in_order(): void
    {
        $user = User::factory()->create();
        $thread = ChatThread::create(['first_user_id' => $user->id, 'second_user_id' => $user->id]);
        $this->actingAs($user)->postJson('/api/messages/send', ['thread_id' => $thread->id, 'message' => str_repeat('x', 5001)])->assertUnprocessable();

        for ($i = 1; $i <= 205; $i++) {
            $thread->messages()->create(['user_id' => $user->id, 'message' => 'Message '.$i]);
        }

        $this->getJson('/api/messages/'.$thread->id)->assertOk()->assertJsonCount(200)->assertJsonPath('0.message', 'Message 6')->assertJsonPath('199.message', 'Message 205');
    }

    public function test_regular_accounts_cannot_access_the_existing_admin_panel(): void
    {
        $user = User::factory()->create();
        $this->assertFalse($user->canAccessPanel(Panel::make()));
        $user->forceFill(['is_admin' => true])->save();
        $this->assertTrue($user->canAccessPanel(Panel::make()));
    }

    public function test_google_signin_reports_missing_configuration(): void
    {
        config(['services.google.client_id' => null]);
        $this->get('/auth/google')->assertRedirect('/auth_page')->assertSessionHas('error');
    }

    public function test_google_signin_creates_verified_users_and_rejects_invalid_state(): void
    {
        $google = (new GoogleUser)->setRaw(['email_verified' => true])->map(['id' => 'google-123', 'email' => 'google@example.com', 'name' => 'Google Traveler']);
        Socialite::shouldReceive('driver')->with('google')->once()->andReturnSelf();
        Socialite::shouldReceive('user')->once()->andReturn($google);
        $this->get('/auth/google/callback')->assertRedirect('/setup_profile');
        $this->assertAuthenticated();
        $this->assertDatabaseHas('users', ['email' => 'google@example.com', 'google_id' => 'google-123']);
        $this->post('/logout');
        Socialite::shouldReceive('driver')->with('google')->once()->andReturnSelf();
        Socialite::shouldReceive('user')->once()->andThrow(new InvalidStateException);
        $this->get('/auth/google/callback')->assertRedirect('/auth_page')->assertSessionHas('error');
        $this->assertGuest();
    }

    public function test_google_signin_does_not_link_existing_email_accounts_automatically(): void
    {
        User::factory()->create(['email' => 'existing@example.com']);
        $google = (new GoogleUser)->setRaw(['email_verified' => true])->map(['id' => 'google-456', 'email' => 'existing@example.com', 'name' => 'Traveler']);
        Socialite::shouldReceive('driver')->with('google')->once()->andReturnSelf();
        Socialite::shouldReceive('user')->once()->andReturn($google);
        $this->get('/auth/google/callback')->assertRedirect('/auth_page');
        $this->assertGuest();
        $this->assertNull(User::first()->google_id);
    }

    public function test_state_changing_routes_require_csrf_tokens(): void
    {
        $this->app->instance('env', 'local');
        $this->post('/newsletter', ['EMAIL' => 'traveler@example.com'])->assertStatus(419);
        $this->post('/auth_page', ['form_type' => 'login'])->assertStatus(419);
    }

    private function signup()
    {
        return $this->from('/auth_page')->post('/auth_page', [
            'form_type' => 'signup', 'username' => 'Traveler', 'email' => 'TRAVELER@example.com', 'phone' => '994501234567', 'password' => 'password123',
        ]);
    }

    private function postFor(User $user, string $title = 'Travel story'): Post
    {
        return $user->posts()->create(['title' => $title, 'content' => 'A travel story.', 'pub_date' => now()]);
    }
}
