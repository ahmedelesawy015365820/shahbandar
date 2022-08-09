<?php

namespace App\Http\Controllers\Api\Dashboard;

use App\Http\Controllers\Controller;
use App\Http\Resources\PermissionResource;
use App\Http\Resources\UserResource;
use App\Traits\Message;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class AuthDashboardController extends Controller
{
    use Message;

    // login user & create token
    public function login(Request $request)
    {

        // Validator request
        $v = Validator::make($request->all(),[
            'email' => 'required|string|email',
            'password' => 'required|string',
            'remember_me' => 'boolean'
        ]);

        if($v->fails()) {
            return $this->sendError('Your Email/Password is wrong',$v->errors(),401);
        }

        //start access token
        $credentials = $request->only("email", "password");

        if ($token = Auth::guard('api')->attempt($credentials)) {

             $user = Auth::guard('api')->user();

            if($user->status == 1){

                return  $this->sendResponse($this->respondWithToken($token),'Data exited successfully');

            }else{
                return $this->sendError('Your Email/Password is wrong');
            }

        }else{

            return $this->sendError('Your Email/Password is wrong');
        }

    }//**********end login************/


    //logout
    public function logout(Request $request) {

        auth()->guard('api')->logout();

        return $this->sendResponse([],'User successfully signed out');
    }//**********end logout************/


    public function authorizeUser()
    {
        $user = auth()->guard('api')->check();

        if($user){
            return  $this->sendResponse(['success' => true],'Data exited successfully');
        }else{
            return $this->sendError(['error' => true]);
        }
    }


    // create token
    protected function respondWithToken($token)
    {

        return [
            'access_token' => $token,
            'token_type' => 'bearer',
            'user' => new UserResource(auth()->guard('api')->user()),
            'permission' =>  PermissionResource::collection((auth()->guard('api')->user()->getAllPermissions()))
        ];

    }//**********end respondWithToken************/
}
