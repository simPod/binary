<?php

declare(strict_types=1);

namespace Kafkiansky\Binary;

/**
 * @api
 */
final class ByteStream implements Stream
{
    public function __construct(
        private string $bytes = '',
    ) {
    }

    /**
     * {@inheritdoc}
     */
    public function write(string $bytes): void
    {
        $this->bytes .= $bytes;
    }

    /**
     * {@inheritdoc}
     */
    public function read(int $n): string
    {
        return substr($this->bytes, 0, $n);
    }

    /**
     * {@inheritdoc}
     */
    public function consume(int $n): string
    {
        $buf = substr($this->bytes, 0, $n);
        $this->bytes = substr($this->bytes, $n);

        return $buf;
    }

    public function eof(): bool
    {
        return $this->bytes === '';
    }

    /**
     * {@inheritdoc}
     */
    public function size(): int
    {
        return \strlen($this->bytes);
    }

    /**
     * {@inheritdoc}
     */
    public function getIterator(): \Traversable
    {
        yield from str_split($this->bytes);
    }
}
