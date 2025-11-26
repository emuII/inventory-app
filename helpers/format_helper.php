<?php
function ordinalEn($number)
{
    $suffix = 'th';
    if ($number % 100 < 11 || $number % 100 > 13) {
        switch ($number % 10) {
            case 1:
                $suffix = 'st';
                break;
            case 2:
                $suffix = 'nd';
                break;
            case 3:
                $suffix = 'rd';
                break;
        }
    }
    return $number . $suffix;
}
function formatCurrency($amount)
{
    return 'Rp ' . number_format($amount, 0, ',', '.');
}
function formatDateIndo($dateString)
{
    $months = [
        1 => 'Januari',
        'Februari',
        'Maret',
        'April',
        'Mei',
        'Juni',
        'Juli',
        'Agustus',
        'September',
        'Oktober',
        'November',
        'Desember'
    ];

    $dateParts = explode('-', $dateString);
    if (count($dateParts) !== 3) {
        return $dateString; // Return as is if format is unexpected
    }

    $year = $dateParts[0];
    $month = (int)$dateParts[1];
    $day = (int)$dateParts[2];

    $monthName = $months[$month] ?? '';

    return "{$day} {$monthName} {$year}";
}
