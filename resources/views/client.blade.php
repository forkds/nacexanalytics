@extends('layout.layout')

@section('content') 

    <div class="row">
        <div class="col-sm-12">
        {{ Form::open(array('id' => 'form', 'url' => '/clientes/detalle', 'class' => ' form-inline')) }}

            <input id="field" type="hidden" name="action" value="">

            <input  type="hidden" name="current_id" value="{{ $id }}">

            <div class="form-group col-sm-6" style="padding-left:0">
                {{ Form::select('id', $select, 1956, ['class' => 'form-control select2 mx-sm-8'])   }}

            </div>

            <button class="btn btn-primary" 
                    style="margin-left:5px;"
                    onclick="
                    getElementById('field').value='GET';
                    getElementById('form').submit();
                    ">{{ trans('nacex-analytics.CLIENT_SELECT_BTN') }}
            </button>                               

            <button class="btn btn-primary" 
                    style="margin-left:5px;"
                    onclick="
                    $('select').val('{{ $prev_id }}').trigger('change');
                    getElementById('field').value='PRE';
                    getElementById('form').submit();
                    ">
                <i class="fa fa-reply"></i>
            </button>                               

            <button class="btn btn-primary" 
                    style="margin-left:5px;"
                    onclick="
                    $('select').val('{{ $post_id }}').trigger('change');
                    getElementById('field').value='POST';
                    getElementById('form').submit();
                    ">
                <i class="fa fa-share"></i>
            </button>                               


        {{ Form::close() }}


        </div>
    </div>

    <hr>

@for ($index = 0; $index < count($arrayGraph); $index++)

@if ($arrayGraph[$index][1]['items']->isNotEmpty())

    <div class="row">
        <div class="col-sm-8">
            <div class="card-box">
                <h4 class="m-t-0 m-b-20 header-title"><b>{{ $arrayGraph[$index][0]['label'] }}</b></h4>

                <div class="line-chart{{ $index }}">
                    <svg style="height:300px;width:100%"></svg>
                </div>
            </div>
        </div>

        <div class="col-sm-4">
            <div class="card-box">
                <h4 class="m-t-0 m-b-20 header-title"><b>{{ $arrayGraph[$index][1]['label'] }}</b></h4>

                <div class="bar-chart{{ $index }}">
                    <svg style="height:300px;width:100%"></svg>
                </div>
            </div>
        </div>
    </div>

@endif

@endfor



@endsection

@section('title')
	Cliente
@endsection

@section('css')
        <link href="{{ asset('assets/plugins/nvd3/build/nv.d3.min.css') }}" rel="stylesheet" type="text/css" />
        <link href="{{ asset('assets/plugins/select2/css/select2.min.css') }}" rel="stylesheet" type="text/css" />
@endsection




@section('scripts')

        <!-- Nvd3 js -->
        <script src="{{ asset('assets/plugins/d3/d3.min.js') }}"></script>
        <script src="{{ asset('assets/plugins/nvd3/build/nv.d3.min.js') }}"></script>
        <script src="{{ asset('assets/plugins/select2/js/select2.min.js') }}" type="text/javascript"></script>



<script type="text/javascript">



(function($) {
    'use strict';
    
    // Select2
    $(".select2").select2();

    var cnt;

    for (cnt = 0; cnt < 3; cnt++) 
    {
        (function(index) 
        {

            nv.addGraph(function()
            {
                var chartId = '.line-chart'  + index + ' svg';
                var lineChart = nv.models.lineChart();
                var height = 300;

                lineChart.useInteractiveGuideline(true);
                lineChart.xAxis.tickFormat(d3.format(',0d'));
                lineChart.yAxis.tickFormat(d3.format(',.0f'));
                lineChart.yAxis.ticks(5);
                lineChart.forceY([0, getMonthlyScale(index)]);

                var days = ["Ene", "Feb", "Mar", "Abr", "May", "Jun","Jul", "Ago", "Sep", "Oct", "Nov", "Dic"];

                lineChart.xAxis
                    .tickValues([0, 1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11])
                    .tickFormat(function(d){
                    return days[d]
                    });

                d3.select(chartId).attr('perserveAspectRatio', 'xMinYMid').datum(getMonthlyGraphData(index)).transition().duration(500).call(lineChart);
                
                nv.utils.windowResize(lineChart.update);
            
                lineChart.update;

                return lineChart;
            });

            nv.addGraph(function() 
            {
                var chartId = '.bar-chart'  + index + ' svg';

                var barChart = nv.models.discreteBarChart().x(function(d) {
                    return d.label;
                }).y(function(d) {
                    return d.value;
                }).staggerLabels(true).tooltips(true).showValues(false).duration(250);
                barChart.xAxis.tickFormat(d3.format('.0d'));
                barChart.yAxis.tickFormat(d3.format(',.0f'));
                barChart.yAxis.ticks(5);
                barChart.forceY([0, getAnnualScale(index)]);
                //barChart.showYTicks(false);
                d3.select(chartId).datum(getAnnualGraphData(index)).call(barChart);

                nv.utils.windowResize(barChart.update);

                barChart.update;

                return barChart;
            });


        })(cnt);
    }

    function getAnnualGraphData (index)
    {
        var arrayPHP = [];

        arrayPHP = {!! json_encode($arrayGraph) !!};

        var arrayValuesIn  = arrayPHP[index][1]['graph']['values'];
        
        var arrayValuesOut = [];

        for(var i=0; i<arrayValuesIn.length; i++)
        {
            var label = arrayValuesIn[i]['label'];
            var value = parseFloat(arrayValuesIn[i]['value']);
            var color = arrayValuesIn[i]['color'];

            arrayValuesOut.push({label:label, value:value, color:color});
        }

        var arrayJS = [];

        arrayJS.push ({key:arrayPHP['key'], values:arrayValuesOut});

        return arrayJS;
    }

    function getMonthlyGraphData (index)
    {

        var arrayPHP  = [];

        var arrayJSON = [];

        arrayJSON = {!! json_encode($arrayGraph) !!};

        arrayPHP = arrayJSON[index][0]['graph'];

        var arrayJS = [];

        for(var i=0; i<arrayPHP.length; i++)
        {
            var arrayValores = [];

            arrayValores = arrayPHP[i]['values'];

            var ArraySalida = [];

            for(var j=0; j<arrayValores.length; j++)
            {
                var ArrayPar = [];

                var valorX = arrayValores[j]['x'];
                var valorY = parseFloat(arrayValores[j]['y']);

                ArraySalida.push({x:valorX, y:valorY});
            }

            arrayJS.push({values: ArraySalida, key:arrayPHP[i]['key'], color:arrayPHP[i]['color'], strokeWidth: 3.0});

        }

        return arrayJS;
    }

    function getAnnualScale (index)
    {
        var arrayJSON = {!! json_encode($arrayGraph) !!};
        var scale     = arrayJSON[index][1]['scale'];
        
        return parseFloat (scale);
    }

    function getMonthlyScale (index)
    {
        var arrayJSON = {!! json_encode($arrayGraph) !!};
        var scale     = arrayJSON[index][0]['scale'];
        
        return parseFloat (scale);
    }



})(jQuery);        

</script>

@endsection
