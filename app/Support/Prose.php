<?php

namespace App\Support;

use Illuminate\Support\HtmlString;

/**
 * Renders admin-entered plain text (tool descriptions and content sections).
 *
 * The text is HTML-escaped and line breaks are kept, as before. On top of
 * that, Markdown-style links are turned into anchors — but only to paths on
 * this site, e.g. `[word counter](/tools/word-counter)`. External, protocol-
 * relative and `javascript:` URLs are left as plain text, so admin content
 * can never inject an off-site or script link.
 */
class Prose
{
    public static function render(?string $text): HtmlString
    {
        $html = e((string) $text);

        $html = preg_replace(
            '@\[([^\]\n]+)\]\((/(?!/)[A-Za-z0-9\-._~/?#=&;%]*)\)@',
            '<a href="$2">$1</a>',
            $html
        );

        return new HtmlString(nl2br($html));
    }
}
