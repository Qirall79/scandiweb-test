<?php

class Dvd extends Product
{

    private $attributes;

    public function __construct($input, $conn)
    {
        parent::__construct($input, $conn);

        $this->attributes = [
            "size" => array_key_exists("size", $input) ? (int) $input["size"] : -1,
        ];
    }

    public function validateAttributes()
    {
        if (is_numeric($this->attributes["size"]) && $this->attributes["size"] >= 0) {
            return [];
        }
        return ["Invalid size"];
    }
}
