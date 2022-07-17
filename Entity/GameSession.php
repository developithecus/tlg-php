<?php

require_once 'Turn.php';

class GameSession
{
    private $players = array();
    private $sessionID;
    private $winner;
    private $currentTurn;

    private static $_sessions = array();

    private function __construct($sessionID)
    {
        $this->sessionID = $sessionID;
        self::$_sessions[] = $this;
        $this->currentTurn = new Turn(1, $this);
    }

    public function countPlayers()
    {
        return count($this->players);
    }

    public function getUsers(): array
    {
        return $this->players;
    }

    public function otherPlayer($player): User
    {
        return $this->players[0] === $player ? $this->players[1] : $this->players[0];
    }

    public function currentTurn()
    {
        return $this->currentTurn;
    }

    public function addPlayer($player)
    {
        if (count($this->players) < 2) {
            $this->players[] = $player;
            return 'OK';
        } else {
            return '2';
        }
    }

    public function nextTurn()
    {
        $this->currentTurn = new Turn($this->currentTurn->getTurnNumber() + 1, $this);
        $this->currentTurn()->switchTurnPhase();
        $this->currentTurn()->bindUsers();
    }

    public function __toString()
    {
        return $this->sessionID;
    }

    public function getBattleResults()
    {
        return $this->currentTurn()->execStrategies($this->getUsers()[0], $this->getUsers()[1]);
    }

    public static function getSessionByID($hash)
    {
        foreach (self::$_sessions as $session) {
            if ($session == $hash) {
                return $session;
            }
        }

        return new GameSession($hash);
    }

    public static function getSessionByUser($user)
    {
        foreach (self::$_sessions as $session) {
            if ($session->player1() === $user || $session->player2() === $user) {
                return $session;
            }
        }

        return null;
    }

}