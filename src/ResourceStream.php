<?php

declare(strict_types=1);

namespace Kafkiansky\Binary;

use Kafkiansky\Binary\Exception\ResourceIsNotAccessible;
use Kafkiansky\Binary\Exception\ResourceIsNotReadable;
use Kafkiansky\Binary\Exception\ResourceIsNotWritable;

/**
 * @api
 */
final class ResourceStream implements Stream, Seekable
{
    /**
     * @param resource $resource
     */
    public function __construct(
        private readonly mixed $resource,
    ) {
    }

    /**
     * {@inheritdoc}
     */
    public function write(string $bytes): void
    {
        \set_error_handler(static function (int $errno, string $errstr): never {
            throw new ResourceIsNotWritable($errstr, $errno);
        });

        try {
            fwrite($this->resource, $bytes);
        } finally {
            \restore_error_handler();
        }
    }

    /**
     * {@inheritdoc}
     */
    public function read(int $n): string
    {
        \set_error_handler(static function (int $errno, string $errstr): never {
            throw new ResourceIsNotReadable($errstr, $errno);
        });

        try {
            $pos = ftell($this->resource);
            $buf = fread($this->resource, $n);
            fseek($this->resource, $pos);

            return $buf;
        } finally {
            \restore_error_handler();
        }
    }

    /**
     * {@inheritdoc}
     */
    public function consume(int $n): string
    {
        \set_error_handler(static function (int $errno, string $errstr): never {
            throw new ResourceIsNotReadable($errstr, $errno);
        });

        try {
            return fread($this->resource, $n);
        } finally {
            \restore_error_handler();
        }
    }

    public function eof(): bool
    {
        return feof($this->resource);
    }

    /**
     * {@inheritdoc}
     */
    public function size(): int
    {
        \set_error_handler(static function (int $errno, string $errstr): never {
            throw new ResourceIsNotAccessible($errstr, $errno);
        });

        try {
            /** @var int<0, max> */
            return fstat($this->resource)['size'];
        } finally {
            \restore_error_handler();
        }
    }

    public function seek(): void
    {
        \set_error_handler(static function (int $errno, string $errstr): never {
            throw new ResourceIsNotAccessible($errstr, $errno);
        });

        try {
            fseek($this->resource, 0);
        } finally {
            \restore_error_handler();
        }
    }

    /**
     * {@inheritdoc}
     */
    public function getIterator(): \Traversable
    {
        while (!$this->eof()) {
            yield $this->consume(1);
        }
    }
}
