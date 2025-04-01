<?php

declare(strict_types=1);

namespace App\Enums;

enum PlacementDirectionEnum: string
{
    case PlaceOnZ = 'Z';
    case PlaceOnY = 'Y';
    case PlaceOnX = 'X';
    case None = '-';
}
