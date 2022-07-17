<?php

class Turn
{
    const BEGIN_TURN = 1;
    const SUMMON_PHASE = 2;
    const STRATEGY_PHASE = 4;
    const BATTLE_PHASE = 8;
    const END_TURN = 16;
    private $turnNumber = 0;
    private $words = array();
    private $currentPhase;
    private $gameSession;
    private $players = array();
    private $strategies = array();

    public function __construct($turnNumber, GameSession $gameSession)
    {
        $this->turnNumber = $turnNumber;
        $this->currentPhase = self::BEGIN_TURN;
        $this->gameSession = $gameSession;
    }

    public function bindUsers()
    {
        $this->players = $this->gameSession->getUsers();
    }

    public function getWords()
    {
        return $this->words;
    }

    private function index(User $user)
    {
        return $index = $this->players[0] === $user ? 0 : 1;
    }

    public function setWord(User $user, string $word)
    {
        $index = $this->index($user);
        $this->words[$index] = $word;
        if (isset($this->words[1 - $index])) {
            return 'OK';
        }

        return 0;
    }

    public function bothWords()
    {
        return count($this->words) == 2;
    }

    public function setStrategy(User $user, $strategy)
    {
        $index = $this->index($user);
        $this->strategies[$index] = $strategy;
        if (!empty($this->strategies[1 - $index])) {
            return 'OK';
        }

        return 0;
    }

    public function currentPhase()
    {
        return $this->currentPhase;
    }

    public function switchTurnPhase()
    {
        $this->currentPhase <<= 1;
    }

    public function getTurnNumber()
    {
        return $this->turnNumber;
    }

    public function execStrategies($player1, $player2)
    {
        return "The strategies have been executed";
    }
}