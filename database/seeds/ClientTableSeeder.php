<?php

use Illuminate\Database\Seeder;
use App\Client;
use App\Office;

class ClientTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $office = Office::find(1);

    	for ($x=1; $x<=10; $x++)
    	{
		    $client = new Client;
		    $client->code = sprintf("%05d", $x);
		    $client->name = 'Client ' . $client->code;
            $client->year = 2015;
            $client->office_id = $office->id;
            $client->save();
		    //$office->clients()->associate($office)->save();    		
    	}
    }
}
