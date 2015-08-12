<?php

namespace App\Hands;

abstract class Hands {
    protected $orderStraight;
    protected $orderValues;

    protected $cards;
    protected $arrayCards;
    protected $values;
    protected $suits;
    protected $qtyValues;
    protected $qtySuits;

    protected $isOk;

	public function __construct($cards)
	{
		$this->cards = $cards;

        $this->orderStraight = ['A', 2, 3, 4, 5, 6, 7, 8, 9, 10, 'J', 'Q', 'K', 'A'];
        $this->orderValues = [2, 3, 4, 5, 6, 7, 8, 9, 10, 'J', 'Q', 'K', 'A'];
        $this->isOk = null;
	}

	public function setArrayCards($arrayCards)
	{
		$this->arrayCards = $arrayCards;
	}

	public function setValuesArray($values)
	{
		$this->values = $values;
		$this->qtyValues = count($values);
	}

	public function setSuitsArray($suits)
	{
		$this->suits = $suits;
		$this->qtySuits = count($suits);
	}

	abstract function isOk();
	abstract function getBestCards();
}