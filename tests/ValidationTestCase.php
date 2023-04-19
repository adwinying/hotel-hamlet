<?php

namespace Tests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Validator;

abstract class ValidationTestCase extends TestCase
{
    /**
     * @return array<string, mixed>
     */
    abstract protected function baseInput(): array;

    abstract protected function request(): FormRequest;

    /**
     * @return array<string, mixed>
     */
    abstract public static function formData(): array;

    /**
     * @param array<string, mixed> $input
     * @return array<string, mixed>
     */
    protected function makeInput(array $input, ?string $exceptKey): array
    {
        $baseInput = $this->baseInput();

        $input = array_replace($baseInput, $input);
        $input = $exceptKey ? Arr::except($input, $exceptKey) : $input;

        return $input;
    }

    /**
     * Form Validation Test
     *
     * @dataProvider formData
     *
     * @param array<string, mixed>|null $input
     */
    public function testFormValidation(bool $expected, ?array $input = null, ?string $exceptKey = null): void
    {
        $request = $this->request();
        $rules   = $request->rules();
        $input   = $this->makeInput($input ?? [], $exceptKey);

        $request->merge($input);

        $validator = Validator::make($input, $rules);

        if (method_exists($request, 'withValidator')) {
            $request->withValidator($validator);
        }

        $result = $validator->passes();

        if ($expected !== $result) {
            dump($validator->errors());
        }

        $this->assertEquals($expected, $result);
    }
}
