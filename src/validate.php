<?php
declare(strict_types=1);

/**
 * ITCS333 A4 — pure validation functions.
 * They are unit-tested by the autograder (tests/UnitTest.php).
 */

// true when the value is not empty after removing spaces
function validate_required(string $v): bool
{
    return trim($v) !== '';
}

// true when the value is a valid email address
function validate_email(string $v): bool
{
    return filter_var(trim($v), FILTER_VALIDATE_EMAIL) !== false;
}

// true when the number of characters is between $min and $max (inclusive)
function validate_length(string $v, int $min, int $max): bool
{
    $len = mb_strlen($v);
    return $len >= $min && $len <= $max;
}
