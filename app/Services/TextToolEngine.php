<?php

namespace App\Services;

use App\Models\Tool;

class TextToolEngine
{
    public function handle(array $data, Tool $tool): array
    {
        $slug = $tool->slug;

        return match (true) {
            str_contains($slug, 'json')     => $this->jsonFormatter($data),
            str_contains($slug, 'summarize') || str_contains($slug, 'summarizer') => $this->textSummarizer($data),
            str_contains($slug, 'word-count') || str_contains($slug, 'word_count') => $this->wordCount($data),
            str_contains($slug, 'case')     => $this->caseConverter($data),
            str_contains($slug, 'grammar')  => $this->grammarChecker($data),
            default => $this->wordCount($data),
        };
    }

    /* ═══════════════════════════════════════════════════════════════
       GRAMMAR CHECKER
       Rule-based: spelling, contractions, phrases, punctuation,
       capitalization, articles, double words, and spacing.
    ═══════════════════════════════════════════════════════════════ */
    public function grammarChecker(array $data): array
    {
        $text = trim($data['text'] ?? '');

        if (empty($text)) {
            return ['success' => false, 'error' => 'Please enter some text to check.'];
        }
        if (str_word_count($text) < 2) {
            return ['success' => false, 'error' => 'Please enter at least a few words.'];
        }
        if (mb_strlen($text) > 15000) {
            return ['success' => false, 'error' => 'Text too long. Please limit to approximately 2,500 words.'];
        }

        $issues  = [];
        $current = $text;

        // Apply all correction passes
        $current = $this->gcFixMisspellings($current, $issues);
        $current = $this->gcFixContractions($current, $issues);
        $current = $this->gcFixPhraseErrors($current, $issues);
        $current = $this->gcFixDoubleWords($current, $issues);
        $current = $this->gcFixArticles($current, $issues);
        $current = $this->gcFixSpacingPunctuation($current, $issues);
        $current = $this->gcFixCapitalization($current, $issues);
        $current = $this->gcFixDoubleSpaces($current, $issues);

        // Group duplicate issues
        $grouped = [];
        foreach ($issues as $issue) {
            $key = $issue['type'] . ':' . mb_strtolower($issue['original']);
            if (!isset($grouped[$key])) {
                $grouped[$key] = array_merge($issue, ['count' => 1]);
            } else {
                $grouped[$key]['count']++;
            }
        }
        $issues = array_values($grouped);

        $totalIssues = array_sum(array_column($issues, 'count'));

        return [
            'success'        => true,
            'original'       => $text,
            'corrected'      => $current,
            'diff_html'      => $this->gcDiff($text, $current),
            'issues'         => $issues,
            'issue_count'    => $totalIssues,
            'word_count'     => str_word_count($text),
            'sentence_count' => max(1, preg_match_all('/[.!?]+(?:\s|$)/u', $text)),
            'is_clean'       => $totalIssues === 0,
            'changed'        => $text !== $current,
        ];
    }

    /* ─── Spelling corrections ─── */
    private function gcFixMisspellings(string $text, array &$issues): string
    {
        static $dict = null;
        if ($dict === null) {
            $dict = [
                /* Common misspellings – value is the correction */
                'absense'       => 'absence',        'accomodate'    => 'accommodate',
                'accidently'    => 'accidentally',   'acheive'       => 'achieve',
                'achive'        => 'achieve',        'acording'      => 'according',
                'acros'         => 'across',         'adress'        => 'address',
                'agressive'     => 'aggressive',     'alot'          => 'a lot',
                'amature'       => 'amateur',        'anual'         => 'annual',
                'arguement'     => 'argument',       'athiest'       => 'atheist',
                'beleive'       => 'believe',        'belive'        => 'believe',
                'benifit'       => 'benefit',        'buisness'      => 'business',
                'calander'      => 'calendar',       'catagory'      => 'category',
                'cemetary'      => 'cemetery',       'cheif'         => 'chief',
                'collegue'      => 'colleague',      'commited'      => 'committed',
                'completley'    => 'completely',     'concious'      => 'conscious',
                'concieve'      => 'conceive',       'copywrite'     => 'copyright',
                'critism'       => 'criticism',      'curiousity'    => 'curiosity',
                'dacade'        => 'decade',         'definate'      => 'definite',
                'definately'    => 'definitely',     'desicion'      => 'decision',
                'dilemna'       => 'dilemma',        'disatisfied'   => 'dissatisfied',
                'dissapear'     => 'disappear',      'dissapoint'    => 'disappoint',
                'embarass'      => 'embarrass',      'enviroment'    => 'environment',
                'equiped'       => 'equipped',       'existance'     => 'existence',
                'experiance'    => 'experience',     'expereince'    => 'experience',
                'firey'         => 'fiery',          'foriegn'       => 'foreign',
                'fourty'        => 'forty',          'freind'        => 'friend',
                'goverment'     => 'government',     'gaurd'         => 'guard',
                'grammer'       => 'grammar',        'gratefull'     => 'grateful',
                'guarentee'     => 'guarantee',      'hieght'        => 'height',
                'hierachy'      => 'hierarchy',      'humerous'      => 'humorous',
                'immediatly'    => 'immediately',    'independant'   => 'independent',
                'indispensible' => 'indispensable',  'intellegent'   => 'intelligent',
                'knowlegde'     => 'knowledge',      'langugage'     => 'language',
                'lenght'        => 'length',         'liason'        => 'liaison',
                'lisense'       => 'license',        'liturature'    => 'literature',
                'maintenence'   => 'maintenance',    'medival'       => 'medieval',
                'millenium'     => 'millennium',     'miniscule'     => 'minuscule',
                'mischievious'  => 'mischievous',    'misspeled'     => 'misspelled',
                'naturaly'      => 'naturally',      'neccessary'    => 'necessary',
                'necesary'      => 'necessary',      'neice'         => 'niece',
                'noticable'     => 'noticeable',     'occassion'     => 'occasion',
                'occured'       => 'occurred',       'occurrance'    => 'occurrence',
                'paralell'      => 'parallel',       'peice'         => 'piece',
                'perseverence'  => 'perseverance',   'persistance'   => 'persistence',
                'phenominal'    => 'phenomenal',     'posession'     => 'possession',
                'priviledge'    => 'privilege',      'pronounciation' => 'pronunciation',
                'questionaire'  => 'questionnaire',  'recieve'       => 'receive',
                'reconize'      => 'recognize',      'recomend'      => 'recommend',
                'referance'     => 'reference',      'relevent'      => 'relevant',
                'rediculous'    => 'ridiculous',     'remeber'       => 'remember',
                'repitition'    => 'repetition',     'resturant'     => 'restaurant',
                'restarant'     => 'restaurant',     'rythem'        => 'rhythm',
                'schedual'      => 'schedule',       'seperate'      => 'separate',
                'simalar'       => 'similar',        'souviner'      => 'souvenir',
                'speach'        => 'speech',         'succesful'     => 'successful',
                'supercede'     => 'supersede',      'suprise'       => 'surprise',
                'temperture'    => 'temperature',    'tendancy'      => 'tendency',
                'thier'         => 'their',          'threshhold'    => 'threshold',
                'tomarrow'      => 'tomorrow',       'tommorow'      => 'tomorrow',
                'tommorrow'     => 'tomorrow',       'truely'        => 'truly',
                'untill'        => 'until',          'usefull'       => 'useful',
                'usally'        => 'usually',        'visious'       => 'vicious',
                'vulnerible'    => 'vulnerable',     'wether'        => 'whether',
                'wierd'         => 'weird',          'writting'      => 'writing',
                'writeing'      => 'writing',        'yoiu'          => 'you',
                'teh'           => 'the',            'recieved'      => 'received',
                'acheived'      => 'achieved',       'beleived'      => 'believed',
                'freindly'      => 'friendly',       'govermental'   => 'governmental',
                'independance'  => 'independence',   'occurence'     => 'occurrence',
                'perseverance'  => 'perseverance',   'preferably'    => 'preferably',
                'publically'    => 'publicly',       'relevant'      => 'relevant',
                'seize'         => 'seize',          'harrasment'    => 'harassment',
            ];
        }

        foreach ($dict as $wrong => $right) {
            $pattern = '/(?<![a-zA-Z])' . preg_quote($wrong, '/') . '(?![a-zA-Z])/u';
            $text = preg_replace_callback($pattern, function ($m) use ($wrong, $right, &$issues) {
                // Preserve original casing (Title / ALL CAPS)
                $original = $m[0];
                $fix = $right;
                if (mb_strtoupper($original) === $original) {
                    $fix = mb_strtoupper($right);
                } elseif (mb_strtoupper(mb_substr($original, 0, 1)) === mb_substr($original, 0, 1)) {
                    $fix = mb_strtoupper(mb_substr($right, 0, 1)) . mb_substr($right, 1);
                }
                $issues[] = [
                    'type'      => 'spelling',
                    'severity'  => 'error',
                    'original'  => $original,
                    'corrected' => $fix,
                    'message'   => '"' . $original . '" → "' . $fix . '"',
                ];
                return $fix;
            }, $text) ?? $text;
        }

        return $text;
    }

    /* ─── Missing apostrophes in contractions ─── */
    private function gcFixContractions(string $text, array &$issues): string
    {
        // Only fix forms that are NOT real words (e.g. "cant" is skipped, "dont" is not a word)
        $contractions = [
            'dont'    => "don't",    'doesnt'  => "doesn't",
            'didnt'   => "didn't",   'wouldnt' => "wouldn't",
            'shouldnt'=> "shouldn't",'couldnt' => "couldn't",
            'isnt'    => "isn't",    'arent'   => "aren't",
            'wasnt'   => "wasn't",   'werent'  => "weren't",
            'hasnt'   => "hasn't",   'havent'  => "haven't",
            'hadnt'   => "hadn't",   'wont'    => "won't",
            'itsnt'   => "isn't",    'theyre'  => "they're",
            'youre'   => "you're",   'were'    => null,  // skip – real word
            'hes'     => null,       'shes'    => null,  // skip – ambiguous
        ];

        foreach ($contractions as $wrong => $right) {
            if ($right === null) continue;
            $pattern = '/\b' . preg_quote($wrong, '/') . '\b/iu';
            $text = preg_replace_callback($pattern, function ($m) use ($wrong, $right, &$issues) {
                $original = $m[0];
                // Preserve case
                $fix = (mb_strtoupper($original) === $original) ? mb_strtoupper($right) : $right;
                if (mb_strtoupper(mb_substr($original, 0, 1)) === mb_substr($original, 0, 1)
                    && mb_strtolower($original) !== $original) {
                    $fix = mb_strtoupper(mb_substr($right, 0, 1)) . mb_substr($right, 1);
                }
                $issues[] = [
                    'type'      => 'punctuation',
                    'severity'  => 'error',
                    'original'  => $original,
                    'corrected' => $fix,
                    'message'   => 'Missing apostrophe: "' . $original . '" → "' . $fix . '"',
                ];
                return $fix;
            }, $text) ?? $text;
        }

        return $text;
    }

    /* ─── Common phrase-level errors ─── */
    private function gcFixPhraseErrors(string $text, array &$issues): string
    {
        $phrases = [
            '/\bcould\s+of\b/i'  => ['could have',  'grammar',     '"could of" should be "could have"'],
            '/\bshould\s+of\b/i' => ['should have', 'grammar',     '"should of" should be "should have"'],
            '/\bwould\s+of\b/i'  => ['would have',  'grammar',     '"would of" should be "would have"'],
            '/\bmust\s+of\b/i'   => ['must have',   'grammar',     '"must of" should be "must have"'],
            '/\bmight\s+of\b/i'  => ['might have',  'grammar',     '"might of" should be "might have"'],
            '/\bmore\s+better\b/i' => ['better',    'grammar',     '"more better" is redundant; use "better"'],
            '/\bmore\s+worse\b/i'  => ['worse',     'grammar',     '"more worse" is redundant; use "worse"'],
            '/\bvery\s+unique\b/i' => ['unique',    'style',       '"unique" is absolute; "very unique" is redundant'],
            '/\bdue\s+to\s+the\s+fact\s+that\b/i' => ['because', 'style', '"due to the fact that" → "because"'],
            '/\bin\s+order\s+to\b/i' => ['to',      'style',       '"in order to" can usually be shortened to "to"'],
            '/\banyways\b/i'     => ['anyway',      'grammar',     '"anyways" is non-standard; use "anyway"'],
            '/\bno\s+where\b/i'  => ['nowhere',     'spelling',    '"no where" should be one word: "nowhere"'],
            '/\bin\s+case\s+of\s+the\s+event/i' => ['in the event', 'style', 'Wordy phrase'],
        ];

        foreach ($phrases as $pattern => [$fix, $type, $msg]) {
            $text = preg_replace_callback($pattern, function ($m) use ($fix, $type, $msg, &$issues) {
                $issues[] = [
                    'type'      => $type,
                    'severity'  => $type === 'grammar' ? 'error' : 'suggestion',
                    'original'  => $m[0],
                    'corrected' => $fix,
                    'message'   => $msg,
                ];
                return $fix;
            }, $text) ?? $text;
        }

        return $text;
    }

    /* ─── Double words ─── */
    private function gcFixDoubleWords(string $text, array &$issues): string
    {
        return preg_replace_callback(
            '/\b(\w+)\s+\1\b/ui',
            function ($m) use (&$issues) {
                $issues[] = [
                    'type'      => 'grammar',
                    'severity'  => 'error',
                    'original'  => $m[0],
                    'corrected' => $m[1],
                    'message'   => 'Repeated word: "' . $m[0] . '" → "' . $m[1] . '"',
                ];
                return $m[1];
            },
            $text
        ) ?? $text;
    }

    /* ─── Article errors (a / an) ─── */
    private function gcFixArticles(string $text, array &$issues): string
    {
        /* Words starting with vowel sounds that still take "a" */
        $aBeforeVowel = ['uni', 'use', 'user', 'used', 'useful', 'usually', 'usual',
            'utility', 'uniform', 'union', 'unique', 'unit', 'universe', 'university',
            'europe', 'european', 'one', 'once', 'ewe', 'ewes'];

        /* Words starting with "h" but with silent h – take "an" */
        $anBeforeH = ['hour', 'hours', 'hourly', 'honest', 'honestly', 'honesty',
            'honor', 'honours', 'honorary', 'heir', 'heiress', 'heirloom'];

        // "a" before a vowel-starting word (that shouldn't use "a")
        $text = preg_replace_callback(
            '/\ba\s+([aeiouAEIOU]\w*)/u',
            function ($m) use ($aBeforeVowel, &$issues) {
                $word  = mb_strtolower($m[1]);
                foreach ($aBeforeVowel as $exc) {
                    if (str_starts_with($word, $exc)) return $m[0]; // correct as "a"
                }
                $fix = 'an ' . $m[1];
                $issues[] = [
                    'type'      => 'grammar',
                    'severity'  => 'error',
                    'original'  => $m[0],
                    'corrected' => $fix,
                    'message'   => '"' . $m[0] . '" → "' . $fix . '" (use "an" before vowel sounds)',
                ];
                return $fix;
            },
            $text
        ) ?? $text;

        // "an" before a consonant-starting word
        $text = preg_replace_callback(
            '/\ban\s+([b-df-hj-np-tv-zB-DF-HJ-NP-TV-Z]\w*)/u',
            function ($m) use ($anBeforeH, &$issues) {
                $word  = mb_strtolower($m[1]);
                foreach ($anBeforeH as $exc) {
                    if (str_starts_with($word, $exc)) return $m[0]; // keep "an hour" etc.
                }
                $fix = 'a ' . $m[1];
                $issues[] = [
                    'type'      => 'grammar',
                    'severity'  => 'error',
                    'original'  => $m[0],
                    'corrected' => $fix,
                    'message'   => '"' . $m[0] . '" → "' . $fix . '" (use "a" before consonant sounds)',
                ];
                return $fix;
            },
            $text
        ) ?? $text;

        return $text;
    }

    /* ─── Space before punctuation ─── */
    private function gcFixSpacingPunctuation(string $text, array &$issues): string
    {
        // Remove space before , . ; : ! ?
        $text = preg_replace_callback(
            '/(\w)\s+([,\.;:!?])/u',
            function ($m) use (&$issues) {
                $fix = $m[1] . $m[2];
                $issues[] = [
                    'type'      => 'punctuation',
                    'severity'  => 'error',
                    'original'  => $m[0],
                    'corrected' => $fix,
                    'message'   => 'Remove space before "' . $m[2] . '"',
                ];
                return $fix;
            },
            $text
        ) ?? $text;

        // Ensure space after , ; : (but not before end of string or another punctuation)
        $text = preg_replace_callback(
            '/([,;:])([a-zA-Z])/u',
            function ($m) use (&$issues) {
                $fix = $m[1] . ' ' . $m[2];
                $issues[] = [
                    'type'      => 'punctuation',
                    'severity'  => 'error',
                    'original'  => $m[0],
                    'corrected' => $fix,
                    'message'   => 'Add space after "' . $m[1] . '"',
                ];
                return $fix;
            },
            $text
        ) ?? $text;

        // Ensure space after . ! ? followed by a letter (sentence boundary)
        $text = preg_replace_callback(
            '/([.!?])([A-Z])/u',
            function ($m) use (&$issues) {
                $fix = $m[1] . ' ' . $m[2];
                $issues[] = [
                    'type'      => 'punctuation',
                    'severity'  => 'error',
                    'original'  => $m[0],
                    'corrected' => $fix,
                    'message'   => 'Add space after sentence-ending punctuation',
                ];
                return $fix;
            },
            $text
        ) ?? $text;

        return $text;
    }

    /* ─── Capitalization ─── */
    private function gcFixCapitalization(string $text, array &$issues): string
    {
        // Fix first character of the text
        if (isset($text[0]) && ctype_lower($text[0])) {
            $upper = mb_strtoupper(mb_substr($text, 0, 1)) . mb_substr($text, 1);
            $issues[] = [
                'type'      => 'capitalization',
                'severity'  => 'error',
                'original'  => mb_substr($text, 0, 1),
                'corrected' => mb_strtoupper(mb_substr($text, 0, 1)),
                'message'   => 'Text should begin with a capital letter',
            ];
            $text = $upper;
        }

        // Fix "i" as standalone pronoun
        $text = preg_replace_callback('/(?<![a-zA-Z\'])i(?![a-zA-Z\'])/u', function ($m) use (&$issues) {
            $issues[] = [
                'type'      => 'capitalization',
                'severity'  => 'error',
                'original'  => 'i',
                'corrected' => 'I',
                'message'   => 'The pronoun "I" must always be capitalized',
            ];
            return 'I';
        }, $text) ?? $text;

        // Fix lowercase after sentence-ending punctuation
        $text = preg_replace_callback('/([.!?]\s+)([a-z])/u', function ($m) use (&$issues) {
            $issues[] = [
                'type'      => 'capitalization',
                'severity'  => 'error',
                'original'  => $m[2],
                'corrected' => mb_strtoupper($m[2]),
                'message'   => 'First word of a sentence must be capitalized',
            ];
            return $m[1] . mb_strtoupper($m[2]);
        }, $text) ?? $text;

        // Fix days of the week
        $days = ['monday','tuesday','wednesday','thursday','friday','saturday','sunday'];
        foreach ($days as $day) {
            $text = preg_replace_callback('/\b' . $day . '\b/u', function ($m) use (&$issues) {
                $fix = ucfirst($m[0]);
                $issues[] = [
                    'type'      => 'capitalization',
                    'severity'  => 'error',
                    'original'  => $m[0],
                    'corrected' => $fix,
                    'message'   => 'Days of the week must be capitalized: "' . $fix . '"',
                ];
                return $fix;
            }, $text) ?? $text;
        }

        // Fix months
        $months = ['january','february','march','april','may','june','july',
                   'august','september','october','november','december'];
        foreach ($months as $month) {
            $text = preg_replace_callback('/\b' . $month . '\b/u', function ($m) use (&$issues) {
                $fix = ucfirst($m[0]);
                $issues[] = [
                    'type'      => 'capitalization',
                    'severity'  => 'error',
                    'original'  => $m[0],
                    'corrected' => $fix,
                    'message'   => 'Month names must be capitalized: "' . $fix . '"',
                ];
                return $fix;
            }, $text) ?? $text;
        }

        return $text;
    }

    /* ─── Multiple spaces → single ─── */
    private function gcFixDoubleSpaces(string $text, array &$issues): string
    {
        if (preg_match('/  +/', $text)) {
            $issues[] = [
                'type'      => 'punctuation',
                'severity'  => 'warning',
                'original'  => 'double spaces',
                'corrected' => 'single spaces',
                'message'   => 'Multiple consecutive spaces removed',
            ];
            $text = preg_replace('/  +/', ' ', $text) ?? $text;
        }
        return $text;
    }

    /* ─── Word-level diff HTML ─── */
    private function gcDiff(string $original, string $corrected): string
    {
        if ($original === $corrected) {
            return nl2br(htmlspecialchars($corrected));
        }

        // Tokenise preserving whitespace
        $oTok = preg_split('/(\s+)/u', $original,  -1, PREG_SPLIT_DELIM_CAPTURE | PREG_SPLIT_NO_EMPTY) ?: [];
        $cTok = preg_split('/(\s+)/u', $corrected, -1, PREG_SPLIT_DELIM_CAPTURE | PREG_SPLIT_NO_EMPTY) ?: [];

        $n = count($oTok);
        $m = count($cTok);

        // LCS table (O(n*m) – safe for ≤ 1500 tokens each side)
        if ($n > 1500 || $m > 1500) {
            return nl2br(htmlspecialchars($corrected)); // too long – skip diff
        }

        $dp = array_fill(0, $n + 1, array_fill(0, $m + 1, 0));
        for ($i = 1; $i <= $n; $i++) {
            for ($j = 1; $j <= $m; $j++) {
                $dp[$i][$j] = $oTok[$i-1] === $cTok[$j-1]
                    ? $dp[$i-1][$j-1] + 1
                    : max($dp[$i-1][$j], $dp[$i][$j-1]);
            }
        }

        // Trace back
        $ops = []; $i = $n; $j = $m;
        while ($i > 0 || $j > 0) {
            if ($i > 0 && $j > 0 && $oTok[$i-1] === $cTok[$j-1]) {
                array_unshift($ops, ['eq',  $cTok[$j-1]]);
                $i--; $j--;
            } elseif ($j > 0 && ($i === 0 || $dp[$i][$j-1] >= $dp[$i-1][$j])) {
                array_unshift($ops, ['ins', $cTok[$j-1]]);
                $j--;
            } else {
                array_unshift($ops, ['del', $oTok[$i-1]]);
                $i--;
            }
        }

        // Build HTML
        $html = '';
        foreach ($ops as $op) {
            $safe   = htmlspecialchars($op[1]);
            $isWs   = ctype_space($op[1]);
            if ($op[0] === 'eq') {
                $html .= $safe;
            } elseif ($op[0] === 'ins') {
                $html .= $isWs ? $safe : '<ins class="gc-ins">' . $safe . '</ins>';
            } else {
                $html .= $isWs ? '' : '<del class="gc-del">' . $safe . '</del>';
            }
        }

        return nl2br($html);
    }

    public function jsonFormatter(array $data): array
    {
        $json   = trim($data['json'] ?? '');
        $indent = max(2, min(8, (int) ($data['indent'] ?? 4)));
        $action = $data['action'] ?? 'format'; // format, minify, validate

        if (empty($json)) {
            return ['success' => false, 'error' => 'Please enter JSON to process.'];
        }

        $decoded = json_decode($json);
        if (json_last_error() !== JSON_ERROR_NONE) {
            return [
                'success' => false,
                'error'   => 'Invalid JSON: ' . json_last_error_msg(),
                'results' => [
                    ['label' => 'Status', 'value' => '❌ Invalid JSON'],
                    ['label' => 'Error', 'value' => json_last_error_msg()],
                ],
            ];
        }

        if ($action === 'minify') {
            $output = json_encode($decoded);
        } else {
            $output = json_encode($decoded, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
            // Apply custom indent
            if ($indent !== 4) {
                $output = preg_replace_callback('/^( +)/m', function ($m) use ($indent) {
                    $spaces = strlen($m[1]);
                    return str_repeat(' ', ($spaces / 4) * $indent);
                }, $output);
            }
        }

        $stats = $this->analyzeJson($decoded);

        return [
            'success' => true,
            'output'  => $output,
            'results' => [
                ['label' => 'Status',      'value' => '✅ Valid JSON', 'highlight' => true],
                ['label' => 'Size (raw)',   'value' => $this->formatBytes(strlen($json))],
                ['label' => 'Size (formatted)', 'value' => $this->formatBytes(strlen($output ?? ''))],
                ['label' => 'Keys',         'value' => $stats['keys']],
                ['label' => 'Arrays',       'value' => $stats['arrays']],
                ['label' => 'Depth',        'value' => $stats['depth']],
            ],
        ];
    }

    private function analyzeJson(mixed $data, int $depth = 0): array
    {
        static $keys = 0, $arrays = 0, $maxDepth = 0;
        $keys = $arrays = $maxDepth = 0;

        $analyze = function ($item, $d) use (&$analyze, &$keys, &$arrays, &$maxDepth) {
            $maxDepth = max($maxDepth, $d);
            if (is_object($item)) {
                foreach ((array)$item as $k => $v) {
                    $keys++;
                    $analyze($v, $d + 1);
                }
            } elseif (is_array($item)) {
                $arrays++;
                foreach ($item as $v) {
                    $analyze($v, $d + 1);
                }
            }
        };

        $analyze($data, 0);
        return compact('keys', 'arrays', 'maxDepth') + ['depth' => $maxDepth];
    }

    private function formatBytes(int $bytes): string
    {
        if ($bytes < 1024) return $bytes . ' B';
        if ($bytes < 1048576) return round($bytes / 1024, 1) . ' KB';
        return round($bytes / 1048576, 1) . ' MB';
    }

    public function textSummarizer(array $data): array
    {
        $text    = trim($data['text'] ?? '');
        $ratio   = max(0.1, min(0.9, (float) ($data['ratio'] ?? 0.3)));
        $method  = $data['method'] ?? 'extractive';

        if (strlen($text) < 50) {
            return ['success' => false, 'error' => 'Text is too short to summarize. Please enter at least 50 characters.'];
        }

        // Split into sentences
        $sentences = preg_split('/(?<=[.!?])\s+/', $text, -1, PREG_SPLIT_NO_EMPTY);
        $wordCount = str_word_count($text);

        if (count($sentences) < 2) {
            return ['success' => false, 'error' => 'Please enter multiple sentences for summarization.'];
        }

        // Score sentences by word frequency (extractive summarization)
        $words = array_map('strtolower', str_word_count($text, 1));
        $stopWords = ['the', 'a', 'an', 'and', 'or', 'but', 'in', 'on', 'at', 'to', 'for', 'of', 'with', 'by', 'from', 'is', 'it', 'this', 'that', 'was', 'are', 'as', 'be', 'been', 'being', 'have', 'has', 'had', 'do', 'does', 'did', 'will', 'would', 'could', 'should', 'may', 'might'];
        $wordFreq = array_count_values(array_diff($words, $stopWords));

        $sentenceScores = [];
        foreach ($sentences as $i => $sentence) {
            $sentWords = array_map('strtolower', str_word_count($sentence, 1));
            $score = 0;
            foreach ($sentWords as $word) {
                $score += $wordFreq[$word] ?? 0;
            }
            // Prefer sentences near the beginning
            $posScore = 1 - ($i / count($sentences)) * 0.3;
            $sentenceScores[$i] = $score * $posScore;
        }

        arsort($sentenceScores);
        $keepCount = max(1, (int) ceil(count($sentences) * $ratio));
        $topIndices = array_slice(array_keys($sentenceScores), 0, $keepCount);
        sort($topIndices);

        $summary = implode(' ', array_map(fn($i) => $sentences[$i], $topIndices));

        return [
            'success' => true,
            'summary' => $summary,
            'results' => [
                ['label' => 'Summary', 'value' => $summary, 'highlight' => true],
                ['label' => 'Original Length',  'value' => $wordCount . ' words'],
                ['label' => 'Summary Length',   'value' => str_word_count($summary) . ' words'],
                ['label' => 'Compression',      'value' => round((1 - str_word_count($summary) / $wordCount) * 100) . '%'],
                ['label' => 'Sentences',        'value' => $keepCount . ' of ' . count($sentences)],
            ],
        ];
    }

    public function wordCount(array $data): array
    {
        $text = $data['text'] ?? '';

        if (empty(trim($text))) {
            return ['success' => false, 'error' => 'Please enter some text.'];
        }

        $words     = str_word_count($text);
        $chars     = strlen($text);
        $charsNoSp = strlen(preg_replace('/\s/', '', $text));
        $sentences = preg_match_all('/[.!?]+/', $text);
        $paragraphs = count(array_filter(preg_split('/\n\n+/', trim($text))));
        $readTime  = max(1, ceil($words / 200));

        return [
            'success' => true,
            'results' => [
                ['label' => 'Words',               'value' => number_format($words), 'highlight' => true],
                ['label' => 'Characters',          'value' => number_format($chars)],
                ['label' => 'Characters (no spaces)', 'value' => number_format($charsNoSp)],
                ['label' => 'Sentences',           'value' => $sentences],
                ['label' => 'Paragraphs',          'value' => $paragraphs],
                ['label' => 'Reading Time',        'value' => $readTime . ' min'],
            ],
        ];
    }

    public function caseConverter(array $data): array
    {
        $text = $data['text'] ?? '';

        if (empty(trim($text))) {
            return ['success' => false, 'error' => 'Please enter some text.'];
        }

        return [
            'success' => true,
            'results' => [
                ['label' => 'UPPERCASE',     'value' => strtoupper($text), 'copyable' => true],
                ['label' => 'lowercase',     'value' => strtolower($text), 'copyable' => true],
                ['label' => 'Title Case',    'value' => ucwords(strtolower($text)), 'copyable' => true],
                ['label' => 'Sentence case', 'value' => ucfirst(strtolower($text)), 'copyable' => true],
                ['label' => 'camelCase',     'value' => lcfirst(str_replace(' ', '', ucwords(strtolower($text)))), 'copyable' => true],
                ['label' => 'snake_case',    'value' => strtolower(str_replace(' ', '_', $text)), 'copyable' => true],
                ['label' => 'kebab-case',    'value' => strtolower(str_replace(' ', '-', $text)), 'copyable' => true],
            ],
        ];
    }
}
