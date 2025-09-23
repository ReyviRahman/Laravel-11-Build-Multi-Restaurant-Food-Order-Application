<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\City;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class CityController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $city = City::latest()->get();
        return view('admin.backend.city.all_cities', compact('city'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        City::create([
            'city_name' => $request->city_name,
            'city_slug' => Str::slug($request->city_name), 
        ]);

        $notification = [
            'message' => 'City Inserted Successfully',
            'alert_type' => 'success'
        ];

        return redirect()->route('cities.index')->with($notification);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, City $city)
    {
        $city->update([
            'city_name' => $request->city_name,
            'city_slug' => Str::slug($request->city_name)
        ]);

        $notification = [
            'message' => 'City Updated Successfully',
            'alert_type' => 'success'
        ];

        return redirect()->route('cities.index')->with($notification);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(City $city)
    {
        $city->delete();

        $notification = [
            'message' => 'City Deleted Successfully',
            'alert_type' => 'success'
        ]; 
        
        return redirect()->route('cities.index')->with($notification);
    }
}
