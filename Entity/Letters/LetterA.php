<?php

class LetterA extends Letter
{
    public function __construct()
    {
        $this->attack = 3;
        $this->defense = 0.9;
        $this->abilityName = 'Agility';
        $this->abilityDescription = 'Gains a big boost of evasion.';
    }

    public function getSymbol()
    {
        return "A";
    }

    public function useAbility($word)
    {
        if (!$this->isCanceled()) {
            $word->evasion *= 1.5;                  // gives 1.5 times more evasion than the total evasion of the word
        }                                           // to rethink the numbers
    }
}