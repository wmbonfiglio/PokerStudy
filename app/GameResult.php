<?php

namespace App;

class GameResult
{
	private $table;
	private $players;
	private $playersHands;
	private $playersResults;
	private $orderOfPlayers;

	public function __construct($table, $players)
	{
		$this->table = $table;
		$this->players = $players;
		$this->playersHands = [];
		$this->playersResults = [];
		$this->orderOfPlayers = [];

		$this->initHands();
		$this->calculateHands();
		$this->orderBestHand();
	}

	private function initHands()
	{
        foreach ($this->players as $key => $player) {
            $hand = new Hand($player->getCards(), $this->table->getCards());
            $this->playersHands[$key] = $hand;
        }
	}

	private function calculateHands()
	{
        foreach ($this->players as $key => $player) {
            $this->playersResults[$key] = $this->playersHands[$key]->calcularPontos();
        }
    }

    private function orderBestHand()
    {
    	$orderPlayers = [];
    	foreach ($this->playersResults as $key => $result) {
    		$orderPlayers[$key] = $result['pontos'];
    	}
    	arsort($orderPlayers);
    	$this->orderPlayers = $orderPlayers;
        // $bestHand = ['pontos' => 0];
        // foreach ($this->players as $key => $player) {
	       //  if ($playersResults[$key]['pontos'] > $bestHand['pontos']) {
	       //      $bestHand = $playersResults[$key];
	       //      $bestHand['jogador'] = $player;
	       //  } elseif ($playersResults[$key]['pontos'] == $bestHand['pontos']) {
	       //      // TODO: tratar empate
	       //  }
        // }
        // $this->bestHands = $bestHand;
    }

	public function printAllResults()
	{
        foreach ($this->players as $key => $player) {
            print PHP_EOL . 'Jogador ' . $key . ':' . PHP_EOL;
            $player->printCards();
            print 'Pontos :';
            print $this->playersResults[$key]['pontos'];
        }
	}

	public function getMaxPoints()
	{
		foreach ($this->orderPlayers as $key => $points) {
			return $points;
		}
	}

	public function getPlayersResults()
	{
		return $this->playersResults;
	}

	public function printWinnerCards()
	{
		foreach ($this->orderPlayers as $key => $points) {
			return $this->players[$key]->printCards();
		}
	}

	public function printTableCards()
	{
		$this->table->printCards();
	}
}
