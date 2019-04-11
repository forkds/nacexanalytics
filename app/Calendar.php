<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Calendar;
use App\Office;

class Calendar extends Model
{
	protected $table = 'calendars';
    //
    public function offices()
    {
      return $this->belongsTo(Office::class);
    }

    public function getCalendar ($office_id, $year)
    {
    	$modelOffice = new Office;

    	$office = $modelOffice->find($office_id);

    	if ($office)
    	{
    		$office->calendars->where('year', $year);
    	}
    }

}
