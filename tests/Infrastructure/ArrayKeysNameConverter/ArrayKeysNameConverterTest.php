<?php

declare(strict_types=1);

namespace Tests\Infrastructure\ArrayKeysNameConverter;

use App\Infrastructure\ArrayKeysNameConverter\ArrayKeysNameConverter;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Serializer\NameConverter\CamelCaseToSnakeCaseNameConverter;

final class ArrayKeysNameConverterTest extends TestCase
{
    private $converter;

    protected function setUp(): void
    {
        $this->converter = new ArrayKeysNameConverter(new CamelCaseToSnakeCaseNameConverter());
    }

    public function testSingleValueArray(): void
    {
        $input = ['one_key' => 1];

        $output = $this->converter->convert($input);

        self::assertEquals(['oneKey' => 1], $output);
    }

    public function testDoubleValueArray(): void
    {
        $input = ['key_one' => 1, 'key_two' => 2];

        $output = $this->converter->convert($input);

        self::assertEquals(['keyOne' => 1, 'keyTwo' => 2], $output);
    }

    public function testFirstValueIsSinvleValueArray(): void
    {
        $input = [
            'key_one' => [
                'key_two' => 2,
            ],
        ];

        $output = $this->converter->convert($input);

        self::assertEquals(
            [
                'keyOne' => [
                    'keyTwo' => 2,
                ],
            ],
            $output
        );
    }

    public function testFirstValueIsSingleValueArrayAndSecondValueIsSingleValue(): void
    {
        $input = [
            'key_one'   => [
                'key_two' => 2,
            ],
            'key_three' => 3,
        ];

        $output = $this->converter->convert($input);

        self::assertEquals(
            [
                'keyOne'   => [
                    'keyTwo' => 2,
                ],
                'keyThree' => 3,
            ],
            $output
        );
    }

    public function testLastValueIsSingleValueArray(): void
    {
        $input = [
            'key_one' => 1,
            'key_two' => [
                'key_three' => 3,
            ],
        ];

        $output = $this->converter->convert($input);

        self::assertEquals(
            [
                'keyOne' => 1,
                'keyTwo' => [
                    'keyThree' => 3,
                ],
            ],
            $output
        );
    }
}
