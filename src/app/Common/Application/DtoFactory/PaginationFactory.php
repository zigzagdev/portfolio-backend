<?php

namespace App\Common\Application\DtoFactory;

use App\Common\Application\Dto\Pagination;
use Illuminate\Support\Arr;

class PaginationFactory
{
    public static function build(
        array $data,
        array $pagination
    ): Pagination
    {
        return new Pagination(
            $data,
            self::getValue($pagination, 'current_page'),
            self::getValue($pagination, 'from'),
            self::getValue($pagination, 'to'),
            self::getValue($pagination, 'per_page'),
            self::getValue($pagination, 'path'),
            self::getValue($pagination, 'last_page'),
            self::getValue($pagination, 'total'),
            self::getValue($pagination, 'first_page_url'),
            self::getValue($pagination, 'last_page_url'),
            self::getValue($pagination, 'next_page_url'),
            self::getValue($pagination, 'prev_page_url'),
            self::getValue($pagination, 'links')
        );
    }

    private static function getValue(array $array, string $key): mixed
    {
        return Arr::get($array, $key, null);
    }
}