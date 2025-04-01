<?php

namespace App\Models;

class BoxSet
{
    public function __construct(
        public readonly Box $boxType,
        public int $amount,
    ) {
    }

    public function __toString(): string
    {
        return sprintf(
            '%d boxes %s',
            $this->amount,
            $this->boxType,
        );
    }
}