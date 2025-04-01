<?php

namespace App\Services;

use App\Models\BoxSet;
use App\Models\ContainerSet;

class OutputFormatter
{
    private const LINE = '#######################################################################';
    private const DELIMITER = "#\t";

    /**
     * @param array<BoxSet> $transport
     * @return void
     */
    public static function printTransport(array $transport): void
    {
        echo self::LINE . PHP_EOL;
        echo self::DELIMITER . PHP_EOL;
        foreach ($transport as $boxSet) {
            echo self::DELIMITER . $boxSet . PHP_EOL;
        }
        echo self::DELIMITER . PHP_EOL;
        echo self::LINE . PHP_EOL;
    }

    /**
     * @param array<ContainerSet> $transport
     * @return void
     */
    public static function printSolution(array $transport): void
    {
        foreach ($transport as $boxSet) {
            echo $boxSet . PHP_EOL;
        }
        echo PHP_EOL;
        echo PHP_EOL;
    }
}