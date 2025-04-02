<?php

declare(strict_types=1);

namespace App\Models;

readonly class BoxSet
{
    public function __construct(
        public Box $boxType,
        public int $amount,
    ) {
    }

    public function __toString(): string
    {
        return sprintf(
            '%d boxes [%s]',
            $this->amount,
            $this->boxType,
        );
    }
}