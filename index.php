<?php

require 'vendor/autoload.php';

use App\Models\Box;
use App\Models\BoxSet;
use App\Models\StandardDryContainer10;
use App\Models\StandardDryContainer40;
use App\Services\ContainerCalculator;
use App\Services\OutputFormatter;

$calculator = new ContainerCalculator([
    StandardDryContainer40::class,
    StandardDryContainer10::class,
]);
$transport1 = [
    new BoxSet(boxType: new Box(78, 79, 93), amount: 27),
];
OutputFormatter::printTransport($transport1);
OutputFormatter::printSolution($calculator->calculate($transport1));

$calculator = new ContainerCalculator([
    StandardDryContainer40::class,
    StandardDryContainer10::class,
]);
$transport2 = [
    new BoxSet(boxType: new Box(30, 60, 90), amount: 24),
    new BoxSet(boxType: new Box(75, 100, 200), amount: 33),
];
OutputFormatter::printTransport($transport2);
OutputFormatter::printSolution($calculator->calculate($transport2));

$calculator = new ContainerCalculator([
    StandardDryContainer40::class,
    StandardDryContainer10::class,
]);
$transport3 = [
    new BoxSet(boxType: new Box(80, 100, 200), amount: 10),
    new BoxSet(boxType: new Box(60, 80, 150), amount: 25),
];
OutputFormatter::printTransport($transport3);
OutputFormatter::printSolution($calculator->calculate($transport3));
