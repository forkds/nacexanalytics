<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Billing;
use App\Express;
use App\Office;
use App\Lib;

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
        # Get Billings model
        $bModel = new Billing ($id);

        # Prepare Express Monthly Info
        $bMonthlyItems      = $bModel->getAmmountMonthlyByIdOffice ();
        $bMonthlyGraph      = Lib::getGraphDataByMonth ($bMonthlyItems);
        $bMonthlyMaxValue   = Lib::getMaxValue($bMonthlyItems);
        $bMonthlyGraphScale = Lib::getGraphScale($bMonthlyMaxValue);
        $bMonthlyLabel      = trans('nacex-analytics.PANEL_MONTHLY_GRAPH_LABEL');

        # Prepare Express Annual Info
        $bAnnualItems      = $bModel->getAmmountAnnualByIdOffice ();
        $bAnnualGraph      = Lib::getGraphDataByYear ($bAnnualItems);
        $bAnnualMaxValue   = Lib::getMaxValue($bAnnualItems);
        $bAnnualGraphScale = Lib::getGraphScale($bAnnualMaxValue);
        $bAnnualLabel      = trans('nacex-analytics.PANEL_MONTHLY_GRAPH_LABEL');

        # Prepare Billing Page Info
        $bByMonth = [
            'items' => $bMonthlyItems,
            'graph' => $bMonthlyGraph,
            'scale' => $bMonthlyGraphScale,
            'label' => $bMonthlyLabel,
                    ];
        $bByYear  = [
            'items' => $bAnnualItems,
            'graph' => $bAnnualGraph,
            'scale' => $bAnnualGraphScale,
            'label' => $bAnnualLabel,
                    ];
        # Get Express model
        $xModel = new Express($id);

        # Prepare Express Monthly Info
        $xMonthlyItems      = $xModel->getAmmountMonthlyByIdOffice ();
        $xMonthlyGraph      = Lib::getGraphDataByMonth ($xMonthlyItems, $bMonthlyItems);
        $xMonthlyMaxValue   = Lib::getMaxValue($xMonthlyItems);
        $xMonthlyGraphScale = Lib::getGraphScale($xMonthlyMaxValue);
        $xMonthlyLabel      = trans('nacex-analytics.PANEL_MONTHLY_GRAPH_LABEL_EXPRESS');

        # Prepare Express Annual Info
        $xAnnualItems      = $xModel->getAmmountAnnualByIdOffice ();
        $xAnnualGraph      = Lib::getGraphDataByYear ($xAnnualItems, $bAnnualItems);
        $xAnnualMaxValue   = Lib::getMaxValue($xAnnualItems);
        $xAnnualGraphScale = Lib::getGraphScale($xAnnualMaxValue);
        $xAnnualLabel      = trans('nacex-analytics.PANEL_ANNUAL_GRAPH_LABEL_EXPRESS');

        # Prepare Express Page Info
        $xByMonth = [
            'items' => $xMonthlyItems,
            'graph' => $xMonthlyGraph,
            'scale' => $xMonthlyGraphScale,
            'label' => $xMonthlyLabel,
                    ];
        $xByYear  = [
            'items' => $xAnnualItems,
            'graph' => $xAnnualGraph,
            'scale' => $xAnnualGraphScale,
            'label' => $xAnnualLabel,
                    ];

        # get Calendars
        $office_model = new Office;
        $calendars = $office_model->findOrFail($id)->calendars()->get();

        # Prepare Ratios Monthly Info;
        $rMonthlyItems      = $bModel->getRatioByMonth ($bMonthlyItems,$calendars);
        $rMonthlyGraph      = Lib::getGraphDataByMonth ($rMonthlyItems, $bMonthlyItems);
        $rMonthlyMaxValue   = Lib::getMaxValue($rMonthlyItems);
        $rMonthlyGraphScale = Lib::getGraphScale($rMonthlyMaxValue);
        $rMonthlyLabel      = trans('nx.PANEL_LABEL_GRAPH_RATIO_MONTH');

        # Prepare Ratios Annual Info;
        $rAnnualItems      = $bModel->getRatioByYear ($bAnnualItems,$calendars);
        $rAnnualGraph      = Lib::getGraphDataByYear ($rAnnualItems, $bAnnualItems);
        $rAnnualMaxValue   = Lib::getMaxValue($rAnnualItems);
        $rAnnualGraphScale = Lib::getGraphScale($rAnnualMaxValue);
        $rAnnualLabel      = trans('nx.PANEL_LABEL_GRAPH_RATIO_YEAR');

        # Prepare Ratio Page Info
        $rByMonth = [
            'items' => $rMonthlyItems,
            'graph' => $rMonthlyGraph,
            'scale' => $rMonthlyGraphScale,
            'label' => $rMonthlyLabel,
                    ];
        $rByYear  = [
            'items' => $rAnnualItems,
            'graph' => $rAnnualGraph,
            'scale' => $rAnnualGraphScale,
            'label' => $rAnnualLabel,
                    ];

        # Prepare Page Info
        $graphData = [];

        $graphData[] = [$bByMonth, $bByYear];
        $graphData[] = [$rByMonth, $rByYear];
        $graphData[] = [$xByMonth, $xByYear];

        # Check if data exists
        $no_data = true;

        foreach ($graphData as $data)
        {
            if ($data[1]['items']->isNotEmpty())
            {
                $no_data = false;
                break;
            }
        }


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
        $params['annualGraphLabel']   = $annualGraphLabel;
        $params['monthlyGraphLabel']  = $monthlyGraphLabel;
        $params['arrayGraph']         = $graphData;
        $params['no_data']            = $no_data;


    	return view ('panel', $params);

    }

    public function showPost(Request $request)
    {
    	$this->show($request->id);
    }

}
