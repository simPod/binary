<?php

declare(strict_types=1);

namespace Kafkiansky\Binary;

/**
 * @api
 */
interface WriteBytes
{
    /**
     * @throws BinaryException
     */
    public function write(string $bytes): self;
}
