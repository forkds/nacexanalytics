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

    protected $office_id;

    function __construct($id = 0) 
    {
        # Setting var office_id
        $this->office_id = $id;
    }

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

    public function getAmmountMonthlyByIdOffice ()
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
        ->where('OFI.id', '=', $this->office_id)
        ->selectraw('BIL.year AS year, BIL.month AS month, sum(BIL.billing) as amount')
        ->groupBy('BIL.year', 'BIL.month')
        ->orderBy('BIL.year')
        ->orderBy('BIL.month')
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
        ->join('billings AS BIL', function ($join)
            {
                $join->on('CLI.id', '=', 'BIL.client_id'); 
        })
        ->where('OFI.id', '=', $this->office_id)
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

    public function getRatioByYear ($billings, $calendars)
    {
        $ratios = clone ($billings);

        $office_model = new Office;

        $days = 20;

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
                $ratio->amount/= $days;
            }
            else
            {
                $ratio->amount/= (20*12);
            }


        }

        return $ratios;
    }

    public function getRatioByMonth ($billings, $calendars)
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

            $days = 0;

            if ($calendar)
            {
                $month = (int)$ratio->month;

                $days  = (int)$calendar['m' . $month];
            }

            $days = $days ? $days : 20;

            $ratio->amount/= $days;

        }

        return $ratios;
    }
}
