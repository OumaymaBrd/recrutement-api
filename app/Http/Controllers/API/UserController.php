<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Repositories\UserRepositoryInterface;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{
    protected $userRepository;

    public function __construct(UserRepositoryInterface $userRepository)
    {
        $this->userRepository = $userRepository;
        $this->middleware('auth:sanctum');
    }

    public function getProfil()
    {
        return response()->json(request()->user());
    }

    public function updateProfil(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'sometimes|required|string|max:255',
            'email' => 'sometimes|required|string|email|max:255|unique:users,email,' . $request->user()->id,
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $user = $this->userRepository->update($request->user()->id, $request->all());
        return response()->json($user);
    }

    public function deleteUser($id)
    {
        $user = request()->user();
        
        if (!$user->isAdmin() && $user->id != $id) {
            return response()->json(['message' => 'Vous n\'êtes pas autorisé à supprimer cet utilisateur'], 403);
        }
        
        $this->userRepository->delete($id);
        return response()->json(['message' => 'Utilisateur supprimé avec succès']);
    }
}