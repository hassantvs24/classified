<?php

if (!function_exists('result')) {
    function result($data)
    {
        return ['message' => 'Data Successfully Find', 'data' => $data];
    }
}

if (!function_exists('db_range')) {
    function db_range($date_range)
    {
        $sp_date = explode(" - ", $date_range);
        $dates[] = date('Y-m-d 00:00:00', strtotime(str_replace("/","-", $sp_date[0])));
        $dates[] = date('Y-m-d 23:59:59', strtotime(str_replace("/","-", $sp_date[1])));
        return $dates;
    }
}
