<?php

namespace App\Http\Controllers;

use Hashids;

trait ControllerTrait
{

    protected function parse_filters(string $unparsed_filters, array $accepted_filters)
    {

        $filters = [];

        $unparsed_filters_array = explode(',', $unparsed_filters);

        foreach ($unparsed_filters_array as $unparsed_filter) {
            
            $f = explode('=', $unparsed_filter);

            if ($f[0] && $f[1] && array_key_exists($f[0], $accepted_filters)) {
                
                $filters[$accepted_filters[$f[0]]] = array_map(function($x) {

                    $decoded_id = Hashids::decode($x);
                    return is_array($decoded_id) ? $decoded_id[0] : null;

                }, explode(' ', $f[1]));
                
            }

        }

        return $filters;

    }

    protected function unhash_id($hashed_id)
    {

        $unhashed_array = Hashids::decode($hashed_id);
        return is_array($unhashed_array) ? array_shift($unhashed_array) : null ;

    }

    protected function generate_string(string $pool = '0123456789', $string_length = 4) {

        $random_string = '';
        
        $pool_length = strlen($pool);

        for($i = 0; $i < $string_length; $i++) {
            $random_character = $pool[mt_rand(0, $pool_length - 1)];
            $random_string .= $random_character;
        }
    
        return $random_string;
        
    }

}