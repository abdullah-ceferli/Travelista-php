<?php

namespace App\Http\Controllers;

use App\Http\Requests\CommentRequest;
use App\Http\Requests\PostRequest;
use App\Models\Post;
use App\Models\Tag;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class BlogController extends Controller
{
    public function index(Request $request): View
    {
        $data = $request->validate(['q' => ['nullable', 'string', 'max:200'], 'tag' => ['nullable', 'integer', 'exists:tags,id']]);
        $search = $data['q'] ?? '';
        $posts = Post::with(['author', 'tags'])->withCount('comments')
            ->when($search !== '', function ($query) use ($search) {
                $query->where(function ($query) use ($search) {
                    $query->where('title', 'like', '%'.$search.'%')->orWhere('content', 'like', '%'.$search.'%');
                });
            })
            ->when($data['tag'] ?? null, fn ($query, $tag) => $query->whereHas('tags', fn ($query) => $query->whereKey($tag)))
            ->latest('pub_date')->paginate(5)->withQueryString();

        return view('blog-home', [
            'posts' => $posts,
            'search' => $search,
            'popular_posts' => Post::orderByDesc('view_count')->latest('pub_date')->take(4)->get(),
            'categories' => Tag::withCount('posts')->orderBy('name')->get(),
            'tags' => Tag::orderBy('name')->get(),
        ]);
    }

    public function latest(): RedirectResponse
    {
        $post = Post::latest('pub_date')->first();

        return $post ? to_route('blog.show', $post) : to_route('blog.index');
    }

    public function show(Post $post): View
    {
        $post->increment('view_count');
        $post->load(['author', 'tags']);
        $comments = $post->comments()->with('user')->oldest()->get();

        return view('blog-single', ['post' => $post, 'comments' => $comments, 'comment_count' => $comments->count()]);
    }

    public function create(): View
    {
        return view('add-blog', [
            'tags' => Tag::orderBy('name')->get(),
            'selected_tags' => old('tags', []),
            'typed_title' => old('title', ''),
            'typed_content' => old('content', ''),
        ]);
    }

    public function store(PostRequest $request): RedirectResponse
    {
        $data = $request->validated();
        $image = $request->file('blog_img')?->store('posts', 'public');

        try {
            $post = DB::transaction(function () use ($request, $data, $image) {
                $post = $request->user()->posts()->create([
                    'title' => $data['title'], 'content' => $data['content'], 'blog_img' => $image, 'pub_date' => now(),
                ]);
                $tags = $data['tags'] ?? [];

                if (! empty($data['new_tag'])) {
                    $tags[] = Tag::firstOrCreate(['name' => trim($data['new_tag'])])->id;
                }

                $post->tags()->sync(array_unique($tags));

                return $post;
            });
        } catch (\Throwable $exception) {
            if ($image) {
                Storage::disk('public')->delete($image);
            }

            throw $exception;
        }

        return to_route('blog.show', $post);
    }

    public function comment(CommentRequest $request, Post $post): RedirectResponse
    {
        $data = $request->validated();

        if ($user = $request->user()) {
            $data['user_id'] = $user->id;
            $data['name'] = trim(($user->first_name ?? $user->name).' '.$user->last_name);
            $data['email'] = $user->email;
        }

        $post->comments()->create($data);

        return redirect(route('blog.show', $post).'#comments-section');
    }
}
