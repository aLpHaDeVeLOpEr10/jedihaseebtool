<?php

namespace App\Services;

use App\Models\Tool;

class CalculatorEngine
{
    public function handle(array $data, Tool $tool): array
    {
        $slug = $tool->slug;

        return match (true) {
            str_contains($slug, 'percentage')  => $this->percentage($data),
            str_contains($slug, 'bmi')         => $this->bmi($data),
            str_contains($slug, 'loan')        => $this->loan($data),
            str_contains($slug, 'tip')         => $this->tip($data),
            str_contains($slug, 'date-diff') || str_contains($slug, 'date-difference') => $this->dateDiff($data),
            str_contains($slug, 'random-number') => $this->randomNumber($data),
            default                            => ['success' => false, 'error' => 'Calculation not implemented for this tool yet.'],
        };
    }

    public function percentage(array $data): array
    {
        $value   = (float) ($data['value'] ?? 0);
        $percent = (float) ($data['percent'] ?? 0);

        if ($value == 0 && $percent == 0) {
            return ['success' => false, 'error' => 'Please enter valid values.'];
        }

        $result       = ($value * $percent) / 100;
        $valueMinusP  = $value - $result;
        $valuePlusP   = $value + $result;

        return [
            'success' => true,
            'results' => [
                ['label' => "{$percent}% of {$value}", 'value' => number_format($result, 4)],
                ['label' => "{$value} + {$percent}%", 'value' => number_format($valuePlusP, 4)],
                ['label' => "{$value} - {$percent}%", 'value' => number_format($valueMinusP, 4)],
                ['label' => 'Percentage of total (if total is 100)', 'value' => number_format(($result / 100) * 100, 4) . '%'],
            ],
        ];
    }

    public function bmi(array $data): array
    {
        $unit   = $data['unit'] ?? 'metric';
        $weight = (float) ($data['weight'] ?? 0);
        $height = (float) ($data['height'] ?? 0);

        if ($weight <= 0 || $height <= 0) {
            return ['success' => false, 'error' => 'Please enter valid weight and height.'];
        }

        if ($unit === 'metric') {
            $heightM = $height / 100;
            $bmi     = $weight / ($heightM * $heightM);
        } else {
            // Imperial: weight in lbs, height in inches
            $bmi = (703 * $weight) / ($height * $height);
        }

        $bmi      = round($bmi, 1);
        $category = $this->bmiCategory($bmi);

        $weightUnit  = $unit === 'metric' ? 'kg' : 'lbs';
        $heightUnit  = $unit === 'metric' ? 'cm' : 'in';

        return [
            'success' => true,
            'results' => [
                ['label' => 'Your BMI', 'value' => $bmi, 'highlight' => true],
                ['label' => 'Category', 'value' => $category['label']],
                ['label' => 'Weight', 'value' => "{$weight} {$weightUnit}"],
                ['label' => 'Height', 'value' => "{$height} {$heightUnit}"],
            ],
            'category' => $category,
            'chart' => [
                ['label' => 'Underweight', 'max' => 18.5, 'color' => '#3b82f6'],
                ['label' => 'Normal',      'max' => 25,   'color' => '#22c55e'],
                ['label' => 'Overweight',  'max' => 30,   'color' => '#f59e0b'],
                ['label' => 'Obese',       'max' => 40,   'color' => '#ef4444'],
            ],
        ];
    }

    private function bmiCategory(float $bmi): array
    {
        return match (true) {
            $bmi < 18.5 => ['label' => 'Underweight', 'color' => 'text-blue-600', 'bg' => 'bg-blue-100'],
            $bmi < 25   => ['label' => 'Normal weight', 'color' => 'text-green-600', 'bg' => 'bg-green-100'],
            $bmi < 30   => ['label' => 'Overweight', 'color' => 'text-yellow-600', 'bg' => 'bg-yellow-100'],
            default     => ['label' => 'Obese', 'color' => 'text-red-600', 'bg' => 'bg-red-100'],
        };
    }

    public function loan(array $data): array
    {
        $principal   = (float) ($data['principal'] ?? 0);
        $annualRate  = (float) ($data['annual_rate'] ?? 0);
        $termMonths  = (int)   ($data['term_months'] ?? 0);

        if ($principal <= 0 || $termMonths <= 0) {
            return ['success' => false, 'error' => 'Please enter valid loan amount and term.'];
        }

        if ($annualRate == 0) {
            $monthlyPayment = $principal / $termMonths;
            $totalPayment   = $principal;
            $totalInterest  = 0;
        } else {
            $r              = $annualRate / 100 / 12;
            $monthlyPayment = $principal * $r * pow(1 + $r, $termMonths) / (pow(1 + $r, $termMonths) - 1);
            $totalPayment   = $monthlyPayment * $termMonths;
            $totalInterest  = $totalPayment - $principal;
        }

        return [
            'success' => true,
            'results' => [
                ['label' => 'Monthly Payment',  'value' => '$' . number_format($monthlyPayment, 2), 'highlight' => true],
                ['label' => 'Total Payment',    'value' => '$' . number_format($totalPayment, 2)],
                ['label' => 'Total Interest',   'value' => '$' . number_format($totalInterest, 2)],
                ['label' => 'Loan Amount',      'value' => '$' . number_format($principal, 2)],
                ['label' => 'Annual Rate',      'value' => $annualRate . '%'],
                ['label' => 'Loan Term',        'value' => $termMonths . ' months (' . round($termMonths / 12, 1) . ' years)'],
            ],
            'schedule_preview' => $this->loanSchedulePreview($principal, $annualRate / 100 / 12, $monthlyPayment, min($termMonths, 12)),
        ];
    }

    private function loanSchedulePreview(float $principal, float $r, float $monthly, int $months): array
    {
        $balance  = $principal;
        $schedule = [];
        for ($i = 1; $i <= $months; $i++) {
            $interest     = $balance * $r;
            $principalPmt = $monthly - $interest;
            $balance      -= $principalPmt;
            $schedule[]   = [
                'month'     => $i,
                'payment'   => round($monthly, 2),
                'principal' => round($principalPmt, 2),
                'interest'  => round($interest, 2),
                'balance'   => max(0, round($balance, 2)),
            ];
        }
        return $schedule;
    }

    public function tip(array $data): array
    {
        $bill        = (float) ($data['bill'] ?? 0);
        $tipPercent  = (float) ($data['tip_percent'] ?? 15);
        $people      = max(1, (int) ($data['people'] ?? 1));

        if ($bill <= 0) {
            return ['success' => false, 'error' => 'Please enter a valid bill amount.'];
        }

        $tipAmount  = ($bill * $tipPercent) / 100;
        $total      = $bill + $tipAmount;
        $perPerson  = $total / $people;
        $tipPerPerson = $tipAmount / $people;

        return [
            'success' => true,
            'results' => [
                ['label' => 'Tip Amount',          'value' => '$' . number_format($tipAmount, 2), 'highlight' => true],
                ['label' => 'Total Bill',          'value' => '$' . number_format($total, 2)],
                ['label' => 'Per Person',          'value' => '$' . number_format($perPerson, 2)],
                ['label' => 'Tip Per Person',      'value' => '$' . number_format($tipPerPerson, 2)],
            ],
            'quick_tips' => array_map(fn($pct) => [
                'percent' => $pct,
                'tip'     => number_format(($bill * $pct) / 100, 2),
                'total'   => number_format($bill + ($bill * $pct) / 100, 2),
            ], [10, 15, 18, 20, 25]),
        ];
    }

    public function dateDiff(array $data): array
    {
        $date1 = $data['date1'] ?? null;
        $date2 = $data['date2'] ?? null;

        if (!$date1 || !$date2) {
            return ['success' => false, 'error' => 'Please enter both dates.'];
        }

        try {
            $d1    = new \DateTime($date1);
            $d2    = new \DateTime($date2);
            $diff  = $d1->diff($d2);
            $days  = abs($diff->days);
            $weeks = floor($days / 7);
            $months = $diff->m + ($diff->y * 12);
            $years = $diff->y;

            return [
                'success' => true,
                'results' => [
                    ['label' => 'Days',    'value' => number_format($days), 'highlight' => true],
                    ['label' => 'Weeks',   'value' => number_format($weeks)],
                    ['label' => 'Months',  'value' => $months],
                    ['label' => 'Years',   'value' => $years],
                    ['label' => 'Hours',   'value' => number_format($days * 24)],
                    ['label' => 'Minutes', 'value' => number_format($days * 24 * 60)],
                ],
                'from' => $d1->format('D, M j, Y'),
                'to'   => $d2->format('D, M j, Y'),
                'is_future' => $d2 > $d1,
            ];
        } catch (\Exception $e) {
            return ['success' => false, 'error' => 'Invalid date format.'];
        }
    }

    public function randomNumber(array $data): array
    {
        $min   = (int) ($data['min'] ?? 1);
        $max   = (int) ($data['max'] ?? 100);
        $count = min(100, max(1, (int) ($data['count'] ?? 1)));

        if ($min >= $max) {
            return ['success' => false, 'error' => 'Minimum must be less than maximum.'];
        }

        $numbers = [];
        for ($i = 0; $i < $count; $i++) {
            $numbers[] = random_int($min, $max);
        }

        return [
            'success' => true,
            'results' => [
                ['label' => 'Generated Numbers', 'value' => implode(', ', $numbers), 'highlight' => true],
                ['label' => 'Count', 'value' => $count],
                ['label' => 'Range', 'value' => "{$min} to {$max}"],
            ],
            'numbers' => $numbers,
        ];
    }
}
