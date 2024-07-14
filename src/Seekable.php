<?php

declare(strict_types=1);

namespace Kafkiansky\Binary;

/**
 * @internal
 * @psalm-internal Kafkiansky\Binary
 */
interface Seekable
{
    /**
     * @throws BinaryException
     */
    public function seek(): void;
}
