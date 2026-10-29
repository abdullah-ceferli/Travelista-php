from pathlib import Path
import re

VIEW_DIR = Path('resources/views')
files = sorted(VIEW_DIR.glob('*.blade.php'))

replacements = [
    (re.compile(r'@if\(\$not \$\$loop->\$last\)'), '@if(!$loop->last)'),
    (re.compile(r'@if\(\$not \$loop->\$last\)'), '@if(!$loop->last)'),
    (re.compile(r'@if\(\$not \$\$loop->\$last\)'), '@if(!$loop->last)'),
    (re.compile(r'@if\(\$not \$loop->\$last\)'), '@if(!$loop->last)'),
    (re.compile(r'@if\(\$not \$\$loop->\$last\)'), '@if(!$loop->last)'),
    (re.compile(r'@if\(\$not \$\$loop->\$last\)'), '@if(!$loop->last)'),
]

# Define replacements as plain strings first for easier maintainability
replacements = [
    (r'@if($not $$loop->$last)', '@if(!$loop->last)'),
    (r'{{ $$post->$view_count ?? \'0\' }}', '{{ $post->view_count ?? \'0\' }}'),
    (r'{{ $$post->$total_comments ?? \'0\' }}',
     '{{ $post->total_comments ?? \'0\' }}'),
    (r'{{ post.content|truncatewords:30 }}',
     '{{ \Illuminate\Support\Str::words($post->content, 30) }}'),
    (r'{{ post.content|striptags|truncatechars:100 }}',
     '{{ \Illuminate\Support\Str::limit(strip_tags($post->content), 100) }}'),
    (r'@if($tag->id $in $selected_tags)',
     '@if(in_array($tag->id, $selected_tags))'),
    (r'{{ typed_title|default:\'\' }}', '{{ $typed_title ?? \''\' }}'),
    (r'{{ typed_content|default:\'\' }}', '{{ $typed_content ?? \''\' }}'),
    (r"@if($message->tags == '$error' $or $message->tags == '$danger')", "@if($message->tags == 'error' || $message->tags == 'danger')"),
    (r"{{ $$profile_user->$about_me ?? '$No $description $provided.' }}", "{{ $profile_user->about_me ?? 'No description provided.' }}"),
    (r"{{ $$logged_in_user->$about_me ?? '$Welcome $to $my $blog $profile!' }}", "{{ $logged_in_user->about_me ?? 'Welcome to my blog profile!' }}"),
    (r"{{ $$logged_in_user->$name ?? '$User' }}", "{{ $logged_in_user->name ?? 'User' }}"),
    (r"{{ $$logged_in_user->$last_name ?? '' }}", "{{ $logged_in_user->last_name ?? '' }}"),
    (r"@if($item->user_img){{ $item->user_img->url }}@elseimg/user-img/user1.png@endif", "{{ $item->user_img ? $item->user_img->url : asset('img/user-img/user1.png') }}"),
    (r'@if($$loop->$counter <= $item->stars) ★ @else ☆ @endif @endforeach', '@if($loop->iteration <= $item->stars) ★ @else ☆ @endif @endforeach'),
    (r'@if($$loop->$first)active@endif', '@if($loop->first) active @endif'),
    (r'{{ $$logged_in_user->$about_me ?? ', '{{ $logged_in_user->about_me ?? '),
]

# Manual pattern replacements for more complex cases
manual_replacements = [
    (r'@if\(\$not \$\$loop->\$last\)', '@if(!$loop->last)'),
    (r'\$\$loop->\$first', '$loop->first'),
    (r'\$\$loop->\$counter', '$loop->iteration'),
    (r'\$\$loop->\$last', '$loop->last'),
    (r'\$\$profile_user->\$about_me', '$profile_user->about_me'),
    (r'\$\$logged_in_user->\$about_me', '$logged_in_user->about_me'),
    (r'\$\$logged_in_user->\$name', '$logged_in_user->name'),
    (r'\$\$logged_in_user->\$last_name', '$logged_in_user->last_name'),
    (r'\$\$post->\$view_count', '$post->view_count'),
    (r'\$\$post->\$total_comments', '$post->total_comments'),
]

# direct string replacements for issue patterns
string_replacements = [
    ("@if($tag->id $in $selected_tags)", "@if(in_array($tag->id, $selected_tags))"),
    ("{{ typed_title|default:'' }}", "{{ $typed_title ?? '' }}"),
    ("{{ typed_content|default:'' }}", "{{ $typed_content ?? '' }}"),
    ("@if($message->tags == '$error' $or $message->tags == '$danger')", "@if($message->tags == 'error' || $message->tags == 'danger')"),
    ("{{ $$profile_user->$about_me ?? '$No $description $provided.' }}", "{{ $profile_user->about_me ?? 'No description provided.' }}"),
    ("{{ $$logged_in_user->$about_me ?? '$Welcome $to $my $blog $profile!' }}", "{{ $logged_in_user->about_me ?? 'Welcome to my blog profile!' }}"),
    ("{{ $$logged_in_user->$name ?? '$User' }}", "{{ $logged_in_user->name ?? 'User' }}"),
    ("{{ $$logged_in_user->$last_name ?? '' }}", "{{ $logged_in_user->last_name ?? '' }}"),
    ("{{ $$post->$view_count ?? '0' }}", "{{ $post->view_count ?? '0' }}"),
    ("{{ $$post->$total_comments ?? '0' }}", "{{ $post->total_comments ?? '0' }}"),
    ("@if($not $$loop->$last)", "@if(!$loop->last)"),
    ("@if($$loop->$counter <= $item->stars)", "@if($loop->iteration <= $item->stars)"),
    ("@if($$loop->$first)", "@if($loop->first)"),
    ("@if($$loop->$last)", "@if($loop->last)"),
    ("{{ post.content|truncatewords:30 }}", "{{ \\Illuminate\\Support\\Str::words($post->content, 30) }}"),
    ("{{ post.content|striptags|truncatechars:100 }}", "{{ \\Illuminate\\Support\\Str::limit(strip_tags($post->content), 100) }}"),
    ("src=\"@if($item->user_img){{ $item->user_img->url }}@elseimg/user-img/user1.png@endif\"", 'src="{{ $item->user_img ? $item->user_img->url : asset(\'img/user-img/user1.png\') }}"'),
    ("src=\"@if($logged_in_user){{ $logged_in_user->profile_img->url }}@else/static/img/user-img/user-info.png@endif\"", 'src="{{ $logged_in_user ? $logged_in_user->profile_img->url : asset(\'img/user-img/user-info.png\') }}"'),
]

# Additional generic fixes
regex_replacements = [
    (re.compile(r'@if\(\$not \$\$loop->\$last\)'), '@if(!$loop->last)'),
    (re.compile(r'@if\(\$\$loop->\$first\)'), '@if($loop->first)'),
    (re.compile(r'@if\(\$\$loop->\$last\)'), '@if($loop->last)'),
    (re.compile(r'@if\(\$\$loop->\$counter\s*<=\s*([^\)]+)\)'), r'@if($loop->iteration <= \1)'),
    (re.compile(r'\$\$loop->\$counter'), '$loop->iteration'),
    (re.compile(r'\$\$loop->\$first'), '$loop->first'),
    (re.compile(r'\$\$loop->\$last'), '$loop->last'),
    (re.compile(r'\$\$profile_user->\$about_me'), '$profile_user->about_me'),
    (re.compile(r'\$\$logged_in_user->\$about_me'), '$logged_in_user->about_me'),
    (re.compile(r'\$\$logged_in_user->\$name'), '$logged_in_user->name'),
    (re.compile(r'\$\$logged_in_user->\$last_name'), '$logged_in_user->last_name'),
    (re.compile(r'\$\$post->\$view_count'), '$post->view_count'),
    (re.compile(r'\$\$post->\$total_comments'), '$post->total_comments'),
    (re.compile(r'\$\$loop->\$counter'), '$loop->iteration'),
]

for path in files:
    text = path.read_text(encoding='utf-8')
    original = text
    for old, new in string_replacements:
        text = text.replace(old, new)
    for pattern, repl in regex_replacements:
        text = pattern.sub(repl, text)

    # handle specific text patterns with invalid php-like syntax in Blade expressions
    text = re.sub(r'{{\s*post\.content\|truncatewords:30\s*}}', '{{ \\Illuminate\\Support\\Str::words($post->content, 30) }}', text)
    text = re.sub(r'{{\s*post\.content\|striptags\|truncatechars:100\s*}}', '{{ \\Illuminate\\Support\\Str::limit(strip_tags($post->content), 100) }}', text)
    text = re.sub(r'{{\s*typed_title\|default:\'\'\s*}}', '{{ $typed_title ?? \''\' }}', text)
    text = re.sub(r'{{\s*typed_content\|default:\'\'\s*}}', '{{ $typed_content ?? \''\' }}', text)
    text = re.sub(r'@if\(\$tag->id \$in \$selected_tags\)', '@if(in_array($tag->id, $selected_tags))', text)
    text = re.sub(r'@if\(\$message->tags == \'\$error\' \$or \$message->tags == \'\$danger\'\)', "@if($message->tags == 'error' || $message->tags == 'danger')", text)

    # fix nested source attribute edge cases
    text = text.replace('src="@if($item->user_img){{ $item->user_img->url }}@elseimg/user-img/user1.png@endif"', 'src="{{ $item->user_img ? $item->user_img->url : asset(\'img/user-img/user1.png\') }}"')
    text = text.replace('src="@if($logged_in_user){{ $logged_in_user->profile_img->url }}@else/static/img/user-img/user-info.png@endif"', 'src="{{ $logged_in_user ? $logged_in_user->profile_img->url : asset(\'img/user-img/user-info.png\') }}"')

    if text != original:
        path.write_text(text, encoding='utf-8')
        print(f'Updated {path}')
