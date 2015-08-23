<?php

namespace App;

class Game
{
    private $table;
    private $deck;
    private $players;
    private $phases;
    private $phase;
    private $bestHands;
    private $gameResult;
    private $startOfGame;
    private $endOfGame;

    public function __construct()
    {
        $this->table = new Table();
        $this->deck = new Deck();
        $this->phases = ['start', 'playerCards', 'threeCardsToTable', 'fourthCardToTable', 'fifthCardToTable', 'calculate'];
        $this->phase = null;
        $this->players = [];
        $this->bestHands = null;
        $this->gameResult = null;
        $this->startOfGame = false;
        $this->endOfGame = false;
    }

    public function addPlayer(Player $player)
    {
        if (count($this->players) >= 8) {
            return false;
        }

        $this->players[] = $player;
        return true;
    }

    public function nextPhase()
    {
        $phase = array_search($this->phase, $this->phases);
        if ($phase === false) {
            $phase = 0;
        } else {
            $phase++;
        }

        if (!isset($this->phases[$phase])) {
            return false;
        }

        if ($this->{$this->phases[$phase]}() !== false) {
            $this->phase = $this->phases[$phase];
            return true;
        }

        return false;
    }
    
    /*
    $x=new \App\Game;
    $x->addPlayer(new \App\Player());
    $x->addPlayer(new \App\Player());
    $x->nextPhase();
     */
    
    private function start()
    {
        // Condições para começar
        // - 2 jogadores
        // - deck com 54 cartas
        $this->deck->shuffleCards();
        $this->startOfGame = true;
    }

    private function playerCards()
    {
        // Duas cartas para cada jogador
        for ($i=0; $i<2; $i++) {
            foreach ($this->players as &$player) {
                $card = $this->deck->getOneCard();
                $player->addCard($card);
            }
        }
    }

    private function threeCardsToTable()
    {
        // Descarte
        $this->deck->getOneCard();
        // Três cartas para a mesa
        for ($i=0; $i<3; $i++) {
            $card = $this->deck->getOneCard();
            $this->table->addCard($card);
        }
    }

    private function fourthCardToTable()
    {
        // Descarte
        $this->deck->getOneCard();

        $card = $this->deck->getOneCard();
        $this->table->addCard($card);
    }

    private function fifthCardToTable()
    {
        // Descarte
        $this->deck->getOneCard();

        $card = $this->deck->getOneCard();
        $this->table->addCard($card);
    }

    private function calculate()
    {
        $this->gameResult = new GameResult($this->table, $this->players);

        $this->endOfGame = true;
    }

    private function calculateOLD()
    {
        $bestHand = ['pontos' => 0];
        foreach ($this->players as $key => &$player) {
            $hand = new Hand($player['player']->getCards(), $this->table->getCards());
            $retorno = $hand->calcularPontos();
            $player['result'] = $retorno;
            if ($retorno['pontos'] > $bestHand['pontos']) {
                $bestHand = $retorno;
                $bestHand['jogador'] = $player['player'];
            } elseif ($retorno['pontos'] == $bestHand['pontos']) {
                // TODO: tratar empate
            }
        }
        $this->bestHands = $bestHand;

        $this->endOfGame = true;
    }

    public function printTableCards()
    {
        $this->table->printCards();
    }

    public function printPlayerCards()
    {
        if ($this->endOfGame === false) {
            print PHP_EOL . '*** Wait the end of game :)' . PHP_EOL;
            return false;
        }

        foreach ($this->players as $key => $player) {
            print PHP_EOL . 'Jogador ' . $key . ':' . PHP_EOL;
            $player->printCards();
        }
    }

    public function printCards()
    {
        $this->printTableCards();
        $this->printPlayerCards();
    }

    public function printResults()
    {
        $this->gameResult->printAllResults();
    }

    public function getResults()
    {
        return $this->gameResult;
        // return ['players' => $this->players, 'table' => $this->table, 'mostPoints' => $this->gameResult->getMaxPoints()];
    }

    public function gameFinished()
    {
        return $this->endOfGame;
    }
}
