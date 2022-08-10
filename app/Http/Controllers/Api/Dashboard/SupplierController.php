<?php

namespace App\Http\Controllers\Api\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\Supplier;
use App\Traits\Message;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Validator;

class SupplierController extends Controller
{

    use Message;

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $suppliers = Supplier::select('id','name_supplier','status','phone_supplier')
        ->when($request->search, function ($q) use ($request) {
            return $q->where('name_supplier', 'like', '%' . $request->search . '%')
                    ->orWhere('name', 'like', '%' . $request->search . '%');

        })
        ->latest()->paginate(10);

        return $this->sendResponse(['suppliers' => $suppliers], 'Data exited successfully');
    }


    public function activationSupplier($id)
    {
        $department = Supplier::find($id);

        if ($department->status == 1)
        {
            $department->update([
                "status" => 0
            ]);
        }else{
            $department->update([
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
                'name_supplier' => ['required','string'],
                'address' => ['required','string'],
                'phone_supplier' => ['required','string'],
                'commercial_record' => ['nullable','string'],
                'tax_card' => ['nullable','string'],
                'name' => ['nullable','string'],
                'phone' => ['nullable','string'],
            ]);

            if ($v->fails()) {
                return $this->sendError('There is an error in the data', $v->errors());
            }
            $data = $request->only(['name_supplier','address','phone_supplier','commercial_record','tax_card','name','phone']);

            $supplier = Supplier::create($data);

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

            $supplier = Supplier::find($id);

            return $this->sendResponse(['supplier' => $supplier], 'Data exited successfully');

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

            $supplier = Supplier::find($id);

            // Validator request
            $v = Validator::make($request->all(), [
                    'name_supplier' => ['required','string'],
                    'address' => ['required','string'],
                    'phone_supplier' => ['required','string'],
                    'commercial_record' => ['nullable','string'],
                    'tax_card' => ['nullable','string'],
                    'name' => ['nullable','string'],
                    'phone' => ['nullable','string'],
                ]);

            if ($v->fails()) {
                return $this->sendError('There is an error in the data', $v->errors());
            }

            $data = $request->only(['name_supplier','address','phone_supplier','commercial_record','tax_card','name','phone']);

            $supplier->update($data);

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
            $supplier = Supplier::find($id);
            if ($supplier){

                $supplier->delete();
                return $this->sendResponse([],'Deleted successfully');
            }else{
                return $this->sendError('ID is not exist');
            }

        }catch (\Exception $e){
            return $this->sendError('An error occurred in the system');
        }

    }
}
