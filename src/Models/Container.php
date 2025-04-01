<?php

declare(strict_types=1);

namespace App\Models;

abstract class Container
{
    public function __construct(
        public float $width,
        public float $height,
        public float $length,
    ) {
    }

    public function __toString(): string
    {
        return sprintf(
            '%s %scm x %scm x %scm',
            static::class,
            $this->width,
            $this->height,
            $this->length,
        );
    }
}
