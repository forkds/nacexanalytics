<?php

namespace App\Imports;

use App\Import;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithBatchInserts;
use Maatwebsite\Excel\Concerns\WithValidation;

class Imports implements ToModel, WithHeadingRow, WithValidation, WithBatchInserts
{   
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {
        
/*
        return new Import([

            'cliente'    => $row['cliente'],
            'nombre'     => $row['nombre'],
            'enero'      => $row['enero'],
            'febrero'    => $row['febrero'],
            'marzo  '    => $row['marzo'],
            'abril'      => $row['abril'],
            'mayo'       => $row['mayo'],
            'junio'      => $row['junio'],
            'julio'      => $row['julio'],
            'agosto'     => $row['agosto'],
            'septiembre' => $row['septiembre'],
            'octubre'    => $row['octubre'],
            'noviembre'  => $row['noviembre'],
            'diciembre'  => $row['diciembre'],
            'Fecha'   => $row['Fecha '],
            'Client'  => $row['Cod. Cliente'],
            'Billing' => $row['Econ_importe '],

            //
        ]);
*/
    }

    public function rules(): array
    {
    /*    
        return [
            'cliente' => 'required',
            'nombre' => 'required',
        ];
    */
    }


    public function customValidationMessages()
    {
        /*
        return [
            'cliente.required' => 'Please enter cliente',
            'nombre.required' => 'Please enter nombre',
        ];
        */
    }   
             
    public function batchSize(): int
    {
        return 1000;
    }

}
