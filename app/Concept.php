<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Office;

class Concept extends Model
{
    //
    protected $table = 'concepts';

    protected $office_id;

    protected $office_model;

    protected $fillable = ['code','name','office_id','year','m1','m2','m3','m4','m5','m6','m7','m8','m9','m10','m11','m12'];


	function __construct($id = 0) 
	{
    	# Setting var office_id
        $this->office_id = $id;
    }

    public function offices()
    {
      return $this->belongsTo(Office::class);
    }


    public function getConcepts ()
    {
    	# Get model
    	$office_model = new Office;

    	# Get & return data
    	return  Office::findOrFail($this->office_id)->concepts()->selectRaw('code')->groupBy('code')->get()->toArray();
    }

    public function getItemsByConceptByYear ($concept)
    {
    	# Get model
    	$office_model = new Office;

    	# Set Raw info
        $raw = 'sum(m1+m2+m3+m4+m5+m6+m7+m8+m9+m10+m11+m12) as tot, code, year ';

    	# Get & return data
    	return  Office::findOrFail($this->office_id)
    					->concepts()
    					->selectRaw($raw)
    					->groupBy('code', 'year')
    					->where('code', $concept)
    					->orderBy('year')
    					->get()
    					->toArray();
    }

    public function getItemsByConceptByMonth ($concept)
    {
    	# Get model
    	$office_model = new Office;

    	# Get office_id
    	# $office_id = \Auth::user()->getActiveOffice()->id;

    	# Set Raw info
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
        $raw.= 'code, year ';

    	# Get & return data
    	return  Office::findOrFail($this->office_id)
    					->concepts()
    					->selectRaw($raw)
    					->groupBy('code', 'year')
    					->where('code', $concept)
    					->orderBy('year')
    					->get()
    					->toArray();
    }

    public function getGraphDataByMonth ($items)
    {
    	# Items has one concept and differents years
    	

    	# Initializations
    	$arrayGraph = array();
        $cnt_color  = 0;

        # Loop
		foreach ($items as $item) :

			# Initialize arrayMonths
			$arrayMonths[0]  = ['x' => 0,  'y' => $item['t1']];
			$arrayMonths[1]  = ['x' => 1,  'y' => $item['t2']];
			$arrayMonths[2]  = ['x' => 2,  'y' => $item['t3']];
			$arrayMonths[3]  = ['x' => 3,  'y' => $item['t4']];
			$arrayMonths[4]  = ['x' => 4,  'y' => $item['t5']];
			$arrayMonths[5]  = ['x' => 5,  'y' => $item['t6']];
			$arrayMonths[6]  = ['x' => 6,  'y' => $item['t7']];
			$arrayMonths[7]  = ['x' => 7,  'y' => $item['t8']];
			$arrayMonths[8]  = ['x' => 8,  'y' => $item['t9']];
			$arrayMonths[9]  = ['x' => 9,  'y' => $item['t10']];
			$arrayMonths[10] = ['x' => 10, 'y' => $item['t11']];
			$arrayMonths[11] = ['x' => 11, 'y' => $item['t12']];

    		$arrayGraph[] = array('values' => $arrayMonths, 'key' => 'Año ' . $item['year'], 'color' => $this->getGraphColors($cnt_color));

            $cnt_color++;

		endforeach;

		# Return
    	return $arrayGraph;
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
