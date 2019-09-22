<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Client;
use App\Billing;
use App\Office;
use App\Calendar;

class MonthlyClientController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('office');
    }


    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {

        // Create new model
        $office = new Office;

        // get current office_id of the auth user
        $office_id = \Auth::user()->getActiveOffice()->id;

        // Setting dates: current year and 
        $year_0 = date('Y');
        $year_1 = date('Y')-1;

        // Get Clients
        $model = new Client;
        $items = $office->findOrFail($office_id)->clients()->get();
        //$items = $model->where('office_id','=', $office_id)->get();

        $amount_0 = [];
        $amount_1 = [];

        $data = [];


        $billing_year_0 =  $model->getMonthlyBillingsByOffice ($office_id, $year_0);
        $billing_year_1 =  $model->getMonthlyBillingsByOffice ($office_id, $year_1);


        foreach ($items as $item) :

            for ($x=1; $x<=12; $x++) :

                $amount_0[$item->id][$x] = 0.0;
                $amount_1[$item->id][$x] = 0.0;

            endfor;

        endforeach;

        foreach ($billing_year_0 as $info) :

            $amount_0[$info->client_id][$info->month] = floatval($info->amount);

        endforeach;

        foreach ($billing_year_1 as $info) :

            $amount_1[$info->client_id][$info->month] = floatval($info->amount);

        endforeach;

        
        // Get Calendars
        $calendar_0 = $office->findOrFail($office_id)->calendars()->where('year', $year_0)->first();
        $calendar_1 = $office->findOrFail($office_id)->calendars()->where('year', $year_1)->first();

        $lab_days_0 = [];
        $lab_days_1 = [];


        for ($x=1; $x<=12; $x++) :

            $field = 'm' . $x;

            $lab_days_0[$x] = $calendar_0 ? $calendar_0->{$field} : 20;
            $lab_days_1[$x] = $calendar_1 ? $calendar_1->{$field} : 20;

        endfor;



        foreach ($items as $item) :

            for ($x=1; $x<=12; $x++) :

                $data [$item->id][$x]['bil_month_0'] = $amount_0[$item->id][$x];
                $data [$item->id][$x]['bil_month_1'] = $amount_1[$item->id][$x];

                $data [$item->id][$x]['lab_days_0'] = $lab_days_0[$x];
                $data [$item->id][$x]['lab_days_1'] = $lab_days_1[$x];

                $average_0 = 0;
                $average_1 = 0;

                if ((int) $lab_days_0[$x] > 0) 
                {
                    $average_0 = round($amount_0[$item->id][$x] / $lab_days_0[$x], 2);
                }

                if ((int) $lab_days_0[$1] > 0) 
                {
                    $average_1 = round($amount_1[$item->id][$x] / $lab_days_1[$x], 2);
                }


                $data [$item->id][$x]['bil_day_0'] = $average_0;
                $data [$item->id][$x]['bil_day_1'] = $average_1;

                if ($average_1 > 0.0) :
                    
                    $data [$item->id][$x]['bil_day_ratio'] = round(($average_0-$average_1)/$average_1, 2);

                else :

                    $data [$item->id][$x]['bil_day_ratio'] = ".";
                
                endif;


            endfor;

        endforeach;

        $params = [];

        $params['calendar'] = 

        $params['items'] = $items;
        $params['data']  = $data;

        // return view
        return view ('monthly_billings', $params);


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
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
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
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
