<?php

namespace App\Common\Application\Dto;

use Illuminate\Support\Arr;

class Pagination
{
    public function __construct(
        private array $data,
        private ?int $currentPage = null,
        private ?int $from = null,
        private ?int $to = null,
        private ?int $perPage = null,
        private ?string $path = null,
        private ?int $lastPage = null,
        private ?int $total = null,
        private ?string $firstPageUrl = null,
        private ?string $lastPageUrl = null,
        private ?string $nextPageUrl = null,
        private ?string $prevPageUrl = null,
        private ?array $links = null
    ) {}

    public function getData(): array
    {
        return $this->data;
    }

    public function getCurrentPage(): ?int
    {
        return $this->currentPage;
    }

    public function getFrom(): ?string
    {
        return $this->from;
    }

    public function getTo(): ?string
    {
        return $this->to;
    }

    public function getPerPage(): ?int
    {
        return $this->perPage;
    }

    public function getPath(): ?string
    {
        return $this->path;
    }

    public function getLastPage(): ?int
    {
        return $this->lastPage;
    }

    public function getTotal(): ?int
    {
        return $this->total;
    }

    public function getFirstPageUrl(): ?string
    {
        return $this->firstPageUrl;
    }

    public function getLastPageUrl(): ?string
    {
        return $this->lastPageUrl;
    }

    public function getNextPageUrl(): ?string
    {
        return $this->nextPageUrl;
    }

    public function getPrevPageUrl(): ?string
    {
        return $this->prevPageUrl;
    }

    public function getLinks(): ?array
    {
        return $this->links;
    }

    public function toArray(): array
    {
        $dataArray = Arr::map($this->data, function ($item) {
            if (is_array($item)) {
                return $item;
            }
            return $item;
        });

        return [
            'data' => $dataArray,
            'current_page' => $this->currentPage,
            'from' => $this->from,
            'to' => $this->to,
            'per_page' => $this->perPage,
            'path' => $this->path,
            'last_page' => $this->lastPage,
            'total' => $this->total,
            'first_page_url' => $this->firstPageUrl,
            'last_page_url' => $this->lastPageUrl,
            'next_page_url' => $this->nextPageUrl,
            'prev_page_url' => $this->prevPageUrl,
            'links' => $this->links
        ];
    }
}