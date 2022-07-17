<?php

require_once '../Application/users.php';

class User extends WebSocketUser
{
    private $gameSessionID;
    private $name;
    private $health = 100;
    private $readyNewTurn = false;
    private static $_users = array();

    public function __construct($id, $socket)
    {
        parent::__construct($id, $socket);

        self::$_users[] = $this;
    }

    public function getName() {
        return $this->name;
    }

    public function setName($name) {
        $this->name = $name;
    }

    public function getGameSessionID()
    {
        return $this->gameSessionID;
    }

    public function setGameSessionID(string $hash)
    {
        $this->gameSessionID = $hash;
    }

    public function getHealth()
    {
        return $this->health;
    }

    public function damage($damage)
    {
        $this->health -= $damage;
    }

    public static function getUsersBySession(string $hash)
    {
        $result = array();
        foreach (self::$_users as $u) {
            if ($u->getGameSessionID() === $hash) {
                $result[] = $u;
            }
        }

        return $result;
    }

    public function isUserReadyNewTurn()
    {
        return $this->readyNewTurn;
    }

    public function setReadyNewTurn($readyState)
    {
        $this->readyNewTurn = $readyState;
    }
}