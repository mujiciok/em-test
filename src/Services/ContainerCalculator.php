<?php

declare(strict_types=1);

namespace App\Services;

use App\Enums\PlacementDirectionEnum;
use App\Exceptions\MisplacementException;
use App\Models\Box;
use App\Models\BoxSet;
use App\Models\Container;
use App\Models\ContainerSet;

class ContainerCalculator
{
    /**
     * @var array<array<BoxSet>>
     */
    private array $cargo;
    private RotationHandler $rotationHandler;

    /**
     * @param array<class-string<Container>> $availableContainerTypes
     * @param positive-int $z
     * @param positive-int $y
     * @param positive-int $x
     * @param array<ContainerSet> $usedContainers
     */
    public function __construct(
        private array $availableContainerTypes,
        private ?Container $container = null,
        private int $z = 0,
        private int $y = 0,
        private int $x = 0,
        private array $usedContainers = [],
    ) {
        $this->rotationHandler = new RotationHandler();
    }

    /**
     * @param array<BoxSet> $cargo
     * @return void
     */
    public function setCargo(array $cargo): void
    {
        $additionalCargos = [];
        foreach ($cargo as $boxSet) {
            $boxRotationStates = $this->rotationHandler->getAvailableRotationPositions($boxSet->boxType);
            $additionalCargos[] = array_map(
                fn($rotatedBox) => new BoxSet(boxType: $rotatedBox, amount: $boxSet->amount),
                $boxRotationStates,
            );
        }

        $this->cargo = cartesianProduct($additionalCargos);
    }

    /**
     * @return array<array<BoxSet>>
     */
    public function getCargo(): array
    {
        return $this->cargo;
    }

    /**
     * @param array<BoxSet> $transport
     * @return array<ContainerSet>
     * @throws MisplacementException
     */
    public function calculate(array $transport): array
    {
        $this->initAvailableContainers($this->availableContainerTypes);

        $orderedTransport = $this->orderTransportByVolumeDesc($transport);
        foreach ($orderedTransport as $boxSet) {
            $boxAmount = $boxSet->amount;
            while ($boxAmount > 0) {
                $placementDirection = $this->getNextPlacementDirection($boxSet->boxType);
                if ($placementDirection === PlacementDirectionEnum::None) {
                    $this->initContainer($this->availableContainerTypes[0]);
                    $this->incrementContainer($this->availableContainerTypes[0]);
                    $placementDirection = PlacementDirectionEnum::PlaceOnX;
                }
                $this->placeBox($boxSet->boxType, $placementDirection);
                $boxAmount--;
            }
        }

        return array_values($this->usedContainers);
    }

    /**
     * Order by box volume, to start with boxes of greater size
     * @param array<BoxSet> $transport
     * @return array<BoxSet>
     */
    private function orderTransportByVolumeDesc(array $transport): array
    {
        $orderedTransport = $transport;
        usort(
            $orderedTransport,
            fn(BoxSet $a, BoxSet $b) => $b->boxType->getVolume() <=> $a->boxType->getVolume(),
        );

        return $orderedTransport;
    }

    /**
     * Order by container volume, to start with containers of greater size and use smaller containers for the last batch
     * @TODO check if last container can be swapped with smaller containers
     * @param array<class-string<Container>> $containerTypes
     * @return array<class-string<Container>> $containerTypes
     */
    private function orderContainerByVolumeDesc(array $containerTypes): array
    {
        $orderedContainerTypes = $containerTypes;
        usort(
            $orderedContainerTypes,
            function (string $containerClassA, string $containerClassB) {
                /** @var Container $containerA */
                $containerA = new $containerClassA;
                /** @var Container $containerB */
                $containerB = new $containerClassB;

                return $containerB->getVolume() <=> $containerA->getVolume();
            },
        );

        return $orderedContainerTypes;
    }

    private function initAvailableContainers(array $availableContainerTypes): void
    {
        $this->availableContainerTypes = $this->orderContainerByVolumeDesc($availableContainerTypes);
        foreach ($this->availableContainerTypes as $availableContainerType) {
            $this->initContainer($availableContainerType); // @TODO (SRP)
            $this->usedContainers[$availableContainerType] = new ContainerSet(
                containerType: new $availableContainerType,
                amount: 0,
            );
        }
    }

    /**
     * @param class-string<Container> $containerType
     * @return void
     */
    private function initContainer(string $containerType): void
    {
        $this->container = new $containerType;
        $this->x = 0;
        $this->y = 0;
        $this->z = 0;
    }

    /**
     * @param class-string<Container> $containerType
     * @return void
     */
    private function incrementContainer(string $containerType): void
    {
        $this->usedContainers[$containerType]->amount++;
    }

    /**
     * @throws MisplacementException
     */
    private function placeBox(Box $box, PlacementDirectionEnum $direction): void
    {
        if (PlacementDirectionEnum::PlaceOnZ === $direction) {
            $this->z += $box->width;
            return;
        }

        if (PlacementDirectionEnum::PlaceOnY === $direction) {
            $this->z += $box->width;
            $this->y += $box->height;
            return;
        }

        if (PlacementDirectionEnum::PlaceOnX === $direction) {
            $this->z += $box->width;
            $this->y += $box->height;
            $this->x += $box->length;
            return;
        }

        throw new MisplacementException();
    }

    private function getNextPlacementDirection(Box $box): PlacementDirectionEnum
    {
        // if coordinates not set - initialize new container
        if ($this->x + $this->y + $this->z === 0) {
            return PlacementDirectionEnum::None;
        }

        $fitsX = $this->container->length > $this->x + $box->length;
        $fitsY = $this->container->height > $this->y + $box->height;
        $fitsZ = $this->container->width > $this->z + $box->width;

        if ($fitsZ) {
            return PlacementDirectionEnum::PlaceOnZ;
        }

        if ($fitsY) {
            $this->z = 0;

            return PlacementDirectionEnum::PlaceOnY;
        }

        if ($fitsX) {
            $this->z = 0;
            $this->y = 0;

            return PlacementDirectionEnum::PlaceOnX;
        }

        return PlacementDirectionEnum::None;
    }
}
