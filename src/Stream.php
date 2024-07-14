<?php

declare(strict_types=1);

namespace Kafkiansky\Binary;

/**
 * @api
 * @template-extends \IteratorAggregate<array-key, string>
 */
interface Stream extends \IteratorAggregate
{
    /**
     * @param non-empty-string $bytes
     * @throws BinaryException
     */
    public function write(string $bytes): void;

    /**
     * Read only reads without affecting the stream.
     * In other words, repeated reads should return the same data.
     *
     * @psalm-return ($n is positive-int ? non-empty-string : string)
     * @throws BinaryException
     */
    public function read(int $n): string;

    /**
     * Consume reads data affecting the stream.
     * In other words, repeated reads should return the different data.
     *
     * @psalm-return ($n is positive-int ? non-empty-string : string)
     * @throws BinaryException
     */
    public function consume(int $n): string;

    public function eof(): bool;

    /**
     * @return int<0, max>
     * @throws BinaryException
     */
    public function size(): int;
}
