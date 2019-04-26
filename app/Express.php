<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use App\Office;
use App\Express;

class Express extends Model
{
    //
    protected $table    = 'express';
    protected $fillable = ['client_id', 'year', 'month', 'billing'];
    protected $office_id;

    function __construct($id = 0) 
    {
        # Setting var office_id
        $this->office_id = $id;
    }

    # Define relation with clients
    public function clients()
    {
      return $this->belongsTo(Client::class);
    }
    
    # Batch Insert form XLS to table 
    public function insertImportFile ($year, $month, $rows, $batch=100)
    {
        # Delete Expres >= @year
	    $this->deleteByOfficeByYearByMonth ($year, $month);

        $client = new Client;

        $billings = [];

        foreach ($rows as $row)
        {
            # If not exist client -> create
            $client = Client::firstOrCreate(
                [
                    'code'      => $row['cod_cliente'], 
                    'office_id' => $this->office_id
                ], 
                [
                    'code' => '00000', 
                    'name' => $row['cod_cliente'], 
                    'year' => $year
                ]);            


            // Set paramts to inset record
            $billings[] = [
                            'client_id'  => $client->id,
                            'year'       => $year,
                            'month'      => $month,
                            'billing'    => (float)$row['econ_importe'],
                            ];

            // batch insert
            if (count($billings) >= $batch)
            {
                Express::insert($billings);
                $billings = [];
            }
        }

        // if remain record to insert ... batch insert
        if ($billings) Express::insert($billings);
    }

    public function deleteByOfficeByYear ($year)
    {
        # Delete all express from office >= @year
        $items = DB::table('express AS EXP')
        ->join('clients AS CLI', function ($join)
            {
                $join->on('CLI.id', '=', 'EXP.client_id'); 
        })
        ->join('offices AS OFI', function ($join)
            {
                $join->on('OFI.id', '=', 'CLI.office_id'); 
        })
        ->where('OFI.id', '=', $this->office_id)
        ->where('EXP.year', '>=', $year)
        ->select('EXP.*')
        ->delete();

        return $items;
    }

    public function deleteByOfficeByYearByMonth ($year, $month)
    {
        # Delete all express from office >= @year
        $items = DB::table('express AS EXP')
        ->join('clients AS CLI', function ($join)
            {
                $join->on('CLI.id', '=', 'EXP.client_id'); 
        })
        ->join('offices AS OFI', function ($join)
            {
                $join->on('OFI.id', '=', 'CLI.office_id'); 
        })
        ->where('OFI.id',    '=', $this->office_id)
        ->where('EXP.year',  '=', $year)
        ->where('EXP.month', '=', $month)
        ->select('EXP.*')
        ->delete();

        return $items;
    }

    public function getAmmountMonthlyByIdClient ($client_id)
    {
        return Express::selectRaw('year, month, sum(billing) as amount')
        ->groupBy('year', 'month')
        ->where('client_id', '=', $client_id)
        ->orderBy('year', 'asc')
        ->orderBy('month', 'asc')
        ->get();
    }

    public function getAmmountAnnualByIdClient ($client_id)
    {
        return Express::selectRaw('client_id, year, sum(billing) as amount')
        ->groupBy('client_id', 'year')
        ->having('client_id', '=', $client_id)
        ->orderBy('year', 'asc')
        ->get();
    }

    public function getAmmountMonthlyByIdOffice ()
    {
        $items = DB::table('offices AS OFI')
        ->join('clients AS CLI', function ($join)
            {
                $join->on('OFI.id', '=', 'CLI.office_id'); 
        })
        ->join('express AS EXP', function ($join)
            {
                $join->on('CLI.id', '=', 'EXP.client_id'); 
        })
        ->where('OFI.id', '=', $this->office_id)
        ->selectraw('EXP.year AS year, EXP.month AS month, sum(EXP.billing) as amount')
        ->groupBy('EXP.year', 'EXP.month')
        ->orderBy('EXP.year')
        ->orderBy('EXP.month')
        ->get();

        return $items;
    }

    public function getAmmountAnnualByIdOffice ()
    {
        $items = DB::table('offices AS OFI')
        ->join('clients AS CLI', function ($join)
            {
                $join->on('OFI.id', '=', 'CLI.office_id'); 
        })
        ->join('express AS EXP', function ($join)
            {
                $join->on('CLI.id', '=', 'EXP.client_id'); 
        })
        ->where('OFI.id', '=', $this->office_id)
        ->selectraw('EXP.year AS year, sum(EXP.billing) as amount')
        ->groupBy('EXP.year')
        ->orderBy('EXP.year')
        ->get();

        return $items;
    }

}
