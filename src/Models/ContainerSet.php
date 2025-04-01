<?php

namespace App\Models;

class ContainerSet
{
    public function __construct(
        public readonly Container $containerType,
        public int $amount,
    ) {
    }

    public function __toString(): string
    {
        return sprintf(
            '%d containers %s',
            $this->amount,
            $this->containerType,
        );
    }
}