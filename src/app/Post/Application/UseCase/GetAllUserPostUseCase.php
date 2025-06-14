<?php

namespace App\Post\Application\UseCase;


use App\Common\Application\Dto\Pagination;
use App\Post\Application\QueryServiceInterface\GetAllUserPostQueryServiceInterface;
use App\Post\Application\Dto\GetAllUserPostDto;
use App\Post\Domain\Entity\PostEntity;

class GetAllUserPostUseCase
{
    public function __construct(
        private readonly GetAllUserPostQueryServiceInterface $queryService
    ) {}

    public function handle(
        int $userId,
        int $perPage,
        int $currentPage
    ): Pagination
    {
        $pagination = $this->queryService->getAllUserPosts(
            $userId,
            $perPage,
            $currentPage
        );

        $dtoList = array_map(
            fn(PostEntity $e) => new GetAllUserPostDto(
                id: $e->getId()->getValue(),
                userId: $e->getUserId()->getValue(),
                content: $e->getContent(),
                mediaPath: $e->getMediaPath() ?? null,
                visibility: $e->getPostVisibility()->getStringValue()
            ),
            $pagination->getData()
        );

        return new Pagination(
            data: $dtoList,
            currentPage: $pagination->getCurrentPage(),
            from: $pagination->getFrom(),
            to: $pagination->getTo(),
            perPage: $pagination->getPerPage(),
            path: $pagination->getPath(),
            lastPage: $pagination->getLastPage(),
            total: $pagination->getTotal(),
            firstPageUrl: $pagination->getFirstPageUrl(),
            lastPageUrl: $pagination->getLastPageUrl(),
            nextPageUrl: $pagination->getNextPageUrl(),
            prevPageUrl: $pagination->getPrevPageUrl(),
            links: $pagination->getLinks()
        );
    }
}