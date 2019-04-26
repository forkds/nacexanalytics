<?php

namespace App\Http\Controllers;

use App\Imports\Imports;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;
use \App\Client;
use \App\Office;
use \App\Billing;
use \App\ImportExpress;
use \App\Lib;

class ImportExpressController extends Controller
{
    //
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('office');
        $this->middleware('role:manager|admin');
    }

    //
	public function edit()
	{
		$params = [];

		# Get page title
		$params['office_id'] = \Auth::user()->getActiveOffice()->id;
		$params['pageTitle'] = trans('import_express.PAGE_TITLE');
		$params['msgPreSubmit'] = trans('import_express.MSG_PRE_SUBMIT');
		$params['btn_help'] = trans('import_express.LABEL_BTN_HELP');

        return view('import_express')->with($params);
	}

	public function upload(Request $request)
	{
		$data = clone $request;

		$has_file     = $data->hasFile('excel');
		$file_upload  = $has_file ? $data->file('excel')->isValid() : 0;
		$file_ext     = $file_upload ? $data->file('excel')->getClientOriginalExtension() : 0;
		$file_mime    = $file_upload ? $data->file('excel')->getMimeType() : 0;

		$data->request->add(['file_ext'  => $file_ext]);
		$data->request->add(['file_mime' => $file_mime]);

		$arrayErrors = [
			'excel.required' => trans('import_express.MSG_ERR_EXCEL_REQUIRED'),
			'excel.max'      => trans('import_express.MSG_ERR_EXCEL_MAX'),
			'file_ext.in'    => trans('import_express.MSG_ERR_FILE_EXT_IN'),
			'file_mime.in'   => trans('import_express.MSG_ERR_FILE_MIME_IN'),
			'file_upload.required' => trans('import_express.MSG_ERR_FILE_UPLOAD_REQUIRED'),
		];

		$arrayValidations = [];
		$arrayValidations['excel'] = 'required|max:200';
		$this->validate($data, $arrayValidations, $arrayErrors);

		$arrayValidations['file_ext'] = 'bail|required|in:xls';
		$this->validate($data, $arrayValidations, $arrayErrors);

		$arrayValidations['file_mime'] = 'required|in:application/vnd.ms-office';
		$this->validate($data, $arrayValidations, $arrayErrors);

		return $this->import ($data);
	}

	public function import($request) 
    {
        set_time_limit ( 500 );

		$alerts = [];


        try {
            $excel = Excel::toArray(new Imports, $request->file('excel'));
        } catch (\Maatwebsite\Excel\Validators\ValidationException $e) {
            $failures = $e->failures();
	        $params['import_errors'] = $failures;
            return $this->edit()->with($params);
        } 

        $rows = $excel[0];

        $import = new ImportExpress;

        $errors = $import->validation($rows);

        if ($errors)
       	{
			$alerts[] = [
				'type'  => 'alert-danger', 
				'msg'   => trans('import_express.MSG_IMPORT_ERR'),
				'lines' => $errors,
			];
	        
	        return $this->edit()->with('alerts', $alerts);
    	}

    	$office = Office::findOrFail($request->office_id);
    	$year   = $request->year;
    	$batch  = 250;

    	# Get first row
    	$row = $rows[0];

        # Convert Excel Date to Unixstamp
        # $unixstamp = ( intval($row['fecha']) - 25569) * 86400;
        $unixstamp = Lib::ExcelDateToUnixtimestamp ($row['fecha']);

        # Get Year & Month from Unixstamp
        $year  = date ('Y', intval($unixstamp));
        $month = date ('m', intval($unixstamp));

    	$modelExpress = new \App\Express ($office->id);
		$modelExpress->insertImportFile ($year, $month, $rows, $batch);

		$alerts[] = [
			'type'  => 'alert-success', 
			'msg'   => trans('import_express.MSG_IMPORT_OK'), 
			'lines' => [ 
				trans('import_express.MSG_LINE_OK_NAME', ['name' => $request->file('excel')->getClientOriginalName()]),
				trans('import_express.MSG_LINE_OK_YEAR',  ['year'  => $year]),
				trans('import_express.MSG_LINE_OK_MONTH', ['month' => $month]),
				trans('import_express.MSG_LINE_OK_ROWS',  ['rows'  => count($rows)]),
			]
		];

        return $this->edit()->with('alerts', $alerts);

    }    

}
