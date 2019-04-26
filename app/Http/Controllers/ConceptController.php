<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Office;
use App\Concept;

class ConceptController extends Controller
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

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // get current office_id of the auth user
        $office_id = \Auth::user()->getActiveOffice()->id;       

        # Create model
        $concept_model = new Concept ($office_id);


        $raw = 'sum(m1)  as t1, ';
        $raw.= 'sum(m2)  as t2, ';
        $raw.= 'sum(m3)  as t3, ';
        $raw.= 'sum(m4)  as t4, ';
        $raw.= 'sum(m5)  as t5, ';
        $raw.= 'sum(m6)  as t6, ';
        $raw.= 'sum(m7)  as t7, ';
        $raw.= 'sum(m8)  as t8, ';
        $raw.= 'sum(m9)  as t9, ';
        $raw.= 'sum(m10) as t10, ';
        $raw.= 'sum(m11) as t11, ';
        $raw.= 'sum(m12) as t12, ';
        $raw.= 'year ';

        $raw = 'sum(m1+m2+m3+m4+m5+m6+m7+m8+m9+m10+m11+m12) as tot, code, year ';

        # Get Concepts
        $concepts = $concept_model->getConcepts();

        # Get Items for each concept
        $items = [];

        if ($concepts)
        {
            foreach ($concepts as $concept)
            {
                # Get item
                $item  = $concept_model->getItemsByConceptByMonth($concept);

                $graph = $concept_model->getGraphDataByMonth ($item);

                $items[] = $graph;

            }
        }

        // return view
        return view ('concepts')->with('items', $items);

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
