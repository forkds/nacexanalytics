@extends('layout.layout')

@section('content')

	<div class="axf">
    	
    	<p>This is my body content: {{ $id }}</p>

        <p>Registros: {{ $count }}</p>

        <p>Cookie: {{ Cookie::get('timeOut')}} </p>

        <p>Cookie: {{ $cookie }} </p>

@if ($data->isNotEmpty())

        <table>

@foreach ($data as $reg)
            
            <tr>
                <td>{{ $reg->id_office }}</td>
                <td>{{ $reg->id_customer }}</td>
                <td>{{ $reg->year }}</td>
                <td>{{ $reg->month }}</td>
                <td>{{ $reg->sum }}</td>
            </tr>

@endforeach

        </table>

@else  

            <p> No data </p>

@endif 

	
	</div>


    <div class="row">
        <div class="col-sm-6">
            <div class="card-box">
                <h4 class="m-t-0 m-b-20 header-title"><b>Line Chart</b></h4>

                <div class="line-chart">
                    <svg style="height:400px;width:100%"></svg>
                </div>
            </div>
        </div>
        <div class="col-sm-6">
            <div class="card-box">
                <h4 class="m-t-0 m-b-20 header-title"><b>Line Chart</b></h4>

                <div class="line-chart">
                    <svg style="height:400px;width:100%"></svg>
                </div>
            </div>
        </div>
    </div>


@endsection

@section('title')
	Títol
@endsection

@section('css')
        <link href="{{ asset('assets/plugins/nvd3/build/nv.d3.min.css') }}" rel="stylesheet" type="text/css" />
@endsection




@section('scripts')

        <!-- Nvd3 js -->
        <script src="{{ asset('assets/plugins/d3/d3.min.js') }}"></script>
        <script src="{{ asset('assets/plugins/nvd3/build/nv.d3.min.js') }}"></script>


<script type="text/javascript">


(function($) {
    'use strict';
    
    nv.addGraph(function() {
        var lineChart = nv.models.lineChart();
        var height = 300;
        lineChart.useInteractiveGuideline(true);
        lineChart.xAxis.tickFormat(d3.format(',r'));""
        lineChart.yAxis.axisLabel('Facturación (Euros)').tickFormat(d3.format(',.2f'));

        var days = ["Ene", "Feb", "Mar", "Abr", "May", "Jun","Jul", "Ago", "Sep", "Oct", "Nov", "Dic"];

        lineChart.xAxis
            .tickValues([0, 1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11])
            .tickFormat(function(d){
            return days[d]
            });

        d3.select('.line-chart svg').attr('perserveAspectRatio', 'xMinYMid').datum(graphData()).transition().duration(500).call(lineChart);
        nv.utils.windowResize(lineChart.update);
        return lineChart;
    });

    //Pie chart example data. Note how there is only a single array of key-value pairs.
    function graphData() {

        return {!! json_encode($graph) !!};

    }

})(jQuery);        

</script>

@endsection
