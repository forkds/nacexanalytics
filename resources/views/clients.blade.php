@extends('layout.layout')

@section('content')

	<form id="hidden-form" action="{{ action('ClientController@postShow') }}" method="POST" style="display:none;">
		{{ csrf_field() }}
		<input id="hidden-field" type="hidden" name="id" value="0">
		alex
	</form>		

@php


@endphp	

    <div class="row">

        <div class="col-md-12 col-md-offset-2">

            <div class="card-box table-responsive">

		        <table id="datatable" class="table table-bordered stripe table-stripe" style="width:100%;">

<?php

$th_style      = "background-color:#D0D0D0;";
$th_style_year = $th_style . "text-align:right;";

?>
		            <thead>
		                <tr>
		                    <th style="{{ $th_style }}">Código</th>
		                    <th style="{{ $th_style }}">Nombre</th>
                            <th style="{{ $th_style }}">Creado</th>
                            <th style="{{ $th_style_year }}">Fact. {{ $year1 }}</th>
                            <th style="{{ $th_style_year }}">Fact. {{ $year2 }}</th>
                            <th style="{{ $th_style_year }}">% ABC {{ $year2 }}</th>
                            <th style="{{ $th_style_year }}">Fact. Acum. {{ $year2 }}</th>
                            <th style="{{ $th_style_year }}">Fact. Acum. {{ $year3 }}</th>
                            <th style="{{ $th_style_year }}">% ABC {{ $year3 }}</th>
		                    <th style="{{ $th_style }}">&nbsp;</th>
		                </tr>
		            </thead>


		            <tbody>

@foreach ($items as $item)

<?php
$style1 = "padding:6px;text-align:right;";
$style2 = "padding:6px;text-align:right;";

$style11 = "padding:6px;text-align:right;";
$style12 = "padding:6px;text-align:right;";

$value1 = number_format($item->year1, 2, '.', ',');
$value2 = number_format($item->year2, 2, '.', ',');
$value3 = number_format($item->year3, 2, '.', ',');
$valuem = number_format($item->month, 2, '.', ',');

$abc_client = number_format($item->abc_client, 2) . "%";
$abc_office = number_format($item->abc_office, 2) . "%";

$abc_client2 = number_format($item->abc_client2, 2) . "%";
$abc_office2 = number_format($item->abc_office2, 2) . "%";

if ((float)$item->year1 > (float)$item->year2)
{
    $style2.= "color:red;";
}

if ((float)$item->month > (float)$item->year3)
{
    $style12.= "color:red;";
}

?>
				
						<tr>
							<td style="padding:6px;">{{ $item->code }}</td>
							<td style="padding:6px;">{{ $item->name }}</td>
                            <td style="padding:6px;">{{ $item->year }}</td>
                            <td style="<?php echo($style1); ?>">{{ $value1 }}</td>
                            <td style="<?php echo($style2); ?>">{{ $value2 }}</td>
                            <td style="<?php echo($style2); ?>">{{ $abc_client }}</td>

                            <td style="<?php echo($style11); ?>">{{ $valuem }}</td>
                            <td style="<?php echo($style12); ?>">{{ $value3 }}</td>
                            <td style="<?php echo($style12); ?>">{{ $abc_client2 }}</td>

							<td style="padding:6px;">
								<button class="btn btn-sm btn-primary" 
										onclick="
										getElementById('hidden-field').value={{ $item->id }};
										getElementById('hidden-form').submit();
										">
									<i class="fa fa-search"></i>
								</button>								
							</td>
						</tr>
@endforeach
					</tbody>

				</table>

	        </div>

        </div>

    </div>
@endsection

@section('header_user')
    {{ \Auth::user()->name }}
@endsection

@section('header_office')
    {{ \Auth::user()->getActiveOffice()->code }}
@endsection

@section('css')
        <!-- DataTables -->
        <link href="{{ asset('assets/plugins/datatables/dataTables.bootstrap4.min.css') }}" rel="stylesheet" type="text/css" />
        <link href="{{ asset('assets/plugins/datatables/buttons.bootstrap4.min.css') }}" rel="stylesheet" type="text/css" />
        <!-- Responsive datatable examples -->
        <link href="https://cdn.datatables.net/1.10.20/css/jquery.dataTables.min.css" rel="stylesheet" type="text/css" />
        <!-- Fixed Header datatable -->
        <link href="https://cdn.datatables.net/fixedheader/3.1.6/css/fixedHeader.dataTables.min.css" rel="stylesheet" type="text/css" />
        <!-- Responsive datatable examples -->
        <link href="{{ asset('assets/plugins/datatables/responsive.bootstrap4.min.css') }}" rel="stylesheet" type="text/css" />
        <!-- Multi Item Selection examples -->
        <link href="{{ asset('assets/plugins/datatables/select.bootstrap4.min.css') }}" rel="stylesheet" type="text/css" />

@endsection


@section('scripts')
        <!-- Required datatable js -->
        <script src="{{ asset('assets/plugins/datatables/jquery.dataTables.min.js') }}"></script>
        <script src="{{ asset('assets/plugins/datatables/dataTables.bootstrap4.min.js') }}"></script>
        <!-- Buttons examples -->
        <script src="{{ asset('assets/plugins/datatables/dataTables.buttons.min.js') }}"></script>
        <script src="{{ asset('assets/plugins/datatables/buttons.bootstrap4.min.js') }}"></script>
        <script src="{{ asset('assets/plugins/datatables/jszip.min.js') }}"></script>
        <script src="{{ asset('assets/plugins/datatables/pdfmake.min.js') }}"></script>
        <script src="{{ asset('assets/plugins/datatables/vfs_fonts.js') }}"></script>
        <script src="{{ asset('assets/plugins/datatables/buttons.html5.min.js') }}"></script>
        <script src="{{ asset('assets/plugins/datatables/buttons.print.min.js') }}"></script>
        <script src="{{ asset('assets/pages/jquery.init_data_tables.js') }}"></script>
        <script src="https://cdn.datatables.net/fixedheader/3.1.6/js/dataTables.fixedHeader.min.js"></script>



        <script type="text/javascript">
            $(document).ready(function() {

                axfInitDataTables();

                
//                $('#datatable').DataTable( {
//
//                    fixedHeader: {
//                        header: true,
//                        headerOffset: 60
//                    },
//                    paging: false,
//                } );                

                $('tr').on ('click', function(e) {

                    e.preventDefault();

                    $(this).find('button').click();
                    //alert('click');

                });

            } );

        </script>

@endsection
