<?php

if (! function_exists('format_angka')) {
    /**
     * Format angka tanpa trailing .0
     *
     * Contoh:
     *   format_angka(85.0)   => "85"
     *   format_angka(85.5)   => "85.5"
     *   format_angka(85.50)  => "85.5"
     *   format_angka(85.456) => "85.46" (kalau $decimals = 2)
     */
    function format_angka($value, int $decimals = 2): string
    {
        if ($value === null || $value === '') {
            return '0';
        }

        $value = (float) $value;

        // Kalau nilainya bulat → tampilkan integer
        if (floor($value) == $value) {
            return number_format($value, 0, ',', '.');
        }

        // Kalau ada desimal → buang trailing zero
        $formatted = number_format($value, $decimals, ',', '.');

        // Hapus trailing .0 / .00
        $formatted = rtrim($formatted, '0');
        $formatted = rtrim($formatted, ',');

        return $formatted;
    }
}