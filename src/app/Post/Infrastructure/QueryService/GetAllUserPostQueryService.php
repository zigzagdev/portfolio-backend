<?php

namespace App\Post\Infrastructure\QueryService;

use App\Models\Post;
use App\Post\Application\QueryServiceInterface\GetAllUserPostQueryServiceInterface;
use App\Post\Domain\EntityFactory\PostFromModelEntityFactory;
use App\Common\Application\Dto\Pagination as PaginationDto;

class GetAllUserPostQueryService implements GetAllUserPostQueryServiceInterface
{
    public function __construct(
        private readonly Post $post
    ) {}

    public function getAllUserPosts(
        int $userId,
        int $perPage,
        int $currentPage,
    ): PaginationDto
    {
        $userPosts = $this->post
            ->where('user_id', $userId)
            ->orderBy('created_at', 'desc')
            ->paginate(
                $perPage,
                ['*'],
                'page',
                $currentPage
            );

        return $this->paginationDto($userPosts->toArray());
    }

    private function paginationDto(
        array $data
    ): PaginationDto
    {
        $dtos = array_map(
            fn($item) => PostFromModelEntityFactory::build($item),
            $data['data']
        );

        return new PaginationDto(
            $dtos,
            currentPage: $data['current_page'] ?? null,
            from: $data['from'] ?? null,
            to: $data['to'] ?? null,
            perPage: $data['per_page'] ?? null,
            path: $data['path'] ?? null,
            lastPage: $data['last_page'] ?? null,
            total: $data['total'] ?? null,
            firstPageUrl: $data['first_page_url'] ?? null,
            lastPageUrl: $data['last_page_url'] ?? null,
            nextPageUrl: $data['next_page_url'] ?? null,
            prevPageUrl: $data['prev_page_url'] ?? null,
            links: $data['links'] ?? null
        );
    }
}