<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class CategorySeeder extends Seeder
{
    public function run(): void
    {
        $categories = [
            ['name' => 'Basic Everyday Calculators',      'icon' => '🔢', 'color' => '#6366f1', 'description' => 'Everyday math calculators for quick calculations'],
            ['name' => 'Scientific & Math Calculators',   'icon' => '📐', 'color' => '#8b5cf6', 'description' => 'Advanced scientific and mathematical tools'],
            ['name' => 'Finance Calculators',             'icon' => '💰', 'color' => '#10b981', 'description' => 'Financial planning and investment calculators'],
            ['name' => 'Health & Fitness Calculators',    'icon' => '💪', 'color' => '#f59e0b', 'description' => 'Health, fitness, and nutrition calculators'],
            ['name' => 'Education Calculators',           'icon' => '📚', 'color' => '#3b82f6', 'description' => 'Study and education tools'],
            ['name' => 'Date & Time Calculators',         'icon' => '📅', 'color' => '#ec4899', 'description' => 'Date, time, and calendar tools'],
            ['name' => 'Unit Conversion Calculators',     'icon' => '📏', 'color' => '#14b8a6', 'description' => 'Convert between different units of measurement'],
            ['name' => 'Construction & Engineering',      'icon' => '🏗️', 'color' => '#f97316', 'description' => 'Construction and engineering calculators'],
            ['name' => 'Business Calculators',            'icon' => '📊', 'color' => '#0ea5e9', 'description' => 'Business metrics and ROI calculators'],
            ['name' => 'Internet & Digital Calculators',  'icon' => '🌐', 'color' => '#6366f1', 'description' => 'Digital and internet-related calculators'],
            ['name' => 'Lifestyle & Misc Calculators',    'icon' => '🎯', 'color' => '#d946ef', 'description' => 'Miscellaneous lifestyle calculators'],
            ['name' => 'Unit Converters',                 'icon' => '🔄', 'color' => '#22c55e', 'description' => 'Convert between units of length, weight, temperature and more'],
            ['name' => 'Expense Trackers',                'icon' => '💳', 'color' => '#ef4444', 'description' => 'Track your expenses and budget'],
            ['name' => 'To-Do Tools',                     'icon' => '✅', 'color' => '#10b981', 'description' => 'Task management and to-do list tools'],
            ['name' => 'Pomodoro & Focus Timers',         'icon' => '⏱️', 'color' => '#f43f5e', 'description' => 'Focus and productivity timers'],
            ['name' => 'QR Code Generators',              'icon' => '📱', 'color' => '#1e293b', 'description' => 'Generate QR codes for any content'],
            ['name' => 'Password Generators',             'icon' => '🔐', 'color' => '#dc2626', 'description' => 'Generate secure passwords'],
            ['name' => 'Notes Tools',                     'icon' => '📝', 'color' => '#fbbf24', 'description' => 'Online note-taking tools'],
            ['name' => 'File Tools',                      'icon' => '📁', 'color' => '#64748b', 'description' => 'File management and conversion tools'],
            ['name' => 'CSV & Data Tools',                'icon' => '📋', 'color' => '#0891b2', 'description' => 'CSV, Excel, and data processing tools'],
            ['name' => 'PDF Tools',                       'icon' => '📄', 'color' => '#dc2626', 'description' => 'PDF creation and manipulation tools'],
            ['name' => 'Image Tools',                     'icon' => '🖼️', 'color' => '#7c3aed', 'description' => 'Image resizing, converting, and editing tools'],
            ['name' => 'Text Tools',                      'icon' => '✍️', 'color' => '#059669', 'description' => 'Text processing, analysis, and formatting tools'],
            ['name' => 'Color Tools',                     'icon' => '🎨', 'color' => '#f43f5e', 'description' => 'Color palette generators and color tools'],
            ['name' => 'Education & Learning',            'icon' => '🎓', 'color' => '#2563eb', 'description' => 'Flashcards, quizzes, and study tools'],
            ['name' => 'Developer Tools',                 'icon' => '💻', 'color' => '#1e293b', 'description' => 'Tools for developers and coders'],
            ['name' => 'Security Tools',                  'icon' => '🔒', 'color' => '#dc2626', 'description' => 'Hash generators, encryption and security tools'],
            ['name' => 'AI & Automation Tools',           'icon' => '🤖', 'color' => '#6366f1', 'description' => 'AI-powered and automation tools'],
            ['name' => 'Statistics Calculators',          'icon' => '📈', 'color' => '#0284c7', 'description' => 'Statistical analysis and data visualization'],
            ['name' => 'Random Generators',               'icon' => '🎲', 'color' => '#7c3aed', 'description' => 'Random number, name, and content generators'],
            ['name' => 'Mini Games',                      'icon' => '🎮', 'color' => '#ec4899', 'description' => 'Fun browser-based mini games'],
            ['name' => 'System & Monitor Tools',          'icon' => '🖥️', 'color' => '#475569', 'description' => 'System monitoring and utility tools'],
            ['name' => 'Typography Tools',                'icon' => '🔤', 'color' => '#0f172a', 'description' => 'Font, typography, and ASCII tools'],
        ];

        foreach ($categories as $i => $cat) {
            Category::updateOrCreate(
                ['slug' => Str::slug($cat['name'])],
                [
                    'name'        => $cat['name'],
                    'slug'        => Str::slug($cat['name']),
                    'description' => $cat['description'],
                    'icon'        => $cat['icon'],
                    'color'       => $cat['color'],
                    'is_active'   => true,
                    'sort_order'  => $i,
                    'seo_title'   => $cat['name'] . ' - Free Online Tools',
                    'seo_description' => $cat['description'] . '. Free, fast, and easy to use.',
                ]
            );
        }
    }
}
