from pathlib import Path
import re

VIEW_DIR = Path('resources/views')
ROUTE_MAP = {
    'index': '/index',
    'about': '/about',
    'packages': '/packages',
    'hotels': '/hotels',
    'insurance': '/insurance',
    'profile': '/profile',
    'auth_page': '/auth_page',
    'blog-home': '/blog-home',
    'add_blog': '/add-blog',
    'elements': '/elements',
    'contact': '/contact',
    'connect': '/connect',
    'verify': '/verify',
    'blog-single': '/blog-single',
    'setup_profile': '/setup_profile',
}


def dotted_to_php(expr: str) -> str:
    expr = expr.strip()
    # Replace Django loop variable access for Blade
    expr = re.sub(r'\bforloop\.', '$loop->', expr)
    # Convert Django 'not' operator
    expr = re.sub(r'\bnot\s+([a-zA-Z0-9_\.]+)',
                  lambda m: '!' + dotted_to_php(m.group(1)), expr)

    # Do not convert function calls or already-Blade helpers
    if re.match(r'^(asset|route|url|optional|sprintf|nl2br|e|csrf|old|session)\(', expr):
        return expr

    # Convert name/path-like tokens into PHP variable/property syntax
    def replace_token(match):
        token = match.group(0)
        if token.startswith('$') or '->' in token or token.startswith('http'):
            return token
        if token in ('true', 'false', 'null'):
            return token
        return '$' + '->'.join(token.split('.'))

    expr = re.sub(r'\b[a-zA-Z_][a-zA-Z0-9_\.]*\b', replace_token, expr)
    return expr


def url_repl(match):
    name = match.group('name')
    args = match.group('args')
    target = ROUTE_MAP.get(name)
    if not target:
        return '#'
    if not args:
        return '{{ url(' + repr(target) + ') }}'
    arg_text = args.strip()
    arg_text = re.sub(r'^(?:pk|id)\s*=\s*', '', arg_text)
    arg_text = dotted_to_php(arg_text)
    return '{{ url(' + repr(target + '/') + ' . ' + arg_text + ') }}'


def convert_text(text: str) -> str:
    original = text
    text = re.sub(r'{%\s*load\s+static\s*%}\s*\n?', '', text)
    text = text.replace('{% csrf_token %}', '@csrf')
    text = re.sub(
        r"{%-?\s*static\s+'([^']+)'\s*-?%}", r"{{ asset('\1') }}", text)
    text = re.sub(
        r"{%-?\s*url\s+'(?P<name>[^']+)'(?:\s+(?P<args>[^%]+?))?\s*-?%}", url_repl, text)

    def if_repl(match):
        expr = match.group(1)
        return '@if(' + dotted_to_php(expr) + ')'
    text = re.sub(r'{%\s*if\s+([^%]+?)\s*%}', if_repl, text)
    text = text.replace('{% else %}', '@else')
    text = text.replace('{% endif %}', '@endif')

    # Loop conversion
    has_empty = '{% empty %}' in text
    if has_empty:
        def for_repl(match):
            var = match.group(1)
            iterable = match.group(2).strip()
            iterable = re.sub(r'\.all$', '', iterable)
            return '@forelse (' + dotted_to_php(iterable) + ' as $' + var + ')'
        text = re.sub(
            r'{%\s*for\s+(\w+)\s+in\s+([a-zA-Z0-9_\.]+)\s*%}', for_repl, text)
        text = text.replace('{% empty %}', '@empty')
        text = text.replace('{% endfor %}', '@endforelse')
    else:
        def for_repl(match):
            var = match.group(1)
            iterable = match.group(2).strip()
            iterable = re.sub(r'\.all$', '', iterable)
            return '@foreach (' + dotted_to_php(iterable) + ' as $' + var + ')'
        text = re.sub(
            r'{%\s*for\s+(\w+)\s+in\s+([a-zA-Z0-9_\.]+)\s*%}', for_repl, text)
        text = text.replace('{% endfor %}', '@endforeach')

    # Django filters to Blade/PHP
    text = re.sub(r'{{\s*([a-zA-Z0-9_\.]+)\s*\|\s*linebreaks\s*}}',
                  lambda m: '{!! nl2br(e(' + dotted_to_php(m.group(1)) + ')) !!}', text)
    text = re.sub(r'{{\s*([a-zA-Z0-9_\.]+)\s*\|\s*stringformat:"([^"]+)"\s*}}',
                  lambda m: '{{ sprintf(' + repr('%' + m.group(2)) + ', ' + dotted_to_php(m.group(1)) + ') }}', text)
    text = re.sub(r'{{\s*([a-zA-Z0-9_\.]+)\s*\|\s*default:"([^"]*)"\s*}}',
                  lambda m: '{{ ' + dotted_to_php(m.group(1)) + ' ?? ' + repr(m.group(2)) + ' }}', text)
    text = re.sub(r'{{\s*([a-zA-Z0-9_\.]+)\s*\|\s*date:"([^"]+)"\s*}}', lambda m: '{{ optional(' +
                  dotted_to_php(m.group(1)) + ')->format(' + repr(m.group(2)) + ') ?? "" }}', text)

    def blade_expr(match):
        expr = match.group(1).strip()
        if re.search(r'[\|\(\)]', expr):
            return '{{ ' + expr + ' }}'
        return '{{ ' + dotted_to_php(expr) + ' }}'

    text = re.sub(r'{{\s*([^{}%]+?)\s*}}', blade_expr, text)
    return text if text != original else original


def convert_file(path: Path) -> bool:
    text = path.read_text(encoding='utf-8')
    new_text = convert_text(text)
    if new_text != text:
        path.write_text(new_text, encoding='utf-8')
        print(f'Updated {path}')
        return True
    print(f'No changes {path}')
    return False


def main():
    if not VIEW_DIR.exists():
        print('views directory not found:', VIEW_DIR)
        return
    for file in sorted(VIEW_DIR.glob('*.blade.php')):
        convert_file(file)


if __name__ == '__main__':
    main()
