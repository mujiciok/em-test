<?php

require 'vendor/autoload.php';

use App\Models\Box;
use App\Models\BoxSet;
use App\Models\StandardDryContainer10;
use App\Models\StandardDryContainer40;
use App\Services\ContainerCalculator;
use App\Services\OutputFormatter;

$availableContainerTypes = [
//    StandardDryContainer10::class,
    StandardDryContainer40::class,
];
$calculator = new ContainerCalculator($availableContainerTypes);

// @TODO create TransportHandler class (DRY)
$transport1 = [
    new BoxSet(
        boxType: new Box(
            width: 78,
            height: 79,
            length: 93,
//@TODO enable rotation on different axes, for different results
//            rotateZ: true,
//            rotateY: true,
//            rotateX: true,
        ),
        amount: 27,
    ),
];
$calculator->setCargo($transport1);
foreach ($calculator->getCargo() as $cargo) {
    OutputFormatter::printCargo($cargo);
    OutputFormatter::printSolution($calculator->calculate($cargo));
}

$transport2 = [
    new BoxSet(boxType: new Box(30, 60, 90), amount: 24),
    new BoxSet(boxType: new Box(75, 100, 200), amount: 33),
];
$calculator->setCargo($transport2);
foreach ($calculator->getCargo() as $cargo) {
    OutputFormatter::printCargo($cargo);
    OutputFormatter::printSolution($calculator->calculate($cargo));
}

$transport3 = [
    new BoxSet(boxType: new Box(80, 100, 200), amount: 10),
    new BoxSet(boxType: new Box(60, 80, 150), amount: 25),
];
$calculator->setCargo($transport3);
foreach ($calculator->getCargo() as $cargo) {
    OutputFormatter::printCargo($cargo);
    OutputFormatter::printSolution($calculator->calculate($cargo));
}
