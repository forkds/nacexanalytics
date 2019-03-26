<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\OfficeUser;

class OfficeController extends Controller
{
	public function set(Request $request)
	{

        # get model
        $model = new OfficeUser;

        # get user offices
		$offices = $request->user()->offices()->get();

		foreach ($offices as $office)
		{
	        # clr user offices
	        $rs = $model
	    			->where('user_id',  "=", $request->user()->id)
	        		->where('office_id',"=", $office->id)
	        		->first();
	        $rs->active=FALSE;
	        $rs->save();
		}  

        # set office
        $rs = $model
    			->where('user_id',  "=", $request->user()->id)
        		->where('office_id',"=", $request->id)
        		->first();
        $rs->active=TRUE;
        $rs->save();

		return redirect('/panel');
	}
}
