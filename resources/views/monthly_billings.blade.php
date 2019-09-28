@extends('layout.layout')

@section('content')

	<form id="hidden-form" action="{{ action('ClientController@postShow') }}" method="POST" style="display:none;">
		{{ csrf_field() }}
		<input id="hidden-field" type="hidden" name="id" value="0">
		alex
	</form>			

    <div class="row">

        <div class="col-md-12 col-md-offset-2">

            <div class="card-box table-responsive">
		
		        <table id="datatable" class="table table-bordered table-responsive">

		            <thead>
		                <tr>
		                    <th>Cliente</th>
                            <th>&nbsp;</th>
                            <th>Ene</th>
                            <th>Feb</th>
                            <th>Mar</th>
                            <th>Abr</th>
                            <th>May</th>
                            <th>Jun</th>
                            <th>Jul</th>
                            <th>Ago</th>
                            <th>Sep</th>
                            <th>Oct</th>
                            <th>Nov</th>
                            <th>Dic</th>
		                    <th>Acción</th>
		                </tr>
		            </thead>


		            <tbody>

@foreach ($items as $item)

						<tr>
							<td>{{ $item->code }}-{{ $item->name }}</td>
                            <td>
                                <span class="legend" data-toggle="tooltip" data-placement="top" title="Facturación / Mes">€/Mes</span><br>
                                <span class="legend" data-toggle="tooltip" data-placement="top" title="Días trabajados">Lab/m</span><br>
                                <span class="legend" data-toggle="tooltip" data-placement="top" title="Facturación / Día">€/Día</span><br>
                                <hr>
                                <span class="legend" data-toggle="tooltip" data-placement="top" title="Facturación / Mes (año anterior)">€/Mes</span><br>
                                <span class="legend" data-toggle="tooltip" data-placement="top" title="Días trabajados (año anterior)">Lab/m</span><br>
                                <span class="legend" data-toggle="tooltip" data-placement="top" title="Facturación / Día (año anterior)">€/Día</span><br>
                                <hr>
                                <span class="legend" data-toggle="tooltip" data-placement="top" title="Diferencia interanual">Dif</span><br>
                            </td>


<?php for ($x=1; $x <= 12; $x++) : ?>

<?php

    $style1= $data[$item->id][$x]['bil_month_0'] >= $data[$item->id][$x]['bil_month_1'] ? '' : 'color:red;';
    $style2= $data[$item->id][$x]['bil_day_ratio'] >= 0.0 ? '' : 'color:red;';

?>

                            <td style="text-align:right;">
                                <span style="{{ $style1 }}">{{ $data[$item->id][$x]['bil_month_0'] }}</span><br>
                                {{ $data[$item->id][$x]['lab_days_0'] }}<br>
                                {{ $data[$item->id][$x]['bil_day_0'] }}<br>
                                <hr>
                                {{ $data[$item->id][$x]['bil_month_1'] }}<br>
                                {{ $data[$item->id][$x]['lab_days_1'] }}<br>
                                {{ $data[$item->id][$x]['bil_day_1'] }}<br>
                                <hr>
                                <span style="{{ $style2 }}">{{ $data[$item->id][$x]['bil_day_ratio'] }}%</span><br>
                            </td>

<?php endfor; ?>
                            <td>&nbsp;</td>
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
        <link href="{{ asset('assets/plugins/datatables/responsive.bootstrap4.min.css') }}" rel="stylesheet" type="text/css" />
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

        <script type="text/javascript">
            $(document).ready(function() {

                axfInitDataTables();

            } );

        </script>

@endsection
