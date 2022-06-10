<?php

declare(strict_types=1);

use PHPUnit\Framework\TestCase;
use Jhernandes\BrazilianAddress\Domain\Cep;

class CepTest extends TestCase
{
    public function testCanCreateFromValidString(): void
    {
        $this->assertInstanceOf(
            Cep::class,
            Cep::fromString('01156-060')
        );
    }

    public function testCanBeFormatted(): void
    {
        $this->assertEquals(
            '01156-060',
            Cep::fromString('01156060')->formatted()
        );
    }

    public function testCannotBeCreatedFromInvalidCep(): void
    {
        $this->expectException(\InvalidArgumentException::class);

        Cep::fromString('00000-00');
    }

    public function testCreateCepFromSaoPaulo()
    {
        $this->assertEquals('SP', Cep::fromString('01156060')->fromState());
    }

    public function testSearchAddressFromInvalidCep()
    {
        $this->expectException(\UnexpectedValueException::class);
        Cep::searchCep('123');
    }

    public function testSearchAddressFromValidCep()
    {
        $this->assertIsArray(Cep::searchCep('01156060'));
    }
}
