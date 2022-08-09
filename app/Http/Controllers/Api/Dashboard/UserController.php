<?php

namespace App\Http\Controllers\Api\Dashboard;

use App\Http\Controllers\Controller;
use App\Http\Resources\UserResource;
use Illuminate\Http\Request;
use App\Models\User;
use App\Traits\Message;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{

    use Message;

    public function index()
    {

        // get user
        $user = User::paginate(10);

        return $this->sendResponse(UserResource::collection($user),'Data exited successfully');

    }//**********end index************/

    public function store(Request $request)
    {
        DB::beginTransaction();
        try {

            // Validator request
            $v = Validator::make($request->all(), [
                'name' => 'required|string',
                'email' => 'required|string|email|unique:users',
                'role_name' => 'required',
                "status" => 'in:0,1',
                'password' => 'required|string',
                'confirmtion' => 'required|same:password',

            ]);

            if($v->fails()) {
                return $this->sendError('There is an error in the data',$v->errors());
            }

            // start create user
            $user =  User::create([
                "name" => $request->name,
                "email" => $request->email,
                "password" => $request->password,
                "auth_id" => 1,
                'role_name'=> $request->role_name,
                "status" => ($request->status == 1? 1:0)
            ]);

            $user->assignRole($request->input('role_name'));

            DB::commit();
            if($user){
                return  $this->sendResponse([],'Data exited successfully');
            }else{
                return $this->sendError('An error occurred in the system');
            }

        }
        catch (\Exception $e){

            DB::rollBack();
            return $this->sendError('An error occurred in the system');

        }
    }//**********end store************/


    public function update(Request $request, $id)
    {
        DB::beginTransaction();
        try {
            // find user
            $user = User::find($id);

            if ($user) {

                // Validator request
                $v = Validator::make($request->all(), [
                    'name' => 'required|string',
                    'email' => "required|string|email|unique:users,email,$user->id",
                    'role_name' => 'required',
                    "status" => 'in:0,1',
                    'password' => 'min:8',
                    'confirmtion' => 'same:password|required_with:password',

                ]);

                if ($v->fails()) {
                    return $this->sendError('There is an error in the data', $v->errors());
                }

                // start update user
                $data = [];
                $data['name'] = $request->name;
                $data['email'] = $request->email;
                $data['auth_id'] = 1;
                $data['role_name'] = $request->role_name;
                if ($request->password != '') {
                    $data['password'] = $request->password;
                }
                $data['status'] = $request->status == 1 ? 1 : 0;

                $user->update($data);

                DB::table('model_has_roles')->where('model_id',$user->id)->delete();

                $user->assignRole($request->input('role_name'));


                DB::commit();
                if ($user) {
                    return $this->sendResponse([], 'Edited successfully');
                } else {
                    return $this->sendError('There is an error in the data');
                }

            }else{
                return $this->sendError('An error occurred in the system');
            }
        }
        catch (\Exception $e){

            DB::rollBack();
            return $this->sendError('An error occurred in the system');

        }

    }//**********end update************//


    public function destroy($id)
    {

        try{

            $user = User::find($id);

            if($user){
                $user->delete();
                return $this->sendResponse([],'Deleted successfully');
            }

            return $this->sendError('ID is not exist');

        }catch (\Exception $e ){
            return $this->sendError('An error occurred in the system');
        }

    }//**********end destroy************/
}
