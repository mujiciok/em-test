<?php

declare(strict_types=1);

namespace App\Models;

final class StandardDryContainer10 extends Container
{
    public const WIDTH = 234.8;
    public const HEIGHT = 238.44;
    public const LENGTH = 270.4;

    public function __construct()
    {
        parent::__construct(self::WIDTH, self::HEIGHT, self::LENGTH);
    }
}
