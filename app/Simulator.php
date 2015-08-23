<?php

namespace App;

class Simulator
{
	private $game;

	public function __construct($tests, $players)
	{
		$resultados = ['vencedor' => [], 'perdedor' => [], 'maos_vencedoras' => []];
		for ($i=0; $i<$tests; $i++) {
			// Zerar o jogo
			$this->game = new \App\Game();
			// Passos para simular
			$this->sim($players);
			// Pegar os resultados para análise
			// $this->game->printResults();
			$results = $this->game->getResults();

			echo 'Table Cards:' . PHP_EOL;
			$results->printTableCards();
			foreach ($results->getPlayersResults() as $key => $player) {
				$pontos = $player['pontos'];
				$mao = 'perdedor';
				if ($pontos == $results->getMaxPoints()) {
					$mao = 'vencedor';
					echo 'Winner Cards:' . PHP_EOL;
					$results->printWinnerCards();
					echo PHP_EOL . PHP_EOL;
				}
				$agrupado = $this->wichGameIsThis($pontos);
				if (isset($resultados[$mao][$agrupado])) {
					$resultados[$mao][$agrupado]++;
				} else {
					$resultados[$mao][$agrupado] = 1;
				}
			}
		}
		ksort($resultados['vencedor']);
		ksort($resultados['perdedor']);
		echo 'Mãos vencedoras:' . PHP_EOL;
		var_dump($resultados['vencedor']);
		echo PHP_EOL . 'Mãos perdedoras:' . PHP_EOL;
		var_dump($resultados['perdedor']);
	}
// $x = new \App\Simulator(100, 5);
	private function sim($players)
	{
        for ($i=0; $i<$players; $i++) {
            $this->game->addPlayer(new Player());
        }

        while (!$this->game->gameFinished()) {
        	$this->game->nextPhase();
        }
	}

	private function wichGameIsThis($points)
	{
		if ($points < Hand::POINTS_PAIR)
			return '1-carta_maior';
		elseif ($points < Hand::POINTS_TWOPAIRS)
			return '2-par';
		elseif ($points < Hand::POINTS_THREE)
			return '3-dois_pares';
		elseif ($points < Hand::POINTS_STRAIGHT)
			return '4-trinca';
		elseif ($points < Hand::POINTS_FLUSH)
			return '5-sequencia';
    	elseif ($points < Hand::POINTS_FULLHOUSE)
			return '6-flush';
		elseif ($points < Hand::POINTS_FOUR)
			return '7-full_house';
		elseif ($points < Hand::POINTS_STRAIGHTFLUSH)
			return '8-quadra';
		elseif ($points < Hand::POINTS_ROYALFLUSH)
			return '9-straight_flush';
		else
			return '999-royal_flush';
	}
}