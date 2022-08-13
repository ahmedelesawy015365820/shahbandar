<?php

namespace App\Http\Controllers\Api\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\MeasurementUnit;
use App\Traits\Message;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class MeasurementUnitController extends Controller
{
    use Message;

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $measures = MeasurementUnit::
            when($request->search, function ($q) use ($request) {
                return $q->where('name', 'like', '%' . $request->search . '%');

            })
            ->latest()->paginate(10);

        return $this->sendResponse(['measures' => $measures], 'Data exited successfully');
    }


    public function activationMeausre($id)
    {
        $measure = MeasurementUnit::find($id);

        if ($measure->status == 1)
        {
            $measure->update([
                "status" => 0
            ]);
        }else{
            $measure->update([
                "status" => 1
            ]);
        }
        return $this->sendResponse([], 'Data exited successfully');
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        try {
            DB::beginTransaction();

            // Validator request
            $v = Validator::make($request->all(), [
                'name' => ['required','string'],
            ]);

            if ($v->fails()) {
                return $this->sendError('There is an error in the data', $v->errors());
            }
            $data = $request->only(['name']);

            $measure = MeasurementUnit::create($data);

            DB::commit();

            return $this->sendResponse([], 'Data exited successfully');

        } catch (\Exception $e) {
            DB::rollBack();
            return $this->sendError('An error occurred in the system');
        }
    }



    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        try {

            $measure = MeasurementUnit::find($id);

            return $this->sendResponse(['measure' => $measure], 'Data exited successfully');

        } catch (\Exception $e) {

            return $this->sendError('An error occurred in the system');

        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        DB::beginTransaction();
        try {

            $measure = MeasurementUnit::find($id);

            // Validator request
            $v = Validator::make($request->all(), [
                'name' => ['required','string'],
            ]);

            if ($v->fails()) {
                return $this->sendError('There is an error in the data', $v->errors());
            }

            $data = $request->only(['name']);

            $measure->update($data);

            DB::commit();
            return $this->sendResponse([],'Data exited successfully');
        }catch (\Exception $e){

            DB::rollBack();
            return $this->sendError('An error occurred in the system');
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        try {
            $measure = MeasurementUnit::find($id);
            if ($measure) {

                $measure->delete();
                return $this->sendResponse([], 'Deleted successfully');
            } else {
                return $this->sendError('ID is not exist');
            }

        } catch (\Exception $e) {
            return $this->sendError('An error occurred in the system');
        }
    }
}
