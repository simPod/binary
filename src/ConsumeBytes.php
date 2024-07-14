<?php

declare(strict_types=1);

namespace Kafkiansky\Binary;

/**
 * @api
 */
interface ConsumeBytes
{
    /**
     * @psalm-return ($n is positive-int ? non-empty-string : string)
     * @throws BinaryException
     */
    public function consume(int $n): string;
}
