<?php

declare(strict_types=1);

namespace App\Models;

/**
 * Box size:
 * width - size on Z axis
 * height - size on Y axis
 * length - size on X axis
 */
class Box
{
    /**
     * @TODO maybe combine width,height,length in a SizeDto
     * @TODO maybe combine rotateZ,rotateY,rotateX in a RotationStateDto
     * @TODO __construct(public SizeDto $size, public RotationStateDto)
     * @param int $width
     * @param int $height
     * @param int $length
     * @param bool $rotateZ
     * @param bool $rotateY
     * @param bool $rotateX
     */
    public function __construct(
        public int $width,
        public int $height,
        public int $length,
        public bool $rotateZ = false,
        public bool $rotateY = false,
        public bool $rotateX = false,
    ) {
    }

    public function __toString(): string
    {
        return sprintf(
            '%scm x %scm x %scm',
            $this->width,
            $this->height,
            $this->length,
        );
    }

    public function getVolume(): float
    {
        return $this->width * $this->height * $this->length;
    }
}
