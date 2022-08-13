<?php

namespace App\Http\Controllers\Api\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\Company;
use App\Traits\Message;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Validator;

class CompanyController extends Controller
{
    use Message;

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $categories = Company::with('media:file_name,mediable_id')
            ->when($request->search, function ($q) use ($request) {
                return $q->where('name', 'like', '%' . $request->search . '%');
            })->latest()->paginate(10);

        return $this->sendResponse(['categories' => $categories], 'Data exited successfully');
    }


    public function activationCompany($id)
    {
        $company = Company::find($id);

        if ($company->status == 1)
        {
            $company->update([
                "status" => 0
            ]);
        }else{
            $company->update([
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
                'file' => 'required|file|mimes:png,jpg,jpeg',
            ]);

            if ($v->fails()) {
                return $this->sendError('There is an error in the data', $v->errors());
            }
            $data = $request->only(['name']);

            $company = Company::create($data);

            if($request->hasFile('file')){

                $file_size = $request->file->getSize();
                $file_type = $request->file->getMimeType();
                $image = time().'.'. $request->file->getClientOriginalName();

                // picture move
                $request->file->storeAs('company', $image,'general');

                $company->media()->create([
                    'file_name' => $image ,
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
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        try {

            $company = Company::with('media:file_name,mediable_id')->find($id);

            return $this->sendResponse(['company' => $company], 'Data exited successfully');

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

            $company = Company::find($id);

            // Validator request
            $v = Validator::make($request->all(), [
                'name' => 'required|string',
                'file' => 'nullable'.($request->hasFile('file')?'|file|mimes:jpeg,jpg,png':''),
            ]);

            if ($v->fails()) {
                return $this->sendError('There is an error in the data', $v->errors());
            }

            $data = $request->only(['name','status']);

            $company->update($data);

            if($request->hasFile('file')){

                if(File::exists('upload/company/'.$company->media->file_name)){
                    unlink('upload/company/'. $company->media->file_name);
                }
                $company->media->delete();

                $file_size = $request->file->getSize();
                $file_type = $request->file->getMimeType();
                $image = time().'.'. $request->file->getClientOriginalName();

                // picture move
                $request->file->storeAs('company', $image,'general');

                $company->media()->create([
                    'file_name' => $image ,
                    'file_size' => $file_size,
                    'file_type' => $file_type,
                    'file_sort' => 1
                ]);

            }

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
            $company = Company::find($id);
            if ($company){

                if(File::exists('upload/company/'.$company->media->file_name)){
                    unlink('upload/company/'. $company->media->file_name);
                }
                $company->media->delete();

                $company->delete();
                return $this->sendResponse([],'Deleted successfully');
            }else{
                return $this->sendError('ID is not exist');
            }

        }catch (\Exception $e){
            return $this->sendError('An error occurred in the system');
        }
    }
}
