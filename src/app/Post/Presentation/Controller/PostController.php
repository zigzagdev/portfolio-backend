<?php

namespace App\Post\Presentation\Controller;

use App\Http\Controllers\Controller;
use App\Post\Application\UseCase\CreateUseCase;
use App\Post\Presentation\ViewModel\CreatePostViewModel;
use App\Post\Application\UseCommand\CreatePostUseCommand;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Throwable;

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
}
