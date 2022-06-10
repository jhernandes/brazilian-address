<?php

declare(strict_types=1);

use Jhernandes\BrazilianAddress\Domain\Address;
use PHPUnit\Framework\TestCase;

class AddressTest extends TestCase
{
    public function testCanCreateFromValidString(): void
    {
        $this->assertInstanceOf(
            Address::class,
            Address::fromString('Rua Júlio Gonzalez, 100, Barra Funda, 3 Andar Apto 310,São Paulo, SP, 01156060')
        );
    }

    public function testCannotCreateFromInvalidString(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        Address::fromString('Rua Júlio Gonzalez - 100 - Barra Funda - 3 Andar Apto 310 - SP');
    }

    public function testCanReturnAsArray(): void
    {
        $address = Address::fromString('Rua Júlio Gonzalez, 100, Barra Funda,,São Paulo, SP, 01156060');
        $this->assertIsArray($address->jsonSerialize());
    }

    public function testCannotCreateValidAddressFromInvalidStreet(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        Address::fromString('Rua Júlio&*#$ Gonzalez, 100, Barra Funda,,São Paulo, SP, 01156060');
    }

    public function testCannotCreateValidAddressFromInvalidDistrict(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        Address::fromString('Rua Júlio Gonzalez, 100, Barra Funda$$@@!,,São Paulo, SP, 1234');
    }

    public function testCannotCreateValidAddressFromInvalidCity(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        Address::fromString('Rua Júlio Gonzalez, 100, Barra Funda,,São$$@@! Paulo, SP, 1234');
    }

    public function testCannotCreateValidAddressFromInvalidState(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        Address::fromString('Rua Júlio Gonzalez, 100, Barra Funda,,São Paulo, AT, 1234');
    }

    public function testCannotCreateValidAddressFromInvalidCep(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        Address::fromString('Rua Júlio&*#$ Gonzalez, 100, Barra Funda,,São Paulo, SP, 1234');
    }
}
