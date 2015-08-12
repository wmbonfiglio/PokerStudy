<?php

namespace App\Hands;

class Pair extends Hands
{
	private $pairValue;

	public function isOk()
	{
		if (!is_null($this->isOk)) {
			return $this->isOk;
		}

        foreach ($this->values as $value => $qty) {
            if ($qty == 2) {
            	$this->isOk = true;
            	$this->pairValue = $value;
                return true;
            }
        }
        $this->isOk = false;
        return false;
	}

	public function getBestCards()
	{
		$keysFromPair = array_keys($this->arrayCards['value'], $this->pairValue);
		$cards = [];
		// Primeiro as duas cartas do par
		foreach ($keysFromPair as $key) {
			$cards[] = $this->cards[$key];
		}
		// Agora as cartas com maior valor
		$valoresOrdenados = sort(array_keys($this->arrayCards['value']));
		var_dump($valoresOrdenados);exit;
	}
}
