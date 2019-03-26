@extends('layout.layout')


@section('content')
    
    <div class="row">

        <div class="col-sm-12">
        
            <div class="card-box">                
        
                <form 
                    id="page-form" 
                    action="{{ action ('UsuarioController@passwordChange') }}" 
                    method="POST"
                    class="form-horizontal"
                    role="form"
                    onsubmit="formSubmit();">

                    {{ csrf_field() }}

                    <input type="hidden" name="email" value="{{ \Auth::user()->email }}">

                    <div class="form-row">

                        <div class="form-group col-sm-4">

                            <label class="">Password Actual</label>
                            <input class="form-control" 
                                    type="password" 
                                    name="password_act"
                                    required
                                    placeholder="" 
                                    value=""
                                    autofocus>

                        </div>
                    </div>

                    <div class="form-row">

                        <div class="form-group col-sm-4">

                            <label class="">Password Nuevo</label>
                            <input class="form-control" 
                                    type="password" 
                                    name="password"
                                    required
                                    placeholder="" 
                                    value="">

                        </div>
                    </div>

                    <div class="form-row">

                        <div class="form-group col-sm-4">

                            <label class="">Password Confirmación</label>
                            <input class="form-control" 
                                    type="password" 
                                    name="password_confirmation"
                                    required
                                    placeholder="" 
                                    value="">

                        </div>
                    </div>

                    <div class="form-row">

                        <div class="form-group col-sm-4">

                            <button type="submit" class="btn btn-primary on-form-submit-hide">

                                {{ trans('passwords.LABEL_SUBMIT') }}

                            </button>

                        </div>
                    
                    </div>

                </form>


            </div>
        </div>
    </div>


@endsection

@section('title')
    Panel
@endsection

@section('css')
    <link href="{{ asset('/assets/css/fileinput.css') }}" media="all" rel="stylesheet" type="text/css"/>

@endsection

@section('scripts')
    <script src="{{ asset('/assets/js/plugins/sortable.js') }}" type="text/javascript"></script>
    <script src="{{ asset('/assets/js/fileinput.js') }}" type="text/javascript"></script>
    <script src="{{ asset('/assets/js/locales/fr.js') }}" type="text/javascript"></script>
    <script src="{{ asset('/assets/js/locales/es.js') }}" type="text/javascript"></script>

@endsection
