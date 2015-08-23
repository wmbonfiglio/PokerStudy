# PokerStudy

Esse é um projeto pessoal e feito "nas coxas", sem muita ambição, apenas para provar algumas teorias de Poker Texas :)

## Lógica

Toda a lógica do jogo está na pasta /app. Cada arquivo .php representa uma parte do jogo.
 - /App/Card.php é a lógica de uma carta
 - /App/Deck.php é a lógica do baralho completo de 52 cartas, desde embaralhar até distribuir a carta
 - /App/Player.php é a lógica do jogador, o que significa apenas receber e mostrar as cartas
 - /App/Table.php é a lógica da mesa, que funciona como se fosse um jogador
 - /App/Hand.php é onde está a lógica para calcular o valor de uma mão (as regras do jogo)
 - /App/Game.php é onde está a lógica do jogo, desde instanciar cada objeto até seguir as etapas do jogo
 - /App/GameResult.php é onde é feito o cálculo do resultado do jogo
 - /App/Simulator.php é uma classe para auxiliar na execução de um ou mais jogos ao mesmo tempo

## Tests

O arquivo PokerTest.php na pasta /tests fica com os testes de cada mão. Na raiz do projeto, basta digitar:

		phpunit tests/PokerTest.php