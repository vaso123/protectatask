<?php

$points = [
    ['x' => 7, 'y' => 4, 'id' => 'id1'],
    ['x' => 1, 'y' => 2, 'id' => 'id2'],
    ['x' => 2, 'y' => 1, 'id' => 'id3'],
    ['x' => 3, 'y' => 9, 'id' => 'id4'],
    ['x' => 4, 'y' => 5, 'id' => 'id1'],
    ['x' => 1, 'y' => 8, 'id' => 'id2'],
    ['x' => 2, 'y' => 3, 'id' => 'id3'],
];


//Bacuse we use a x = 0 and y = 0 as an origo, it will be always a triangle, what has a 90degree angle
//so I can use Pithagoras formula, a** + b** = c** so to deremine c I need to sqrt c**
$allDistances = [];
foreach ($points as &$point) {
    $distance = sqrt($point['x'] ** 2 + $point['y'] ** 2);
    $point['distance'] = $distance;
}

//I need to sort the points by distance ascending order.  Of course the first point will be in, and I
usort($points, fn($a, $b) => $a['distance'] <=> $b['distance']);

echo "The biggest radius is: " . CalculateBiggestRadius($points) . "\n";

var_dump($points);

function CalculateBiggestRadius($points): float
{
    $uniqueIds = [];
    $biggestRadius = 0.0;
    foreach ($points as $point) {
        if (in_array($point['id'], $uniqueIds)) {
            break;
        }
        $uniqueIds[] = $point['id'];
        $biggestRadius = $point['distance'];
    }
    return floatval($biggestRadius);
}
