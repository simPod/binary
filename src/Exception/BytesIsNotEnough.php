<?php

declare(strict_types=1);

namespace Kafkiansky\Binary\Exception;

use Kafkiansky\Binary\BinaryException;

/**
 * @api
 */
final class BytesIsNotEnough extends \Exception implements BinaryException
{
    public function __construct(
        public readonly int $need,
        public readonly int $actual,
        ?\Throwable $previous = null,
    ) {
        parent::__construct(sprintf('Is not enough bytes to read: need is %d, but actual is %d.', $this->need, $this->actual), previous: $previous);
    }
}
