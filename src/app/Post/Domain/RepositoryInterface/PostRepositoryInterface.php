<?php

namespace App\Post\Domain\RepositoryInterface;

use App\Post\Domain\Entity\PostEntity;

interface  PostRepositoryInterface
{
    public function save(
        PostEntity $entity
    ): ?PostEntity;
}
