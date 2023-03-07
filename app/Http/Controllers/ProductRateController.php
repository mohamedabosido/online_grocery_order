<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreRateRequest;
use App\Models\ProductRate;
use App\Traits\HttpResponses;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProductRateController extends Controller
{
    use HttpResponses;
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $paginate = 10;
        $rates = ProductRate::paginate($paginate);
        return $this->success($rates);
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreRateRequest $request)
    {
        $request->validated($request->all());

        $rate = ProductRate::create([
            'user_id' => Auth::user()->id,
            'product_id' => $request->product_id,
            'rate' => $request->rate,
            'comment' => $request->comment,
        ]);
        return $this->success($rate);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $rate = ProductRate::find($id);
        return $this->success($rate);
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
        $rate = ProductRate::where('id', $id)->update([
            'rate' => $request->rate,
            'comment' => $request->comment,
        ]);
        return $this->success($rate);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $rate = ProductRate::destroy($id);
        if ($rate) {
            return $this->success('', 'Deleted successfully');
        }
        return $this->error('', 'Please check the process', 400);
    }
}
