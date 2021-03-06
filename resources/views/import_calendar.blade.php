@extends('layout.layout')


@section('content')
    
    <div class="row">
        <div class="col-sm-12">
            <div class="card-box">
                <form 
                    id="page-form" 
                    action="{{ action ('CalendarController@upload') }}" 
                    method="POST"
                    class="form-horizontal"
                    enctype="multipart/form-data"
                    role="form"
                    onsubmit="formSubmit();">

                    {{ csrf_field() }}

                    <input type="hidden" name="office_id" value="{{ $office_id }}">

                    <div class="form-row">
                        <div class="form-group col-sm-6">

                            <label class="">{{ trans('import.FILE_LABEL') }}</label>
                            <input id="excel" 
                                name="excel" 
                                type="file" 
                                class="file"
                                data-show-preview="false"
                                data-browse-on-zone-click="false"
                                data-show-cancel="false" 
                                data-show-remove="false" 
                                data-show-upload="false" 
                                data-show-caption="true" 
                                data-msg-placeholder="Select {files} for upload...">

                        </div>
                    </div>

                    <div class="form-row">

                        <div class="form-group col-md-12">

                            <button class="btn btn-primary on-form-submit-hide">

                                {{ trans('import.SUBMIT_LABEL') }}

                            </button>

                        </div>
                    
                    </div>

                </form>


            </div>
        </div>
    </div>

    <!-- Trigger the modal with a button -->
    <button id="btn-modal-help" type="button" class="btn btn-sm waves-effect waves-light btn-primary m-b-5" data-toggle="modal" data-target="#modal" data-animation="fadein" style="display:none;">
        <i class="mdi mdi-crop-free noti-icon"></i>
    </button>

    <!-- Modal -->
    <div id="modal" class="modal fade" role="dialog">
        
        <div class="modal-dialog modal-lg">

            <!-- Modal content-->
            <div class="modal-content" style="border:0px solid gray; border-radius:0px;">

                <div class="modal-header bg-primary" style="border-radius:0px;">
            
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
            
                        <h4 class="modal-title">Importación de datos: calendario</h4>
                    
                    </div>
                
                    <div class="modal-body bg-default">
                        
<p>El calendario permite importar los días laborables por cada mes y año.</p>
<p>Mediante los días laborales la plataforma puede calcular el ratio de facturación (mensual o anual) por días trabajados.</p>
<p>Si no se ha importado el calendario, o no se ha definido para un año concreto, o el valor mensual de dias trabajados es 0, <strong>se considerarán 20 días laborales mensuales por defecto (240 anuales)</strong>.</p>
<hr>
<p>El documento ha importar ha de ser un documento en formato <strong>Microsoft Excel (xls o xlsx)</strong>.</p>
<p>Ha de incluir las siguientes columnas en la primera fila:</p>
<p><strong>'Periodo' 'Ene' 'Feb' 'Mar' Abr' May' 'Jun' 'Jul' 'Ago' 'Sep' 'Oct' 'Nov' 'Dic'</strong></p>
<p>El contenido de cada columna ha de ser un número entero</p>
<hr>
<p>La importación actaulizará los datos almacenados para cada periodo, por lo que puede utilizarse siempre el mismo archivo Excel actualizando el valor de cada mes, y/o añadiendo un nuevo período (año).</p>

                    </div>
              
                    <div class="modal-footer">
                    
                        <button type="button" class="btn btn-primary" data-dismiss="modal">Cerrar</button>
              
                    </div>

                </div>
            
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
