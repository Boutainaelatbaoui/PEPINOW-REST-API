<?php

namespace App\Http\Controllers;

use Spatie\Permission\Models\Role;
use Illuminate\Http\Request;
use App\Models\User;
use GuzzleHttp\Psr7\Response;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    public function login(Request $request){
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'password' => 'required|min:8',
        ]);
        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }
        if (! $token = auth()->attempt($validator->validated())) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }
        return $this->createNewToken($token);
    }

    public function register(Request $request) {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|between:2,100',
            'email' => 'required|string|email|max:100|unique:users',
            'password' => 'required|string|confirmed|min:8',
        ]);
        if($validator->fails()){
            return response()->json($validator->errors(), 400);
        }

        $user = User::create([
            'name'=>$request->name,
            'email'=>$request->email,
            'password'=>Hash::make($request->password),
        ]);
        return response()->json([
            'message' => 'User successfully registered',
            'user' => $user
        ], 201);
    }

    public function logout() {
        auth()->logout();
        return response()->json(['message' => 'User successfully signed out'], 200);
    }

    public function userProfile() {
        return response()->json(auth()->user(), 200);
    }

    public function refresh() {
        return $this->createNewToken(auth()->refresh(), 200);
    }

    protected function createNewToken($token){
        return response()->json([
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => 3600,
            'user' => auth()->user()
        ]);
    }

    function resetPassword(Request $request){
        $request->validate([
            'email' => 'required|email',
        ]);

        $user = User::where('email', $request->email)->first();

        if (!$user) {
            return response()->json(['message' => 'User not found'], 404);
        } else {
            $validator = Validator::make($request->all(), [
                'new_password' => 'required|min:8|confirmed',
            ]);
    
            if ($validator->fails()) {
                return response()->json([
                    'error'=>$validator->errors()
                ]);
            }
            $user->update([
                'password'=>Hash::make($request->new_password)
            ]);
            return response()->json([
                'message'=>'password reseted successfully'
            ], 201);
        }
    }

    public function update(Request $request)
    {
        $user = auth()->user();

        $request->validate([
            'name' => 'required|string',
            'email' => 'required|email|unique:users,email,'.$user->id,
            'password' => 'sometimes|required|min:6',
        ]);

        $user->name = $request->input('name');
        $user->email = $request->input('email');

        if ($request->has('password')) {
            $user->password = Hash::make($request->input('password'));
        }

        $user->save();

        return response()->json(['message' => 'User profile updated successfully',
        'user' => $user,
        ], 201);
    }

    public function index()
    {
        $users = User::with('roles', 'permissions')->get();
        return response()->json($users);
    }

    public function assignRolesAndPermissions(Request $request, $id)
    {
        $user = User::findOrFail($id);

        $roles = $request->input('roles');
        if ($roles) {
            $user->syncRoles($roles);
        }

        $permissions = $request->input('permissions');
        if ($permissions) {
            $user->syncPermissions($permissions);
        }

        return response()->json(['message' => 'Roles and permissions assigned successfully']);
    }

}
