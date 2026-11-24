"""Gera uma cópia navegável do portal para GitHub Pages."""
from html.parser import HTMLParser
from pathlib import Path
from urllib.parse import urljoin, urlparse, unquote
import http.cookiejar
import re
import shutil
import urllib.request

ROOT = Path(__file__).resolve().parents[1]
SOURCE = ROOT / 'site'
DEST = ROOT / 'docs'
BASE = 'http://127.0.0.1:8080'
PREFIX = '/Portal-EXPRESSO-RN/docs'

class Links(HTMLParser):
    def __init__(self):
        super().__init__()
        self.links = set()

    def handle_starttag(self, tag, attrs):
        if tag != 'a':
            return
        address = dict(attrs).get('href', '')
        parsed = urlparse(urljoin(BASE, address))
        if parsed.netloc == '127.0.0.1:8080' and not parsed.query and not parsed.path.startswith(('/wp-admin', '/wp-login', '/wp-json')):
            self.links.add(parsed.path)

session = urllib.request.build_opener(urllib.request.HTTPCookieProcessor(http.cookiejar.CookieJar()))
pending = ['/']
seen = set()
DEST.mkdir(exist_ok=True)

while pending:
    path = pending.pop(0)
    if path in seen or len(seen) > 110:
        continue
    seen.add(path)
    try:
        html = session.open(BASE + path, timeout=45).read().decode('utf-8')
    except Exception as error:
        print('Falhou', path, error, flush=True)
        continue
    if '<html' not in html:
        continue
    links = Links()
    links.feed(html)
    pending.extend(sorted(links.links - seen))
    html = re.sub(r'<div id="wpadminbar".*?</div><!-- #wpadminbar -->', '', html, flags=re.S)
    html = re.sub(r'<style[^>]*id="admin-bar-inline-css"[^>]*>.*?</style>', '', html, flags=re.S)
    html = re.sub(r'(?i)(https?:)?//127\.0\.0\.1:8080', PREFIX, html)
    html = re.sub(r'(?<=")/(?!/|Portal-EXPRESSO-RN)(?=[^"#])', PREFIX + '/', html)
    html = re.sub(r"(?<=')/(?!/|Portal-EXPRESSO-RN)(?=[^'#])", PREFIX + '/', html)
    html = html.replace('<html ', '<html data-theme="light" ', 1) if 'data-theme=' not in html[:200] else html
    target = DEST / unquote(path.lstrip('/')) / 'index.html'
    target.parent.mkdir(parents=True, exist_ok=True)
    target.write_text(html)
    print(path, flush=True)

for item in ('themes', 'plugins', 'uploads'):
    source = SOURCE / 'wp-content' / item
    dest = DEST / 'wp-content' / item
    if item == 'plugins':
        # CSS e JS de Elementor e de seus widgets.
        source = source / 'elementor' / 'assets'
        dest = dest / 'elementor' / 'assets'
    shutil.copytree(source, dest, dirs_exist_ok=True)

(DEST / '.nojekyll').touch()
print('Páginas exportadas', len(seen))
