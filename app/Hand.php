<?php

namespace App;

/*
class App\Hand#678 (8) {
  private $cards => ARRAY COM OBJETOS Card SEM ORDENAÇÃO
  private $arrayCards => 2 ARRAYS:
    'value' => VALORES DAS CARTAS, MANTENDO O INDICE COERENTE COM O PROXIMO ARRAY
    'suit' => NAIPES DAS CARTAS, MANTENDO O INDICE COERENTE COM O ARRAY ANTERIOR
  private $values => VALORES DAS CARTAS É O ÍNDICE E QUANTAS CARTAS DAQUELE VALOR É O VALOR
  private $suits => NAIPES DAS CARTAS É O ÍNDICE E QUANTAS CARTAS DAQUELE NAIPE É O VALOR
}
 */

class Hand
{
    private $orderStraight;
    private $orderValues;

    private $cards;
    private $arrayCards;
    private $values;
    private $suits;
    private $qtyValues;
    private $qtySuits;

    /*
     - Royal Flush
     - Straight Flush
     - Four of a kind
     - Full house
     - Flush
     - Straight
     - Three of a kind
     - Two pair
     - One pair
     - High card
     */
    public function __construct($cardsFromPlayer, $cardsFromTable)
    {
        $this->cards = array_merge($cardsFromPlayer, $cardsFromTable);
        
        $this->orderStraight = ['A', 2, 3, 4, 5, 6, 7, 8, 9, 10, 'J', 'Q', 'K', 'A'];
        $this->orderValues = [2, 3, 4, 5, 6, 7, 8, 9, 10, 'J', 'Q', 'K', 'A'];

        $this->init();
    }

    private function init() {
        $this->values = [];
        $this->suits = [];

        foreach ($this->cards as $key => $card) {
            if (isset($this->values[$card->getValue()])) {
                $this->values[$card->getValue()]++;
            } else {
                $this->values[$card->getValue()] = 1;
            }
            if (isset($this->suits[$card->getSuit()])) {
                $this->suits[$card->getSuit()]++;
            } else {
                $this->suits[$card->getSuit()] = 1;
            }
            $this->arrayCards['value'][$key] = $card->getValue();
            $this->arrayCards['suit'][$key] = $card->getSuit();
        }
        $this->qtyValues = count($this->values);
        $this->qtySuits = count($this->suits);
    }

    public function hasRoyalFlush()
    {
        // Faz duas verificações mais rápidas e com pequena probabilidade de acontecer
        if (!$this->hasFlush() || $this->hasStraight() !== 'A') {
            return false;
        }
        // TODO: Se tem Flush e uma sequencia com final A, agora precisa garantir que são as mesmas cartas
        
        return true;
    }

    public function hasStraightFlush()
    {
        // Faz duas verificações mais rápidas e com pequena probabilidade de acontecer
        if (!$this->hasFlush() || $this->hasStraight() === false) {
            return false;
        }
        // TODO: Se tem Flush e uma sequencia, agora precisa garantir que são as mesmas cartas
        return true;
    }
    /*
    $a1 = new \App\Card('Ouros', 'A');
    $a2 = new \App\Card('Paus', 'A');
    $a3 = new \App\Card('Espadas', 'A');
    $a4 = new \App\Card('Copas', 'A');
    $t = new \App\Card('Ouros', 10);
    $f = new \App\Card('Ouros', 5);
    $th = new \App\Card('Ouros', 3);
    $hand = new \App\Hand([$a1,$a2,$a3,$t,$f,$a4,$th], []);
    $hand->hasFourkind(false);
     */
    
    // OK
    public function hasFourkind($returnCards = false)
    {
        foreach ($this->values as $value => $qty) {
            if ($qty == 4) {
                if ($returnCards === false) {
                    return $this->pointsOfValue($value);
                } else {
                    $cardsIndex = array_keys($this->arrayCards['value'], $value);
                    $cards = [];
                    foreach ($cardsIndex as $key) {
                        $cards[] = $this->cards[$key];
                    }
                    return $cards;
                }
            }
        }
        return false;
    }

    public function hasFullHouse()
    {
        // Verificação mais rápida e condição para o full house
        if (!$this->hasTriple()) {
            return false;
        }
        // Caso tenha um triple, é possível que 1) tenha outro triple ou 2) tenha um double
        return false;
    }

    public function hasFlush()
    {
        foreach ($this->suits as $suit => $qty) {
            if ($qty >= 5) {
                return $this->topValue();
            }
        }
        return false;
    }
    /*
    $a = new \App\Card('Ouros', 'A');
    $k = new \App\Card('Ouros', 'K');
    $q = new \App\Card('Ouros', 'Q');
    $j = new \App\Card('Ouros', 'J');
    $t = new \App\Card('Ouros', 10);
    $f = new \App\Card('Ouros', 5);
    $th = new \App\Card('Ouros', 3);
    $hand = new \App\Hand([$a,$k], [$q,$j,$t,$f,$th]);
    $hand->hasStraight();
     */
    public function hasStraight()
    {
        $sequencia = 0;
        $cards = [];
        foreach ($this->cards as $card) {
            $cards[$card->getValue()] = true;
        }
        for ($i=count($this->orderStraight)-1; $i>=0; $i--) {
            if (isset($cards[$this->orderStraight[$i]])) {
                if (++$sequencia == 5) {
                    return $this->orderStraight[$i+4];
                }
            } else {
                $sequencia = 0;
            }
        }
        return false;
    }
    
    public function hasTriple()
    {
        foreach ($this->values as $value => $qty) {
            if ($qty == 3) {
                return $this->pointsOfValue($value);
            }
        }
        return false;
    }
    
    public function hasTwoPairs()
    {
        $pairs = 0;
        foreach ($this->values as $value => $qty) {
            if ($qty == 2) {
                if (++$pairs == 2) {
                    return $this->topValue($value);
                }
            }
        }
        return false;
    }

    public function hasPair()
    {
        $values = [];
        foreach ($this->cards as $card) {
            if (isset($values[$card->getValue()])) {
                return true;
            }
            $values[$card->getValue()] = 1;
        }
        return false;
    }

    public function topValue($returnPoints = false)
    {
        for ($i=count($this->orderValues)-1; $i>=0; $i--) {
            if (isset($this->values[$this->orderValues[$i]])) {
                $topValue = $this->orderValues[$i];
                break;
            }
        }
        return $this->pointsOfValue($topValue);
    }

    private function pointsOfValue($value)
    {
        return array_search($value, $this->orderValues);
    }

    public function calcularPontos()
    {
        $maisPontos = 0;
        $melhoresCartas = [];
        if (count($this->cards) > 5) {
            $cardNumbers = $this->combinationsOf(5, range(0, count($this->cards)-1));
            foreach ($cardNumbers as $key) {
                $cards = $this->getSomeCards($key);
                $newHand = new Hand($cards, []);
                $pontos = $newHand->quantosPontos();
                if ($pontos > $maisPontos) {
                    $maisPontos = $pontos;
                    $melhoresCartas = $cards;
                }
            }
        }
        return ['pontos' => $maisPontos, "cartas" => $melhoresCartas];
    }

    private function quantosPontos()
    {
        if ($this->hasRoyalFlush()) {
            // Testar se são as mesmas cartas, se for retornar
            // TODO: teste
            return 90000000000;
        } elseif ($this->hasStraightFlush()) {
            // Testar se são as mesmas cartas, se for retornar
            // TODO: retornar valor da carta maior na sequencia
            return 80000000000;
        } elseif ($this->hasFourkind()) {
            // TODO: retornar valor da carta que fez quadra
            return 70000000000;
        } elseif ($this->hasTriple() && $this->hasPair()) {
            // TODO: garantir que o pair seja diferente da trinca
            // TODO: retornar valores das cartas do Full House
            return 60000000000;
        } elseif ($this->hasFlush()) {
            // TODO: retornar valor da maior carta do flush
            return 50000000000 + $this->hasFlush();
        } elseif ($this->hasStraight()) {
            // TODO: Retornar valor da maior carta da sequência
            return 40000000000;
        } elseif ($this->hasTriple()) {
            // TODO: Retornar valor da carta que fez trinca
            return 30000000000 + $this->hasTriple();
        } elseif ($this->hasTwoPairs()) {
            // TODO: Retornar valor da carta que fez trinca
            return 20000000000 + $this->hasTwoPairs();
        } elseif ($this->hasPair()) {
            // TODO: Retornar valor da carta que fez trinca
            return 10000000000 + $this->hasPair();
        } else {
            // TODO: Retornar valor da carta que fez trinca
            return 0100 + $this->topValue();
        }
    }

    // http://iswwwup.com/t/6fb50c978e91/all-combinations-of-r-elements-from-given-array-php.html
    private function combinationsOf($k, $xs)
    {
        if ($k === 0) {
            return array(array());
        }
        if (count($xs) === 0) {
            return array();
        }
        $x = $xs[0];
        $xs1 = array_slice($xs, 1, count($xs) - 1);
        $res1 = $this->combinationsOf($k-1, $xs1);
        for ($i = 0; $i < count($res1); $i++) {
            array_splice($res1[$i], 0, 0, $x);
        }
        $res2 = $this->combinationsOf($k, $xs1);
        return array_merge($res1, $res2);
    }

    private function getSomeCards($arrayNumbers)
    {
        $cards = [];
        foreach ($arrayNumbers as $key) {
            $cards[] = $this->cards[$key];
        }
        return $cards;
    }
}

/*
    public function hasFlush()
    {
        $suits = [];
        foreach ($this->cards as $card) {
            if (isset($suits[$card->getSuit()])) {
                if (++$suits[$card->getSuit()] == 5) {
                    return true;
                }
            } else {
                $suits[$card->getSuit()] = 1;
            }
        }
        return false;
    }

    public function hasStraight()
    {
        $sequencia = 0;
        $cards = [];
        foreach ($this->cards as $card) {
            $cards[$card->getValue()] = true;
        }
        for ($i=0; $i<count($order); $i++) {
            if (isset($cards[$order[$i]])) {
                if (++$sequencia == 5) {
                    return true;
                }
            } else {
                $sequencia = 0;
            }
        }
        return false;
    }

    public function hasFourkind()
    {
        $values = [];
        foreach ($this->cards as $card) {
            if (isset($values[$card->getValue()])) {
                if (++$values[$card->getValue()] == 4) {
                    return true;
                }
            } else {
                $values[$card->getValue()] = 1;
            }
        }
        return false;
    }

    public function hasTriple()
    {
        $values = [];
        foreach ($this->cards as $card) {
            if (isset($values[$card->getValue()])) {
                if (++$values[$card->getValue()] == 3) {
                    return true;
                }
            } else {
                $values[$card->getValue()] = 1;
            }
        }
        return false;
    }

    public function hasPair()
    {
        $values = [];
        foreach ($this->cards as $card) {
            if (isset($values[$card->getValue()])) {
                return true;
            }
            $values[$card->getValue()] = 1;
        }
        return false;
    }

    private function topValue($returnPoints = false)
    {
        $order = [2, 3, 4, 5, 6, 7, 8, 9, 10, 'J', 'Q', 'K', 'A'];
        $maior = 0;
        foreach ($this->cards as $card) {
            $key = array_key($card->getValue(), $order);
            if ($key > $maior) {
                $maior = $key;
            }
        }
        if ($returnPoints) {
            return $maior;
        } else {
            return $order[$maior];
        }
    }
*/