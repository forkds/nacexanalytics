<?php

namespace App\Http\Controllers;
use App\Imports\Imports;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;
use \App\Client;
use \App\Office;
use \App\Billing;
use \App\Import;

class ImportController extends Controller
{
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
		$params['pageTitle'] = trans('import.PAGE_TITLE');
		$params['msgPreSubmit'] = trans('import.MSG_PRE_SUBMIT');
		$params['btn_help'] = trans('import.LABEL_BTN_HELP');

        return view('import')->with($params);
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
			'year.required'  => trans('import.MSG_ERR_YEAR_REQUIRED'),
			'year.digits'    => trans('import.MSG_ERR_YEAR_DIGITS'),
			'year.in'        => trans('import.MSG_ERR_YEAR_IN'),
			'excel.required' => trans('import.MSG_ERR_EXCEL_REQUIRED'),
			'excel.max'      => trans('import.MSG_ERR_EXCEL_MAX'),
			'file_ext.in'    => trans('import.MSG_ERR_FILE_EXT_IN'),
			'file_mime.in'   => trans('import.MSG_ERR_FILE_MIME_IN'),
			'file_upload.required' => trans('import.MSG_ERR_FILE_UPLOAD_REQUIRED'),
		];

		$arrayValidations = [];
		$arrayValidations['year']  = 'required|digits:4|in:2015,2016,2017,2018,2019';
		$arrayValidations['excel'] = 'required|max:200';
		$this->validate($data, $arrayValidations, $arrayErrors);

		//$arrayValidations['file_upload'] = 'required';
		//$this->validate($data, $arrayValidations, $arrayErrors);

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

        $import = new Import;

        $errors = $import->validation($rows);

        if ($errors)
       	{
			$alerts[] = [
				'type'  => 'alert-danger', 
				'msg'   => trans('import.MSG_IMPORT_ERR'),
				'lines' => $errors,
			];
	        
	        return $this->edit()->with('alerts', $alerts);
    	}

    	$office = Office::find($request->office_id);
    	$year   = $request->year;
    	$bath   = 250;

    	$modelBilling = new \App\Billing;
		$modelBilling->insertImportFile ($office, $year, $rows, $bath);

		$alerts[] = [
			'type'  => 'alert-success', 
			'msg'   => trans('import.MSG_IMPORT_OK'), 
			'lines' => [ 
				trans('import.MSG_LINE_OK_NAME', ['name' => $request->file('excel')->getClientOriginalName()]),
				trans('import.MSG_LINE_OK_YEAR', ['year' => $year]),
				trans('import.MSG_LINE_OK_ROWS', ['rows' => count($rows)]),
			]
		];

        return $this->edit()->with('alerts', $alerts);

    }    
}
