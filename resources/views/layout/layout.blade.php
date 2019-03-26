
<!DOCTYPE html>
<html>
    <head>
        
        <title>@yield ("title")</title>

        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width,initial-scale=1">
        <meta name="description" content="Plataforma para análisis de dacturación de clientes Naces">
        <meta name="author" content="Alex Furró">

        <link rel="shortcut icon" href="{{ asset('assets/images/favicon.ico') }}">


        <link href="{{ asset('assets/plugins/switchery/switchery.min.css') }}" rel="stylesheet" />
        @yield("css")        
        <link href="{{ asset('assets/css/bootstrap.min.css') }}" rel="stylesheet" type="text/css">
        <link href="{{ asset('assets/css/icons.css') }}" rel="stylesheet" type="text/css">
        <link href="{{ asset('assets/css/style.css') }}" rel="stylesheet" type="text/css">
        <link href="{{ asset('assets/css/custom.css') }}" rel="stylesheet" type="text/css">

        <style>
            #sidebar-menu > ul > li > a 
            {
                /*border-left: 3px solid #f9cd48 !important;*/
            }
            .side-menu.left {
               /*background: #f9cd48 !important;*/
            }
        </style>


    </head>


    <body class="fixed-left">
        
        <!-- Begin page -->
        <div id="wrapper">

            <!-- Top Bar Start -->
            @include('layout.header')
            <!-- Top Bar End -->

            @if (Auth::check())
            <!-- ========== Left Sidebar Start ========== -->
            @include('layout.left_bar')
            <!-- Left Sidebar End -->
            @endif


            <!-- ============================================================== -->
            <!-- Start right Content here -->
            <!-- ============================================================== -->                      
            <div class="content-page">
                <!-- Start content -->
                <div class="content">
                    <div class="container-fluid">

                        <!-- Ini Wait Windows -->
                        <div class="ajax-hide"></div>    
                        <!-- End Wait Windows -->

                        <!-- Ini Page-Title -->
                        <div class="row">
                            <div class="col-sm-12">
                                <div class="page-title-box">
                                    <h4 class="page-title">{{ isset($pageTitle) ? $pageTitle : '' }}</h4>
                                    @if (isset($btn_help))
                                    <div class="float-right">
                                        <a href="#" onclick="document.getElementById('btn-modal-help').click();">        
                                            <i class="fa fa-question-circle"> {{ $btn_help }}</i>
                                        </a>
                                    </div>
                                    @endif
                                    <div class="clearfix"></div>
                                </div>
                            </div>
                        </div>
                        <!-- End Page-Title -->

                        <!-- Ini Pre Submit -->
                        @if (isset($msgPreSubmit))
                        <div id="div-wait" class="row on-form-submit-show" style="display:none">
                            <div class="col-sm-12">
                                <div class="alert alert-info">

                                    <p>{{ $msgPreSubmit }}</p>

                                </div>
                            </div>
                        </div>
                        @endif
                        <!-- End Pre Submit -->

                        <!-- Ini Form Errors -->
                        @if ($errors->any())
                        <div id="form-errors" class="row on-form-submit-hide" style="display:none">
                            <div class="col-sm-12">
                                <div class="alert alert-danger">
                                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>                
                                    <p>Errores</p>
                                    <ul>
                                        @foreach($errors->all() as $error)
                                            <li>{{ $error }}</li>
                                        @endforeach
                                    </ul>
                                </div>
                            </div>
                        </div>
                        @endif
                        <!-- End Form Errors -->

                        <!-- Ini App Alerts -->
                        @if (isset($alerts))

                        @foreach ($alerts as $alert)

                        <div id="{{ $alert['type'] }}" class="row on-form-submit-hide" style="display:none">
                            
                            <div class="col-sm-12">
                                <div class="alert {{ $alert['type'] }}">

                                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>                
                                    
                                    <p><strong>{{ $alert['msg'] }}</strong></p>

                                    <ul>
                                        @foreach($alert['lines'] as $line)
                                        
                                            <li>{{ $line }}</li>
                                    
                                        @endforeach
                                    </ul>


                                </div>
                            
                            </div>

                        </div>
                        @endforeach            
                        @endif
                        <!-- Ini App Alerts -->

                        @yield("content")

                    </div>
                </div>
            </div>
            <!-- ============================================================== -->
            <!-- End Right content here -->
            <!-- ============================================================== -->


            @if (Auth::check())
            <!-- Right Sidebar -->
            @include('layout.right_bar')
            <!-- /Right-bar -->
            @endif

            <!-- Top Bar Start -->
            @include('layout.footer')
            <!-- Top Bar End -->

        </div>
        <!-- END wrapper -->


    
        <script>
            var resizefunc = [];
        </script>


        <script src="{{ asset('assets/js/modernizr.min.js') }}"></script>
        <script src="{{ asset('assets/js/jquery.min.js') }}"></script>
        <script src="{{ asset('assets/js/popper.min.js') }}"></script><!-- Popper for Bootstrap -->
        <script src="{{ asset('assets/js/bootstrap.min.js') }}"></script>        
        <script src="{{ asset('assets/js/detect.js') }}"></script>
        <script src="{{ asset('assets/js/fastclick.js') }}"></script>
        <script src="{{ asset('assets/js/jquery.slimscroll.js') }}"></script>
        <script src="{{ asset('assets/js/jquery.blockUI.js') }}"></script>        
        <script src="{{ asset('assets/js/waves.js') }}"></script>
        <script src="{{ asset('assets/js/wow.min.js') }}"></script>
        <script src="{{ asset('assets/plugins/switchery/switchery.min.js') }}"></script>
        @yield("scripts")
        <!-- Custom main Js -->
        <script src="{{ asset('assets/js/jquery.core.js') }}"></script>
        <script src="{{ asset('assets/js/jquery.app.js') }}"></script>

        <script type="text/javascript">

            $( document ).ready(function() {

                $('.on-form-submit-hide').show(500);

                $('.button-menu-mobile').click();

            });

            function formSubmit()
            {
                $('.on-form-submit-show').show(500);

                $('.on-form-submit-hide').hide(500);

                $('.div-show-hide').hide(500);

                $('.ajax-hide').removeClass('ajax-hide').addClass('ajax-show');
            }

        </script>


    </body>
</html>