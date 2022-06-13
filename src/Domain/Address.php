<?php

declare(strict_types=1);

namespace Jhernandes\BrazilianAddress\Domain;

use Jhernandes\BrazilianAddress\Domain\Cep;

class Address implements \JsonSerializable
{
    private string $street;
    private string $number;
    private string $district;
    private string $complement;
    private string $city;
    private string $state;
    private Cep $cep;

    public function __construct(
        string $street,
        string $number,
        string $district,
        string $complement,
        string $city,
        string $state,
        string $cep
    ) {
        $this->setStreet($street);
        $this->setNumber($number);
        $this->setDistrict($district);
        $this->setComplement($complement);
        $this->setCity($city);
        $this->setState($state);
        $this->cep = Cep::fromString($cep);
    }

    /**
     * Just inform your full address with this pattern
     * :: street, number, complement, district, city, state, cep
     */
    public static function fromString(string $address): self
    {
        $address = explode(',', $address);
        return new self(
            isset($address[0]) ? trim($address[0]) : '',
            isset($address[1]) ? trim($address[1]) : '',
            isset($address[2]) ? trim($address[2]) : '',
            isset($address[3]) ? trim($address[3]) : '',
            isset($address[4]) ? trim($address[4]) : '',
            isset($address[5]) ? trim($address[5]) : '',
            isset($address[6]) ? trim($address[6]) : '',
        );
    }

    public function jsonSerialize(): array
    {
        return [
            'street' => $this->street,
            'number' => isset($this->number) ?: '',
            'complement' => isset($this->complement) ?: '',
            'district' => $this->district,
            'city' => $this->city,
            'state' => $this->state,
            'cep' => $this->cep,
        ];
    }

    private function setStreet(string $street): void
    {
        $this->ensureIsValid($street);
        $this->street = substr($street, 0, 80);
    }

    private function setNumber(string $number): void
    {
        if (!empty($number)) {
            $this->ensureIsValid($number);
            $this->number = substr($number, 0, 10);
        }
    }

    private function setDistrict(string $district): void
    {
        $this->ensureIsValid($district);
        $this->district = substr($district, 0, 40);
    }

    private function setComplement(string $complement): void
    {
        if (!empty($complement)) {
            $this->ensureIsValid($complement);
            $this->complement = substr($complement, 0, 20);
        }
    }

    private function setCity(string $city): void
    {
        $this->ensureIsValid($city);
        $this->city = substr($city, 0, 80);
    }

    private function setState(string $state): void
    {
        $this->ensureIsValidState($state);
        $this->state = $state;
    }

    private function ensureIsValid(string $string): void
    {
        if (!preg_match('/^[0-9a-zA-ZÀ-ÖØ-öø-ÿ\s]+$/', $string)) {
            throw new \InvalidArgumentException(
                sprintf('%s is not valid', $string)
            );
        }
    }

    private function ensureIsValidState(string $state): void
    {
        $validStates = [
            'AC', 'AL', 'AM', 'AP', 'BA', 'CE', 'DF', 'ES', 'GO', 'MA', 'MG', 'MS',
            'MT', 'PA', 'PB', 'PE', 'PI', 'PR', 'RJ', 'RN', 'RO', 'RR', 'RS', 'SC',
            'SE', 'SP', 'TO',
        ];

        if (!in_array($state, $validStates)) {
            throw new \InvalidArgumentException(
                sprintf('%s is not valid state', $state)
            );
        }
    }
}
