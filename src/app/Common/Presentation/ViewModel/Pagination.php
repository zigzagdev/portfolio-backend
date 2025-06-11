<?php

namespace App\Common\Presentation\ViewModel;

use App\Common\Application\Dto\Pagination as PaginationDto;

class Pagination
{
    public function __construct(
        private array $data,
        private int $currentPage,
        private int $from,
        private int $to,
        private int $perPage,
        private string $path,
        private int $lastPage,
        private int $total,
        private string $firstPageUrl,
        private string $lastPageUrl,
        private ?string $nextPageUrl = null,
        private ?string $prevPageUrl = null,
        private array $links = []
    ) {}

    public function toArray(): array
    {
        return array_merge(
            [
                'data' => $this->data
            ],
            [
                'currentPage' => $this->currentPage,
                'from' => $this->from,
                'to' => $this->to,
                'perPage' => $this->perPage,
                'path' => $this->path,
                'lastPage' => $this->lastPage,
                'total' => $this->total,
                'firstPageUrl' => $this->firstPageUrl,
                'lastPageUrl' => $this->lastPageUrl,
                'nextPageUrl' => $this->nextPageUrl,
                'prevPageUrl' => $this->prevPageUrl,
                'links' => $this->links
            ]
        );
    }
}