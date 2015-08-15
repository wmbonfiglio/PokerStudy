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
    const POINTS_PAIR           = 10000000000;
    const POINTS_TWOPAIRS       = 20000000000;
    const POINTS_THREE          = 30000000000;
    const POINTS_STRAIGHT       = 40000000000;
    const POINTS_FLUSH          = 50000000000;
    const POINTS_FULLHOUSE      = 60000000000;
    const POINTS_FOUR           = 70000000000;
    const POINTS_STRAIGHTFLUSH  = 80000000000;
    const POINTS_ROYALFLUSH     = 90000000000;

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
    
    /* INICIALIZAÇÃO */
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

    /* MÃOS */
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

    public function hasFourkind()
    {
        foreach ($this->values as $value => $qty) {
            if ($qty == 4) {
                return true;
            }
        }
        return false;
    }

    public function hasFullHouse()
    {
        // Verificação mais rápida e condição para o full house
        if ($this->hasTriple() && $this->hasPair()) {
            return true;
        }
        // Caso tenha um triple, é possível que 1) tenha outro triple ou 2) tenha um double
        return false;
    }

    public function hasFlush()
    {
        foreach ($this->suits as $suit => $qty) {
            if ($qty >= 5) {
                return true;
                // return $this->topValue();
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
        for ($i=count($this->orderStraight)-1; $i>=0; $i--) {
            if (isset($cards[$this->orderStraight[$i]])) {
                if (++$sequencia == 5) {
                    return true;
                    // return $this->orderStraight[$i+4];
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
                return true;
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
                    return true;
                }
            }
        }
        return false;
    }

    public function hasPair()
    {
        foreach ($this->values as $value => $qty) {
            if ($qty == 2) {
                return true;
            }
        }
        return false;
    }

    /* PONTUAÇÃO MÃOS */
    // TODO: precisa calcular os kickers - a conta para a mão está certa, falta tratar empates
    public function pointsOfPair()
    {
        foreach ($this->values as $value => $qty) {
            if ($qty == 2) {
                return $this->pointsOfValue($value);
            }
        }
        return false;
    }

    public function pointsOfTwoPairs()
    {
        $points = [];
        foreach ($this->values as $value => $qty) {
            if ($qty == 2) {
                $points[] = $this->pointsOfValue($value);
            }
        }
        rsort($points);
        return $points[0] * 100 + $points[1];
    }

    public function pointsOfTriple()
    {
        foreach ($this->values as $value => $qty) {
            if ($qty == 3) {
                return $this->pointsOfValue($value);
            }
        }
        return false;
    }

    public function pointsOfFullHouse()
    {
        $pointsTriple = $this->pointsOfTriple();
        $pointsPair = $this->pointsOfPair();

        return $pointsTriple * 100 + $pointsPair;
    }

    public function pointsOfStraight()
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

    public function pointsOfFlush()
    {
        $points = [];
        foreach ($this->values as $value => $qty) {
            $points[] = $this->pointsOfValue($value);
        }
        rsort($points);

        return 
            $points[0] * pow(10,8) 
            + $points[1] * pow(10,6)
            + $points[2] * pow(10,4)
            + $points[3] * pow(10,2)
            + $points[4];
    }

    public function pointsOfFour()
    {
        foreach ($this->values as $value => $qty) {
            if ($qty == 4) {
                return $this->pointsOfValue($value);
            }
        }
        return false;
    }

    /* PONTUAÇÃO */
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
            return POINTS_ROYALFLUSH;
        } elseif ($this->hasStraightFlush()) {
            // Testar se são as mesmas cartas, se for retornar
            // TODO: retornar valor da carta maior na sequencia
            return self::POINTS_STRAIGHTFLUSH + $this->pointsOfStraight();
        } elseif ($this->hasFourkind()) {
            // TODO: retornar valor da carta que fez quadra
            return self::POINTS_FOUR + $this->pointsOfFour();
        } elseif ($this->hasFullHouse()) {
            // TODO: garantir que o pair seja diferente da trinca
            // TODO: retornar valores das cartas do Full House
            return self::POINTS_FULLHOUSE + $this->pointsOfFullHouse();
        } elseif ($this->hasFlush()) {
            // TODO: retornar valor da maior carta do flush
            return self::POINTS_FLUSH + $this->pointsOfFlush();
        } elseif ($this->hasStraight()) {
            // TODO: Retornar valor da maior carta da sequência
            return self::POINTS_STRAIGHT + $this->pointsOfStraight();
        } elseif ($this->hasTriple()) {
            // TODO: Retornar valor da carta que fez trinca
            return self::POINTS_THREE + $this->pointsOfTriple();
        } elseif ($this->hasTwoPairs()) {
            // TODO: Retornar valor da carta que fez trinca
            return self::POINTS_TWOPAIRS + $this->pointsOfTwoPairs();
        } elseif ($this->hasPair()) {
            // TODO: Retornar valor da carta que fez trinca
            return self::POINTS_PAIR + $this->pointsOfPair();
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