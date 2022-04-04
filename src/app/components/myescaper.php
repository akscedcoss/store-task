<?php

namespace App\Components;

use Phalcon\Escaper;
use Phalcon\Mvc\Components;

class myescaper
{
    /**
     * sanitize function
     * it will get string as parameter 
     * and return
     * html Sanatized string Back  
     * @param [string] $data
     * @return string
     */
    public function sanitize($data)
    {
        $escaper = new Escaper();
        return $escaper->escapeHtml($data);
    }

    /**
     * sanitizeArray function
     * It will get Array as a parameter and for each it 
     * @param [Array] $data
     * @return array
     */
    public function sanitizeArray($data)
    {
        $escaper = new Escaper();
        $retData = [];
        foreach ($data as $key => $value) {
            $retData[$key] = $escaper->escapeHtml($value);
        }
        return $retData;
    }
}
