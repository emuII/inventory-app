<?php
function env($key, $default = null)
{
    $path = __DIR__ . '/../.env';
    if (!file_exists($path)) return $default;

    $lines = file($path, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);

    foreach ($lines as $line) {
        if (strpos(trim($line), '#') === 0) continue;

        list($envKey, $envValue) = array_map('trim', explode('=', $line, 2));
        if ($envKey === $key) return $envValue;
    }

    return $default;
}

function base_url($path = '')
{
    $base = env('BASE_URL', 'http://localhost');
    return rtrim($base, '/') . '/' . ltrim($path, '/');
}
