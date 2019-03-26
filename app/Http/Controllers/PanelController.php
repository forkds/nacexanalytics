<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Billing;

class PanelController extends Controller
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
    
    public function index()
    {
    	return $this->show(\Auth::user()->getActiveOffice()->id);
    }

    public function show($id=0)
    {

        # Get billings for this client
        $modelBilling    = new Billing;
        $monthlyBillings = $modelBilling->getAmmountMonthlyByIdOffice ($id);
        $annualBillings  = $modelBilling->getAmmountAnnualByIdOffice ($id);

        # Get monthly graph data for this client
        $monthlyGraph = $this->getGraphDataByMonth ($monthlyBillings);

        # Get annual graph data for this client
        $annualGraph  = $this->getGraphDataByYear ($annualBillings);

        # Get annual graph label
        $annualGraphLabel = trans('nacex-analytics.PANEL_ANNUAL_GRAPH_LABEL');

        # Get monthly graph label
        $monthlyGraphLabel = trans('nacex-analytics.PANEL_MONTHLY_GRAPH_LABEL');

        # Get page title
        $pageTitle = trans ('nacex-analytics.PANEL_PAGE_TITLE');

    	$params = [];

    	$office = \Auth::user()->getActiveOffice();

    	$params['office'] = $office;
        $params['pageTitle']          = $pageTitle;
        $params['monthlyGraph']       = $monthlyGraph;
        $params['annualGraph']        = $annualGraph;
        $params['annualGraphLabel']   = $annualGraphLabel;
        $params['monthlyGraphLabel']  = $monthlyGraphLabel;


    	return view ('panel', $params);

    }

    public function showPost(Request $request)
    {
    	$this->show($request->id);
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

}
