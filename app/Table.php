<?php

namespace App;

class Table
{
    private $cards;

    public function __construct()
    {
        $this->cards = [];
    }

    public function addCard(Card $card)
    {
        if (count($this->cards) >= 5) {
            return false;
        }

        $this->cards[] = $card;
        return true;
    }

    public function printCards()
    {
        foreach ($this->cards as $key => $card) {
            print $key . ' - ' . $card->show() . PHP_EOL;
        }
    }

    public function getCards()
    {
        return $this->cards;
    }
}
