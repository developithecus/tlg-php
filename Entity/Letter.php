<?php
class Letter
{
    protected $attack;
    protected $defense;
    protected $abilityName;
    protected $abilityDescription;
    protected $evasion = 3;
    protected $canceled = false;
    protected $isImmune = false;
    protected $isBanned = false;

    public function isCanceled() {
        return $this->canceled;
    }

    public function hasImmunity() {
        return $this->isImmune;
    }

    public function isAllowed() {
        return !$this->isBanned;
    }

    public function setAttack($value)
    {
        $this->attack = $value;
    }

    public function getAttack()
    {
        return $this->attack;
    }

    public function setDefense($value)
    {
        $this->defense = $value;
    }

    public function getDefense()
    {
        return $this->defense;
    }

    public function setEvasion($value)
    {
        $this->evasion = $value;
    }

    public function getEvasion()
    {
        return $this->evasion;
    }
}