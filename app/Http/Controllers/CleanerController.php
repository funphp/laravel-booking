<?php

namespace App\Http\Controllers;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use App\Cleaner;
use App\City;
use Illuminate\Http\Request;
use Session;

class CleanerController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        $cleaner = Cleaner::paginate(25);

        return view('cleaner.index', compact('cleaner'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {
        $city = City::all();
        return view('cleaner.create',['city'=>$city]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function store(Request $request)
    {
        
        $requestData = $request->all();
        
        $cleaner = Cleaner::create($requestData);
        foreach($requestData['city'] as $c) {
            $cleaner->cities()->attach($c);
        }


        Session::flash('flash_message', 'Cleaner added!');

        return redirect('cleaner');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     *
     * @return \Illuminate\View\View
     */
    public function show($id)
    {
        $cleaner = Cleaner::with('cities')->findOrFail($id);

        return view('cleaner.show', compact('cleaner'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     *
     * @return \Illuminate\View\View
     */
    public function edit($id)
    {
        $cleaner = Cleaner::with('cities')->findOrFail($id);
        $city_id =array();
        foreach($cleaner->cities as $ct) {
            $city_id[]=$ct->id;
        }
        $city = City::all();
        foreach($city as $k=>$c) {
            if(in_array($c->id, $city_id)) {
                $city[$k]->selected = true;
            } else {
                $city[$k]->selected = false;
            }
        }

        return view('cleaner.edit', ['cleaner'=>$cleaner, 'city'=>$city]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  int  $id
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function update($id, Request $request)
    {
        
        $requestData = $request->all();
        
        $cleaner = Cleaner::findOrFail($id);
        $cleaner->update($requestData);
        $cleaner->cities()->detach();
        foreach($requestData['city'] as $c) {
            $cleaner->cities()->attach($c);
        }

        Session::flash('flash_message', 'Cleaner updated!');

        return redirect('cleaner');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     *
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function destroy($id)
    {
        Cleaner::destroy($id);

        Session::flash('flash_message', 'Cleaner deleted!');

        return redirect('cleaner');
    }
}
