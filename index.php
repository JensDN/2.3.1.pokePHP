<?php
declare(strict_types=1);
ini_set('display_errors', '1');
ini_set('display_startup_errors', '1');
error_reporting(E_ERROR);
$pokeInput = $_GET['pokemon']; // get request for an input form
switch (isset($pokeInput)) {
    case true:
        $pokemon = makeArr(getData($pokeInput));
            break;
    case false:
        $pokemon = [
    'name' => 'pls input pokemon',
    'sprite' => './default.png',
    'moves' => '#',
    'evolutionChain' => ['firstEvo' => null, 'secondEvo' => null, 'thirdEvo' => null]
    ];
            break;
        default:
            echo "Where is that input?;";
}; // switch case  for the first when input is empty;
function getData($input)
{
    $baseEndpoint = 'http://pokeapi.co/api/v2/pokemon/';
    $data = file_get_contents($baseEndpoint . $input . '/');
    return json_decode($data, true);
} // function to receive data from the pokeApi
function makeArr($data){
    return [
        'name' => $data['name'],
        'sprite' => $data['sprites']['front_default'],
        'moves' => randomMoves($data['moves'], 4),
        'evolutionChain' => evolutionChain($data['species']['url'])
    ];


} // create an array from  all the valuable date from the inputData
function evolutionSprite ($evoName) {
    $data = getData($evoName);
    return $data['sprites']['front_default'];
} // to call the imageURl from the evolutions of the pokemon
function evolutionChain($urlEvoChain): array
{
    $responseForChain = file_get_contents($urlEvoChain);
    $dataForChain = json_decode($responseForChain, true);
    $evolutionChainUrl = $dataForChain['evolution_chain']['url'];
    $responseChain = file_get_contents($evolutionChainUrl);
    $dataChain = json_decode($responseChain, true);

    is_null($dataChain['chain']['species']['name'])?$firstEvolution =['text' => 'No first evolution', 'img' => null] : $firstEvolution = ['text' => $dataChain['chain']['species']['name'], 'imgURL' =>evolutionSprite($dataChain['chain']['species']['name'])];
    is_null($dataChain['chain']['evolves_to'][0]['species']['name'])?$secondEvolution =['text' => 'No second evolution', 'img' => null] : $secondEvolution = ['text' => $dataChain['chain']['evolves_to'][0]['species']['name'], 'imgURL' =>evolutionSprite($dataChain['chain']['evolves_to'][0]['species']['name'])];
    is_null($dataChain['chain']['evolves_to'][0]['evolves_to'][0]['species']['name'])?$thirdEvolution =['text' => 'No third evolution', 'img' => null] : $thirdEvolution = ['text' =>$dataChain['chain']['evolves_to'][0]['evolves_to'][0]['species']['name'], 'imgURL' =>evolutionSprite($dataChain['chain']['evolves_to'][0]['evolves_to'][0]['species']['name'])];

    return ['firstEvo' => $firstEvolution, 'secondEvo' => $secondEvolution, 'thirdEvo' => $thirdEvolution];
};  //return an associate Array for the evolutions of every species
function randomMoves(array $Moves, int $thatArrLenght): array {
    $minvalue = min($thatArrLenght, count($Moves));
    $keysArr = array_rand($Moves, $minvalue);
    $arrayWithAllThe4Moves = Array();
    if ($keysArr == 0) {
        array_push($arrayWithAllThe4Moves, $Moves[$keysArr]['move']['name']);
    }
    else {
        foreach ($keysArr as $value) {
            array_push($arrayWithAllThe4Moves, $Moves[$value]['move']['name']);
        }
    };
    return $arrayWithAllThe4Moves;
}; //create an new shuffeld  array with a lenght provided bij a int

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
         <h3>Evolution</h3>
        <ul>
            <?php foreach ( $pokemon['evolutionChain'] as $itemEvo){
                echo '<li>'.$itemEvo['text'].'</li>';
            } ?>
        </ul>
        <h3>Moves</h3>
        <ul>
            <?php foreach ($pokemon['moves'] as $itemMove){
                echo '<li>'.$itemMove.'</li>';
            } ?>
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
        <a href=<?php echo 'http://pokephp.localhost/?pokemon='.$pokemon['evolutionChain']['firstEvo']['text']?>>
        <button class="level-button" type="button">
            <img src=<?php echo $pokemon['evolutionChain']['firstEvo']['imgURL']?> alt=<?php echo $pokemon['evolutionChain']['firstEvo']['text']?> >
        </button>
        </a>
        <a href=<?php echo 'http://pokephp.localhost/?pokemon='.$pokemon['evolutionChain']['secondEvo']['text']?>>
            <button class="level-button" type="button">
                    <img src=<?php echo $pokemon['evolutionChain']['secondEvo']['imgURL']?> alt=<?php echo $pokemon['evolutionChain']['secondEvo']['text']?> >
            </button>
        </a>
        <a href=<?php echo 'http://pokephp.localhost/?pokemon='.$pokemon['evolutionChain']['thirdEvo']['text']?>>
            <button class="level-button" type="button">
                <img src=<?php echo $pokemon['evolutionChain']['thirdEvo']['imgURL']?> alt=<?php echo $pokemon['evolutionChain']['thirdEvo']['text']?> >
            </button>
        </a>




    </div>

</div>
</body>
</html>