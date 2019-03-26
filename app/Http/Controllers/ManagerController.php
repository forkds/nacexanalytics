<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ManagerController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('role');
    }

    public function upload ()
    {
        $billing = new Billing;

        $data = $billing::where("id_office", "<>", "0888")->orderby('id', 'desc')->get();

    	return view ('layout', array('id' => 1, 'count' => count($data), 'data' => $data));
    }
    

}
