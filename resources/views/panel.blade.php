@extends('layout.layout')

@section('content')

    <div class="row">
        <div class="col-sm-8">
            <div class="card-box">
                <h4 class="m-t-0 m-b-20 header-title"><b>{{ $monthlyGraphLabel }}</b></h4>

                <div class="line-chart">
                    <svg style="height:300px;width:100%"></svg>
                </div>
            </div>
        </div>

        <div class="col-sm-4">
            <div class="card-box">
                <h4 class="m-t-0 m-b-20 header-title"><b>{{ $annualGraphLabel }}</b></h4>

                <div class="bar-chart">
                    <svg style="height:300px;width:100%"></svg>
                </div>
            </div>
        </div>
    </div>

@endsection

@section('title')
	Panel
@endsection

@section('title')
	Panel
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

    // Select2
    $(".open-left").click();


    nv.addGraph(function() {
        var lineChart = nv.models.lineChart();
        var height = 300;
        lineChart.useInteractiveGuideline(true);
        lineChart.xAxis.tickFormat(d3.format(',0d'));
        lineChart.yAxis.tickFormat(d3.format(',.0f'));
        lineChart.yAxis.ticks(5);
        lineChart.forceY([graphMinValue(), graphMaxValue()]);

        var days = ["Ene", "Feb", "Mar", "Abr", "May", "Jun","Jul", "Ago", "Sep", "Oct", "Nov", "Dic"];

        lineChart.xAxis
            .tickValues([0, 1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11])
            .tickFormat(function(d){
            return days[d]
            });

        //lineChart.yDomain([0, max]);

        d3.select('.line-chart svg').attr('perserveAspectRatio', 'xMinYMid').datum(graphData()).transition().duration(500).call(lineChart);
        nv.utils.windowResize(lineChart.update);
        return lineChart;
    });

    nv.addGraph(function() {
        var barChart = nv.models.discreteBarChart().x(function(d) {
            return d.label;
        }).y(function(d) {
            return d.value;
        }).staggerLabels(true).tooltips(true).showValues(false).duration(250);
        barChart.xAxis.tickFormat(d3.format('.0d'));
        barChart.yAxis.tickFormat(d3.format(',.0f'));
        barChart.yAxis.ticks(5);
        barChart.forceY([graphMinValue(), graphAnnualMaxValue()]);
        //barChart.showYTicks(false);
        d3.select('.bar-chart svg').datum(annualGraphData()).call(barChart);
        nv.utils.windowResize(barChart.update);
        return barChart;
    });

    function annualGraphData() 
    {
        var arrayPHP = [];

        arrayPHP = {!! json_encode($annualGraph) !!};

        var arrayValuesIn  = arrayPHP['values'];
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

    function graphData() {

        var arrayPHP = [];

        arrayPHP = {!! json_encode($monthlyGraph) !!};

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

    function graphAnnualMaxValue() 
    {
        var maxValue = 0.0;


        var arrayPHP = [];
        var arrayJS  = [];

        arrayPHP = {!! json_encode($annualGraph) !!};

    	arrayJS  = arrayPHP['values'];


        for(var i=0; i<arrayJS.length; i++)
        {
        	var value = parseFloat(arrayJS[i]['value']);

    		if (value > maxValue)
    		{
    			maxValue = value;
    		}
        }

        return getMaxScale (maxValue);
    }

    function getMaxScale(value)
    {
    	var maxValue = value;

        var scale = 1;

        for (var m=1; m<10; m++)
        {
            scale*=10;

            if (scale > maxValue)
            {
                scale/=10;
                break;
            }
        }

        var testValue = scale;

        for (var x=1; x<10; x++)
        {
            if (testValue > maxValue)
            {
                maxValue = testValue;
                break;
            }

            testValue+= scale;
        }

        return maxValue;
    }


    function graphMaxValue(optionGraph) {

        var maxValue = 0.0;

        var arrayPHP = [];

        arrayPHP = {!! json_encode($monthlyGraph) !!};

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

                if (valorY > maxValue)
                {
                    maxValue = valorY;
                }
            }

        }

        var scale = 1;

        for (var m=1; m<10; m++)
        {
            scale*=10;

            if (scale > maxValue)
            {
                scale/=10;
                break;
            }
        }

        var testValue = scale;

        for (var x=1; x<10; x++)
        {
            if (testValue > maxValue)
            {
                maxValue = testValue;
                break;
            }

            testValue+= scale;
        }

        return maxValue;
    }

    function graphMinValue() {

        var minValue = 0.0;

        var arrayPHP = [];

        arrayPHP = {!! json_encode($monthlyGraph) !!};

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

                if (valorY < minValue)
                {
                    minValue = valorY;
                }
            }

        }

        var scale = 1;

        for (var m=1; m<6; m++)
        {
            scale*=10;

            if (scale > minValue)
            {
                scale/=10;
                break;
            }
        }

        var testValue = scale;

        for (var x=1; x<15; x++)
        {
            if (testValue > minValue)
            {
                minValue = testValue-scale;
                break;
            }

            testValue+= scale;
        }

        return minValue;
    }

})(jQuery);        

</script>

@endsection
