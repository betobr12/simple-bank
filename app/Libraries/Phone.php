<?php

namespace App\Libraries;

class Phone
{

    public $phone;

    public function phone(){

        $this->phone = trim(str_replace('/', '', str_replace(' ', '', str_replace('-', '', str_replace(')', '', str_replace('(', '', $this->phone))))));

        if ( strlen($this->phone) > 11  ) {
            return false;
        }

        $regexCel = '/[0-9]{2}[6789][0-9]{3,4}[0-9]{4}/';

        if (preg_match($regexCel, $this->phone)) {
            return true;
        }else{
            return false;
        }
    }
}
