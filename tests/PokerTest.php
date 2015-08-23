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

    public function testPairKicker()
    {
        // Os dois com par de A, mas o kicker do player 1 é maior
        $cardsPlayer1 = [new Card('Ouros', 'A'), new Card('Copas', 'K')];
        $cardsPlayer2 = [new Card('Espadas', 'A'), new Card('Copas', 'Q')];
        $cardsTable  = [
            new Card('Paus', 'A'),
            new Card('Paus', 7),
            new Card('Copas', 5),
            new Card('Espadas', 3),
            new Card('Espadas', 2)];
        $handPlayer1 = new Hand($cardsPlayer1, $cardsTable);
        $handPlayer2 = new Hand($cardsPlayer2, $cardsTable);
        $resultPlayer1 = $handPlayer1->calcularPontos();
        $resultPlayer2 = $handPlayer2->calcularPontos();

        $pontosMao1 = $resultPlayer1['pontos'];
        $pontosMao2 = $resultPlayer2['pontos'];
        $this->assertGreaterThan($pontosMao2, $pontosMao1);
    }    

    public function testTwoPairsKicker()
    {
        // Os dois com par de A e par de 5, mas player 1 com kicker maior
        $cardsPlayer1 = [new Card('Ouros', 'A'), new Card('Copas', 'K')];
        $cardsPlayer2 = [new Card('Espadas', 'A'), new Card('Copas', 'Q')];
        $cardsTable  = [
            new Card('Paus', 'A'),
            new Card('Paus', 5),
            new Card('Copas', 5),
            new Card('Espadas', 3),
            new Card('Espadas', 2)];
        $handPlayer1 = new Hand($cardsPlayer1, $cardsTable);
        $handPlayer2 = new Hand($cardsPlayer2, $cardsTable);
        $resultPlayer1 = $handPlayer1->calcularPontos();
        $resultPlayer2 = $handPlayer2->calcularPontos();

        $pontosMao1 = $resultPlayer1['pontos'];
        $pontosMao2 = $resultPlayer2['pontos'];
        $this->assertGreaterThan($pontosMao2, $pontosMao1);
    }

    public function testTripleKicker()
    {
        // Os dois com trinca de A, mas player 1 com kicker maior
        $cardsPlayer1 = [new Card('Ouros', 'A'), new Card('Copas', 'K')];
        $cardsPlayer2 = [new Card('Espadas', 'A'), new Card('Copas', 'Q')];
        $cardsTable  = [
            new Card('Paus', 'A'),
            new Card('Copas', 'A'),
            new Card('Paus', 5),
            new Card('Espadas', 3),
            new Card('Espadas', 2)];
        $handPlayer1 = new Hand($cardsPlayer1, $cardsTable);
        $handPlayer2 = new Hand($cardsPlayer2, $cardsTable);
        $resultPlayer1 = $handPlayer1->calcularPontos();
        $resultPlayer2 = $handPlayer2->calcularPontos();

        $pontosMao1 = $resultPlayer1['pontos'];
        $pontosMao2 = $resultPlayer2['pontos'];
        $this->assertGreaterThan($pontosMao2, $pontosMao1);
    }

    public function testStraightKicker()
    {
        // Os dois com sequencia, mas player 1 com sequencia maior
        $cardsPlayer1 = [new Card('Ouros', 'A'), new Card('Copas', 'K')];
        $cardsPlayer2 = [new Card('Espadas', 'K'), new Card('Copas', 'Q')];
        $cardsTable  = [
            new Card('Paus', 'Q'),
            new Card('Paus', 'J'),
            new Card('Copas', 10),
            new Card('Espadas', 9),
            new Card('Espadas', 2)];
        $handPlayer1 = new Hand($cardsPlayer1, $cardsTable);
        $handPlayer2 = new Hand($cardsPlayer2, $cardsTable);
        $resultPlayer1 = $handPlayer1->calcularPontos();
        $resultPlayer2 = $handPlayer2->calcularPontos();

        $pontosMao1 = $resultPlayer1['pontos'];
        $pontosMao2 = $resultPlayer2['pontos'];
        $this->assertGreaterThan($pontosMao2, $pontosMao1);
    }

    public function testFlushKicker()
    {
        // Os dois com flush, mas player 1 com carta maior
        $cardsPlayer1 = [new Card('Ouros', 'A'), new Card('Copas', 'K')];
        $cardsPlayer2 = [new Card('Ouros', 'K'), new Card('Copas', 'Q')];
        $cardsTable  = [
            new Card('Ouros', 'Q'),
            new Card('Ouros', 'J'),
            new Card('Ouros', 10),
            new Card('Ouros', 2),
            new Card('Espadas', 9)];
        $handPlayer1 = new Hand($cardsPlayer1, $cardsTable);
        $handPlayer2 = new Hand($cardsPlayer2, $cardsTable);
        $resultPlayer1 = $handPlayer1->calcularPontos();
        $resultPlayer2 = $handPlayer2->calcularPontos();

        $pontosMao1 = $resultPlayer1['pontos'];
        $pontosMao2 = $resultPlayer2['pontos'];
        $this->assertGreaterThan($pontosMao2, $pontosMao1);
    }

    public function testFullHouseTripleKicker()
    {
        // Os dois com full house, mas player 1 com carta maior
        $cardsPlayer1 = [new Card('Ouros', 'A'), new Card('Copas', 'A')];
        $cardsPlayer2 = [new Card('Ouros', 'K'), new Card('Copas', 'K')];
        $cardsTable  = [
            new Card('Ouros', 5),
            new Card('Espadas', 5),
            new Card('Paus', 5),
            new Card('Ouros', 4),
            new Card('Espadas', 'A')];
        $handPlayer1 = new Hand($cardsPlayer1, $cardsTable);
        $handPlayer2 = new Hand($cardsPlayer2, $cardsTable);
        $resultPlayer1 = $handPlayer1->calcularPontos();
        $resultPlayer2 = $handPlayer2->calcularPontos();

        $pontosMao1 = $resultPlayer1['pontos'];
        $pontosMao2 = $resultPlayer2['pontos'];
        $this->assertGreaterThan($pontosMao2, $pontosMao1);
    }

    public function testFullHousePairKicker()
    {
        // Os dois com full house, mas player 1 com carta maior
        $cardsPlayer1 = [new Card('Ouros', 'A'), new Card('Copas', 'K')];
        $cardsPlayer2 = [new Card('Espadas', 'A'), new Card('Copas', 3)];
        $cardsTable  = [
            new Card('Ouros', 5),
            new Card('Espadas', 5),
            new Card('Paus', 'A'),
            new Card('Ouros', 'A'),
            new Card('Espadas', 'K')];
        $handPlayer1 = new Hand($cardsPlayer1, $cardsTable);
        $handPlayer2 = new Hand($cardsPlayer2, $cardsTable);
        $resultPlayer1 = $handPlayer1->calcularPontos();
        $resultPlayer2 = $handPlayer2->calcularPontos();

        $pontosMao1 = $resultPlayer1['pontos'];
        $pontosMao2 = $resultPlayer2['pontos'];
        $this->assertGreaterThan($pontosMao2, $pontosMao1);
    }
}
