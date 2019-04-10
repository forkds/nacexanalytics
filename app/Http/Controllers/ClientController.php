<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Client;
use App\Billing;
use App\Office;

class ClientController extends Controller
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
        //$this->middleware('role:usuario|manager');

    }

    public function index()
    {
        // get current office_id of the auth user
        $office_id = \Auth::user()->getActiveOffice()->id;

        // filterin clients by current office_id
        $model = new Client;
        //tems = $model->where('office_id','=', $office_id)->get();

        $year1 = date('Y')-2;
        $year2 = date('Y')-1;

        // filterin clients by current office_id and resume y year
        $items = $model->indexBillings($office_id, $year1, $year2);

        $pageTitle = trans ('nacex-analytics.CLIENTS_PAGE_TITLE');

        // setting view params
        $params['year1']     = $year1;
        $params['year2']     = $year2;
        $params['items']     = $items;
        $params['pageTitle'] = $pageTitle;

        // return view
        return view ('clients', $params);
    }

    public function postShow(Request $request)
    {
        $client_id = $request->id;

        //$request->flashExcept('id');

        return $this->show($client_id);
    }

   /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        // get current office_id of the auth user
        $office_id = \Auth::user()->getActiveOffice()->id;

        // Not authorized access
        if (!$this->isValidClient ($office_id, $id))
        {
            abort (301, "No tiene autorización para acceder a este recurso");
        }

        # Get current client
        $items = Client::find($id);

        # Get billings for this client
        $modelBilling    = new Billing;
        $monthlyBillings = $modelBilling->getAmmountMonthlyByIdClient ($id);
        $annualBillings  = $modelBilling->getAmmountAnnualByIdClient ($id);

        # Get monthly graph data for this client
        $monthlyGraph = $this->getGraphDataByMonth ($monthlyBillings);

        # Get annual graph data for this client
        $annualGraph  = $this->getGraphDataByYear ($annualBillings);

        // filterin clients by current office_id
        $model   = new Client;
        $clients = $model->where('office_id','=', $office_id)->get();

        $annualGraphLabel = trans('nacex-analytics.CLIENT_ANNUAL_GRAPH_LABEL');
        //$annualGraphLabel.= $items->code . '-' . $items->name;

        $monthlyGraphLabel = trans('nacex-analytics.CLIENT_MONTHLY_GRAPH_LABEL');
        //$monthlyGraphLabel.= $items->code . '-' . $items->name;

        $pageTitle = trans ('nacex-analytics.CLIENT_PAGE_TITLE');
        $pageTitle.= $items->code . '-' . $items->name;

        // setting view params
        $params['items']              = $items;
        $params['billings']           = $monthlyBillings;
        $params['monthlyGraph']       = $monthlyGraph;
        $params['annualGraph']        = $annualGraph;
        $params['annualGraphLabel']   = $annualGraphLabel;
        $params['monthlyGraphLabel']  = $monthlyGraphLabel;
        $params['pageTitle']          = $pageTitle;
        $params['select']             = $this->toSelectByCode ($clients);
        $params['id']                 = $id;
        $params['prev_id']            = $this->getPrevId ($id);
        $params['post_id']            = $this->getPostId ($id);

        // return view
        return view ('client', $params);

/*
        $active_office = \Auth::user()->getActiveOffice();

    	$rsBilling = new Billing;
    	$dataMonth = $rsBilling->amountByMonthByIdOfficeIdCustomer($active_office->id, $id_customer);
    	$dataYear  = $rsBilling->amountByYearByIdOfficeIdCustomer($active_office->id, $id_customer);

    	$dataGraph = $this->getGraphDataByMonth ($dataYear, $dataMonth);

        $rsClient  = new Client;
        $customer     = $rsClient->show ($active_office->id, $id_customer);

        $selectByCode = $this->toSelectByCode ($rsClient->indexByIdOffice($active_office->id));

        $parameters = [];
        $parameters['id'] = $active_office;
        $parameters['dataMonth'] = $dataMonth;
        $parameters['dataYear'] = $dataYear;
        $parameters['dataGraph'] = $dataGraph;
        $parameters['selectByCode'] = $selectByCode;
        $parameters['customer'] = $customer->id_customer . '-' . $customer->name;


    	return view ('client',$parameters);
*/
        //
    }

   /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function searchByCode(Request $request)
    {
        $customerCode = $request['customerCorde'];

        return $this->show($customerCode);

        //return 'search: ' . $customerCode . ".";

    }

    protected function getPostId ($id)
    {
        // get current office_id of the auth user
        $office_id = \Auth::user()->getActiveOffice()->id;

        $client = Office::find($office_id)->clients()->where('id', '>', $id)->first();

        return $client->id;

    }

    protected function getPrevId ($id)
    {
        // get current office_id of the auth user
        $office_id = \Auth::user()->getActiveOffice()->id;

        $client = Office::find($office_id)->clients()->where('id', '<', $id)->orderby('id', 'desc')->first();
        
        return $client->id;

    }

    protected function getGraphDataByMonth ($billings)
    {
        # Get years
        $arrayYears = [];

        foreach ($billings as $billing)
        {
            if (!isset($arrayYears[$billing->year])) $arrayYears[$billing->year] = $billing->year;
        }

    	$arrayGraph = array();

        $cnt_color = 0;

    	if ($arrayYears)
    	{
    		foreach ($arrayYears as $key => $year)
    		{
    			$arrayMonths = array();

    			$yearPattern = $key;

    			for ($x=0; $x<12; $x++) 
    			{
	    			$arrayMonths[$x] = 0;
    			}

	    		foreach ($billings as $billing)
	    		{
	    			if ($billing->year == $yearPattern)
	    			{
		    			$arrayMonths[$billing->month-1] = ['x' => $billing->month-1, 'y' => $billing->amount];
	    			}
	    		}

	    		$arrayGraph[] = array('values' => $arrayMonths, 'key' => 'Año ' . $yearPattern, 'color' => $this->getGraphColors($cnt_color));

                $cnt_color++;
    		}

    	}

    	return $arrayGraph;
    }


    protected function getGraphDataByYear ($billings)
    {
        $values = [];

        $cnt_color = 0;

        foreach ($billings as $billing)
        {
            $values[] = ['label' => $billing->year, 
                         'value' => $billing->amount,
                         'color' => $this->getGraphColors($cnt_color++)];
        }

        return ['key'=>'Evolución anual', 'values'=>$values];

    }

    protected function toSelectByCode ($clients)
    {
        $select = [];

        foreach ($clients as $client)
        {
            $select[$client->id] = $client->code . '-' . $client->name;
        }

        return $select;
    }


    protected function getGraphColors($number) 
    {
        $colors = array();

        $colors[] = '#039cfd';
        $colors[] = '#f76397';
        $colors[] = '#52bb56';
        $colors[] = '#ffaa00';
        $colors[] = '#dcdcdc';
        $colors[] = '#e6194B';
        $colors[] = '#f58231';
        $colors[] = '#ffe119';
        $colors[] = '#bfef45';
        $colors[] = '#67d1f8';
        $colors[] = '#42d4f4';
        $colors[] = '#911eb4';
        $colors[] = '#f032e6';
        $colors[] = '#a9a9a9';
        $colors[] = '#800000';
        $colors[] = '#9A6324';
        $colors[] = '#469990';
        $colors[] = '#000075';
        $colors[] = '#000000';
        $colors[] = '#039cfd';
        $colors[] = '#7266ba';
        $colors[] = '#fabebe';
        $colors[] = '#ffd8b1';
        $colors[] = '#fffac8';
        $colors[] = '#aaffc3';
        $colors[] = '#e6beff';
        $colors[] = '#2E8B57';
        $colors[] = '#DB7093';
        $colors[] = '#228B22';
        $colors[] = '#00FF7F';
        $colors[] = '#ADFF2F';
        $colors[] = '#B22222';
        $colors[] = '#FFB6C1';
        $colors[] = '#7FFF00';
        $colors[] = '#BA55D3';
        $colors[] = '#66CDAA';
        $colors[] = '#F5F5DC';
        $colors[] = '#778899';
        $colors[] = '#FFA07A';
        $colors[] = '#FF00FF';
        $colors[] = '#3CB371';
        $colors[] = '#48D1CC';
        $colors[] = '#5F9EA0';
        $colors[] = '#D2691E';
        $colors[] = '#FF7F50';
        $colors[] = '#696969';
        $colors[] = '#FF00FF';
        $colors[] = '#4169E1';
        $colors[] = '#8B4513';
        $colors[] = '#F4A460';
        $colors[] = '#FFD700';
        $colors[] = '#DAA520';
        $colors[] = '#F0E68C';
        $colors[] = '#4682B4';
        $colors[] = '#00FF00';
        $colors[] = '#B8860B';


        return (($number > count($colors)-1) ? '#67d1f8' : $colors[$number]); 
    }

    protected function isValidClient ($office_id, $client_id)
    {
        $model = new Client;
        $items = $model
                ->where('office_id','=', $office_id)
                ->where('id','=', $client_id)
                ->get();

        // Not authorized access
        return ($items->isNotEmpty()) ? TRUE : FALSE;

    }

}
