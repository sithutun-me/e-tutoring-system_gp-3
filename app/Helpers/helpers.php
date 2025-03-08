<?php

if (!function_exists('formatDate')) {
    /**
     * Format a date for display.
     *
     * @param string|null $date
     * @param string $format
     * @return string
     */
    function formatDate($date, $format = 'd-m-Y')
    {
        if (!$date) {
            return '';
        }

        try {
            return \Carbon\Carbon::parse($date)->format($format);
        } catch (\Exception $e) {
            return '';
        }
    }
}
