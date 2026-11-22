<?php
declare(strict_types=1);

function validate_required(string $v): bool
{
    return trim($v) !== '';
}

function validate_email(string $v): bool
{
    $trimmed = trim($v);
    if ($trimmed === '') {
        return false;
    }
    return filter_var($trimmed, FILTER_VALIDATE_EMAIL) !== false;
}

function validate_length(string $v, int $min, int $max): bool
{
    $len = mb_strlen($v, 'UTF-8');
    return $len >= $min && $len <= $max;
}