@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-12 col-md-offset-2">

		<p>{{ \Auth::user() }}</p>

		@if (isset($test))
    	<?php var_dump ($test); ?>
    	@endif
        </div>
    </div>
</div>
@endsection
