<?php

namespace App\Http\Controllers\Api\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\Discount;
use App\Traits\Message;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class DiscountController extends Controller
{

    use Message;

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $coupons = Discount::when($request->search,function ($q) use ($request){

            return $q->where('code','like',"%". $request->search ."%");

        })->latest('id','ASC')->paginate(10);

        return $this->sendResponse(['coupons' => $coupons], 'Data exited successfully');
    }

    public function activationDiscount($id)
    {
        $coupon = Discount::find($id);

        if ($coupon->status == 1)
        {
            $coupon->update([
                "status" => 0
            ]);
        }else{
            $coupon->update([
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
                'code'              => 'required|unique:discounts',
                'type'              => 'required',
                'value'             => 'required',
                'description'       => 'nullable',
                'use_times'         => 'required|numeric',
                'start_date'        => 'nullable|date_format:Y-m-d',
                'expire_date'       => 'required_with:start_date|date_format:Y-m-d',
                'greater_than'      => 'nullable|numeric',
            ]);

            if ($v->fails()) {
                return $this->sendError('There is an error in the data', $v->errors());
            }
            $data = $request->only(['code','type','value','description','use_times','start_date','expire_date','greater_than']);

            $discount = Discount::create($data);

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

            $discount = Discount::find($id);

            return $this->sendResponse(['discount' => $discount], 'Data exited successfully');

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

            $discount = Discount::find($id);

            // Validator request
            $v = Validator::make($request->all(), [
                'code'              => 'required|unique:discounts,code,'.$discount->id,
                'type'              => 'required',
                'value'             => 'required|numeric',
                'description'       => 'nullable',
                'use_times'         => 'required|numeric',
                'start_date'        => 'nullable|date_format:Y-m-d',
                'expire_date'       => 'required_with:start_date|date_format:Y-m-d',
                'greater_than'      => 'nullable|numeric',
            ]);

            if ($v->fails()) {
                return $this->sendError('There is an error in the data', $v->errors());
            }

            $data = $request->only(['code','type','value','description','use_times','start_date','expire_date','greater_than']);

            $discount->update($data);

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
            $discount = Discount::find($id);
            if ($discount){

                $discount->delete();
                return $this->sendResponse([],'Deleted successfully');
            }else{
                return $this->sendError('ID is not exist');
            }

        }catch (\Exception $e){
            return $this->sendError('An error occurred in the system');
        }
    }
}
