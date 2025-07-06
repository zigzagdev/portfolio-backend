<?php

namespace App\Post\Presentation\Controller;

use App\Http\Controllers\Controller;
use App\Post\Application\UseCase\CreateUseCase;
use App\Post\Application\UseCase\GetOthersAllPostsUseCase;
use App\Post\Application\UseCase\GetUserEachPostUseCase;
use App\Post\Presentation\ViewModel\CreatePostViewModel;
use App\Post\Application\UseCommand\CreatePostUseCommand;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Throwable;
use App\Post\Application\UseCase\GetAllUserPostUseCase;
use App\Common\Presentation\ViewModelFactory\PaginationFactory as PaginationViewModelFactory;
use App\Post\Application\Dto\GetUserEachPostDto;
use App\Post\Presentation\ViewModel\GetAllUserPostViewModel;

class PostController extends Controller
{
    public function create(
        Request $request,
        int $user_id,
        CreateUseCase $useCase
    ): JsonResponse{
        DB::connection('mysql')->beginTransaction();
        try {

            $command = CreatePostUseCommand::build(
                array_merge(
                    $request->toArray(),
                    ['user_id' => $user_id]
                )
            );

            $dto = $useCase->handle($command);
            $viewModel = new CreatePostViewModel($dto);

            DB::connection('mysql')->commit();

            return response()->json([
                'status' => 'success',
                'data' => $viewModel->toArray(),
            ], 201);
        } catch (Throwable $e) {
            DB::connection('mysql')->rollBack();
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage(),
            ], 500);
        }
    }

    public function getAllPosts(
        int $userId,
        Request $request,
        GetAllUserPostUseCase $useCase
    ): JsonResponse
    {
        try {
            $perPage = $request->query('per_page', 15);
            $currentPage = $request->query('current_page', 1);

            $dto = $useCase->handle(
                userId: $userId,
                perPage: $perPage,
                currentPage: $currentPage
            );

            $viewModels = array_map(
                fn(GetUserEachPostDto $dto) => GetAllUserPostViewModel::build($dto)->toArray(),
                $dto->getData()
            );

            $paginationViewModel = PaginationViewModelFactory::build(
                $dto,
                $viewModels
            )->toArray();

            return response()->json([
                'status' => 'success',
                'data' => $paginationViewModel['data'],
                'meta' => $paginationViewModel['meta'],
            ]);

        } catch (Throwable $e) {
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage(),
            ], 500);
        }
    }

    public function getEachPost(
        int $userId,
        int $postId,
        GetUserEachPostUseCase $useCase
    ): JsonResponse
    {
        try {
            $dto = $useCase->handle(
                userId: $userId,
                postId: $postId
            );

            $viewModelArray = GetAllUserPostViewModel::build($dto)->toArray();

            return response()->json([
                'status' => 'success',
                'data' => $viewModelArray,
            ], 200);

        } catch (Throwable $e) {
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage(),
            ], 500);
        }
    }

    public function edit(
        Request $request,
        int $user_id,
        int $post_id,
        EditUseCase $useCase
    ): JsonResponse {
        DB::connection('mysql')->beginTransaction();
        try {

            $command = EditPostUseCommand::build(
                array_merge(
                    $request->toArray(),
                    ['userId' => $user_id],
                    ['id' => $post_id]
                )
            );

            $dto = $useCase->handle($command);
            $viewModel = new EditPostViewModel($dto);

            DB::connection('mysql')->commit();

            return response()->json([
                'status' => 'success',
                'data' => $viewModel->toArray(),
            ], 200);
        } catch (Throwable $e) {
            DB::connection('mysql')->rollBack();
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage(),
            ], 500);
        }
    }

    public function getOthersPosts(
        Request $request,
        int $userId,
        GetOthersAllPostsUseCase $useCase
    ): JsonResponse
    {
        try {
            $userId = $request->route('userId', $userId);
            $perPage = $request->get('per_page', 15);
            $currentPage = $request->get('current_page', 1);

            $data = $useCase->handle(
                userId: $userId,
                perPage: $perPage,
                currentPage: $currentPage
            );

            $viewModels = array_map(
                fn(GetUserEachPostDto $dto) => GetAllUserPostViewModel::build($dto)->toArray(),
                $data->getData()
            );

            $paginationViewModel = PaginationViewModelFactory::build(
                $data,
                $viewModels
            )->toArray();

            return response()->json([
                'status' => 'success',
                'data' => $paginationViewModel['data'],
                'meta' => $paginationViewModel['meta'],
            ], 200);

        } catch (Throwable $e) {
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage(),
            ], 500);
        }
    }
}
