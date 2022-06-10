<?php

declare(strict_types=1);

namespace Jhernandes\BrazilianAddress\Domain;

class Cep
{
    private string $cep;

    public function __construct($cep)
    {
        $cep = preg_replace('/\D/', '', $cep);
        $this->ensureIsValidCep($cep);
        $this->cep = $cep;
    }

    public static function fromString(string $cep): self
    {
        return new self($cep);
    }

    public function __toString(): string
    {
        return $this->cep;
    }

    public function formatted(): string
    {
        return sprintf(
            '%s-%s',
            substr($this->cep, 0, 5),
            substr($this->cep, 5)
        );
    }

    public function fromState(): string
    {
        $map = [
            'SP' => ['00000000', '01000000'],
            'SP' => ['01000000', '19999999'],
            'RJ' => ['20000000', '28999999'],
            'ES' => ['29000000', '29999999'],
            'MG' => ['30000000', '39999999'],
            'BA' => ['40000000', '48999999'],
            'SE' => ['49000000', '49999999'],
            'PE' => ['50000000', '56999999'],
            'AL' => ['57000000', '57999999'],
            'PB' => ['58000000', '58999999'],
            'RN' => ['59000000', '59999999'],
            'CE' => ['60000000', '63999999'],
            'PI' => ['64000000', '64999999'],
            'MA' => ['65000000', '65999999'],
            'PA' => ['66000000', '68899999'],
            'AP' => ['68900000', '68999999'],
            'AM' => ['69000000', '69299999'],
            'RR' => ['69300000', '69389000'],
            'AM' => ['69400000', '69899999'],
            'AC' => ['69900000', '69999999'],
            'DF' => ['70000000', '73699999'],
            'GO' => ['72800000', '76799999'],
            'TO' => ['77000000', '77999995'],
            'MT' => ['78000000', '78899999'],
            'RO' => ['78900000', '78999999'],
            'MS' => ['79000000', '79999999'],
            'PR' => ['80000000', '87999999'],
            'SC' => ['88000000', '89999999'],
            'RS' => ['90000000', '99999999'],
        ];

        foreach ($map as $state => $range) {
            list($rangeStart, $rangeEnd) = $range;

            if ($this->cep >= $rangeStart && $this->cep <= $rangeEnd) {
                return $state;
            }
        }

        throw new \InvalidArgumentException(
            sprintf('%s is not in range.', $this->cep)
        );
    }

    public static function searchCep(string $cep): array
    {
        $cep = preg_replace('/\D/', '', $cep);
        $address = @file_get_contents("https://viacep.com.br/ws/{$cep}/json/");

        if ($address === false) {
            throw new \UnexpectedValueException(
                sprintf('%s cep could not returned a valid address', $cep)
            );
        }

        return json_decode($address, true);
    }

    private function ensureIsValidCep(string $cep): void
    {
        if (!preg_match('/^[0-9]{8}$/', $cep)) {
            throw new \InvalidArgumentException(
                sprintf('%s is not a valid cep.', $cep)
            );
        }
    }
}
