<?php

class Furniture extends Product
{

    private $attributes;

    public function __construct($input, $conn)
    {
        parent::__construct($input, $conn);

        $this->attributes = [
            "height" => array_key_exists("height", $input) ? (int) $input["height"] : -1,
            "width" => array_key_exists("width", $input) ? (int) $input["width"] : -1,
            "length" => array_key_exists("length", $input) ? (int) $input["length"] : -1,
        ];
    }

    public function validateAttributes()
    {
        if (
            is_numeric($this->attributes["height"]) && is_numeric($this->attributes["width"]) && is_numeric($this->attributes["length"])
            && $this->attributes["height"] >= 0 && $this->attributes["width"] >= 0 && $this->attributes["length"] >= 0
        ) {
            return [];
        }
        return ["Invalid dimensions"];
    }
}
