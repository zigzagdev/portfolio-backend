<?php

namespace App\Post\Presentation\ViewModel;

use App\Post\Application\Dto\GetAllUserPostDtoCollection;

class GetPostsViewModelCollection
{
    private array $viewModels;

    public function __construct(GetAllUserPostDtoCollection $dtoCollection)
    {
        $this->viewModels = array_map(
            fn($dto) => GetPostViewModel::build($dto),
            $dtoCollection->getPosts()
        );
    }

    public function toArray(): array
    {
        return array_map(
            fn(GetPostViewModel $vm) => $vm->toArray(),
            $this->viewModels
        );
    }
}