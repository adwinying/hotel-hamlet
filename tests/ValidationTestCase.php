<?php

namespace Tests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;

abstract class ValidationTestCase extends TestCase
{
    /**
     * @return array<string, mixed>
     */
    abstract protected function baseInput(): array;

    /**
     * @return FormRequest|class-string
     */
    abstract protected function request(): FormRequest|string;

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
        $input   = $this->makeInput($input ?? [], $exceptKey);
        $request = $this->request();

        if (!($request instanceof FormRequest)) {
            $this->validateData($expected, $input);

            return;
        }

        $rules = $request->rules();
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

    /**
     * @param array<string,mixed> $input
     */
    protected function validateData(bool $expected, array $input): void
    {
        $request = $this->request();
        if ($request instanceof FormRequest) {
            return;
        }

        try {
            $request::validate($input);
            $result = true;
        } catch (ValidationException $e) {
            $result = false;
            if ($expected !== $result) {
                dump($e->errors());
            }
        }

        $this->assertEquals($expected, $result);
    }
}
