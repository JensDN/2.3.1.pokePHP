<?php
declare(strict_types=1);
ini_set('display_errors', '1');
ini_set('display_startup_errors', '1');
error_reporting(E_ALL);

$pokeInput = $_GET['pokemon'];
switch (isset($pokeInput)) {
    case true:
        $pokemon = makeArr(getData($pokeInput));
            break;
    case false:
        $pokemon = [
    'name' => 'pls input pokemon',
    'sprite' => '#',
    'moves' => '#',
    'evolutionChain' => '#'
    ];
            break;
        default:
            echo "Where is that input?;";
}
var_dump($pokemon);
function getData($input)
{
    $baseEndpoint = 'http://pokeapi.co/api/v2/pokemon/';
    $data = file_get_contents($baseEndpoint . $input . '/');
    return json_decode($data, true);
}
function makeArr($data){
    $arr = [
        'name' => $data['name'],
        'sprite' => $data['sprites']['front_default'],
        'moves' => randomMoves($data['moves'], 4),
        'evolutionChain' => evolutionChain($data['species']['url'])
    ];
    return $arr;
}
function evolutionChain($urlEvoChain): array
{
    $responseForChain = file_get_contents($urlEvoChain);
    $dataForChain = json_decode($responseForChain, true);
    $evolutionChainUrl = $dataForChain['evolution_chain']['url'];
    $responseChain = file_get_contents($evolutionChainUrl);
    $dataChain = json_decode($responseChain, true);
    $firstEvolution = $dataChain['chain']['species']['name'];
    $secondEvolution = $dataChain['chain']['evolves_to'][0]['species']['name'];
    $thirdEvolution = $dataChain['chain']['evolves_to'][0]['evolves_to'][0]['species']['name'];
    return ['firstEvo' => $firstEvolution, 'secondEvo' => $secondEvolution, 'thirdEvo' => $thirdEvolution];
};
function randomMoves(array $Moves, int $thatArrLenght): array
{
    return array_rand($Moves, min($thatArrLenght, count($Moves)));
}
?>


<!doctype html>
<html lang="en">
<head>
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Press+Start+2P">
    <link rel="stylesheet" href="./base.css">
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>PokeApp</title>
</head>
<body id="forest">
<div id="pokedex">
    <div class="sensor">
        <button></button>
    </div>
    <div class="camera-display">
        <img src=<?php echo $pokemon['sprite'] ?>>
    </div>
    <div class="divider"></div>
    <div class="stats-display">
        <h2><?php echo $pokemon['name'] ?></h2>
        <h3>Abilities</h3>
        <ul>
            <li>Solar-power</li>
            <li>Blaze</li>
        </ul>
        <h3>Moves</h3>
        <ul>
            <li>dragon-rage</li>
            <li>dragon-breath</li>
            <li>dragon-claw</li>
        </ul>
    </div>
    <div class="botom-actions">
        <div id="actions">
            <button class="a"></button>
        </div>
        <div id="cross">
            <button class="cross-button up"></button>
            <button class="cross-button right"
            </button>
            <button class="cross-button down"></button>
            <button class="cross-button left"></button>
            <div class="cross-button center"></div>
        </div>
    </div>
    <div class="input-pad">
        <form name="form" action="" method="get">
            <input type="text" name="pokemon" id="pokemon" placeholder="Choose your pokemon">
        </form>
    </div>

    <div class="bottom-modes">

        <button class="level-button"></button>
        <button class="level-button"></button>
        <button class="level-button"></button>
        <button class="level-button"></button>

        <button class="pokedex-mode black-button">Pokedex</button>
        <button class="game-mode black-button">Game</button>

    </div>

</div>
</body>
</html>