use Illuminate\Support\Facades\Input;

@extends('layout.layout')

@section('content')

{{ Form::open(array('url' => 'photos')) }}

    <div class="form-group">
        {{ Form::label('name', 'Name') }}
        {{ Form::text('name', 'name', array('class' => 'form-control')) }}
    </div>

    <div class="form-group">
        {{ Form::label('email', 'Email') }}
        {{ Form::email('email', 'email', array('class' => 'form-control')) }}

        @if ($errors->has('email'))
            <span class="help-block">
                <strong>{{ $errors->first('email') }}</strong>
            </span>
        @endif

    </div>

    <div class="form-group">
        {{ Form::label('nerd_level', 'Nerd Level') }}
        {{ Form::select('nerd_level', array('0' => 'Select a Level', '1' => 'Sees Sunlight', '2' => 'Foosball Fanatic', '3' => 'Basement Dweller'), 'nerd_level', array('class' => 'form-control')) }}
    </div>

    {{ Form::submit('Create the Nerd!', array('class' => 'btn btn-primary')) }}

{{ Form::close() }}

@endsection

@section('title')
    Títol
@endsection

@section('css')
        <link href="{{ asset('assets/plugins/switchery/switchery.min.css') }}" rel="stylesheet" />
        <link href="{{ asset('assets/plugins/jquery-circliful/css/jquery.circliful.css') }}" rel="stylesheet" type="text/css" />
        <link href="{{ asset('assets/css/bootstrap.min.css') }}" rel="stylesheet" type="text/css">
        <link href="{{ asset('assets/css/icons.css') }}" rel="stylesheet" type="text/css">
        <link href="{{ asset('assets/css/style.css') }}" rel="stylesheet" type="text/css">
@endsection




@section('scripts')

        <script src="{{ asset('assets/js/modernizr.min.js') }}"></script>


        <!-- Plugins  -->
        <script src="{{ asset('assets/js/jquery.min.js') }}"></script>
        <script src="{{ asset('assets/js/popper.min.js') }}"></script><!-- Popper for Bootstrap -->
        <script src="{{ asset('assets/js/bootstrap.min.js') }}"></script>
        <script src="{{ asset('assets/js/detect.js') }}"></script>
        <script src="{{ asset('assets/js/fastclick.js') }}"></script>
        <script src="{{ asset('assets/js/jquery.slimscroll.js') }}"></script>
        <script src="{{ asset('assets/js/jquery.blockUI.js') }}"></script>
        <script src="{{ asset('assets/js/waves.js') }}"></script>
        <script src="{{ asset('assets/js/wow.min.js') }}"></script>
        <script src="{{ asset('assets/js/jquery.nicescroll.js') }}"></script>
        <script src="{{ asset('assets/js/jquery.scrollTo.min.js') }}"></script>
        <script src="{{ asset('assets/plugins/switchery/switchery.min.js') }}"></script>

        <!-- Counter Up  -->
        <script src="{{ asset('assets/plugins/waypoints/lib/jquery.waypoints.min.js') }}"></script>
        <script src="{{ asset('assets/plugins/counterup/jquery.counterup.min.js') }}"></script>

        <!-- circliful Chart -->
        <script src="{{ asset('assets/plugins/jquery-circliful/js/jquery.circliful.min.js') }}"></script>
        <script src="{{ asset('assets/plugins/jquery-sparkline/jquery.sparkline.min.js') }}"></script>

        <!-- skycons -->
        <script src="{{ asset('assets/plugins/skyicons/skycons.min.js') }}" type="text/javascript"></script>

        <!-- Page js  -->
        <script src="{{ asset('assets/pages/jquery.dashboard.js') }}"></script>

        <!-- Custom main Js -->
        <script src="{{ asset('assets/js/jquery.core.js') }}"></script>
        <script src="{{ asset('assets/js/jquery.app.js') }}"></script>


        <script type="text/javascript">
            jQuery(document).ready(function($) {
                $('.counter').counterUp({
                    delay: 100,
                    time: 1200
                });
                $('.circliful-chart').circliful();
            });

            // BEGIN SVG WEATHER ICON
            if (typeof Skycons !== 'undefined'){
                var icons = new Skycons(
                        {"color": "#f9cd48"},
                        {"resizeClear": true}
                        ),
                        list  = [
                            "clear-day", "clear-night", "partly-cloudy-day",
                            "partly-cloudy-night", "cloudy", "rain", "sleet", "snow", "wind",
                            "fog"
                        ],
                        i;

                for(i = list.length; i--; )
                    icons.set(list[i], list[i]);
                icons.play();
            };

        </script>
@endsection
