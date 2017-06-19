<?php

namespace Deseco\Economic\Services;

use Deseco\Economic\Contracts\TypeInterface;

class Object
{
    protected $propsProtected = array();
    protected $required = array();

    protected function protect($key)
    {
        $this->propsProtected[] = $key;
    }

    public function toArray()
    {
        $objVars = get_object_vars($this);
        $return  = array();
        foreach ($objVars as $key => $value) {
            if (!$this->isProtected($key)) {
                if (is_object($value) && method_exists($value, "toArray")) {
                    $return = array_merge($return, $value->toArray());
                    continue;
                }
                if ($this->isRequired($key) && $value === null) {
                    throw new \InvalidArgumentException(sprintf("Missing argument %s", $key));
                }
                $key[0] = strtoupper($key[0]);
                if (is_array($value)) {
                    $value = (array) $value;
                }
                if ($value instanceof TypeInterface) {
                    $value = $value->toXSD();
                }
                $return[$key] = $value;
            }
        }

        return $return;
    }

    protected function isProtected($key)
    {
        return in_array($key, $this->propsProtected) || $key === "propsProtected" || $key === "required";
    }

    protected function isRequired($key)
    {
        return in_array($key, $this->required);
    }

    protected function getHandle($handle, $key = "Number")
    {
        if (is_array($handle) && array_key_exists($key, $handle)) {
            return $handle;
        } else {
            return array("Number" => $handle);
        }
    }
}
