<?php

class Generations
{
    public static function generateToken(string $id): string
    {
        $timestamp = time();
        $token = hash('sha256', $id . $timestamp . bin2hex(random_bytes(16)));

        return $token;
    }

    public static function generateFourDigitCode(): string
    {
        return str_pad(rand(0, 9999), 4, '0', STR_PAD_LEFT);
    }
}