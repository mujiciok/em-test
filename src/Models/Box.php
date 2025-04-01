<?php

declare(strict_types=1);

namespace App\Models;

class Box
{
    public function __construct(
        public int $width,
        public int $height,
        public int $length,
        public bool $rotateX = false,
        public bool $rotateY = false,
        public bool $rotateZ = false,
    ) {
    }

    public function __toString(): string
    {
        return sprintf(
            '%scm x %scm x %scm; %s %s %s',
            $this->width,
            $this->height,
            $this->length,
            $this->rotateX ? 'X' : '-',
            $this->rotateY ? 'Y' : '-',
            $this->rotateZ ? 'Z' : '-',
        );
    }

    public function getVolume(): float
    {
        return $this->width * $this->height * $this->length;
    }
}
