<?php

declare(strict_types=1);

namespace Kafkiansky\Binary\Exception;

use Kafkiansky\Binary\BinaryException;

/**
 * @api
 */
final class BytesCannotBeUnpacked extends \Exception implements BinaryException
{
    public function __construct(
        public readonly string $bytes,
        public readonly string $format,
        ?\Throwable $previous = null,
    ) {
        parent::__construct(sprintf('The bytes "%s" cannot be unpacked using format "%s".', $this->bytes, $this->format), previous: $previous);
    }
}
