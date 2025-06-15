<?php

namespace App\Common\Presentation\ViewModelFactory;

use App\Common\Application\Dto\Pagination as PaginationDto;
use App\Common\Presentation\ViewModel\Pagination;

class PaginationFactory
{
    public static function build(
        PaginationDto $dto,
        array $data
    ): Pagination
    {
        return new Pagination(
            $data,
            $dto->getCurrentPage(),
            $dto->getFrom(),
            $dto->getTo(),
            $dto->getPerPage(),
            $dto->getPath(),
            $dto->getLastPage(),
            $dto->getTotal(),
            $dto->getFirstPageUrl(),
            $dto->getLastPageUrl(),
            $dto->getNextPageUrl(),
            $dto->getPrevPageUrl(),
            $dto->getLinks()
        );
    }
}