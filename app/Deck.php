<?php

namespace App;

class Deck
{
    private $suits;
    private $values;
    private $cards;

    public function __construct()
    {
        $this->cards = [];
        $this->suits = ['Copas', 'Ouros', 'Espadas', 'Paus'];
        $this->values = [2, 3, 4, 5, 6, 7, 8, 9, 10, 'J', 'Q', 'K', 'A'];
        foreach ($this->suits as $suit) {
            foreach ($this->values as $value) {
                $this->cards[] = new Card($suit, $value);
            }
        }
    }

    public function shuffleCards()
    {
        shuffle($this->cards);
        shuffle($this->cards);
        shuffle($this->cards);
    }

    public function getOneCard()
    {
        return array_pop($this->cards);
    }

    public function countCardsLeft()
    {
        return count($this->cards);
    }
}
