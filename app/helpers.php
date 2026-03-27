<?php

if (!function_exists('first_upper_letter')) {
    function first_upper_letter($string)
    {
        return mb_strtoupper(mb_substr($string, 0, 1, 'UTF-8'), 'UTF-8');
    }
}

if (!function_exists('vacancy_status_count')) {
    function vacancy_status_count($vacancies, array $statuses = [])
    {
        if (count($statuses) > 0) {
            $vacancies = $vacancies->whereIn('status', $statuses);
        }
        return count($vacancies);
    }
}
