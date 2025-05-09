<?php

class Something
{

    function processNamePalateElements($namePlateElements)
    {
        foreach ($namePlateElements as $type => $parameters) {
            switch ($type) {
                case "InUn":
                    $this->addInUnits($parameters, $type, $namePlateElements);
                    break;
            }
        }
    }

    private function addInUnits(array $parameters, $type, &$namePlateElements)
    {
        foreach ($parameters as $paramType => $values) {
            foreach ($values as $key => $value) {
                $unitType = ($paramType === 'Un') ? 'V' : 'A';
                $namePlateElements[$type][$paramType][$key] = self::addUnit($value, $unitType);
            }
        }
    }

    public static function addUnit($value, $paramType)
    {
        //do something...
        return 'some_key';
    }
}
