<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\DB;
use App\Billing;
use App\User;
use App\Client;
use App\Office;

class LayoutController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
        //$this->middleware('office');
        //$this->middleware('role:usuario|manager');
    }

    public function test()
    {
        $params = [];

        //$test =  \Auth::user()->offices()->orderBy('name')->get();
        //$test =  \Auth::user()->roles()->orderBy('name')->get();
        
        //$obj  =  new \App\Billing;
        //$test =  $obj->where('client_id','=','1')->get();
   
        //$obj  = Client::find(2);
        //$test = $obj ? $obj->billings()->get() : NULL;

        $obj  = new Client;

        $items = DB::table('billings')
                ->selectRaw("client_id, year, sum(billing) as amount")
                ->groupBy('client_id', 'year')
                ->orderBy('amount','desc')
                ->get();

        $test = [];

        foreach ($items as $item)
        {
            $test[$item->client_id] = $item->amount;
        }

        $params['test'] = $test;

        return view ('test', $params);
    }


    public function index ()
    {

        $billing = new Billing;

        $data = $billing::where("id_office", "<>", "0888")->orderby('id', 'desc')->get();

    	return view ('layout', array('id' => 1, 'count' => count($data), 'data' => $data));
    }

    public function graph ()
    {
        $billing = new Billing;

        $data = $billing::groupby('id_office', 'id_customer', 'year', 'month')
        ->selectRaw('id_office, id_customer, year, month, sum(billing) as sum')
        ->where("id_office", "=", "0859")
        ->where("id_customer", "=", "00001")
        ->orderby('month', 'asc')
        ->get();

        $graph = $this->prepareGraph();

    	return view ('layout', array('id' => 1, 'count' => count($data), 'data' => $data, 'graph' => $graph));
    }


    public function profile ()
    {
        $billing = new Billing;

        $data = $billing::where("id_office", "<>", "0888")->orderby('id', 'desc')->get();

    	return view ('layout', array('id' => 1, 'count' => count($data), 'data' => $data));
    }
    public function analysis ()

    {
        $billing = new Billing;

        $data = $billing::where("id_office", "<>", "0888")->orderby('id', 'desc')->get();

    	return view ('layout', array('id' => 1, 'count' => count($data), 'data' => $data));
    }

    protected function prepareGraph2 ($dataOffice)
    {
    }

    protected function prepareGraph ()
    {
        $months = array("Ene", "Feb", "Mar", "Abr", "May", "Jun", "Jul", "Ago", "Sep", "Oct", "Nov", "Dic");

        $graph = array();

        $year1 = array();
        $year2 = array();
        $year3 = array();

        for ($x=0; $x<12; $x++)
        {
            $year1[] = array('x' => $x, 'y' => ($x+10));
            $year2[] = array('x' => $x, 'y' => ($x+12));
            $year3[] = array('x' => $x, 'y' => ($x+14));
        }

        $graph[] = array('values' => $year1, 'key' => "Year1", 'color' => '#FFA07A');
        $graph[] = array('values' => $year2, 'key' => "Year2", 'color' => '#DAA520');
        $graph[] = array('values' => $year3, 'key' => "Year3", 'color' => '#52bb56');


        return $graph;

    }


}
