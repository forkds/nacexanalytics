<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Import extends Model
{
    //
    protected $fillable = [
    	'cliente', 
    	'nombre', 
    	'enero',
    	'febrero',
    	'marzo',
    	'abril',
    	'mayo',
    	'junio',
    	'julio',
    	'agosto',
    	'septiembre',
    	'octubre',
    	'noviembre',
    	'diciembre',
    ];

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
        $fields['cliente']     = 'string';
        $fields['nombre']      = 'string';
        $fields['enero']       = 'double';
        $fields['febrero']     = 'double';
        $fields['marzo']       = 'double';
        $fields['abril']       = 'double';
        $fields['mayo']        = 'double';
        $fields['junio']       = 'double';
        $fields['julio']       = 'double';
        $fields['agosto']      = 'double';
        $fields['septiembre']  = 'double';
        $fields['octubre']     = 'double';
        $fields['noviembre']   = 'double';
        $fields['diciembre']   = 'double';

        return $fields;
    }

    public function validateFormat ($rows)
    {
        $fields = $this->getValidationArray();

        if (! $rows) 
        	return array(trans('import.MSF_FMT_ERR_EMPTY'));

    	$row = $rows[0];

        if (count($row) < count($fields)) 
        	return array(trans('import.MSG_FMT_ERR_COLS', ['cols' => count($fields)]));

        $errors = [];

    	foreach ($fields as $key => $field)
    	{
    		if (!array_key_exists($key, $row))
			{
				$errors[] = trans('import.MSG_FMT_ERR_FIELD', ['field' => $key]) ;
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
            if ($row['cliente'] === null)
            {
                unset($rows[$row_id]);
            }
            else
            {
                foreach ($fields as $key => $field)
                {
                    if (getType($row[$key]) !== $field)
                    {
                        $errors[] = trans('import.MSG_FMT_ERR_ROW', ['row' => $cnt, 'col' => $key]);
                    }
                }
            }

        	$cnt++;
        }

        return $errors;
    }

}
