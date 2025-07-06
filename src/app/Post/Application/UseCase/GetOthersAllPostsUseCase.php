<?php

namespace App\Post\Application\UseCase;

use App\Common\Application\Dto\Pagination as PaginationDto;
use App\Post\Application\Dto\GetAllUserPostDtoCollection;
use App\Post\Application\Dto\GetUserEachPostDto;
use App\Post\Application\QueryServiceInterface\GetPostQueryServiceInterface;

class GetOthersAllPostsUseCase
{
    public function __construct(
       private readonly  GetPostQueryServiceInterface $queryService
    ){}

    public function handle(
        int $userId,
        int $perPage,
        int $currentPage
    ): PaginationDto {

        $allPosts = $this->queryService->getOthersAllPosts(
            userId: $userId,
            perPage: $perPage,
            currentPage: $currentPage
        );

        $postDtos = new GetAllUserPostDtoCollection(
            array_map(
                fn($post) => GetUserEachPostDto::build($post->toArray()),
                $allPosts->getData()
            )
        );

        return new PaginationDto(
            data: $postDtos->getPosts(),
            total: $allPosts->getTotal(),
            perPage: $allPosts->getPerPage(),
            currentPage: $allPosts->getCurrentPage(),
            lastPage: $allPosts->getLastPage()
        );
    }
}