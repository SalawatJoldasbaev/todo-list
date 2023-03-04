<?php

namespace App\Services;

use Carbon\Carbon;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;

abstract class BaseService
{
    /**
     * Get the validation rules that apply to the service.
     *
     * @return array
     */
    public function rules(): array
    {
        return [];
    }

    /**
     * Validate all datas to execute the service.
     *
     * @param array $data
     * @return bool
     * @throws ValidationException
     */
    public function validate(array $data): bool
    {
        Validator::make($data, $this->rules())
            ->validate();

        return true;
    }

    /**
     * Checks if the value is empty or null.
     *
     * @param  mixed  $data
     * @param  mixed  $index
     * @return mixed
     */
    public function nullOrValue(mixed $data, mixed $index): mixed
    {
        $value = Arr::get($data, $index);

        return is_null($value) || $value === '' ? null : $value;
    }

    /**
     * Checks if the value is empty or null and returns a date from a string.
     *
     * @param  mixed  $data
     * @param  mixed  $index
     * @return Carbon|null
     */
    public function nullOrDate(mixed $data, mixed $index): ?Carbon
    {
        $value = Arr::get($data, $index);

        return is_null($value) || $value === '' ? null : Carbon::parse($value);
    }

    /**
     * Returns the value if it's defined, or false otherwise.
     *
     * @param  mixed  $data
     * @param  mixed  $index
     * @return mixed
     */
    public function valueOrFalse(mixed $data, mixed $index): mixed
    {
        if (empty($data[$index])) {
            return false;
        }

        return $data[$index];
    }
}
