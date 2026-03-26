<?php

if (!function_exists('first_upper_letter')) {
    function first_upper_letter($string)
    {
        return mb_strtoupper(mb_substr($string, 0, 1, 'UTF-8'), 'UTF-8');
    }
}
