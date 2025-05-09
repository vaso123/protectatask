<?php

$params = [
    ['Param_nev' => '', 'Param_ertek' => '']
];
$type = 'some type';
$namePlateElements = [];

//...

foreach ($params as $param) {
    $paramName = $param['Param_nev'];
    $paramValue = $param['Param_ertek'];

    $namePlateElements[$type] ??= [];
    $namePlateElements[$type][$paramName] ??= [];

    if (!in_array($paramValue, $namePlateElements[$type][$paramName])) {
        $namePlateElements[$type][$paramName][] = $paramValue;
    }

}