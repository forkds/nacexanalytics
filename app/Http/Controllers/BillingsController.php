<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Billing

class BillingsController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('office');
        $this->middleware('role:usuario|manager');
    }

    public function index()
    {
        return view('layout', array('id' => 'controller'));
    }
}
