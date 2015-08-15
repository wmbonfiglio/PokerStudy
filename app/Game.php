<?php

namespace App;

class Game
{
    private $table;
    private $deck;
    private $players;
    private $phases;
    private $phase;

    public function __construct()
    {
        $this->table = new Table();
        $this->deck = new Deck();
        $this->phases = ['playerCards', 'threeCardsToTable', 'fourthCardToTable', 'fifthCardToTable'];
        $this->players = [];
    }

    public function addPlayer(Player $player)
    {
        if (count($this->players) >= 8) {
            return false;
        }

        $this->players[] = $player;
        return true;
    }

    public function start()
    {
        // Condições para começar
        // - 2 jogadores
        // - deck com 54 cartas
        $this->deck->shuffleCards();
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
        // Três cartas para a mesa
        for ($i=0; $i<3; $i++) {
            $card = $this->deck->getOneCard();
            $this->table->addCard($card);
        }
    }

    private function fourthCardToTable()
    {
        $card = $this->deck->getOneCard();
        $this->table->addCard($card);
    }

    private function fifthCardToTable()
    {
        $card = $this->deck->getOneCard();
        $this->table->addCard($card);
    }

    public function sim() //simulateFullGame()
    {
        $this->addPlayer(new Player());
        $this->addPlayer(new Player());
        $this->addPlayer(new Player());
        $this->addPlayer(new Player());
        $this->addPlayer(new Player());
        $this->addPlayer(new Player());
        $this->start();
        $this->playerCards();
        $this->threeCardsToTable();
        $this->fourthCardToTable();
        $this->fifthCardToTable();
        $this->table->printCards();
        $bestHand = ['pontos' => 0];
        foreach ($this->players as $key => $player) {
            print PHP_EOL . 'Jogador ' . $key . ':' . PHP_EOL;
            $player->printCards();
            $hand = new Hand($player->getCards(), $this->table->getCards());
            $retorno = $hand->calcularPontos();
            // echo "Pontos: " . $retorno['pontos'] . "\n";
            // echo "Cartas: ";
            // var_dump($retorno['cartas']);
            // echo "\n\n";
            if ($retorno['pontos'] > $bestHand['pontos']) {
                $bestHand = $retorno;
                $bestHand['jogador'] = $player;
            } elseif ($retorno['pontos'] == $bestHand['pontos']) {
                // TODO: tratar empate
            }
        }
        var_dump($bestHand);
    }
}
