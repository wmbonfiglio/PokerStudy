<?php

namespace App;

class Player
{
    private $cards;

    public function __construct()
    {
        $this->cards = [];
    }

    public function addCard(Card $card)
    {
        if (count($this->cards) >= 2) {
            return false;
        }
        $this->cards[] = $card;
        return true;
    }

    public function getCards()
    {
        return $this->cards;
    }

    public function printCards()
    {
        foreach ($this->cards as $key => $card) {
            print $key . ' - ' . $card->show() . PHP_EOL;
        }
    }
}
