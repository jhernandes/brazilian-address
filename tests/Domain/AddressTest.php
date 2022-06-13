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
        $address = Address::fromString('Rua Júlio Gonzalez, 100, Barra Funda, Apto 1051,São Paulo, SP, 01156060');
        $this->assertSame('Rua Júlio Gonzalez', $address->jsonSerialize()['street']);
        $this->assertSame('100', $address->jsonSerialize()['number']);
        $this->assertSame('Barra Funda', $address->jsonSerialize()['district']);
        $this->assertSame('Apto 1051', $address->jsonSerialize()['complement']);
        $this->assertSame('São Paulo', $address->jsonSerialize()['city']);
        $this->assertSame('SP', $address->jsonSerialize()['state']);
        $this->assertSame('01156-060', $address->jsonSerialize()['cep']);
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

    public function testCannotCreateValidAddressFromEmptyCity(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        Address::fromString('Rua Júlio Gonzalez, 100, Barra Funda,,, SP, 1234');
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

    public function testCanCreateValidAddressWithoutNumber(): void
    {
        $this->assertInstanceOf(Address::class, Address::fromString('Rua Júlio Gonzalez,, Barra Funda,,São Paulo, SP, 01156060'));
    }
}
