<?php

namespace App\User\Presentation\Controller;

use App\Http\Controllers\Controller;
use App\User\Application\Factory\RegisterUserCommandFactory;
use App\User\Presentation\ViewModel\Factory\RegisterUserViewModelFactory;
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
        } finally {
            DB::connection('mysql')->commit();
        }
    }
}