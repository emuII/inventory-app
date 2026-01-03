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


function formatCurrencyShort($amount)
{
    if ($amount >= 1000000000) {
        return 'Rp ' . number_format($amount / 1000000000, 1, '.', '') . 'B';
    } elseif ($amount >= 1000000) {
        return 'Rp ' . number_format($amount / 1000000, 1, '.', '') . 'M';
    } elseif ($amount >= 1000) {
        return 'Rp ' . number_format($amount / 1000, 1, '.', '') . 'K';
    } else {
        return 'Rp ' . number_format($amount, 0, ',', '.');
    }
}

function formatNumberShort($number)
{
    if ($number >= 1000000) {
        return number_format($number / 1000000, 1, '.', '') . 'M';
    } elseif ($number >= 1000) {
        return number_format($number / 1000, 1, '.', '') . 'K';
    } else {
        return number_format($number, 0, ',', '.');
    }
}

function formatDashboardNumber($number, $type = 'default')
{
    switch ($type) {
        case 'currency':
            return formatCurrencyShort($number);
        case 'large':
            return formatNumberShort($number);
        default:
            return number_format($number, 0, ',', '.');
    }
}

// Atau versi lebih sederhana
function formatDashboardCurrency($amount)
{
    $absAmount = abs($amount);

    if ($absAmount >= 1000000000) {
        $formatted = 'Rp ' . ($amount / 1000000000) . 'B';
    } elseif ($absAmount >= 1000000) {
        // Untuk 8.5M (satu desimal)
        $value = $amount / 1000000;
        $formatted = 'Rp ' . (floor($value * 10) / 10) . 'M';
    } elseif ($absAmount >= 1000) {
        $value = $amount / 1000;
        $formatted = 'Rp ' . (floor($value * 10) / 10) . 'K';
    } else {
        $formatted = 'Rp ' . number_format($amount, 0, ',', '.');
    }

    // Hapus .0 jika tidak perlu
    $formatted = str_replace('.0', '', $formatted);

    return $formatted;
}
