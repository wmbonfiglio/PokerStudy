<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

use \App\Card;
use \App\Hand;

class PokerTest extends TestCase
{
    public function testPair()
    {
        $cardsPlayer = [new Card('Ouros', 'A'), new Card('Copas', 'A')];
        $cardsTable  = [
            new Card('Paus', 5),
            new Card('Copas', 6),
            new Card('Espadas', 3),
            new Card('Paus', 9),
            new Card('Espadas', 2)];
        $hand = new Hand($cardsPlayer, $cardsTable);

        $this->assertTrue($hand->hasPair());
        $pontosMao = $hand->calcularPontos()['pontos'];
        $this->assertGreaterThan(Hand::POINTS_PAIR, $pontosMao);
        $this->assertLessThan(Hand::POINTS_TWOPAIRS, $pontosMao);
    }

    public function testTwoPairs()
    {
        $cardsPlayer = [new Card('Ouros', 'A'), new Card('Copas', 'A')];
        $cardsTable  = [
            new Card('Paus', 5),
            new Card('Copas', 5),
            new Card('Espadas', 3),
            new Card('Paus', 9),
            new Card('Espadas', 2)];
        $hand = new Hand($cardsPlayer, $cardsTable);

        $this->assertTrue($hand->hasTwoPairs());
        $pontosMao = $hand->calcularPontos()['pontos'];
        $this->assertGreaterThan(Hand::POINTS_TWOPAIRS, $pontosMao);
        $this->assertLessThan(Hand::POINTS_THREE, $pontosMao);
    }

    public function testTriple()
    {
        $cardsPlayer = [new Card('Ouros', 'A'), new Card('Copas', 'A')];
        $cardsTable  = [
            new Card('Paus', 'A'),
            new Card('Paus', 5),
            new Card('Copas', 6),
            new Card('Espadas', 3),
            new Card('Espadas', 2)];
        $hand = new Hand($cardsPlayer, $cardsTable);

        $this->assertTrue($hand->hasTriple());

        $resultado = $hand->calcularPontos();
        $pontosMao = $resultado['pontos'];
        $this->assertGreaterThan(Hand::POINTS_THREE, $pontosMao);
        $this->assertLessThan(Hand::POINTS_STRAIGHT, $pontosMao);
    }

    public function testStraight()
    {
        $cardsPlayer = [new Card('Ouros', 'A'), new Card('Copas', 'A')];
        $cardsTable  = [
            new Card('Paus', 6),
            new Card('Paus', 5),
            new Card('Copas', 4),
            new Card('Espadas', 3),
            new Card('Espadas', 2)];
        $hand = new Hand($cardsPlayer, $cardsTable);

        $this->assertTrue($hand->hasStraight());

        $resultado = $hand->calcularPontos();
        $pontosMao = $resultado['pontos'];
        $this->assertGreaterThan(Hand::POINTS_STRAIGHT, $pontosMao);
        $this->assertLessThan(Hand::POINTS_FLUSH, $pontosMao);
    }

    public function testFlush()
    {
        $cardsPlayer = [new Card('Ouros', 'A'), new Card('Copas', 'A')];
        $cardsTable  = [
            new Card('Paus', 9),
            new Card('Copas', 7),
            new Card('Copas', 5),
            new Card('Copas', 3),
            new Card('Copas', 2)];
        $hand = new Hand($cardsPlayer, $cardsTable);

        $this->assertTrue($hand->hasFlush());

        $resultado = $hand->calcularPontos();
        $pontosMao = $resultado['pontos'];
        $this->assertGreaterThan(Hand::POINTS_FLUSH, $pontosMao);
        $this->assertLessThan(Hand::POINTS_FULLHOUSE, $pontosMao);
    }

    public function testFullHouse()
    {
        $cardsPlayer = [new Card('Ouros', 'A'), new Card('Copas', 'A')];
        $cardsTable  = [
            new Card('Paus', 'A'),
            new Card('Paus', 5),
            new Card('Copas', 5),
            new Card('Espadas', 3),
            new Card('Espadas', 2)];
        $hand = new Hand($cardsPlayer, $cardsTable);

        $this->assertTrue($hand->hasFullHouse());

        $resultado = $hand->calcularPontos();
        $pontosMao = $resultado['pontos'];
        $this->assertGreaterThan(Hand::POINTS_FULLHOUSE, $pontosMao);
        $this->assertLessThan(Hand::POINTS_FOUR, $pontosMao);
    }
}
