<?php

use Carbon\Carbon;

function getDecimal($valor, $dec=2){
    return number_format($valor,$dec, ",", ".");
}

function getCurrency($valor, $currency="€"){
    return number_format($valor,2, ",", ".")." ".$currency;
}

function getFecha($value)
{

    if (is_null($value)) return null;

    return Carbon::parse($value)->format('d/m/Y');

}
