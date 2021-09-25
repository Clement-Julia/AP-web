<?php

function sortFunction( $a, $b ) {
    return strtotime($a) - strtotime($b);
}

function isValidDate($date, $format = 'Y-m-d'){
    $dt = DateTime::createFromFormat($format, $date);
    return $dt && $dt->format($format) === $date;
}