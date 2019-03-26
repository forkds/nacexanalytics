<?php

use Illuminate\Database\Seeder;
use App\Billing;
use App\Client;

class BillingTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

    	$clients = array (1, 2, 3);

    	$billings = array (
    		array(15.23,23.34,38.45,5.76,87.23,45.33,67.23,42.56, 67.54, 34.88, 98.12, 67.43, 78.67),
    		array(19.23,21.21,38.55,55.66,77.23,67.33,13.23,0.56, 45.54, 54.88, 67.12, 86.43, 101.67),
    		array(33.23,31.21,18.55,45.66,57.23,97.33,113.23,10.56, 56.54, 76.88, 88.12, 23.43, 96.67)
    	);

    	$years = array (2016, 2017, 2018);

		for ($CntC = 1; $CntC <= count($clients); $CntC++)
		{
			for ($Cnt = 1; $Cnt <= count($years); $Cnt++)
			{
		    	for ($x=1; $x<=12; $x++)
		    	{    		
				    $billing = new Billing();
				    $billing->year = $years[$Cnt-1];
				    $billing->month = $x;
				    $billing->billing = $billings[$Cnt-1][$x];
				    $billing->client_id = $clients[$CntC-1];
				    $billing->save();
		    	}			
			}    				
		}












         
    }
}
