<?php

declare(strict_types=1);

namespace Kafkiansky\Binary\Tests;

use Kafkiansky\Binary\BinaryException;
use Kafkiansky\Binary\Buffer;
use Kafkiansky\Binary\ByteStream;
use Kafkiansky\Binary\Endianness;
use Kafkiansky\Binary\ResourceStream;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

#[CoversClass(Buffer::class)]
#[CoversClass(ByteStream::class)]
#[CoversClass(ResourceStream::class)]
final class BufferTest extends TestCase
{
    public static function readFixtures(): iterable
    {
        yield 'int8' => [
            fn (Buffer $buffer, int $value): Buffer => $buffer->writeInt8($value),
            fn (Buffer $buffer): int => $buffer->readInt8(),
            120,
            1,
        ];

        yield 'uint8' => [
            fn (Buffer $buffer, int $value): Buffer => $buffer->writeUint8($value),
            fn (Buffer $buffer): int => $buffer->readUint8(),
            220,
            1,
        ];

        yield 'int16' => [
            fn (Buffer $buffer, int $value): Buffer => $buffer->writeInt16($value),
            fn (Buffer $buffer): int => $buffer->readInt16(),
            -32766,
            2,
        ];

        yield 'uint16' => [
            fn (Buffer $buffer, int $value): Buffer => $buffer->writeUint16($value),
            fn (Buffer $buffer): int => $buffer->readUint16(),
            65534,
            2,
        ];

        yield 'int32' => [
            fn (Buffer $buffer, int $value): Buffer => $buffer->writeInt32($value),
            fn (Buffer $buffer): int => $buffer->readInt32(),
            -2147483647,
            4,
        ];

        yield 'uint32' => [
            fn (Buffer $buffer, int $value): Buffer => $buffer->writeUint32($value),
            fn (Buffer $buffer): int => $buffer->readUint32(),
            4294967295,
            4,
        ];

        yield 'int64' => [
            fn (Buffer $buffer, int $value): Buffer => $buffer->writeInt64($value),
            fn (Buffer $buffer): int => $buffer->readInt64(),
            \PHP_INT_MIN,
            8,
        ];

        yield 'uint64' => [
            fn (Buffer $buffer, int $value): Buffer => $buffer->writeUint64($value),
            fn (Buffer $buffer): int => $buffer->readUint64(),
            \PHP_INT_MAX,
            8,
        ];

        yield 'varint/1' => [
            fn (Buffer $buffer, int $value): Buffer => $buffer->writeVarInt($value),
            fn (Buffer $buffer): int => $buffer->readVarInt(),
            10,
            1,
        ];

        yield 'varint/2' => [
            fn (Buffer $buffer, int $value): Buffer => $buffer->writeVarInt($value),
            fn (Buffer $buffer): int => $buffer->readVarInt(),
            300,
            2,
        ];

        yield 'varint/5' => [
            fn (Buffer $buffer, int $value): Buffer => $buffer->writeVarInt($value),
            fn (Buffer $buffer): int => $buffer->readVarInt(),
            2 ** 32,
            5,
        ];

        yield 'varuint/1' => [
            fn (Buffer $buffer, int $value): Buffer => $buffer->writeVarUint($value),
            fn (Buffer $buffer): int => $buffer->readVarUint(),
            10,
            1,
        ];

        yield 'varuint/2' => [
            fn (Buffer $buffer, int $value): Buffer => $buffer->writeVarUint($value),
            fn (Buffer $buffer): int => $buffer->readVarUint(),
            300,
            2,
        ];

        yield 'varuint/5' => [
            fn (Buffer $buffer, int $value): Buffer => $buffer->writeVarUint($value),
            fn (Buffer $buffer): int => $buffer->readVarUint(),
            2 ** 32,
            5,
        ];

        yield 'float' => [
            fn (Buffer $buffer, float $value): Buffer => $buffer->writeFloat($value),
            fn (Buffer $buffer): float => $buffer->readFloat(),
            1.5,
            4,
        ];

        yield 'double' => [
            fn (Buffer $buffer, float $value): Buffer => $buffer->writeDouble($value),
            fn (Buffer $buffer): float => $buffer->readDouble(),
            1.2,
            8,
        ];

        yield 'string' => [
            fn (Buffer $buffer, string $value): Buffer => $buffer->write($value),
            fn (Buffer $buffer): string => $buffer->read(4),
            'test',
            4,
        ];
    }

    public static function consumeFixtures(): iterable
    {
        yield 'int8' => [
            fn (Buffer $buffer, int $value): Buffer => $buffer->writeInt8($value),
            fn (Buffer $buffer): int => $buffer->consumeInt8(),
            120,
            1,
        ];

        yield 'uint8' => [
            fn (Buffer $buffer, int $value): Buffer => $buffer->writeUint8($value),
            fn (Buffer $buffer): int => $buffer->consumeUint8(),
            220,
            1,
        ];

        yield 'int16' => [
            fn (Buffer $buffer, int $value): Buffer => $buffer->writeInt16($value),
            fn (Buffer $buffer): int => $buffer->consumeInt16(),
            -32766,
            2,
        ];

        yield 'uint16' => [
            fn (Buffer $buffer, int $value): Buffer => $buffer->writeUint16($value),
            fn (Buffer $buffer): int => $buffer->consumeUint16(),
            65534,
            2,
        ];

        yield 'int32' => [
            fn (Buffer $buffer, int $value): Buffer => $buffer->writeInt32($value),
            fn (Buffer $buffer): int => $buffer->consumeInt32(),
            -2147483647,
            4,
        ];

        yield 'uint32' => [
            fn (Buffer $buffer, int $value): Buffer => $buffer->writeUint32($value),
            fn (Buffer $buffer): int => $buffer->consumeUint32(),
            4294967295,
            4,
        ];

        yield 'int64' => [
            fn (Buffer $buffer, int $value): Buffer => $buffer->writeInt64($value),
            fn (Buffer $buffer): int => $buffer->consumeInt64(),
            \PHP_INT_MIN,
            8,
        ];

        yield 'uint64' => [
            fn (Buffer $buffer, int $value): Buffer => $buffer->writeUint64($value),
            fn (Buffer $buffer): int => $buffer->consumeUint64(),
            \PHP_INT_MAX,
            8,
        ];

        yield 'varint/1' => [
            fn (Buffer $buffer, int $value): Buffer => $buffer->writeVarInt($value),
            fn (Buffer $buffer): int => $buffer->consumeVarInt(),
            10,
            1,
        ];

        yield 'varint/2' => [
            fn (Buffer $buffer, int $value): Buffer => $buffer->writeVarInt($value),
            fn (Buffer $buffer): int => $buffer->consumeVarInt(),
            300,
            2,
        ];

        yield 'varint/5' => [
            fn (Buffer $buffer, int $value): Buffer => $buffer->writeVarInt($value),
            fn (Buffer $buffer): int => $buffer->consumeVarInt(),
            2 ** 32,
            5,
        ];

        yield 'varuint/1' => [
            fn (Buffer $buffer, int $value): Buffer => $buffer->writeVarUint($value),
            fn (Buffer $buffer): int => $buffer->consumeVarUint(),
            10,
            1,
        ];

        yield 'varuint/2' => [
            fn (Buffer $buffer, int $value): Buffer => $buffer->writeVarUint($value),
            fn (Buffer $buffer): int => $buffer->consumeVarUint(),
            300,
            2,
        ];

        yield 'varuint/5' => [
            fn (Buffer $buffer, int $value): Buffer => $buffer->writeVarUint($value),
            fn (Buffer $buffer): int => $buffer->consumeVarUint(),
            2 ** 32,
            5,
        ];

        yield 'float' => [
            fn (Buffer $buffer, float $value): Buffer => $buffer->writeFloat($value),
            fn (Buffer $buffer): float => $buffer->consumeFloat(),
            1.5,
            4,
        ];

        yield 'double' => [
            fn (Buffer $buffer, float $value): Buffer => $buffer->writeDouble($value),
            fn (Buffer $buffer): float => $buffer->consumeDouble(),
            1.2,
            8,
        ];

        yield 'string' => [
            fn (Buffer $buffer, string $value): Buffer => $buffer->write($value),
            fn (Buffer $buffer): string => $buffer->consume(4),
            'test',
            4,
        ];
    }

    /**
     * @template T
     * @param callable(Buffer, T): Buffer $write
     * @param callable(Buffer): T         $read
     * @param T                           $value
     */
    #[DataProvider('readFixtures')]
    public function testReadByteStream(callable $write, callable $read, mixed $value, int $size): void
    {
        /** @var Endianness $endian */
        foreach ([Endianness::big(), Endianness::little()] as $endian) {
            $buffer = Buffer::fromString('', $endian);
            $buffer = $write($buffer, $value);
            self::assertCount($size, $buffer);
            self::assertSame($value, $read($buffer));
            self::assertCount($size, $buffer);
        }
    }

    /**
     * @template T
     * @param callable(Buffer, T): Buffer $write
     * @param callable(Buffer): T         $read
     * @param T                           $value
     */
    #[DataProvider('readFixtures')]
    public function testReadResourceStream(callable $write, callable $read, mixed $value, int $size): void
    {
        /** @var Endianness $endian */
        foreach ([Endianness::big(), Endianness::little()] as $endian) {
            $resource = fopen('php://temp', 'a+');
            self::assertIsResource($resource);

            $buffer = Buffer::fromResource($resource, $endian);
            $buffer = $write($buffer, $value);
            self::assertCount($size, $buffer);
            fseek($resource, 0);
            self::assertSame($value, $read($buffer));
            self::assertCount($size, $buffer);
        }
    }

    /**
     * @template T
     * @param callable(Buffer, T): Buffer $write
     * @param callable(Buffer): T         $read
     * @param T                           $value
     */
    #[DataProvider('consumeFixtures')]
    public function testConsumeByteStream(callable $write, callable $read, mixed $value, int $size): void
    {
        /** @var Endianness $endian */
        foreach ([Endianness::big(), Endianness::little()] as $endian) {
            $buffer = Buffer::fromString('', $endian);
            $buffer = $write($buffer, $value);
            self::assertCount($size, $buffer);
            self::assertSame($value, $read($buffer));
            self::assertCount(0, $buffer);
        }
    }

    /**
     * @template T
     * @param callable(Buffer, T): Buffer $write
     * @param callable(Buffer): T         $read
     * @param T                           $value
     */
    #[DataProvider('consumeFixtures')]
    public function testConsumeResourceStream(callable $write, callable $read, mixed $value, int $size): void
    {
        /** @var Endianness $endian */
        foreach ([Endianness::big(), Endianness::little()] as $endian) {
            $resource = fopen('php://temp', 'a+');
            self::assertIsResource($resource);

            $buffer = Buffer::fromResource($resource, $endian);
            $buffer = $write($buffer, $value);
            self::assertCount($size, $buffer);
            fseek($resource, 0);
            self::assertSame($value, $read($buffer));
            self::assertCount(0, $buffer);
        }
    }

    public function testEmptyBuffer(): void
    {
        self::expectException(BinaryException::class);
        self::expectExceptionMessage('Is not enough bytes to read: need is 1, but actual is 0.');
        Buffer::empty()->readInt8();
    }

    public function testSplit(): void
    {
        $buffer = Buffer::empty(Endianness::network());
        $buffer->writeUint32(20);
        $buffer->writeUint32(30);

        $newBuffer = $buffer->split(4);
        self::assertCount(4, $newBuffer);
        self::assertSame(20, $newBuffer->consumeUint32());
        self::assertCount(4, $buffer);
        self::assertSame(30, $buffer->consumeUint32());
    }

    public function testClone(): void
    {
        $buffer = Buffer::empty(Endianness::network());
        $buffer->writeUint32(2);
        $buffer2 = $buffer->clone();
        self::assertCount(0, $buffer2);
        $buffer2->writeUint32(2);
        self::assertSame($buffer->reset(), $buffer2->reset());
    }
}
