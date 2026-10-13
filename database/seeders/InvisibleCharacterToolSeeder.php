<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Tool;
use Illuminate\Database\Seeder;

/**
 * Adds the Invisible Character tool on its own, so it can be installed on a
 * live database with `php artisan db:seed --class=InvisibleCharacterToolSeeder`
 * without re-running ToolSeeder (which re-randomises every tool's counters).
 *
 * Re-running it refreshes the copy, content sections and FAQs below.
 * Links in content use `[anchor](/path)` and are rendered by App\Support\Prose.
 */
class InvisibleCharacterToolSeeder extends Seeder
{
    public function run(): void
    {
        $category = Category::where('name', 'Text Tools')->first();
        if (!$category) {
            return;
        }

        $tool = Tool::withTrashed()->firstOrNew(['slug' => 'invisible-character']);

        // Keep the real counters on an existing row; only seed them on create.
        if (!$tool->exists) {
            $tool->view_count = 0;
            $tool->use_count  = 0;
        }

        $tool->fill([
            'category_id'       => $category->id,
            'name'              => 'Invisible Character',
            'icon'              => '👻',
            'color'             => '#434CB6',
            'tool_type'         => 'text',
            'status'            => 'active',
            'is_featured'       => false,
            'has_custom_blade'  => true,
            'sort_order'        => 140,
            'short_description' => 'Copy and paste invisible characters (ㅤ) and blank text, generate any amount, and find hidden characters in your text.',
            'long_description'  => $this->intro(),
            // " - Toolsearch" is appended by the layout: 47 + 13 = 60 characters.
            'seo_title'         => 'Invisible Character (ㅤ) Copy & Paste Blank Text',
            'seo_description'   => 'Copy and paste an invisible character (ㅤ) in one click. Generate blank text for empty messages and names, or find and remove hidden characters in any text.',
            'seo_keywords'      => 'invisible character, invisible character copy and paste, blank text, invisible text, blank character, empty character, hangul filler, zero width space, remove hidden characters',
        ]);
        $tool->deleted_at = null;
        $tool->save();

        $tool->contents()->delete();
        foreach ($this->sections() as $i => [$key, $title, $content]) {
            $tool->contents()->create([
                'section_key' => $key,
                'title'       => $title,
                'content'     => $content,
                'sort_order'  => $i,
            ]);
        }

        $tool->faqs()->delete();
        foreach ($this->faqs() as $i => [$question, $answer]) {
            $tool->faqs()->create([
                'question'   => $question,
                'answer'     => $answer,
                'sort_order' => $i,
            ]);
        }
    }

    private function intro(): string
    {
        return <<<'TXT'
An invisible character is a Unicode character that shows nothing on screen but still counts as text. You use it when an app won't accept an empty field — a blank WhatsApp message, an empty-looking username, or spacing in a bio that would otherwise be trimmed.

The quickest option is the Hangul Filler (U+3164, ㅤ). Click the first copy button above, then paste it wherever you need blank text. Unicode classifies it as a letter rather than a space, so many apps that strip spaces keep it.

This page does three things:
• Copy — one-click buttons for the three most useful blank characters, plus a table of 70 invisible Unicode characters, each with its code and HTML entity.
• Generate — copy anywhere from 1 to 10,000 blank characters at once, for apps that require a minimum length.
• Detect and clean — paste any text into the tester to highlight every hidden character, then remove them in one click.

Everything runs in your browser. Nothing you type or paste is sent to our server.
TXT;
    }

    /** @return array<int, array{0: string, 1: string, 2: string}> [section_key, title, content] */
    private function sections(): array
    {
        return [
            ['how_to_use', 'How to Copy and Paste an Invisible Character', <<<'TXT'
1. Click "Hangul Filler" in the One-Click Copy box. The button turns green to confirm it was copied.
2. Open the app and tap the field where you want blank text.
3. Paste with Ctrl+V (Cmd+V on Mac), or long-press and choose Paste on a phone.
4. Send or save as usual.

Need more than one character? Use the Blank Text Generator: choose a character, set the count, and press Copy. The preview shows one highlighted box for each character, so you can see exactly what you're copying.

Not sure it worked? Paste into the Test & Detect box. If the grey placeholder text disappears and the counter goes up, your clipboard contains an invisible character.
TXT],
            ['which_character', 'Which Invisible Character Should You Use?', <<<'TXT'
Different apps filter text in different ways, so it helps to know what each character is.

• Hangul Filler (U+3164) — the best first choice for names and empty messages. It is classed as a letter, so it usually survives apps that trim spaces.
• Halfwidth Hangul Filler (U+FFA0) — a narrower version to try if the full-width one is rejected.
• Braille Pattern Blank (U+2800) — a blank Braille cell. It is classed as a symbol, which makes it a good fallback when Hangul fillers are blocked.
• Zero-Width Space (U+200B) — has no width at all. Useful for hidden line-break points inside long words or links, but some apps remove it.
• Unicode spaces (U+2000–U+200A, U+3000 and others) — spaces of different widths. They are useful for precise spacing in documents, but apps that trim spaces often remove them.

If one character doesn't work, try the next one in this list, or paste two or three in a row.
TXT],
            ['use_cases', 'Blank Messages, Names and Bios', <<<'TXT'
Common uses for invisible text:

• Empty messages — paste a Hangul Filler into WhatsApp, Telegram or Messenger to send a message that looks blank.
• Blank or spaced-out usernames — many games and social apps accept an invisible character as a display name, or as spacing inside one.
• Bios and captions — add extra spacing or blank lines in a profile where the app would otherwise collapse them.
• Required form fields — fill a field that can't be left empty when you have nothing to enter.

Apps change their filters over time, and some deliberately block invisible names to prevent impersonation. If a character is rejected, try a different one from the table, and always test it before relying on it.
TXT],
            ['troubleshooting', 'Why Your Invisible Character Disappears', <<<'TXT'
If your blank text vanishes after pasting or saving, it is usually one of these:

• The app trims whitespace. Regular and Unicode spaces are removed from the start and end of fields. Use the Hangul Filler or Braille Blank instead, because they aren't classed as spaces.
• The app removes zero-width characters. Some platforms strip characters like U+200B automatically. Switch to a character with visible width.
• There's a minimum length. Some fields need two or more characters. Generate three to five blank characters and paste them together.
• The font has no glyph for it. On rare systems a filler may appear as a small box. Try a different character.
• The platform blocks it on purpose. Some sites reject names made only of invisible characters. In that case, no character will get through.
TXT],
            ['detect_remove', 'How to Find and Remove Hidden Characters in Text', <<<'TXT'
Invisible characters aren't always added on purpose. They often come along when you copy text from web pages, PDFs, chat apps or word processors, and they can cause real problems:

• Code fails with "unexpected character" errors because of a zero-width space you can't see.
• A password or coupon code is rejected because a hidden character was copied with it.
• Spreadsheet lookups and search return no match even though the text looks identical.
• A Right-to-Left Override character makes a file name display backwards — a trick sometimes used to disguise file types.

To check your text, paste it into Test & Detect. Each hidden character is shown as a highlighted code (such as U+200B), and the panel below lists how many of each were found. Press "Remove invisible" to clean the text: zero-width and formatting characters are deleted, and unusual spaces become normal spaces. Normal spaces, tabs and line breaks are left as they are.

If two texts look identical but don't match, compare them line by line with the [Diff Checker](/tools/diff-checker). To see how many characters your text really contains, paste it into the [Word Counter](/tools/word-counter).
TXT],
            ['vs_space', 'Invisible Character vs. a Regular Space', <<<'TXT'
A regular space (U+0020) is whitespace. Most apps trim it from the start and end of a field, and an input that contains only spaces is usually treated as empty.

An invisible character such as the Hangul Filler is not whitespace. Unicode classifies it as a letter, so apps generally treat it as real content, even though it displays as blank. That is why an invisible character can send a "blank" message when a space can't.

Need to show a special character on a web page instead of pasting it? Use its HTML entity from the table above, or convert text with the [HTML Encoder/Decoder](/tools/html-encoder-decoder).
TXT],
            ['responsible_use', 'Using Invisible Text Responsibly', <<<'TXT'
Invisible characters are harmless for spacing and blank messages, but keep a few things in mind:

• Platform rules: some services don't allow blank names, or treat invisible characters used to dodge filters or impersonate others as a violation. Check the rules of the site you're using.
• Accessibility: screen readers handle these characters inconsistently. Some skip them and some read them out, so avoid putting them inside words that people need to read.
• Search and copying: hidden characters inside words can stop text from matching in search, and they travel along when someone copies your text.

Looking for more ways to work with text? Browse all our [text tools](/categories/text-tools).
TXT],
        ];
    }

    /** @return array<int, array{0: string, 1: string}> [question, answer] */
    private function faqs(): array
    {
        return [
            ['How do I type an invisible character?', 'Most keyboards can\'t type one. Copy it from this page, then paste it with Ctrl+V, Cmd+V, or long-press and Paste on a phone.'],
            ['Which invisible character works best?', 'Start with the Hangul Filler (U+3164). If an app rejects it, try the Halfwidth Hangul Filler (U+FFA0) or the Braille Pattern Blank (U+2800).'],
            ['How do I send an empty message on WhatsApp?', 'Copy the Hangul Filler, paste it into the message box and tap send. The message arrives looking blank.'],
            ['Why does my invisible character disappear after I paste it?', 'The app is probably trimming spaces or stripping zero-width characters. Use a Hangul Filler or Braille Blank, or paste several characters in a row.'],
            ['How can I tell if text has hidden characters in it?', 'Paste it into the Test & Detect box on this page. Every hidden character is highlighted with its Unicode code, and you can remove them all in one click.'],
            ['Is this tool free, and is my text private?', 'It\'s free with no sign-up. It runs entirely in your browser, so nothing you paste is uploaded.'],
        ];
    }
}
