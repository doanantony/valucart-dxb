<?php

namespace App\Rules;

use Hashids;
use Illuminate\Support\Facades\DB;
use Illuminate\Contracts\Validation\Rule;
use Illuminate\Validation\Rules\DatabaseRule;

class Uniquehash implements Rule
{

    use DatabaseRule;

    protected $except_column;

    protected $except_value;

    /**
     * Create a new rule instance.
     *
     * @param  string  $table
     * @param  string  $column
     * @param  string  $except_column
     * @param  mixed   $except_value
     * @return void
     */
    public function __construct($table, $column = 'NULL', $except_column = null, $except_value = null)
    {

        $this->table = $table;
        $this->column = $column;
        $this->except_column = $except_column;
        $this->except_value = $except_value;
    }

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

            $except_column = $this->except_column;
            $except_value = $this->except_value;

            $rows = DB::table($this->table)
                        ->where($this->column, $unhased_value)
                        ->when(
                            !is_null($except_column) && !is_null($except_value),
                            function($query, $except_column, $except_value) {
                                return $query->whereNot($except_column, $except_value);
                            })
                        ->count();

            if ($rows == 0) {
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
        return 'The :attribute should be unique.';
    }

    /**
     * Convert the rule to a validation string.
     *
     * @return string
     */
    public function __toString()
    {
        return rtrim(sprintf('uniquehash:%s,%s,%s,%s,%s',
            $this->table,
            $this->column,
            $this->except_column,
            $this->except_value,
            $this->formatWheres()
        ), ',');
    }
    
}
