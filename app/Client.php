<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use App\Office;
use App\Client;


class Client extends Model
{
    protected $table = 'clients';

    protected $fillable = ['code', 'name', 'office_id', 'year'];


    public function offices()
    {
      return $this->belongsTo(Office::class);
    }

    public function billings()
    {
      return $this->hasMany(Billing::class);
    }

    public function express()
    {
      return $this->hasMany(Express::class);
    }


    public function index()
    {
    	return Client::select('id_office', 'id_customer', 'name')
    	->groupBy('id_office', 'id_customer', 'name')
    	->orderBy('id_office')
    	->orderBy('id_customer')
    	->get();
    }

    public function indexByIdOffice($id_office)
    {
    	return $this->index()->where('id_office', '=', $id_office);
    }

    public function indexByIdOfficeIdCustomer($id_office, $id_customer)
    {
    	return $this->ByIdOffice($id_office)->where('id_customer', '=', $id_customer);
    }

    public function show($id_office, $id_customer)
    {
        return $this->where('id_office', '=', $id_office)
        ->where('id_customer', '=', $id_customer)
        ->get()
        ->first();
    }

    public function indexBillings ($office_id, $year1, $year2)
    {
        $itemsYear1 = DB::table('billings AS BIL')
                ->join('clients AS CLI', function ($join)
                    {
                        $join->on('CLI.id', '=', 'BIL.client_id');
                    })
                ->where('CLI.office_id', '=', $office_id)
                ->where('BIL.year',$year1)
                ->selectRaw("BIL.client_id, BIL.year, sum(BIL.billing) as amount")
                ->groupBy('BIL.client_id', 'BIL.year')
                ->get();

        $itemsYear2 = DB::table('billings AS BIL')
                ->join('clients AS CLI', function ($join)
                    {
                        $join->on('CLI.id', '=', 'BIL.client_id');
                    })
                ->where('CLI.office_id', '=', $office_id)
                ->where('BIL.year',$year2)
                ->selectRaw("BIL.client_id, BIL.year, sum(BIL.billing) as amount")
                ->groupBy('BIL.client_id', 'BIL.year')
                ->get();

        $amountYear2 = DB::table('billings AS BIL')
                ->join('clients AS CLI', function ($join)
                    {
                        $join->on('CLI.id', '=', 'BIL.client_id');
                    })
                ->where('CLI.office_id', '=', $office_id)
                ->where('BIL.year',$year2)
                ->selectRaw("BIL.year, sum(BIL.billing) as amount")
                ->groupBy('BIL.year')
                ->first();

        $year1 = [];

        foreach ($itemsYear1 as $item)
        {
            $year1[$item->client_id] = $item->amount;
        }

        $year2 = [];

        foreach ($itemsYear2 as $item)
        {
            $year2[$item->client_id] = $item->amount;
        }

        $totalYear2 = 0.00;

        if ($amountYear2) $totalYear2 = $amountYear2->amount;


        $clients = Office::find($office_id)->clients()->get();

        $abc_office = 0.0;

        foreach ($clients as $client)
        {
            $client->year1 = 0.0;
            $client->year2 = 0.0;

            if (isset($year1[$client->id])) $client->year1 = $year1[$client->id];
            if (isset($year2[$client->id])) $client->year2 = $year2[$client->id];

            $client->abc_client = ($amountYear2)? $client->year2/$totalYear2*100 : 0;
            $abc_office        += $client->abc_client;
            $client->abc_office = $abc_office;

        }

        return $clients;
    }

    public function getClientOrCreate($office, $data)
    {
        # Validamos si el cliente existe ...
        $client = $office->clients()->where('code', $data['cliente'])->first();

        if (!$client)
        {
            $client = Client::create(
                [
                    'code'      => $data['cliente'], 
                    'name'      => $data['nombre'],
                    'office_id' => $office->id,
                ]);
        }

        return $client;
    }

    public function deleteByOfficeByYear ($office_id, $year)
    {
        # Delete all clients >= @year
        $items = DB::table('clients AS CLI')
        ->join('offices AS OFI', function ($join)
            {
                $join->on('OFI.id', '=', 'CLI.office_id'); 
        })
        ->where('OFI.id', '=', $office_id)
        ->where('CLI.year', '>=', $year)
        ->select('CLI.*')
        ->delete();

        return $items;
    }

    public function getMonthlyBillings ($client_id, $year)
    {
        $items = DB::table('billings AS BIL')
                ->join('clients AS CLI', function ($join)
                    {
                        $join->on('CLI.id', '=', 'BIL.client_id');
                    })
                ->where('CLI.id', '=', $client_id)
                ->where('BIL.year',$year)
                ->selectRaw("BIL.client_id, BIL.year, BIL.month, sum(BIL.billing) as amount")
                ->groupBy('BIL.client_id', 'BIL.year', 'BIL.month')
                ->get();

        return $items;
    }

    public function getMonthlyBillingsByOffice ($office_id, $year)
    {
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
                ->where('BIL.year',$year)
                ->selectRaw("BIL.client_id, BIL.year, BIL.month, sum(BIL.billing) as amount")
                ->groupBy('BIL.client_id', 'BIL.year', 'BIL.month')
                ->get();

        return $items;
    }

    public function getAmmountAnnualByIdClientByYear ($id, $year)
    {
        $rs = Billing::selectRaw('client_id, year, sum(billing) as amount')
        ->groupBy('client_id', 'year')
        ->having('client_id', '=', $id)
        ->having('year', '=', $year)
        ->orderBy('year', 'asc')
        ->first();

        return $rs? $rs->amount:0.0;
    }



}
