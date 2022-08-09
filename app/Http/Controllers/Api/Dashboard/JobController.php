<?php

namespace App\Http\Controllers\Api\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\Job;
use App\Traits\Message;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class JobController extends Controller
{
    use Message;

    /**
     * get active Job
     */
    public function activeJob()
    {
        $jobs = Job::where('active', 1)->get();
        return $this->sendResponse(['jobs' => $jobs], 'Data exited successfully');
    }


    /**
     * activation Job
     */
    public function activationJob($id)
    {
        $job = Job::find($id);

        if ($job->active == 1)
        {
            $job->update([
                "active" => 0
            ]);
        }else{
            $job->update([
                "active" => 1
            ]);
        }
        return $this->sendResponse([], 'Data exited successfully');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $jobs = Job::when($request->search, function ($q) use ($request) {
            return $q->whereRelation('translations', 'name', 'like', '%' . $request->search . '%');
        })->latest()->paginate(5);

        return $this->sendResponse(['jobs' => $jobs], 'Data exited successfully');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        try {
            DB::beginTransaction();

            // Validator request
            $v = Validator::make($request->all(), [
                'ar.name' => ['required', Rule::unique('job_translations', 'name')],
                'en.name' => ['required', Rule::unique('job_translations', 'name')],
            ]);

            if ($v->fails()) {
                return $this->sendError('There is an error in the data', $v->errors());
            }
            $data = $request->only(['ar','en','Allow_adding_to_sales_team']);

            Job::create($data);

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

            $job = Job::find($id)->makeVisible('translations');

            return $this->sendResponse(['job' => $job], 'Data exited successfully');

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
    public function update(Request $request, Job $job)
    {
        try {

            DB::beginTransaction();

            // Validator request
            $v = Validator::make($request->all(), [
                'ar.name' => ['required', Rule::unique('job_translations', 'name')->whereNot('job_id', $job->id)],
                'en.name' => ['required', Rule::unique('job_translations', 'name')->whereNot('job_id', $job->id)],
            ]);

            if ($v->fails()) {
                return $this->sendError('There is an error in the data', $v->errors());
            }

            $data = $request->only(['ar','en','Allow_adding_to_sales_team']);

            $job->update($data);

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
    public function destroy(Job $job)
    {
        if (count($job->employees) == 0)
        {
            $job->delete();
            return $this->sendResponse([],'Deleted successfully');
        }else{
            return $this->sendError('ID is not exist');
        }
    }
}
