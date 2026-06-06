<?php

namespace App\Services;

use App\Models\Setting;
use App\Models\Tool;

class ConverterEngine
{
    private array $units = [
        'length' => [
            'km'  => 1000,
            'm'   => 1,
            'cm'  => 0.01,
            'mm'  => 0.001,
            'mi'  => 1609.344,
            'yd'  => 0.9144,
            'ft'  => 0.3048,
            'in'  => 0.0254,
            'nm'  => 1852,
        ],
        'weight' => [
            'kg'  => 1,
            'g'   => 0.001,
            'mg'  => 0.000001,
            'lb'  => 0.453592,
            'oz'  => 0.0283495,
            'ton' => 1000,
            'st'  => 6.35029,
        ],
        'temperature' => [], // handled separately
        'area' => [
            'km2'  => 1000000,
            'm2'   => 1,
            'cm2'  => 0.0001,
            'ha'   => 10000,
            'acre' => 4046.86,
            'ft2'  => 0.092903,
            'in2'  => 0.00064516,
            'mi2'  => 2589988,
        ],
        'volume' => [
            'l'   => 1,
            'ml'  => 0.001,
            'gal' => 3.78541,
            'qt'  => 0.946353,
            'pt'  => 0.473176,
            'cup' => 0.236588,
            'fl_oz' => 0.0295735,
            'm3'  => 1000,
            'cm3' => 0.001,
        ],
        'speed' => [
            'mps'  => 1,
            'kph'  => 0.277778,
            'mph'  => 0.44704,
            'knot' => 0.514444,
        ],
        'data' => [
            'b'  => 1,
            'kb' => 1024,
            'mb' => 1048576,
            'gb' => 1073741824,
            'tb' => 1099511627776,
        ],
    ];

    public function handle(array $data, Tool $tool): array
    {
        $slug = $tool->slug;

        if (str_contains($slug, 'currency')) {
            return $this->currency($data);
        }

        return $this->unitConverter($data);
    }

    public function unitConverter(array $data): array
    {
        $value    = (float) ($data['value'] ?? 0);
        $from     = strtolower($data['from'] ?? '');
        $to       = strtolower($data['to'] ?? '');
        $type     = strtolower($data['type'] ?? 'length');

        if ($type === 'temperature') {
            return $this->temperature($value, $from, $to);
        }

        $unitGroup = $this->units[$type] ?? null;
        if (!$unitGroup) {
            return ['success' => false, 'error' => 'Unknown unit type: ' . $type];
        }

        if (!isset($unitGroup[$from]) || !isset($unitGroup[$to])) {
            return ['success' => false, 'error' => 'Unknown unit: ' . $from . ' or ' . $to];
        }

        // Convert to base then to target
        $baseValue = $value * $unitGroup[$from];
        $result    = $baseValue / $unitGroup[$to];

        // Show all conversions in this group
        $allConversions = [];
        foreach ($unitGroup as $unit => $factor) {
            $allConversions[] = [
                'unit'  => strtoupper($unit),
                'value' => number_format($baseValue / $factor, 6),
            ];
        }

        return [
            'success' => true,
            'results' => [
                ['label' => 'Result', 'value' => number_format($result, 6) . ' ' . strtoupper($to), 'highlight' => true],
                ['label' => 'From', 'value' => $value . ' ' . strtoupper($from)],
                ['label' => 'Type', 'value' => ucfirst($type)],
            ],
            'all_conversions' => $allConversions,
        ];
    }

    private function temperature(float $value, string $from, string $to): array
    {
        // Convert to Celsius first
        $celsius = match ($from) {
            'c', 'celsius'    => $value,
            'f', 'fahrenheit' => ($value - 32) * 5 / 9,
            'k', 'kelvin'     => $value - 273.15,
            default           => null,
        };

        if ($celsius === null) {
            return ['success' => false, 'error' => 'Unknown temperature unit: ' . $from];
        }

        $result = match ($to) {
            'c', 'celsius'    => $celsius,
            'f', 'fahrenheit' => ($celsius * 9 / 5) + 32,
            'k', 'kelvin'     => $celsius + 273.15,
            default           => null,
        };

        if ($result === null) {
            return ['success' => false, 'error' => 'Unknown temperature unit: ' . $to];
        }

        return [
            'success' => true,
            'results' => [
                ['label' => 'Result', 'value' => round($result, 4) . '° ' . strtoupper($to), 'highlight' => true],
                ['label' => 'Celsius',    'value' => round($celsius, 4) . '°C'],
                ['label' => 'Fahrenheit', 'value' => round(($celsius * 9 / 5) + 32, 4) . '°F'],
                ['label' => 'Kelvin',     'value' => round($celsius + 273.15, 4) . 'K'],
            ],
        ];
    }

    public function currency(array $data): array
    {
        $amount = (float) ($data['amount'] ?? 1);
        $from   = strtoupper($data['from'] ?? 'USD');
        $to     = strtoupper($data['to'] ?? 'EUR');

        // Load rates from settings
        $ratesJson = Setting::get('currency_rates', '{}');
        $rates     = is_array($ratesJson) ? $ratesJson : json_decode($ratesJson, true);

        if (empty($rates)) {
            // Default fallback rates (USD base)
            $rates = [
                'USD' => 1, 'EUR' => 0.92, 'GBP' => 0.79, 'JPY' => 148.5,
                'AUD' => 1.53, 'CAD' => 1.36, 'CHF' => 0.88, 'CNY' => 7.24,
                'INR' => 83.1, 'MXN' => 17.05, 'BRL' => 4.97, 'KRW' => 1325,
                'SGD' => 1.34, 'HKD' => 7.82, 'NOK' => 10.56, 'SEK' => 10.38,
                'DKK' => 6.88, 'NZD' => 1.63, 'ZAR' => 18.62, 'TRY' => 30.85,
                'AED' => 3.67, 'SAR' => 3.75, 'THB' => 35.02, 'IDR' => 15630,
                'MYR' => 4.72, 'PHP' => 56.7, 'NGN' => 795, 'EGP' => 30.9,
            ];
        }

        if (!isset($rates[$from])) {
            return ['success' => false, 'error' => "Currency '{$from}' not found in rates."];
        }
        if (!isset($rates[$to])) {
            return ['success' => false, 'error' => "Currency '{$to}' not found in rates."];
        }

        $amountInUSD = $amount / $rates[$from];
        $result      = $amountInUSD * $rates[$to];
        $rate        = $rates[$to] / $rates[$from];

        // Common conversions
        $popularPairs = ['USD', 'EUR', 'GBP', 'JPY', 'AUD', 'CAD'];
        $conversions  = [];
        foreach ($popularPairs as $currency) {
            if ($currency !== $from && isset($rates[$currency])) {
                $conversions[] = [
                    'currency' => $currency,
                    'value'    => number_format($amountInUSD * $rates[$currency], 4),
                ];
            }
        }

        return [
            'success' => true,
            'results' => [
                ['label' => 'Converted Amount', 'value' => number_format($result, 4) . ' ' . $to, 'highlight' => true],
                ['label' => 'Exchange Rate',    'value' => "1 {$from} = " . round($rate, 6) . " {$to}"],
                ['label' => 'Original Amount',  'value' => number_format($amount, 2) . ' ' . $from],
            ],
            'conversions' => $conversions,
            'note' => 'Rates are manually managed in admin settings. Update regularly for accuracy.',
        ];
    }
}
