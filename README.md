## Installation

This package can be installed as a [Composer](https://getcomposer.org/) dependency.

```bash
composer require kafkiansky/binary
```

This package requires PHP 8.1 or later.

## Usage

#### Endianness
```php
<?php

declare(strict_types=1);

require_once __DIR__.'/vendor/autoload.php';

use Kafkiansky\Binary\Endianness;

$endian = Endianness::big(); // Big Endian.
$endian = Endianness::little(); // Little Endian.
$endian = Endianness::network(); // Big Endian.
$endian = Endianness::native(); // Machine (native) byte order.
```

#### Buffer
```php
<?php

declare(strict_types=1);

require_once __DIR__.'/vendor/autoload.php';

use Kafkiansky\Binary\{Buffer, Endianness};

$buffer = Buffer::empty(); // Network byte order (big endian) by default.
$buffer = Buffer::empty(Endianness::little()); // Override byte order.

// https://kafka.apache.org/protocol.html#The_Messages_CreateTopics
$bytes = Buffer::empty()
    ->writeInt32(1)
    ->writeInt16(6)
    ->write('events')
    ->writeInt32(1)
    ->writeInt16(1)
    ->writeInt32(0)
    ->writeInt32(0)
    ->reset()
;

$buffer = Buffer::fromString($bytes);

var_dump(
    $buffer->readInt32(),
    $buffer->read($buffer->readInt16()),
    $buffer->readInt32(),
    $buffer->readInt16(),
    $buffer->readInt32(),
    $buffer->readInt32(),
    \assert(0 === \count($buffer)),
);
```

## Available types

- [x] `int8`
- [x] `uint8`
- [x] `int16`
- [x] `uint16`
- [x] `int32`
- [x] `uint32`
- [x] `int64`
- [x] `uint64`
- [x] `f32 (float)`
- [x] `f64 (double)`
- [x] `string`
- [x] `varuint`
- [x] `varint`

## Testing

``` bash
$ composer test
```  

## License

The MIT License (MIT). See [License File](LICENSE) for more information.
