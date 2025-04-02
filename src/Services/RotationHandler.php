<?php

declare(strict_types=1);

namespace App\Services;

use App\Enums\RotationPositionEnum;
use App\Models\Box;

/**
 * Box rotation
 * @see RotationPositionEnum::class:
 * 1. no rotation (Z=Z, Y=Y, X=X)
 * 2. rotation on Z axis swaps Y and X sizes (Z=Z, Y=X, X=Y)
 * 3. rotation on Y axis swaps Z and X sizes (Z=X, Y=Y, X=Z)
 * 4. rotation on X axis swaps Z and Y sizes (Z=Y, Y=Z, X=X)
 * 5. rotation on Z+Y or Y+X or X+Z axes (Z=Y, Y=X, X=Z)
 * 6. rotation on Z+X or Y+Z or X+Y axes (Z=X, Y=Z, X=Y)
 */
class RotationHandler
{
    /**
     * Mapping of "rotation state" to "rotation position enum cases"
     * all flags = false                -> 1
     * rotateZ = true                   -> 1,2
     * rotateY = true                   -> 1,3
     * rotateX = true                   -> 1,4
     * rotateZ = true && rotateY = true -> 1,2,3,5,6
     * rotateZ = true && rotateX = true -> 1,2,4,5,6
     * rotateY = true && rotateX = true -> 1,3,4,5,6
     * all flags = true                 -> 1,2,3,4,5,6
     * @param Box $box
     * @return array<Box>
     */
    public function getAvailableRotationPositions(Box $box): array
    {
        $stateNumber = $this->getNumberRepresentationForRotationState($box->rotateZ, $box->rotateY, $box->rotateX);
        $rotationPositions = match ($stateNumber) {
            0 => [RotationPositionEnum::NoRotation],
            1 => [
                RotationPositionEnum::NoRotation,
                RotationPositionEnum::RotationOnlyZ,
            ],
            10 => [
                RotationPositionEnum::NoRotation,
                RotationPositionEnum::RotationOnlyY,
            ],
            100 => [
                RotationPositionEnum::NoRotation,
                RotationPositionEnum::RotationOnlyX,
            ],
            11 => [
                RotationPositionEnum::NoRotation,
                RotationPositionEnum::RotationOnlyZ,
                RotationPositionEnum::RotationOnlyY,
                RotationPositionEnum::RotationForward,
                RotationPositionEnum::RotationBackward,
            ],
            101 => [
                RotationPositionEnum::NoRotation,
                RotationPositionEnum::RotationOnlyZ,
                RotationPositionEnum::RotationOnlyX,
                RotationPositionEnum::RotationForward,
                RotationPositionEnum::RotationBackward,
            ],
            110 => [
                RotationPositionEnum::NoRotation,
                RotationPositionEnum::RotationOnlyY,
                RotationPositionEnum::RotationOnlyX,
                RotationPositionEnum::RotationForward,
                RotationPositionEnum::RotationBackward,
            ],
            111 => [
                RotationPositionEnum::NoRotation,
                RotationPositionEnum::RotationOnlyZ,
                RotationPositionEnum::RotationOnlyY,
                RotationPositionEnum::RotationOnlyX,
                RotationPositionEnum::RotationForward,
                RotationPositionEnum::RotationBackward,
            ],
        };

        return array_map(
            fn(RotationPositionEnum $position): Box => $this->rotateBox($box, $position),
            $rotationPositions,
        );
    }

    public function rotateBox(Box $box, RotationPositionEnum $position): Box
    {
        $newBox = clone $box;
        $newBox->rotateZ = false;
        $newBox->rotateY = false;
        $newBox->rotateX = false;
        switch ($position) {
            case RotationPositionEnum::RotationOnlyZ:
                $newBox->height = $box->length;
                $newBox->length = $box->height;

                return $newBox;
            case RotationPositionEnum::RotationOnlyY:
                $newBox->width = $box->length;
                $newBox->length = $box->width;

                return $newBox;
            case RotationPositionEnum::RotationOnlyX:
                $newBox->width = $box->height;
                $newBox->height = $box->width;

                return $newBox;
            case RotationPositionEnum::RotationForward:
                $newBox->width = $box->height;
                $newBox->height = $box->length;
                $newBox->length = $box->width;

                return $newBox;
            case RotationPositionEnum::RotationBackward:
                $newBox->width = $box->length;
                $newBox->height = $box->width;
                $newBox->length = $box->height;

                return $newBox;
            default:
                return $newBox;
        }
    }

    /**
     * Possible values: 0, 1, 10, 11, 100, 101, 110, 111
     * @param bool $rotateZ
     * @param bool $rotateY
     * @param bool $rotateX
     * @return int
     */
    private function getNumberRepresentationForRotationState(bool $rotateZ, bool $rotateY, bool $rotateX): int
    {
        $numberZ = (int)$rotateZ;
        $numberY = (int)$rotateY * 10;
        $numberX = (int)$rotateX * 100;

        return $numberZ + $numberY + $numberX;
    }
}
