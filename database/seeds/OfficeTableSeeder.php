<?php

use Illuminate\Database\Seeder;
use App\Office;
use App\Client;

class OfficeTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $objClient = new Client();

        $office_0000 = new Office();
        $office_0000->code = '0000';
        $office_0000->name = 'Oficina defecto';
        $office_0000->save();

	    $office_0856 = new Office();
	    $office_0856->code = '0856';
	    $office_0856->name = 'Oficina 0856';
        $office_0856->save();

        $office_0859 = new Office();
        $office_0859->code = '0859';
        $office_0859->name = 'Oficina 0859';
        $office_0859->save();
	    $office_4622 = new Office();

	    $office_4622->code = '4622';
	    $office_4622->name = 'Oficina 4622';
        $office_4622->save();
    }
}
