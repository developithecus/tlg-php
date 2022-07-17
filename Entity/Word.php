<?php

class Word
{
    protected $letters = [];
    protected $attack;
    protected $defense;
    protected $evasion;

    public static function isWordValid(string $word) {
        return true;
    }
}