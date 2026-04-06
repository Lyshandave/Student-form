<?php
declare(strict_types=1);

// match() expression requires PHP 8.0+
if (PHP_VERSION_ID < 80000) {
    exit('<p style="font-family:sans-serif;color:#b71c1c;padding:2rem">
          ⚠️ PHP 8.0 or higher is required. Current version: ' . PHP_VERSION . '</p>');
}

/**
 * Returns the Philippine university grade equivalent and its CSS class
 * based on a numeric percentage score (0–100).
 */
function grade_equivalent(int $score): array {
    return match(true) {
        $score >= 99 => ['value' => '1.00', 'label' => 'Excellent',    'css' => 'eq--excellent'],
        $score >= 96 => ['value' => '1.25', 'label' => 'Excellent',    'css' => 'eq--excellent'],
        $score >= 93 => ['value' => '1.50', 'label' => 'Very Good',    'css' => 'eq--verygood'],
        $score >= 90 => ['value' => '1.75', 'label' => 'Very Good',    'css' => 'eq--verygood'],
        $score >= 87 => ['value' => '2.00', 'label' => 'Good',         'css' => 'eq--good'],
        $score >= 84 => ['value' => '2.25', 'label' => 'Good',         'css' => 'eq--good'],
        $score >= 81 => ['value' => '2.50', 'label' => 'Satisfactory', 'css' => 'eq--satisfactory'],
        $score >= 78 => ['value' => '2.75', 'label' => 'Satisfactory', 'css' => 'eq--satisfactory'],
        $score >= 75 => ['value' => '3.00', 'label' => 'Passing',      'css' => 'eq--passing'],
        default      => ['value' => '5.00', 'label' => 'Failed',       'css' => 'eq--failed'],
    };
}

/**
 * Returns initials from a first and last name.
 */
function initials(string $first, string $last): string {
    return strtoupper(
        mb_substr($first, 0, 1) . mb_substr($last, 0, 1)
    );
}

/**
 * Sanitizes a string for safe HTML output.
 */
function h(string $value): string {
    return htmlspecialchars($value, ENT_QUOTES, 'UTF-8');
}

/**
 * Safely formats a date string; returns fallback if invalid/null.
 */
function format_date(?string $dateStr, string $format = 'M j, Y'): string {
    if ($dateStr === null || $dateStr === '') {
        return '—';
    }
    $ts = strtotime($dateStr);
    return $ts !== false ? date($format, $ts) : '—';
}
