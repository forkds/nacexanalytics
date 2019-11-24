<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Calendar;
use App\Office;

class Calendar extends Model
{
	protected $table = 'calendars';

    protected $fillable = ['officet_id', 'year', 'm1', 'm2', 'm3', 'm4', 'm5', 'm6', 'm7', 'm8', 'm9', 'm10', 'm11', 'm12'];


    protected $office_id;

    function __construct($id = 0) 
    {
        # Setting var office_id
        $this->office_id = $id;
    }

    //
    public function offices()
    {
      return $this->belongsTo(Office::class);
    }

    public function validation (&$rows)
    {
        $errors = $this->validateFormat($rows);

        if (!$errors)
        {
            $errors = $this->validateContent($rows);
        }

        return $errors;
    }

    public function getValidationArray()
    {
        $fields = [];

        $fields['periodo'] = 'double';
        $fields['ene']     = 'double';
        $fields['feb']     = 'double';
        $fields['mar']     = 'double';
        $fields['abr']     = 'double';
        $fields['may']     = 'double';
        $fields['jun']     = 'double';
        $fields['jul']     = 'double';
        $fields['ago']     = 'double';
        $fields['sep']     = 'double';
        $fields['oct']     = 'double';
        $fields['nov']     = 'double';
        $fields['dic']     = 'double';

        return $fields;
    }

    public function validateFormat ($rows)
    {
        $fields = $this->getValidationArray();

        if (! $rows) 
            return array(trans('import_express.MSF_FMT_ERR_EMPTY'));

        $row = $rows[0];

        if (count($row) < count($fields)) 
            return array(trans('import_express.MSG_FMT_ERR_COLS', ['cols' => count($fields)]));

        $errors = [];

        foreach ($fields as $key => $field)
        {
            if (!array_key_exists($key, $row))
            {
                $errors[] = trans('import_express.MSG_FMT_ERR_FIELD', ['field' => $key]);
            }
        }

        return $errors;


    }

    public function validateContent (&$rows)
    {
        $fields = $this->getValidationArray();

        $errors = [];

        $cnt = 2;

        foreach ($rows as $row_id => $row )
        {
            foreach ($fields as $key => $field)
            {
                if (getType($row[$key]) !== $field)
                {
                    $errors[] = trans('import_express.MSG_FMT_ERR_ROW', ['row' => $cnt, 'col' => $key]);
                }
            }

            $cnt++;
        }

        return $errors;
    }

    # Batch Insert form XLS to table 
    public function insertImportFile ($rows, $batch=100)
    {
        $calendars = [];

        foreach ($rows as $row)
        {
            # Get Year
            $year  = (int)$row['periodo'];

            # Delete Calendar = @year
            $this->deleteByOfficeByYear ($year);

            // Set paramts to inset record
            $calendars[] = [
                            'office_id'  => $this->office_id,
                            'year'       => (int)$row['periodo'],
                            'm1'         => (int)$row['ene'],
                            'm2'         => (int)$row['feb'],
                            'm3'         => (int)$row['mar'],
                            'm4'         => (int)$row['abr'],
                            'm5'         => (int)$row['may'],
                            'm6'         => (int)$row['jun'],
                            'm7'         => (int)$row['jul'],
                            'm8'         => (int)$row['ago'],
                            'm9'         => (int)$row['sep'],
                            'm10'        => (int)$row['oct'],
                            'm11'        => (int)$row['nov'],
                            'm12'        => (int)$row['dic'],
                            ];

            // batch insert
            if (count($calendars) >= $batch)
            {
                Calendar::insert($calendars);
                $calendars = [];
            }
        }

        // if remain record to insert ... batch insert
        if ($calendars) Calendar::insert($calendars);
    }

    public function deleteByOfficeByYear ($year)
    {
        Office::findOrFail($this->office_id)->calendars()->where('year', '=', $year)->delete();
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


    public function getRatioByYear ($billings, $calendars)
    {
        $ratios = clone ($billings);

        $office_model = new Office;


        foreach ($ratios as $ratio)
        {
            $year = (int)$ratio->year;

            $calendar = $office_model->find($this->office_id)->calendars()->where('year', $year)->first();

            $days = 0;

            if ($calendar)
            {
                $days+= (int)$calendar['m1']>0?(int)$calendar['m1']:20;
                $days+= (int)$calendar['m2']>0?(int)$calendar['m2']:20;
                $days+= (int)$calendar['m3']>0?(int)$calendar['m3']:20;
                $days+= (int)$calendar['m4']>0?(int)$calendar['m4']:20;
                $days+= (int)$calendar['m5']>0?(int)$calendar['m5']:20;
                $days+= (int)$calendar['m6']>0?(int)$calendar['m6']:20;
                $days+= (int)$calendar['m7']>0?(int)$calendar['m7']:20;
                $days+= (int)$calendar['m8']>0?(int)$calendar['m8']:20;
                $days+= (int)$calendar['m9']>0?(int)$calendar['m9']:20;
                $days+= (int)$calendar['m10']>0?(int)$calendar['m10']:20;
                $days+= (int)$calendar['m11']>0?(int)$calendar['m11']:20;
                $days+= (int)$calendar['m12']>0?(int)$calendar['m12']:20;
            }

            if ($days > 0)
            {
                $ratio->amount = $days;
            }
            else
            {
                $ratio->amount = (12 * 20); // Dafault labour days by month
            }


        }

        return $ratios;
    }

    /*
    /* @billings: obj [year, month, value]
    /* @calnedars: obj [year, mo1, ,2, m3, ..., m11, m12]
    /*
    /* return $billings, replacing value by dats bay year&montf
    */
    public function getRatioByMonth ($billings)
    {
        $ratios = clone ($billings);

        $office_model = new Office;

        $year_tmp = 0;

        foreach ($ratios as $ratio)
        {
            $year = (int)$ratio->year;

            if ($year != $year_tmp)
            {
                $calendar = $office_model->find($this->office_id)->calendars()->where('year', $year)->first();
                $year_tmp = $year;
            }

            $month_days = 0;

            if ($calendar)
            {
                $month = (int)$ratio->month;

                $month_days  = (int)$calendar['m' . $month];
            }

            $days = $month_days ? $month_days : 20;

            $ratio->amount = $days;

        }

        return $ratios;
    }

}
