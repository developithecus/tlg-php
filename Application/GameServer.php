<?php

require_once 'websockets.php';
require_once '../Entity/User.php';
require_once '../Entity/GameSession.php';
require_once '../Entity/Turn.php';

class GameServer extends WebSocketServer
{
    public function process(User $user, string $message)
    {
        // First, decoding the message
        $message = json_decode($message);

        // Making sure the users are added to the sessionID and that nobody else joins
        $gameSession = GameSession::getSessionByID($message->sessionID);
        switch ($message->destination) {
            case 'player-signature':
                if ($gameSession->addPlayer($user) === '2') {
                    $this->send($user, '2');
                } else {
                    $response = [
                        'sessionID' => (string)($gameSession),
                        'destination' => 'init',
                        'turn-number' => '1',
                        'turn-phase' => '1',
                        'players' => $gameSession->countPlayers()
                    ];
                    $this->send($user, json_encode($response));
                    $user->setName($message->username);
                    if ($gameSession->countPlayers() == 2) {
                        $gameSession->currentTurn()->switchTurnPhase();
                        $gameSession->currentTurn()->bindUsers();
                        $this->updatePlayerTurns($gameSession);
                        foreach($gameSession->getUsers() as $user) {
                            $this->send($user, json_encode([
                                'sessionID' => (string)($gameSession),
                                'destination' => 'update-names',
                                'enemyName' => $gameSession->otherPlayer($user)->getName()
                            ]));
                        }
                    }
                }
                break;
            case 'word-input':
                if ($gameSession->currentTurn()->setWord($user, $message->word) === 'OK') {
                    $this->showPlayerWords($gameSession);
                    $gameSession->currentTurn()->switchTurnPhase();
                    $this->updatePlayerTurns($gameSession);
                }
                break;
            case 'turn-strategy':
                $strategyKeys = $message->strategyKeys;
                $strategyValues = $message->strategyValues;
                $strategy = array_combine($strategyKeys, $strategyValues);
                if ($gameSession->currentTurn()->setStrategy($user, $strategy) === 'OK') {
                    $gameSession->currentTurn()->switchTurnPhase();
                    $this->updatePlayerTurns($gameSession);
                    $this->updateBattleResults($gameSession);
                }
                break;
            case 'ready-new-turn':
                $user->setReadyNewTurn(true);
                if ($gameSession->otherPlayer($user)->isUserReadyNewTurn()) {
                    $gameSession->nextTurn();
                    $this->prepareNewTurn($gameSession);
                    $this->updatePlayerTurns($gameSession);
                    $gameSession->getUsers()[0]->setReadyNewTurn(false);
                    $gameSession->getUsers()[1]->setReadyNewTurn(false);
                }
                break;
        }
    }

    public function log(GameSession $session, string $message)
    {
        $data = [
            'destination' => 'log',
            'message' => $message
        ];
        $this->send($session->getUsers()[0], json_encode($data));
        $this->send($session->getUsers()[1], json_encode($data));
    }

    public function updateBattleResults(GameSession $gameSession)
    {
        $message = [
            'sessionID' => (string)($gameSession),
            'destination' => 'battle-results',
            'message' => $gameSession->getBattleResults()
        ];

        foreach ($gameSession->getUsers() as $user) {
            $this->send($user, json_encode($message));
        }
    }

    public function prepareNewTurn(GameSession $gameSession)
    {
        $message = [
            'sessionID' => (string)($gameSession),
            'destination' => 'init',
            'turn-number' => $gameSession->currentTurn()->getTurnNumber(),
            'turn-phase' => $gameSession->currentTurn()->currentPhase()
        ];

        foreach ($gameSession->getUsers() as $user) {
            $this->send($user, json_encode($message));
        }


    }

    private function updatePlayerTurns(GameSession $gameSession)
    {
        $message = [
            'sessionID' => (string)($gameSession),
            'destination' => 'turn-update',
            'turn-number' => $gameSession->currentTurn()->getTurnNumber(),
            'turn-phase' => $gameSession->currentTurn()->currentPhase()
        ];

        foreach ($gameSession->getUsers() as $user) {
            $this->send($user, json_encode($message));
        }
    }

    private function showPlayerWords(GameSession $gameSession)
    {
        $message = [
            'sessionID' => (string)($gameSession),
            'destination' => 'word-output',
            'turn-number' => $gameSession->currentTurn()->getTurnNumber(),
            'turn-phase' => $gameSession->currentTurn()->currentPhase(),
            'word' => $gameSession->currentTurn()->getWords()[0]
        ];

        $this->send($gameSession->getUsers()[1], json_encode($message));
        $message['word'] = $gameSession->currentTurn()->getWords()[1];
        $this->send($gameSession->getUsers()[0], json_encode($message));
    }

    public function connected(User $user)
    {

    }

    public function closed(User $user)
    {
        $user->setGameSessionID("");
    }
}

$server = new GameServer('0.0.0.0', 9000);

try {
    $server->run();
} catch (Exception $ex) {
    $server->stdout($ex->getMessage());
}