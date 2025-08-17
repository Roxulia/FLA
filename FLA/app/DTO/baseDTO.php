<?php

namespace App\DTO;

abstract class baseDTO
{
    /**
     * Create a new class instance.
     */
    public function __get(string $field)
    {
        if (property_exists($this, $field)) {
            return $this->$field;
        }

        throw new \InvalidArgumentException("Property {$field} does not exist in " . __CLASS__);
    }
}
