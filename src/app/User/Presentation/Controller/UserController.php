<?php

namespace App\User\Presentation\Controller;

use App\Http\Controllers\Controller;
use App\User\Application\Factory\RegisterUserCommandFactory;
use App\User\Application\UseCase\LoginUserUseCase;
use App\User\Application\UseCase\ShowUserUseCase;
use App\User\Presentation\ViewModel\Factory\RegisterUserViewModelFactory;
use App\User\Presentation\ViewModel\ShowUserViewModel;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use App\User\Application\UseCase\RegisterUserUsecase;
use App\User\Application\UseCase\UpdateUseCase;
use App\User\Application\UseCommand\UpdateUserCommand;
use App\User\Presentation\ViewModel\UpdateUserViewModel;
use App\User\Application\UseCase\RequestUserPasswordResetUseCase;
use Illuminate\Support\Facades\DB;
use App\User\Application\UseCase\LogoutUserUseCase;
use Exception;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use Throwable;
use function Symfony\Component\Translation\t;

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

    public function update(
        int $id,
        Request $request,
        UpdateUseCase $useCase
    ): JsonResponse
    {
        DB::connection('mysql')->beginTransaction();
        try {
            $command = UpdateUserCommand::build(
                $id,
                $request
            );

            $dto = $useCase->handle($command);

            DB::connection('mysql')->commit();

            return response()->json([
                'status' => 'success',
                'data' => UpdateUserViewModel::buildFromDto($dto)->toArray(),
            ], 200);
        } catch (Exception $e) {
            DB::connection('mysql')->rollBack();

            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage(),
            ], 500);
        }
    }

    public function login(
        Request $request,
        LoginUserUseCase $useCase
    ): JsonResponse
    {
        try {
            $requestEmail = $request->input('email');
            $requestPassword = $request->input('password');

            $dto = $useCase->handle(
                $requestEmail,
                $requestPassword
            );

            return response()->json([
                'status' => 'success',
                'data' => [
                    'token' => $dto->getToken(),
                    'user' => $dto->toArray()
                ],
            ], 200);

        } catch (Exception $e) {

            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage(),
            ], 500);
        }
    }

    public function logout(
        Request $request,
        LogoutUserUseCase $useCase
    ): JsonResponse
    {
        try {
            $token = $request->bearerToken();

            if (!$token) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Token not provided',
                ], 401);
            }

            // JWTの検証とuser_idの抽出（ここがControllerの責務）
            $decoded = JWT::decode($token, new Key(config('jwt.secret'), 'HS256'));

            if (empty($decoded->user_id)) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Invalid token payload',
                ], 401);
            }

            $useCase->handle($decoded->user_id);

            return response()->json([
                'status' => 'success',
                'message' => 'User logged out successfully',
            ], 200);
        } catch (Throwable $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Invalid or expired token',
            ], 401);
        }
    }

    public function passwordResetRequest(
        Request $request,
        RequestUserPasswordResetUseCase $useCase
    ): JsonResponse {
        DB::beginTransaction();

        try {
            $email = $request->input('email');

            if (empty($email)) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Email is required',
                ], 400);
            }

            $useCase->handle($email);

            DB::commit();

            return response()->json([
                'status' => 'success',
                'message' => 'Password reset link sent to your email.',
            ], 200);
        } catch (Exception $e) {
            DB::rollBack();

            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage(),
            ], 500);
        }
    }
}