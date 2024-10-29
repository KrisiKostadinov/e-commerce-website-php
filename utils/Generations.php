<?php

class Generations
{
    public static function generateToken(string $id): string
    {
        $timestamp = time();
        $token = hash('sha256', $id . $timestamp . bin2hex(random_bytes(16)));

        return $token;
    }
}