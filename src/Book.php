<?php

class Book extends Product
{

    private $attributes;

    public function __construct($input, $conn)
    {
        parent::__construct($input, $conn);

        $this->attributes = [
            "weight" => array_key_exists("weight", $input) ? (int) $input["weight"] : -1,
        ];
    }

    public function validateAttributes()
    {
        if (is_numeric($this->attributes["weight"]) && $this->attributes["weight"] >= 0) {
            return [];
        }
        return ["Invalid weight"];
    }
}
