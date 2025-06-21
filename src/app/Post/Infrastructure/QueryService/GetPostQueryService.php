<?php

namespace App\Post\Infrastructure\QueryService;

use App\Common\Domain\ValueObject\PostId;
use App\Common\Domain\ValueObject\UserId;
use App\Models\Post;
use App\Post\Application\QueryServiceInterface\GetPostQueryServiceInterface;
use App\Post\Domain\Entity\PostEntity;
use App\Post\Domain\EntityFactory\PostFromModelEntityFactory;
use App\Common\Application\Dto\Pagination as PaginationDto;
use App\Models\User;
use ErrorException;

class GetPostQueryService implements GetPostQueryServiceInterface
{
    public function __construct(
        private readonly Post $post,
        private readonly User $user
    ) {}

    public function getAllUserPosts(
        int $userId,
        int $perPage,
        int $currentPage,
    ): PaginationDto
    {
        if (!User::where('id', $userId)->exists()) {
            throw new ErrorException($userId);
        }

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

    public function getEachUserPost(
        UserId $userId,
        PostId $postId
    ): ?PostEntity
    {
        $post = $this->post
            ->where('user_id', $userId->getValue())
            ->where('id', $postId->getValue())
            ->first();

        if (!$post) {
            return null;
        }

        return PostFromModelEntityFactory::build($post->toArray());
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