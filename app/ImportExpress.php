<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ImportExpress extends Model
{
    //
    protected $fillable = [
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

        $fields['cod_cliente']      = 'string|nullable';
        $fields['fecha']            = 'double';
        $fields['econ_importe']     = 'double';

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
				$errors[] = trans('import_express.MSG_FMT_ERR_FIELD', ['field' => $key]) . implode('-', $row) ;
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
            if ($row['cod_cliente'] === null)
            {
                # unset($rows[$row_id]);
                $row['cod_cliente'] = '00000';
            }
            else
            {
                foreach ($fields as $key => $field)
                {
                    $rules = explode ('|', $field);
                    $type  = getType($row[$key]);

                    if (!in_array($type, $rules))
                    //if (getType($row[$key]) !== $field)
                    {
                        $errors[] = trans('import_express.MSG_FMT_ERR_ROW', ['row' => $cnt, 'col' => $key]);
                    }
                }
            }

        	$cnt++;
        }

        return $errors;
    }

}
