@extends('layout.layout')


@section('content')
    
    <div class="row">
        <div class="col-sm-12">
            <div class="card-box">
                <form 
                    id="page-form" 
                    action="{{ action ('ImportController@upload') }}" 
                    method="POST"
                    class="form-horizontal"
                    enctype="multipart/form-data"
                    role="form"
                    onsubmit="formSubmit();">

                    {{ csrf_field() }}

                    <input type="hidden" name="office_id" value="{{ $office_id }}">

                    <div class="form-row">
                        <div class="form-group col-sm-2">

                            <label class="">{{ trans('import.YEAR_LABEL') }}</label>
                            <input class="form-control" 
                                    type="text" 
                                    name="year" 
                                    placeholder="{{ trans('import.PH_YEAR') }}" 
                                    value="{{ old('year', '') }}">

                        </div>
                    </div>

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
            
                        <h4 class="modal-title">Importación de datos:</h4>
                    
                    </div>
                
                    <div class="modal-body bg-default">

                        <p>El documento ha importar corresponde al documento obtenido en:</p>
                        <p><strong> "Listados >> 6.11 Estadísitcas cámara >> 6.11.2 ABC de clientes"</strong></p>
                        <p>de la aplicación <strong>DIANA</strong>.</p>
                        <p>Se ha de seleccionar:</p>
                        <ul>
                            <li>Desde Cliente: Dejas en blanco para exportar todos los clientes</li>
                            <li>Año: El año que queremos exportar</li>
                            <li>Mes de inicio: "1"</li>
                            <li>Mes de fin: "12"</li>
                            <li>Importes con IVA: "N"</li>
                            <li>Agrupado por: "C"</li>
                            <li>Comparativa: "N"</li>
                            <li>Destino impresion: "F"</li>
                            <li>Exportar a: "E"</li>
                        </ul>        

                        <p>Resto de parámetros: el valor por defecto.</p>                
                        <p>El proceso de importación actualiza la información almacenada <strong>del año seleccionado</strong> por la nueva información del documento importado, por lo que se puede actualzar tantas veces como sea necesario. Por ejemplo por si ha sido necesaria modficiar alguna/s factura/s en DIANA y se realiza una nueva importación del documento <strong>"ABC de clientes"<strong>.<p>
                    
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
