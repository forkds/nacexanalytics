<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use \App\Billing;
use \App\Client;

class Billing extends Model
{
    protected $table = "billings";

    protected $fillable = ['client_id', 'year', 'month', 'billing'];

    public function clients()
    {
      return $this->belongsTo(Client::class);
    }

    public function index()
    {
    	return Billing::all();
    }

    public function getAmmountMonthlyByIdClient ($id)
    {
        return Billing::selectRaw('year, month, sum(billing) as amount')
        ->groupBy('year', 'month')
        ->where('client_id', '=', $id)
        ->orderBy('year', 'asc')
        ->orderBy('month', 'asc')
        ->get();
    }

    public function getAmmountAnnualByIdClient ($id)
    {
        return Billing::selectRaw('client_id, year, sum(billing) as amount')
        ->groupBy('client_id', 'year')
        ->having('client_id', '=', $id)
        ->orderBy('year', 'asc')
        ->get();
    }

    public function getAmmountMonthlyByIdOffice ($id)
    {
        $items = DB::table('offices AS OFI')
        ->join('clients AS CLI', function ($join)
            {
                $join->on('OFI.id', '=', 'CLI.office_id'); 
        })
        ->join('billings AS BIL', function ($join)
            {
                $join->on('CLI.id', '=', 'BIL.client_id'); 
        })
        ->where('OFI.id', '=', $id)
        ->selectraw('BIL.year AS year, BIL.month AS month, sum(BIL.billing) as amount')
        ->groupBy('BIL.year', 'BIL.month')
        ->orderBy('BIL.year')
        ->orderBy('BIL.month')
        ->get();

        return $items;
    }

    public function getAmmountAnnualByIdOffice ($id)
    {
        $items = DB::table('offices AS OFI')
        ->join('clients AS CLI', function ($join)
            {
                $join->on('OFI.id', '=', 'CLI.office_id'); 
        })
        ->join('billings AS BIL', function ($join)
            {
                $join->on('CLI.id', '=', 'BIL.client_id'); 
        })
        ->where('OFI.id', '=', $id)
        ->selectraw('BIL.year AS year, sum(BIL.billing) as amount')
        ->groupBy('BIL.year')
        ->orderBy('BIL.year')
        ->get();

        return $items;
    }

    public function indexByIdOfficeIdCustomer($id_office, $id_customer)
    {
    	return $this->index()->where('id_office', '=', $id_office)->where('id_customer', '=', $id_customer);
    }

    public function amountByMonthByIdOfficeIdCustomer($id_office, $id_customer)
    {
        return Billing::selectRaw('year, month, id_office, id_customer, sum(billing) as amount')
        ->groupBy('year', 'month', 'id_office', 'id_customer')
        ->having('id_office', '=', $id_office)
        ->having('id_customer', '=', $id_customer)
        ->get();
    }

    public function amountByYearByIdOfficeIdCustomer($id_office, $id_customer)
    {
    	return Billing::selectRaw('year, id_office, id_customer, sum(billing) as amount')
    	->groupBy('year', 'id_office', 'id_customer')
    	->having('id_office', '=', $id_office)
    	->having('id_customer', '=', $id_customer)
    	->get();
    }

    public function amountByMonthByIdOffice($id_office)
    {
        return Billing::selectRaw('year, month, id_office, sum(billing) as amount')
        ->groupBy('year', 'month', 'id_office')
        ->having('id_office', '=', $id_office)
        ->get();
    }

    public function deleteByOfficeByYear ($office_id, $year)
    {
        # Delete all billings >= @year
        $items = DB::table('billings AS BIL')
        ->join('clients AS CLI', function ($join)
            {
                $join->on('CLI.id', '=', 'BIL.client_id'); 
        })
        ->join('offices AS OFI', function ($join)
            {
                $join->on('OFI.id', '=', 'CLI.office_id'); 
        })
        ->where('OFI.id', '=', $office_id)
        ->where('BIL.year', '>=', $year)
        ->select('BIL.*')
        ->delete();

        return $items;
    }

    public function insertImportFile ($office, $year, $rows, $bath=100)
    {

        # Delete Billings >= @year
        $this->deleteByOfficeByYear($office->id, $year);

        # Delete Clients >= @year
        $modelClient = new Client;
        $modelClient->deleteByOfficeByYear ($office->id, $year);

        $months = $this->getArrayMonths();

        $client = new Client;

        $billings = [];

        foreach ($rows as $row)
        {
            $client = Client::firstOrCreate(
                [
                    'code'      => $row['cliente'], 
                    'office_id' => $office->id
                ], 
                [
                    'name' => $row['nombre'], 
                    'year' => $year
                ]);            

            for ($x=1; $x<=12; $x++)
            {
                $billings[] = [
                                'client_id'  => $client->id,
                                'year'       => $year,
                                'month'      => $x,
                                'billing'    => (float)$row[$months[$x]],
                                ];
            }

            if (count($billings) >= $bath)
            {
                Billing::insert($billings);
                $billings = [];
            }
        }

        if ($billings) Billing::insert($billings);
    }


    public function getArrayMonths ()
    {
        $moths = [];
        $moths[1] = 'enero';
        $moths[2] = 'febrero';
        $moths[3] = 'marzo';
        $moths[4] = 'abril';
        $moths[5] = 'mayo';
        $moths[6] = 'junio';
        $moths[7] = 'julio';
        $moths[8] = 'agosto';
        $moths[9] = 'septiembre';
        $moths[10] = 'octubre';
        $moths[11] = 'noviembre';
        $moths[12] = 'diciembre';

        return $moths;
    }
}
