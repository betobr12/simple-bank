<?php

namespace App\Libraries;

class GenerateCard
{

    public $account_id, $user_id, $date;

    public function cardNumber()
    {
        $data_number = "1234567890";
        $card_number = rand(16,$data_number.substr(preg_replace( '/[^0-9]/', '', $this->date),1,5)).$this->account_id.$this->user_id;

        return $result = (object) array("card_number" => $card_number);
    }
}
