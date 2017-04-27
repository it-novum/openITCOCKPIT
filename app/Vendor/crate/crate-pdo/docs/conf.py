from crate.theme.rtd.conf import *

from sphinx.highlighting import lexers
from pygments.lexers.web import PhpLexer
lexers["php"] = PhpLexer(startinline=True, linenos=1)
lexers["php-annotations"] = PhpLexer(startinline=True, linenos=1)
# primary_domain = "php"

project = u'Crate PDO'
source_suffix = '.rst'
html_theme_options.update({
    'canonical_url_path': 'docs/reference/pdo/',
    'tracking_project': 'crate-pdo',
})
