<?php

namespace SotkClient\Laravel\Rules\Pendidikan;

use SotkClient\Laravel\Facades\SotkClient;
use Illuminate\Contracts\Validation\Rule;

class IdJurusanRule implements Rule
{

    /**
     * Determine if the validation rule passes.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @return bool
     */
    public function passes($attribute, $value)
    {
        try {
            SotkClient::module('pendidikan-jurusan')->getDetail($value);
            return true;
        } catch (\Exception $e) {
            return false;
        }
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'The :attribute is not valid Id Jurusan';
    }
}
