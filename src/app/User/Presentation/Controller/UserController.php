<?php

namespace App\User\Presentation\Controller;

use App\Http\Controllers\Controller;
use App\User\Application\Factory\RegisterUserCommandFactory;
use App\User\Application\UseCase\ShowUserUseCase;
use App\User\Presentation\ViewModel\Factory\RegisterUserViewModelFactory;
use App\User\Presentation\ViewModel\ShowUserViewModel;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use App\User\Application\UseCase\RegisterUserUsecase;
use Illuminate\Support\Facades\DB;
use Exception;

class UserController extends Controller
{
    public function createUser(
        Request $request,
        RegisterUserUsecase $useCase
    ): JsonResponse
    {
        DB::connection('mysql')->beginTransaction();

        try {
            $command = RegisterUserCommandFactory::build($request);

            $dto = $useCase->handle($command);

            DB::connection('mysql')->commit();

            return response()->json([
                'status' => 'success',
                'data' => RegisterUserViewModelFactory::build($dto),
            ], 201);
        } catch (Exception $e) {
            DB::connection('mysql')->rollBack();

            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage(),
            ], 500);
        }
    }

    public function showUser(
        int $id,
        ShowUserUseCase $useCase
    ): JsonResponse
    {
        $dto = $useCase->handle($id);
        $viewModel = ShowUserViewModel::buildFromDto($dto);

        return response()->json([
            'status' => 'success',
            'data' => $viewModel->toArray(),
        ], 200);
    }
}