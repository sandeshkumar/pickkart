<?php

namespace App\Traits;

trait SearchesProducts
{
    protected function buildBooleanQuery(string $query): string
    {
        $words = preg_split('/\s+/', trim($query));
        $terms = array_map(function ($word) {
            $clean = preg_replace('/[+\-><()~*\"@]/', '', $word);
            return $clean ? "+{$clean}*" : '';
        }, $words);

        return implode(' ', array_filter($terms));
    }
}
