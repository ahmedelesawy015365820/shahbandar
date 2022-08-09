<?php

namespace App\Http\Controllers\Api\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\Department;
use App\Models\Employee;
use App\Models\Income;
use App\Models\Job;
use App\Models\User;
use App\Traits\Message;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class EmployeeController extends Controller
{
    use Message;

    public function salesEmployee(){

        $employees = Employee::with('user')->whereRelation('user','status',1)->whereHas('job',function ($q){
           $q->where('Allow_adding_to_sales_team',1);
        })->whereDoesntHave('target')->get();

        return $this->sendResponse(['employees' => $employees], 'Data exited successfully');
    }

    /**
     * change Password
     */

    public function changePassword(Request $request, $id)
    {
        DB::beginTransaction();

        try {
            // Validator request
            $v = Validator::make($request->all(), [
                'password' => 'required|string|min:8|confirmed',
            ]);

            if ($v->fails()) {
                return $this->sendError('There is an error in the data', $v->errors());
            }

            $employee = Employee::find($id)->user->update(["password" => $request->password]);

            DB::commit();
            return $this->sendResponse([], 'Data exited successfully');

        } catch (\Exception $e) {
            DB::rollBack();
            return $this->sendError('An error occurred in the system');
        }


    }

    /**
     * activation Income
     */
    public function activationEmployee($id)
    {
        $employee = Employee::find($id)->user;

        if ($employee->status == 1) {
            $employee->update([
                "status" => 0
            ]);
        } else {
            $employee->update([
                "status" => 1
            ]);
        }
        return $this->sendResponse([], 'Data exited successfully');
    }

    /**
     * get role
     */
    public function role()
    {
        $roles = DB::table('roles')->where('name', '!=', 'SuperAdmin')->get();
        return $this->sendResponse(['roles' => $roles], 'Data exited successfully');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $employees = Employee::with('user:id,name,email,phone,status', 'job', 'department')
            ->where(function ($q) use($request){
            $q->when($request->search,function ($q) use($request){
                return $q->OrWhere('hiring_date','like','%'.$request->search.'%')
                    ->orWhereRelation('user','email','like','%'.$request->search.'%')
                    ->orWhereRelation('user','name','like','%'.$request->search.'%')
                    ->orWhereRelation('user','phone','like','%'.$request->search.'%')
                    ->orWhereRelation('department.translations','name','like','%'.$request->search.'%')
                    ->orWhereRelation('job.translations','name','like','%'.$request->search.'%');
            });

        })->latest()->paginate(5);

        return $this->sendResponse(['employees' => $employees], 'Data exited successfully');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        DB::beginTransaction();

        try {

            // Validator request
            $v = Validator::make($request->all(), [
                'bank_name' => 'required',
                'bank_address' => 'required',
                'bank_iban' => 'required',
                'bank_swift' => 'present',
                'name' => 'required|string',
                'email' => 'required|string|email|unique:users',
                'password' => 'required|string|min:8',
                'confirmtion' => 'required|same:password',
                'department_id' => 'required|integer|exists:departments,id',
                'job_id' => 'required|integer|exists:jobs,id',
                'phone' => 'required|string|unique:users,phone',
                'role_name' => 'required',
                'address' => 'required|string|min:3',
                'National_ID' => 'required|string|min:8|unique:employees,National_ID',
                'birth_date' => 'required|date',
                'hiring_date' => 'required|date',
                'salary' => 'required',
                'file' => 'image|mimes:jpeg,jpg,png,webp|max:5048',
            ]);

            if ($v->fails()) {
                return $this->sendError('There is an error in the data', $v->errors());
            }

            // start create user
            $user = User::create([
                "name" => $request->name,
                "email" => $request->email,
                "password" => $request->password,
                "auth_id" => 1,
                'role_name' => [$request->role_name],
                "status" => 1,
                'phone' => $request->phone
            ]);
            $user->assignRole($request->input('role_name'));

            $user->employee()->create([
                'department_id' => $request->department_id,
                'job_id' => $request->job_id,
                'address' => $request->address,
                'National_ID' => $request->National_ID,
                'birth_date' => $request->birth_date,
                'hiring_date' => $request->hiring_date,
                'salary' => $request->salary,
            ]);
            $user->banks()->create([
                'name' => $request->bank_name,
                'address' => $request->bank_address,
                'iban' => $request->bank_iban,
                'swift' => $request->bank_swift
            ]);

            $file_size = $request->file->getSize();
            $file_type = $request->file->getMimeType();
            $image = time() . '.' . $request->file->getClientOriginalName();

            // picture move
            $request->file->storeAs($user->id, $image, 'employee');

            $user->media()->create([
                'file_name' => $image,
                'file_size' => $file_size,
                'file_type' => $file_type,
                'file_sort' => 1
            ]);

            DB::commit();
            return $this->sendResponse([], 'Data exited successfully');

        } catch (\Exception $e) {
            DB::rollBack();
            return $this->sendError('An error occurred in the system');
        }

    }

    public function edit($id)
    {
        try {

            $employee = Employee::with('user:id,name,email,phone,status,role_name')->find($id)->makeVisible('translations');
            $media = $employee->user->media->file_name;
            $employee['bank'] = $employee->user->banks[0];
            $department = Department::where('active', 1)->get();
            $job = Job::where('active', 1)->get();
            $roles = DB::table('roles')->where('name', '!=', 'SuperAdmin')->get();

            return $this->sendResponse(['employee' => $employee, 'departments' => $department, 'jobs' => $job, 'roles' => $roles, 'media' => $media], 'Data exited successfully');

        } catch (\Exception $e) {

            return $this->sendError('An error occurred in the system');

        }
    }


    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Employee $employee)
    {
        try {

            DB::beginTransaction();

            // Validator request
            $v = Validator::make($request->all(), [
                'bank_name' => 'required',
                'bank_address' => 'required',
                'bank_iban' => 'required',
                'bank_swift' => 'present',
                'name' => 'required|string',
                'email' => 'required|string|email|unique:users,email' . ($employee->user_id ? ",$employee->user_id" : ''),
                'department_id' => 'required|integer|exists:departments,id',
                'job_id' => 'required|integer|exists:jobs,id',
                'phone' => 'required|string|unique:users,phone' . ($employee->user_id ? ",$employee->user_id" : ''),
                'role_name' => 'required',
                'address' => 'required|string|min:3',
                'National_ID' => 'required|string|min:8|unique:employees,National_ID' . ($employee->id ? ",$employee->id" : ''),
                'birth_date' => 'required|date',
                'hiring_date' => 'required|date',
                'salary' => 'required',
                'file' => 'nullable' . ($request->hasFile('file') ? '|mimes:jpeg,jpg,png,webp|max:5048' : ''),
            ]);

            if ($v->fails()) {
                return $this->sendError('There is an error in the data', $v->errors());
            }

            $employee->user()->update([
                "name" => $request->name,
                "email" => $request->email,
                'role_name' => [$request->role_name],
                'phone' => $request->phone
            ]);

            $employee->user->assignRole($request->input('role_name'));

            $employee->update([
                'department_id' => $request->department_id,
                'job_id' => $request->job_id,
                'address' => $request->address,
                'National_ID' => $request->National_ID,
                'birth_date' => $request->birth_date,
                'hiring_date' => $request->hiring_date,
                'salary' => $request->salary,
            ]);

            $employee->user->banks->first()->update([
                'name' => $request->bank_name,
                'address' => $request->bank_address,
                'iban' => $request->bank_iban,
                'swift' => $request->bank_swift
            ]);

            if ($request->hasFile('file')) {
                if (File::exists('admin/img/employee/' . $employee->user->id . '/' . $employee->user->media->file_name)) {
                    unlink('admin/img/employee/' . $employee->user->id . '/' . $employee->user->media->file_name);
                }
                $employee->user->media->delete();
                $file_size = $request->file->getSize();
                $file_type = $request->file->getMimeType();
                $image = time() . '.' . $request->file->getClientOriginalName();

                // picture move
                $request->file->storeAs($employee->user->id, $image, 'employee');

                $employee->user->media()->create([
                    'file_name' => $image,
                    'file_size' => $file_size,
                    'file_type' => $file_type,
                    'file_sort' => 1
                ]);
            }

            DB::commit();

            return $this->sendResponse([], 'Data exited successfully');
        } catch (\Exception $e) {

            DB::rollBack();
            return $this->sendError('An error occurred in the system');
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Income $income)
    {
        if (count($income->incomeAndExpense) > 0) {
            return $this->sendError('can not delete');
        }

        $income->delete();

        return $this->sendResponse([], 'Data exited successfully');
    }
}
