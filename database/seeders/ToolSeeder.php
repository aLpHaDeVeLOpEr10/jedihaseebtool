<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Tool;
use App\Models\ToolFaq;
use App\Models\ToolInput;
use App\Services\BladeGeneratorService;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class ToolSeeder extends Seeder
{
    public function run(): void
    {
        $this->seedWorkingTools();
        $this->seedAdditionalTools();
    }

    private function seedWorkingTools(): void
    {
        $tools = [
            // ===================== CALCULATORS =====================
            [
                'category' => 'Basic Everyday Calculators',
                'name'     => 'Percentage Calculator',
                'slug'     => 'percentage-calculator',
                'icon'     => '📊',
                'color'    => '#6366f1',
                'tool_type' => 'calculator',
                'short_description' => 'Calculate percentages, percentage changes, and percentage of numbers instantly.',
                'long_description'  => "Our Percentage Calculator makes it easy to calculate any percentage quickly. Whether you need to find what percentage one number is of another, calculate a discount, or find percentage change, this tool handles it all.\n\nSimply enter a value and a percentage to get instant results including the percentage amount, the value increased by that percentage, and the value decreased by that percentage.",
                'is_featured' => true,
                'seo_title' => 'Percentage Calculator - Calculate Percentages Online Free',
                'seo_description' => 'Free online percentage calculator. Calculate percentages, percentage of a number, percentage change, and more instantly.',
                'seo_keywords' => 'percentage calculator, calculate percentage, percent calculator, percentage of number',
                'inputs' => [
                    ['field_name' => 'value', 'field_label' => 'Value', 'field_type' => 'number', 'placeholder' => 'e.g. 200', 'required' => true, 'help_text' => 'Enter the base number'],
                    ['field_name' => 'percent', 'field_label' => 'Percentage (%)', 'field_type' => 'number', 'placeholder' => 'e.g. 15', 'required' => true, 'help_text' => 'Enter the percentage'],
                ],
                'faqs' => [
                    ['question' => 'How do I calculate a percentage?', 'answer' => 'To calculate a percentage, divide the part by the whole and multiply by 100. For example, 50/200 × 100 = 25%.'],
                    ['question' => 'How do I calculate a percentage discount?', 'answer' => 'Multiply the original price by the discount percentage and divide by 100. Then subtract from the original price.'],
                    ['question' => 'What is percentage increase?', 'answer' => 'Percentage increase is the change in value expressed as a percentage of the original value: ((new - old) / old) × 100.'],
                ],
            ],
            [
                'category' => 'Health & Fitness Calculators',
                'name'     => 'BMI Calculator',
                'slug'     => 'bmi-calculator',
                'icon'     => '⚖️',
                'color'    => '#f59e0b',
                'tool_type' => 'calculator',
                'short_description' => 'Calculate your Body Mass Index (BMI) with metric or imperial units.',
                'long_description'  => "BMI (Body Mass Index) is a value calculated from a person's weight and height. It's used as a screening tool to identify whether a person is underweight, normal weight, overweight, or obese.\n\nNote: BMI is a simple index and doesn't account for muscle mass, bone density, or fat distribution. Consult a healthcare professional for medical advice.",
                'is_featured' => true,
                'seo_title' => 'BMI Calculator - Body Mass Index Calculator',
                'seo_description' => 'Calculate your BMI (Body Mass Index) instantly. Enter your height and weight in metric or imperial units to find your BMI category.',
                'seo_keywords' => 'BMI calculator, body mass index, bmi calculator metric, healthy weight calculator',
                'inputs' => [
                    ['field_name' => 'unit', 'field_label' => 'Unit System', 'field_type' => 'select', 'options' => [['value' => 'metric', 'label' => 'Metric (kg/cm)'], ['value' => 'imperial', 'label' => 'Imperial (lbs/in)']], 'required' => true],
                    ['field_name' => 'weight', 'field_label' => 'Weight', 'field_type' => 'number', 'placeholder' => 'e.g. 70', 'required' => true, 'help_text' => 'Weight in kg (metric) or lbs (imperial)'],
                    ['field_name' => 'height', 'field_label' => 'Height', 'field_type' => 'number', 'placeholder' => 'e.g. 175', 'required' => true, 'help_text' => 'Height in cm (metric) or inches (imperial)'],
                ],
                'faqs' => [
                    ['question' => 'What is a healthy BMI?', 'answer' => 'A BMI between 18.5 and 24.9 is considered healthy for adults. Below 18.5 is underweight, 25-29.9 is overweight, and 30 or above is obese.'],
                    ['question' => 'Is BMI accurate?', 'answer' => 'BMI is a useful screening tool but has limitations. It doesn\'t account for muscle mass, age, sex, or fat distribution. Athletes may have a high BMI due to muscle, not fat.'],
                ],
            ],
            [
                'category' => 'Finance Calculators',
                'name'     => 'Loan Calculator',
                'slug'     => 'loan-calculator',
                'icon'     => '🏦',
                'color'    => '#10b981',
                'tool_type' => 'calculator',
                'short_description' => 'Calculate monthly loan payments, total interest, and view the full amortization schedule.',
                'long_description'  => "Our Loan Calculator helps you understand the true cost of borrowing. Enter your loan amount, interest rate, and term to instantly see your monthly payment, total amount paid, and total interest cost.\n\nAn amortization schedule showing the first 12 months of payments is also provided, breaking down each payment into principal and interest components.",
                'is_featured' => true,
                'seo_title' => 'Loan Calculator - Monthly Payment & Interest Calculator',
                'seo_description' => 'Free loan calculator. Calculate monthly payments, total interest, and amortization schedule for any loan amount, rate, and term.',
                'seo_keywords' => 'loan calculator, monthly payment calculator, mortgage calculator, amortization calculator',
                'inputs' => [
                    ['field_name' => 'principal', 'field_label' => 'Loan Amount ($)', 'field_type' => 'number', 'placeholder' => 'e.g. 10000', 'required' => true, 'validation' => ['min' => 1]],
                    ['field_name' => 'annual_rate', 'field_label' => 'Annual Interest Rate (%)', 'field_type' => 'number', 'placeholder' => 'e.g. 5.5', 'required' => true, 'validation' => ['min' => 0, 'step' => '0.1']],
                    ['field_name' => 'term_months', 'field_label' => 'Loan Term (months)', 'field_type' => 'number', 'placeholder' => 'e.g. 60', 'required' => true, 'help_text' => '12 = 1 year, 60 = 5 years, 360 = 30 years'],
                ],
                'faqs' => [
                    ['question' => 'How is the monthly payment calculated?', 'answer' => 'Monthly payment = P × r × (1+r)^n / ((1+r)^n - 1), where P is principal, r is monthly interest rate, and n is number of months.'],
                    ['question' => 'What is an amortization schedule?', 'answer' => 'An amortization schedule shows how each payment is split between paying down the principal and paying interest over the life of the loan.'],
                ],
            ],
            [
                'category' => 'Basic Everyday Calculators',
                'name'     => 'Tip Calculator',
                'slug'     => 'tip-calculator',
                'icon'     => '🍽️',
                'color'    => '#f97316',
                'tool_type' => 'calculator',
                'short_description' => 'Calculate how much to tip at a restaurant. Split the bill between friends.',
                'is_featured' => false,
                'inputs' => [
                    ['field_name' => 'bill', 'field_label' => 'Bill Amount ($)', 'field_type' => 'number', 'placeholder' => 'e.g. 45.50', 'required' => true],
                    ['field_name' => 'tip_percent', 'field_label' => 'Tip Percentage (%)', 'field_type' => 'number', 'placeholder' => 'e.g. 18', 'default_value' => '18', 'required' => true],
                    ['field_name' => 'people', 'field_label' => 'Number of People', 'field_type' => 'number', 'placeholder' => 'e.g. 4', 'default_value' => '1', 'required' => true],
                ],
                'faqs' => [
                    ['question' => 'How much should I tip?', 'answer' => 'In the US, 15-20% is standard for restaurants. 10% for OK service, 20% for great service, 25%+ for excellent.'],
                ],
            ],
            [
                'category' => 'Date & Time Calculators',
                'name'     => 'Date Difference Calculator',
                'slug'     => 'date-difference-calculator',
                'icon'     => '📅',
                'color'    => '#ec4899',
                'tool_type' => 'calculator',
                'short_description' => 'Find the number of days, weeks, months, and years between two dates.',
                'is_featured' => false,
                'inputs' => [
                    ['field_name' => 'date1', 'field_label' => 'Start Date', 'field_type' => 'date', 'required' => true],
                    ['field_name' => 'date2', 'field_label' => 'End Date', 'field_type' => 'date', 'required' => true],
                ],
                'faqs' => [],
            ],
            [
                'category' => 'Random Generators',
                'name'     => 'Random Number Generator',
                'slug'     => 'random-number-generator',
                'icon'     => '🎲',
                'color'    => '#7c3aed',
                'tool_type' => 'calculator',
                'short_description' => 'Generate truly random numbers between any range. Generate multiple numbers at once.',
                'is_featured' => false,
                'inputs' => [
                    ['field_name' => 'min', 'field_label' => 'Minimum', 'field_type' => 'number', 'default_value' => '1', 'required' => true],
                    ['field_name' => 'max', 'field_label' => 'Maximum', 'field_type' => 'number', 'default_value' => '100', 'required' => true],
                    ['field_name' => 'count', 'field_label' => 'How Many Numbers?', 'field_type' => 'number', 'default_value' => '1', 'required' => true, 'validation' => ['min' => 1, 'max' => 100]],
                ],
                'faqs' => [],
            ],

            // ===================== CONVERTERS =====================
            [
                'category' => 'Unit Converters',
                'name'     => 'Unit Converter',
                'slug'     => 'unit-converter',
                'icon'     => '🔄',
                'color'    => '#22c55e',
                'tool_type' => 'converter',
                'short_description' => 'Convert between length, weight, temperature, area, volume, speed, and data units.',
                'is_featured' => true,
                'seo_title' => 'Unit Converter - Length, Weight, Temperature & More',
                'seo_description' => 'Free unit converter for length, weight, temperature, area, volume, speed, and data. Instant conversion between all measurement units.',
                'inputs' => [
                    ['field_name' => 'value', 'field_label' => 'Value', 'field_type' => 'number', 'placeholder' => 'e.g. 100', 'required' => true],
                    ['field_name' => 'type', 'field_label' => 'Unit Type', 'field_type' => 'select',
                     'options' => [
                         ['value' => 'length', 'label' => 'Length (m, km, ft, mi...)'],
                         ['value' => 'weight', 'label' => 'Weight (kg, lb, oz...)'],
                         ['value' => 'temperature', 'label' => 'Temperature (C, F, K)'],
                         ['value' => 'area', 'label' => 'Area (m², ft², acre...)'],
                         ['value' => 'volume', 'label' => 'Volume (L, gal, ml...)'],
                         ['value' => 'speed', 'label' => 'Speed (km/h, mph...)'],
                         ['value' => 'data', 'label' => 'Data (B, KB, MB, GB...)'],
                     ], 'required' => true],
                    ['field_name' => 'from', 'field_label' => 'From Unit', 'field_type' => 'text', 'placeholder' => 'e.g. km', 'required' => true, 'help_text' => 'Enter unit: m, km, ft, mi, kg, lb, c, f, k, l, gal, mps, kph, mph, b, kb, mb, gb...'],
                    ['field_name' => 'to', 'field_label' => 'To Unit', 'field_type' => 'text', 'placeholder' => 'e.g. mi', 'required' => true],
                ],
                'faqs' => [
                    ['question' => 'What units are supported?', 'answer' => 'We support length (m, km, ft, mi, yd, in, cm, mm), weight (kg, g, lb, oz, ton), temperature (C, F, K), area (m², ft², km², acre, ha), volume (L, ml, gal, qt, cup), speed (m/s, km/h, mph, knot), and data (B, KB, MB, GB, TB).'],
                ],
            ],
            [
                'category' => 'Finance Calculators',
                'name'     => 'Currency Converter',
                'slug'     => 'currency-converter',
                'icon'     => '💱',
                'color'    => '#10b981',
                'tool_type' => 'converter',
                'short_description' => 'Convert between world currencies using manually managed exchange rates.',
                'is_featured' => true,
                'seo_title' => 'Currency Converter - Convert Between World Currencies',
                'seo_description' => 'Free currency converter supporting 28+ world currencies. Convert USD, EUR, GBP, JPY, and more instantly.',
                'inputs' => [
                    ['field_name' => 'amount', 'field_label' => 'Amount', 'field_type' => 'number', 'placeholder' => 'e.g. 100', 'default_value' => '1', 'required' => true],
                    ['field_name' => 'from', 'field_label' => 'From Currency', 'field_type' => 'select',
                     'options' => [
                         ['value' => 'USD', 'label' => 'USD - US Dollar'],
                         ['value' => 'EUR', 'label' => 'EUR - Euro'],
                         ['value' => 'GBP', 'label' => 'GBP - British Pound'],
                         ['value' => 'JPY', 'label' => 'JPY - Japanese Yen'],
                         ['value' => 'AUD', 'label' => 'AUD - Australian Dollar'],
                         ['value' => 'CAD', 'label' => 'CAD - Canadian Dollar'],
                         ['value' => 'CHF', 'label' => 'CHF - Swiss Franc'],
                         ['value' => 'CNY', 'label' => 'CNY - Chinese Yuan'],
                         ['value' => 'INR', 'label' => 'INR - Indian Rupee'],
                         ['value' => 'MXN', 'label' => 'MXN - Mexican Peso'],
                         ['value' => 'BRL', 'label' => 'BRL - Brazilian Real'],
                         ['value' => 'KRW', 'label' => 'KRW - South Korean Won'],
                         ['value' => 'SGD', 'label' => 'SGD - Singapore Dollar'],
                         ['value' => 'AED', 'label' => 'AED - UAE Dirham'],
                         ['value' => 'NGN', 'label' => 'NGN - Nigerian Naira'],
                     ], 'required' => true],
                    ['field_name' => 'to', 'field_label' => 'To Currency', 'field_type' => 'select',
                     'options' => [
                         ['value' => 'EUR', 'label' => 'EUR - Euro'],
                         ['value' => 'USD', 'label' => 'USD - US Dollar'],
                         ['value' => 'GBP', 'label' => 'GBP - British Pound'],
                         ['value' => 'JPY', 'label' => 'JPY - Japanese Yen'],
                         ['value' => 'AUD', 'label' => 'AUD - Australian Dollar'],
                         ['value' => 'CAD', 'label' => 'CAD - Canadian Dollar'],
                         ['value' => 'CHF', 'label' => 'CHF - Swiss Franc'],
                         ['value' => 'CNY', 'label' => 'CNY - Chinese Yuan'],
                         ['value' => 'INR', 'label' => 'INR - Indian Rupee'],
                         ['value' => 'MXN', 'label' => 'MXN - Mexican Peso'],
                         ['value' => 'BRL', 'label' => 'BRL - Brazilian Real'],
                         ['value' => 'NGN', 'label' => 'NGN - Nigerian Naira'],
                         ['value' => 'AED', 'label' => 'AED - UAE Dirham'],
                         ['value' => 'ZAR', 'label' => 'ZAR - South African Rand'],
                     ], 'required' => true],
                ],
                'faqs' => [
                    ['question' => 'Are the exchange rates live?', 'answer' => 'Exchange rates are manually managed by the admin and should be updated regularly. They are not connected to a live feed.'],
                ],
            ],

            // ===================== GENERATORS =====================
            [
                'category' => 'Password Generators',
                'name'     => 'Password Generator',
                'slug'     => 'password-generator',
                'icon'     => '🔐',
                'color'    => '#dc2626',
                'tool_type' => 'generator',
                'short_description' => 'Generate strong, secure passwords with customizable length and character types.',
                'is_featured' => true,
                'seo_title' => 'Password Generator - Generate Secure Passwords Online',
                'seo_description' => 'Free online password generator. Create strong, secure, random passwords with letters, numbers, and symbols.',
                'inputs' => [
                    ['field_name' => 'length', 'field_label' => 'Password Length', 'field_type' => 'range', 'default_value' => '16', 'validation' => ['min' => 4, 'max' => 64]],
                    ['field_name' => 'count', 'field_label' => 'Number of Passwords', 'field_type' => 'number', 'default_value' => '1', 'validation' => ['min' => 1, 'max' => 10]],
                    ['field_name' => 'uppercase', 'field_label' => 'Include Uppercase (A-Z)', 'field_type' => 'checkbox', 'default_value' => '1'],
                    ['field_name' => 'lowercase', 'field_label' => 'Include Lowercase (a-z)', 'field_type' => 'checkbox', 'default_value' => '1'],
                    ['field_name' => 'numbers', 'field_label' => 'Include Numbers (0-9)', 'field_type' => 'checkbox', 'default_value' => '1'],
                    ['field_name' => 'symbols', 'field_label' => 'Include Symbols (!@#$)', 'field_type' => 'checkbox', 'default_value' => '1'],
                ],
                'faqs' => [
                    ['question' => 'How long should my password be?', 'answer' => 'For strong security, use at least 12-16 characters with a mix of uppercase, lowercase, numbers, and symbols.'],
                    ['question' => 'Is this generator secure?', 'answer' => 'Yes, passwords are generated in your browser using cryptographically secure random functions. They are never sent to our servers.'],
                ],
            ],
            [
                'category' => 'QR Code Generators',
                'name'     => 'QR Code Generator',
                'slug'     => 'qr-code-generator',
                'icon'     => '📱',
                'color'    => '#1e293b',
                'tool_type' => 'generator',
                'short_description' => 'Generate QR codes for URLs, text, emails, and more. Download as PNG.',
                'is_featured' => true,
                'seo_title' => 'QR Code Generator - Free QR Code Creator Online',
                'seo_description' => 'Create custom QR codes for URLs, text, wifi, and more. Download your QR code as PNG for free.',
                'inputs' => [
                    ['field_name' => 'text', 'field_label' => 'Content (URL, Text, etc.)', 'field_type' => 'textarea', 'placeholder' => 'https://example.com or any text...', 'required' => true],
                    ['field_name' => 'size', 'field_label' => 'Size (px)', 'field_type' => 'range', 'default_value' => '300', 'validation' => ['min' => 100, 'max' => 500, 'step' => 50]],
                    ['field_name' => 'color', 'field_label' => 'QR Color', 'field_type' => 'color', 'default_value' => '#000000'],
                    ['field_name' => 'background', 'field_label' => 'Background Color', 'field_type' => 'color', 'default_value' => '#ffffff'],
                ],
                'faqs' => [
                    ['question' => 'What content can I encode in a QR code?', 'answer' => 'URLs, plain text, email addresses, phone numbers, SMS messages, WiFi credentials, vCards, and more.'],
                    ['question' => 'How do I scan a QR code?', 'answer' => 'Use your phone\'s camera app (on most modern phones) or a free QR code scanner app.'],
                ],
            ],
            [
                'category' => 'Color Tools',
                'name'     => 'Color Palette Generator',
                'slug'     => 'color-palette-generator',
                'icon'     => '🎨',
                'color'    => '#f43f5e',
                'tool_type' => 'generator',
                'short_description' => 'Generate beautiful color palettes from a base color. Multiple harmony modes.',
                'is_featured' => true,
                'inputs' => [
                    ['field_name' => 'base_color', 'field_label' => 'Base Color', 'field_type' => 'color', 'default_value' => '#6366f1', 'required' => true],
                    ['field_name' => 'mode', 'field_label' => 'Color Harmony', 'field_type' => 'select',
                     'options' => [
                         ['value' => 'analogous', 'label' => 'Analogous'],
                         ['value' => 'complementary', 'label' => 'Complementary'],
                         ['value' => 'triadic', 'label' => 'Triadic'],
                         ['value' => 'monochromatic', 'label' => 'Monochromatic'],
                         ['value' => 'split-complementary', 'label' => 'Split Complementary'],
                     ]],
                    ['field_name' => 'count', 'field_label' => 'Number of Colors', 'field_type' => 'range', 'default_value' => '5', 'validation' => ['min' => 3, 'max' => 8]],
                ],
                'faqs' => [
                    ['question' => 'What are color harmonies?', 'answer' => 'Color harmonies are combinations of colors that are pleasing to the eye. Analogous uses adjacent colors, complementary uses opposite colors, and triadic uses three evenly spaced colors.'],
                ],
            ],

            // ===================== TEXT TOOLS =====================
            [
                'category' => 'Developer Tools',
                'name'     => 'JSON Formatter',
                'slug'     => 'json-formatter',
                'icon'     => '📋',
                'color'    => '#0ea5e9',
                'tool_type' => 'text',
                'short_description' => 'Format, validate, and minify JSON. Instant JSON beautifier and validator.',
                'is_featured' => true,
                'seo_title' => 'JSON Formatter & Validator - Format JSON Online',
                'seo_description' => 'Free online JSON formatter, validator, and minifier. Instantly format and beautify JSON code.',
                'inputs' => [
                    ['field_name' => 'json', 'field_label' => 'JSON Input', 'field_type' => 'textarea', 'placeholder' => '{"key": "value", "array": [1, 2, 3]}', 'required' => true],
                    ['field_name' => 'action', 'field_label' => 'Action', 'field_type' => 'select',
                     'options' => [
                         ['value' => 'format', 'label' => 'Format & Beautify'],
                         ['value' => 'minify', 'label' => 'Minify'],
                         ['value' => 'validate', 'label' => 'Validate Only'],
                     ]],
                    ['field_name' => 'indent', 'field_label' => 'Indent Size', 'field_type' => 'select',
                     'options' => [
                         ['value' => '2', 'label' => '2 spaces'],
                         ['value' => '4', 'label' => '4 spaces'],
                         ['value' => '8', 'label' => '8 spaces'],
                     ], 'default_value' => '4'],
                ],
                'faqs' => [
                    ['question' => 'What is JSON?', 'answer' => 'JSON (JavaScript Object Notation) is a lightweight data interchange format. It\'s easy for humans to read and write, and easy for machines to parse and generate.'],
                    ['question' => 'What is JSON minification?', 'answer' => 'Minification removes all unnecessary whitespace and formatting from JSON, reducing file size for transmission.'],
                ],
            ],
            [
                'category' => 'Text Tools',
                'name'     => 'Text Summarizer',
                'slug'     => 'text-summarizer',
                'icon'     => '✍️',
                'color'    => '#059669',
                'tool_type' => 'text',
                'short_description' => 'Summarize long text using extractive summarization. Get key sentences automatically.',
                'is_featured' => false,
                'inputs' => [
                    ['field_name' => 'text', 'field_label' => 'Text to Summarize', 'field_type' => 'textarea', 'placeholder' => 'Paste your text here (minimum 50 characters)...', 'required' => true],
                    ['field_name' => 'ratio', 'field_label' => 'Summary Length', 'field_type' => 'select',
                     'options' => [
                         ['value' => '0.2', 'label' => 'Very Short (20%)'],
                         ['value' => '0.3', 'label' => 'Short (30%)'],
                         ['value' => '0.5', 'label' => 'Medium (50%)'],
                         ['value' => '0.7', 'label' => 'Long (70%)'],
                     ], 'default_value' => '0.3'],
                ],
                'faqs' => [
                    ['question' => 'How does the summarizer work?', 'answer' => 'We use extractive summarization — analyzing word frequency and sentence position to identify the most important sentences in your text.'],
                ],
            ],

            // ===================== PRODUCTIVITY =====================
            [
                'category' => 'To-Do Tools',
                'name'     => 'To-Do List',
                'slug'     => 'todo-list',
                'icon'     => '✅',
                'color'    => '#10b981',
                'tool_type' => 'productivity',
                'short_description' => 'A clean, fast to-do list that saves locally in your browser. No account needed.',
                'is_featured' => true,
                'inputs' => [],
                'faqs' => [
                    ['question' => 'Is my data saved?', 'answer' => 'Yes, your tasks are saved in your browser\'s local storage. They persist across page refreshes but are specific to your device and browser.'],
                    ['question' => 'Can I sync across devices?', 'answer' => 'Currently tasks are stored locally in your browser. They won\'t sync across different devices.'],
                ],
            ],
            [
                'category' => 'Notes Tools',
                'name'     => 'Notes App',
                'slug'     => 'notes-app',
                'icon'     => '📝',
                'color'    => '#fbbf24',
                'tool_type' => 'productivity',
                'short_description' => 'A minimal notes app with local storage. Create, edit, and search notes.',
                'is_featured' => false,
                'inputs' => [],
                'faqs' => [
                    ['question' => 'Where are my notes stored?', 'answer' => 'Notes are saved in your browser\'s local storage. They\'re private and only accessible on your device.'],
                ],
            ],
        ];

        foreach ($tools as $toolData) {
            $category = Category::where('name', $toolData['category'])->first();
            if (!$category) continue;

            $inputs = $toolData['inputs'] ?? [];
            $faqs   = $toolData['faqs']   ?? [];

            unset($toolData['inputs'], $toolData['faqs'], $toolData['category']);

            $tool = Tool::updateOrCreate(
                ['slug' => $toolData['slug']],
                array_merge($toolData, [
                    'category_id' => $category->id,
                    'status'      => 'active',
                    'sort_order'  => 0,
                    'view_count'  => rand(100, 5000),
                    'use_count'   => rand(50, 2000),
                ])
            );

            // Seed inputs
            $tool->inputs()->delete();
            foreach ($inputs as $i => $input) {
                $tool->inputs()->create([
                    'field_name'    => $input['field_name'],
                    'field_label'   => $input['field_label'],
                    'field_type'    => $input['field_type'],
                    'placeholder'   => $input['placeholder'] ?? null,
                    'default_value' => $input['default_value'] ?? null,
                    'required'      => $input['required'] ?? false,
                    'options'       => isset($input['options']) ? json_encode($input['options']) : null,
                    'validation'    => isset($input['validation']) ? json_encode($input['validation']) : null,
                    'help_text'     => $input['help_text'] ?? null,
                    'sort_order'    => $i,
                ]);
            }

            // Seed FAQs
            $tool->faqs()->delete();
            foreach ($faqs as $i => $faq) {
                $tool->faqs()->create([
                    'question'   => $faq['question'],
                    'answer'     => $faq['answer'],
                    'sort_order' => $i,
                ]);
            }

            // Generate blade files for todo and notes
            if (in_array($tool->slug, ['todo-list', 'notes-app'])) {
                try {
                    $generator = app(BladeGeneratorService::class);
                    $generator->generate($tool);
                } catch (\Exception $e) {
                    // Continue silently
                }
            }
        }
    }

    private function seedAdditionalTools(): void
    {
        $additionalTools = [
            // Calculators
            ['category' => 'Basic Everyday Calculators', 'name' => 'Average Calculator', 'slug' => 'average-calculator', 'icon' => '📊', 'tool_type' => 'calculator'],
            ['category' => 'Basic Everyday Calculators', 'name' => 'Fraction Calculator', 'slug' => 'fraction-calculator', 'icon' => '½', 'tool_type' => 'calculator'],
            ['category' => 'Basic Everyday Calculators', 'name' => 'Discount Calculator', 'slug' => 'discount-calculator', 'icon' => '🏷️', 'tool_type' => 'calculator'],
            ['category' => 'Scientific & Math Calculators', 'name' => 'Scientific Calculator', 'slug' => 'scientific-calculator', 'icon' => '🔬', 'tool_type' => 'calculator'],
            ['category' => 'Scientific & Math Calculators', 'name' => 'Statistics Calculator', 'slug' => 'statistics-calculator', 'icon' => '📈', 'tool_type' => 'calculator'],
            ['category' => 'Scientific & Math Calculators', 'name' => 'Triangle Calculator', 'slug' => 'triangle-calculator', 'icon' => '📐', 'tool_type' => 'calculator'],
            ['category' => 'Finance Calculators', 'name' => 'Compound Interest Calculator', 'slug' => 'compound-interest-calculator', 'icon' => '📈', 'tool_type' => 'calculator'],
            ['category' => 'Finance Calculators', 'name' => 'Savings Calculator', 'slug' => 'savings-calculator', 'icon' => '🏦', 'tool_type' => 'calculator'],
            ['category' => 'Finance Calculators', 'name' => 'Mortgage Calculator', 'slug' => 'mortgage-calculator', 'icon' => '🏠', 'tool_type' => 'calculator'],
            ['category' => 'Finance Calculators', 'name' => 'ROI Calculator', 'slug' => 'roi-calculator', 'icon' => '💹', 'tool_type' => 'calculator'],
            ['category' => 'Finance Calculators', 'name' => 'Tax Calculator', 'slug' => 'tax-calculator', 'icon' => '💸', 'tool_type' => 'calculator'],
            ['category' => 'Health & Fitness Calculators', 'name' => 'Calorie Calculator', 'slug' => 'calorie-calculator', 'icon' => '🥗', 'tool_type' => 'calculator'],
            ['category' => 'Health & Fitness Calculators', 'name' => 'Ideal Weight Calculator', 'slug' => 'ideal-weight-calculator', 'icon' => '⚖️', 'tool_type' => 'calculator'],
            ['category' => 'Health & Fitness Calculators', 'name' => 'Body Fat Calculator', 'slug' => 'body-fat-calculator', 'icon' => '💪', 'tool_type' => 'calculator'],
            ['category' => 'Health & Fitness Calculators', 'name' => 'Water Intake Calculator', 'slug' => 'water-intake-calculator', 'icon' => '💧', 'tool_type' => 'calculator'],
            ['category' => 'Date & Time Calculators', 'name' => 'Age Calculator', 'slug' => 'age-calculator', 'icon' => '🎂', 'tool_type' => 'calculator'],
            ['category' => 'Date & Time Calculators', 'name' => 'Time Zone Converter', 'slug' => 'time-zone-converter', 'icon' => '🌍', 'tool_type' => 'converter'],
            ['category' => 'Date & Time Calculators', 'name' => 'Countdown Timer', 'slug' => 'countdown-timer', 'icon' => '⏰', 'tool_type' => 'productivity'],
            ['category' => 'Construction & Engineering', 'name' => 'Concrete Calculator', 'slug' => 'concrete-calculator', 'icon' => '🏗️', 'tool_type' => 'calculator'],
            ['category' => 'Construction & Engineering', 'name' => 'Area Calculator', 'slug' => 'area-calculator', 'icon' => '📐', 'tool_type' => 'calculator'],
            ['category' => 'Business Calculators', 'name' => 'Profit Margin Calculator', 'slug' => 'profit-margin-calculator', 'icon' => '💼', 'tool_type' => 'calculator'],
            ['category' => 'Business Calculators', 'name' => 'Break Even Calculator', 'slug' => 'break-even-calculator', 'icon' => '📊', 'tool_type' => 'calculator'],
            ['category' => 'Internet & Digital Calculators', 'name' => 'Bandwidth Calculator', 'slug' => 'bandwidth-calculator', 'icon' => '📡', 'tool_type' => 'calculator'],
            ['category' => 'Internet & Digital Calculators', 'name' => 'Download Time Calculator', 'slug' => 'download-time-calculator', 'icon' => '⬇️', 'tool_type' => 'calculator'],

            // Converters
            ['category' => 'Unit Converters', 'name' => 'Temperature Converter', 'slug' => 'temperature-converter', 'icon' => '🌡️', 'tool_type' => 'converter'],
            ['category' => 'Unit Converters', 'name' => 'Length Converter', 'slug' => 'length-converter', 'icon' => '📏', 'tool_type' => 'converter'],
            ['category' => 'Unit Converters', 'name' => 'Weight Converter', 'slug' => 'weight-converter', 'icon' => '⚖️', 'tool_type' => 'converter'],
            ['category' => 'Unit Converters', 'name' => 'Volume Converter', 'slug' => 'volume-converter', 'icon' => '🧪', 'tool_type' => 'converter'],
            ['category' => 'Unit Converters', 'name' => 'Speed Converter', 'slug' => 'speed-converter', 'icon' => '💨', 'tool_type' => 'converter'],
            ['category' => 'Unit Converters', 'name' => 'Data Storage Converter', 'slug' => 'data-storage-converter', 'icon' => '💾', 'tool_type' => 'converter'],

            // Text tools
            ['category' => 'Text Tools', 'name' => 'Word Counter', 'slug' => 'word-counter', 'icon' => '🔢', 'tool_type' => 'text'],
            ['category' => 'Text Tools', 'name' => 'Case Converter', 'slug' => 'case-converter', 'icon' => '✍️', 'tool_type' => 'text'],
            ['category' => 'Text Tools', 'name' => 'Lorem Ipsum Generator', 'slug' => 'lorem-ipsum-generator', 'icon' => '📄', 'tool_type' => 'generator'],
            ['category' => 'Text Tools', 'name' => 'Text Reverser', 'slug' => 'text-reverser', 'icon' => '🔄', 'tool_type' => 'text'],
            ['category' => 'Text Tools', 'name' => 'Duplicate Line Remover', 'slug' => 'duplicate-line-remover', 'icon' => '🗑️', 'tool_type' => 'text'],

            // Developer tools
            ['category' => 'Developer Tools', 'name' => 'HTML Encoder/Decoder', 'slug' => 'html-encoder-decoder', 'icon' => '🌐', 'tool_type' => 'text'],
            ['category' => 'Developer Tools', 'name' => 'URL Encoder/Decoder', 'slug' => 'url-encoder-decoder', 'icon' => '🔗', 'tool_type' => 'text'],
            ['category' => 'Developer Tools', 'name' => 'Base64 Encoder/Decoder', 'slug' => 'base64-encoder-decoder', 'icon' => '🔐', 'tool_type' => 'text'],
            ['category' => 'Developer Tools', 'name' => 'Markdown Editor', 'slug' => 'markdown-editor', 'icon' => '📝', 'tool_type' => 'text'],
            ['category' => 'Developer Tools', 'name' => 'Regex Tester', 'slug' => 'regex-tester', 'icon' => '🔍', 'tool_type' => 'text'],
            ['category' => 'Developer Tools', 'name' => 'CSS Minifier', 'slug' => 'css-minifier', 'icon' => '🎨', 'tool_type' => 'text'],
            ['category' => 'Developer Tools', 'name' => 'SQL Formatter', 'slug' => 'sql-formatter', 'icon' => '🗄️', 'tool_type' => 'text'],
            ['category' => 'Developer Tools', 'name' => 'Diff Checker', 'slug' => 'diff-checker', 'icon' => '🔄', 'tool_type' => 'text'],
            ['category' => 'Developer Tools', 'name' => 'Cron Expression Generator', 'slug' => 'cron-expression-generator', 'icon' => '⏱️', 'tool_type' => 'generator'],

            // Security
            ['category' => 'Security Tools', 'name' => 'MD5 Hash Generator', 'slug' => 'md5-hash-generator', 'icon' => '🔒', 'tool_type' => 'generator'],
            ['category' => 'Security Tools', 'name' => 'SHA256 Hash Generator', 'slug' => 'sha256-hash-generator', 'icon' => '🔒', 'tool_type' => 'generator'],
            ['category' => 'Security Tools', 'name' => 'UUID Generator', 'slug' => 'uuid-generator', 'icon' => '🆔', 'tool_type' => 'generator'],
            ['category' => 'Security Tools', 'name' => 'Password Strength Checker', 'slug' => 'password-strength-checker', 'icon' => '🛡️', 'tool_type' => 'text'],

            // Image tools
            ['category' => 'Image Tools', 'name' => 'Image Compressor', 'slug' => 'image-compressor', 'icon' => '🗜️', 'tool_type' => 'file'],
            ['category' => 'Image Tools', 'name' => 'Image Resizer', 'slug' => 'image-resizer', 'icon' => '🖼️', 'tool_type' => 'file'],
            ['category' => 'Image Tools', 'name' => 'Image Converter', 'slug' => 'image-converter', 'icon' => '🔄', 'tool_type' => 'file'],
            ['category' => 'Image Tools', 'name' => 'Watermark Tool', 'slug' => 'watermark-tool', 'icon' => '💧', 'tool_type' => 'file'],
            ['category' => 'Image Tools', 'name' => 'Meme Generator', 'slug' => 'meme-generator', 'icon' => '😂', 'tool_type' => 'generator'],

            // Productivity
            ['category' => 'Pomodoro & Focus Timers', 'name' => 'Pomodoro Timer', 'slug' => 'pomodoro-timer', 'icon' => '🍅', 'tool_type' => 'productivity'],
            ['category' => 'Pomodoro & Focus Timers', 'name' => 'Study Timer', 'slug' => 'study-timer', 'icon' => '📚', 'tool_type' => 'productivity'],
            ['category' => 'Expense Trackers', 'name' => 'Expense Tracker', 'slug' => 'expense-tracker', 'icon' => '💳', 'tool_type' => 'productivity'],
            ['category' => 'Expense Trackers', 'name' => 'Budget Planner', 'slug' => 'budget-planner', 'icon' => '📊', 'tool_type' => 'productivity'],

            // Education
            ['category' => 'Education & Learning', 'name' => 'Flashcard Creator', 'slug' => 'flashcard-creator', 'icon' => '🃏', 'tool_type' => 'productivity'],
            ['category' => 'Education & Learning', 'name' => 'Quiz Generator', 'slug' => 'quiz-generator', 'icon' => '❓', 'tool_type' => 'generator'],
            ['category' => 'Education & Learning', 'name' => 'Typing Speed Test', 'slug' => 'typing-speed-test', 'icon' => '⌨️', 'tool_type' => 'game'],

            // Color tools
            ['category' => 'Color Tools', 'name' => 'Hex to RGB Converter', 'slug' => 'hex-to-rgb-converter', 'icon' => '🎨', 'tool_type' => 'converter'],
            ['category' => 'Color Tools', 'name' => 'Color Picker', 'slug' => 'color-picker', 'icon' => '🖌️', 'tool_type' => 'text'],
            ['category' => 'Color Tools', 'name' => 'Gradient Generator', 'slug' => 'gradient-generator', 'icon' => '🌈', 'tool_type' => 'generator'],

            // Random generators
            ['category' => 'Random Generators', 'name' => 'Name Generator', 'slug' => 'name-generator', 'icon' => '👤', 'tool_type' => 'generator'],
            ['category' => 'Random Generators', 'name' => 'Random Word Generator', 'slug' => 'random-word-generator', 'icon' => '📖', 'tool_type' => 'generator'],
            ['category' => 'Random Generators', 'name' => 'Dice Roller', 'slug' => 'dice-roller', 'icon' => '🎲', 'tool_type' => 'generator'],
            ['category' => 'Random Generators', 'name' => 'Coin Flip', 'slug' => 'coin-flip', 'icon' => '🪙', 'tool_type' => 'generator'],
            ['category' => 'Random Generators', 'name' => 'Wheel of Fortune', 'slug' => 'wheel-of-fortune', 'icon' => '🎡', 'tool_type' => 'game'],

            // Mini Games
            ['category' => 'Mini Games', 'name' => 'Tic Tac Toe', 'slug' => 'tic-tac-toe', 'icon' => '⭕', 'tool_type' => 'game'],
            ['category' => 'Mini Games', 'name' => 'Memory Match Game', 'slug' => 'memory-match-game', 'icon' => '🃏', 'tool_type' => 'game'],
            ['category' => 'Mini Games', 'name' => 'Word Scramble', 'slug' => 'word-scramble', 'icon' => '🔤', 'tool_type' => 'game'],
            ['category' => 'Mini Games', 'name' => 'Number Guessing Game', 'slug' => 'number-guessing-game', 'icon' => '🔢', 'tool_type' => 'game'],

            // Typography
            ['category' => 'Typography Tools', 'name' => 'ASCII Art Generator', 'slug' => 'ascii-art-generator', 'icon' => '🔤', 'tool_type' => 'generator'],
            ['category' => 'Typography Tools', 'name' => 'Font Preview Tool', 'slug' => 'font-preview-tool', 'icon' => '🔡', 'tool_type' => 'text'],

            // File tools
            ['category' => 'File Tools', 'name' => 'File Size Calculator', 'slug' => 'file-size-calculator', 'icon' => '📁', 'tool_type' => 'calculator'],
            ['category' => 'CSV & Data Tools', 'name' => 'CSV Viewer', 'slug' => 'csv-viewer', 'icon' => '📋', 'tool_type' => 'file'],
            ['category' => 'CSV & Data Tools', 'name' => 'JSON to CSV Converter', 'slug' => 'json-to-csv-converter', 'icon' => '🔄', 'tool_type' => 'converter'],
            ['category' => 'PDF Tools', 'name' => 'PDF Merger', 'slug' => 'pdf-merger', 'icon' => '📄', 'tool_type' => 'file'],

            // AI tools
            ['category' => 'AI & Automation Tools', 'name' => 'Text Paraphraser', 'slug' => 'text-paraphraser', 'icon' => '🤖', 'tool_type' => 'text'],
            ['category' => 'AI & Automation Tools', 'name' => 'Grammar Checker', 'slug' => 'grammar-checker', 'icon' => '✅', 'tool_type' => 'text'],
        ];

        foreach ($additionalTools as $i => $toolData) {
            $category = Category::where('name', $toolData['category'])->first();
            if (!$category) continue;

            $slug = $toolData['slug'];
            unset($toolData['category']);

            Tool::updateOrCreate(
                ['slug' => $slug],
                array_merge($toolData, [
                    'category_id'       => $category->id,
                    'status'            => 'active',
                    'sort_order'        => $i + 100,
                    'color'             => '#6366f1',
                    'short_description' => 'Free online ' . strtolower($toolData['name']) . '. Fast, easy, and no registration required.',
                    'view_count'        => rand(10, 500),
                    'use_count'         => rand(5, 200),
                    'is_featured'       => false,
                ])
            );
        }
    }
}
