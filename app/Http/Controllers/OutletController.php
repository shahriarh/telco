<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\BaseController as BaseController;
use App\Models\Outlet;
use App\Http\Requests\ProductRequest;
use App\Http\Resources\OutletResouece;

class OutletController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $outlets = Outlet::latest()->get();
        return response()->json([OutletResouece::collection($outlets), 'Outlets fetched.']);
        
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $input = $request->all();
        $validator = $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'required',
            'lattitude' => 'required',
            'longitude' => 'required',
            'image' => 'nullable',
            ]);
        $outlet = Outlet::create($input);
        return response()->json([ 'Outlet created successfully' => auth()->check() ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $outlet = Outlet::find($id);
        if (is_null($outlet)) {
            return response()->json('Outlet not found', 404); 
        }
        return response()->json([new OutletResouece($outlet)]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    
    public function update(Request $request, Outlet $outlet)
    {
        $input = $request->all();

        $validator = Validator::make($input, [
            'name' => 'required',
            'phone' => 'required',
            'lattitude' => 'required',
            'longitude' => 'required',
            'image' => 'nullable',
        ]);

        if($validator->fails()){
            return response()->json($validator->errors());       
        }

        $outlet->name = $input['name'];
        $outlet->phone = $input['phone'];
        $outlet->lattitude = $input['lattitude'];
        $outlet->longitude = $input['longitude'];
        $outlet->image = $input['image'];
        $outlet->save();
        
        return $this->handleResponse(new OutletResouece($task), 'Outlet successfully updated!');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Outlet $outlet)
    {
        $outlet->delete();
        return response()->json('Outlet deleted successfully');
    }
}
