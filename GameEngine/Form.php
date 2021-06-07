<?php

class Form
{

    public $valuearray = array();
    private $errorarray = array();
    private $errorcount;

    public function __construct()
    {
        if (isset($_SESSION['errorarray']) && isset($_SESSION['valuearray'])) {
            $this->errorarray = $_SESSION['errorarray'];
            $this->valuearray = $_SESSION['valuearray'];
            $this->errorcount = count($this->errorarray);

            unset($_SESSION['errorarray']);
            unset($_SESSION['valuearray']);
        } else {
            $this->errorcount = 0;
        }
    }

    public function addError($field, $error)
    {
        $this->errorarray[$field] = $error;
        $this->errorcount = count($this->errorarray);
    }

    public function getError($field)
    {
        if (array_key_exists($field, $this->errorarray)) {
            return $this->errorarray[$field];
        } else {
            return "";
        }
    }

    public function getValue($field)
    {
        if (array_key_exists($field, $this->valuearray)) {
            return $this->valuearray[$field];
        } else {
            return "";
        }
    }

    public function getDiff($field, $cookie)
    {
        if (array_key_exists($field, $this->valuearray) && $this->valuearray[$field] != $cookie) {
            return $this->valuearray[$field];
        } else {
            return $cookie;
        }
    }

    public function getRadio($field, $value)
    {
        if (array_key_exists($field, $this->valuearray) && $this->valuearray[$field] == $value) {
            return "checked";
        } else {
            return "";
        }
    }

    public function returnErrors()
    {
        return $this->errorcount;
    }

    public function getErrors()
    {
        return $this->errorarray;
    }
}
