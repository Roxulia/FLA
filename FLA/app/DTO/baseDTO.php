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

    public function __eq(object $obj) : bool
    {
        $this_values = get_object_vars($this);
        $obj_values = get_object_vars($obj);
        if($this_values !== $obj_values)
        {
            return false;
        }
        return $this_values === $obj_values;
    }
}
