<?php

if (!function_exists('url')) {

    function url(){
        $url = app(\Core\Libs\Url::class);

       return $url;
    }
}