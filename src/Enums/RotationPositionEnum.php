<?php

namespace App\Enums;

enum RotationPositionEnum: int
{
    case NoRotation = 1;
    case RotationOnlyZ = 2;
    case RotationOnlyY = 3;
    case RotationOnlyX = 4;
    /**
     * "Forward" means in Z-Y-X chain pairing by 2 coordinates, moving forward (Z+Y or Y+X or X+Z)
     */
    case RotationForward = 5;
    /**
     * "Backward" means in Z-Y-X chain pairing by 2 coordinates, moving backward (X+Y or Y+Z or Z+X)
     */
    case RotationBackward = 6;
}
