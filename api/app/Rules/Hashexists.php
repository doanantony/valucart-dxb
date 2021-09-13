<?php

namespace App\Rules;

use Hashids;
use Illuminate\Support\Facades\DB;
use Illuminate\Contracts\Validation\Rule;
use Illuminate\Validation\Rules\DatabaseRule;

class Hashexists implements Rule
{

    use DatabaseRule;

    /**
     * Determine if the validation rule passes.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @return bool
     */
    public function passes($attribute, $value)
    {   
        
        $unhased_array = Hashids::decode($value);

        if (is_array($unhased_array)) {

            $unhased_value = array_shift($unhased_array);

            if (is_null($unhased_value)) {
                return false;
            }

            $rows = DB::table($this->table)->where($this->column, $unhased_value)->count();

            if ($rows > 0) {
                return true;
            }

        }

        return false;

    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'The :attribute seems to be invalid.';
    }

    /**
     * Convert the rule to a validation string.
     *
     * @return string
     */
    public function __toString()
    {
        return rtrim(sprintf('hashexists:%s,%s,%s',
            $this->table,
            $this->column,
            $this->formatWheres()
        ), ',');
    }
    
}
