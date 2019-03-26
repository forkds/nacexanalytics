<?php

$header_user_name   = "";
$header_user_office = "";

if (\Auth::user()) :

    $header_user_name = \Auth::user()->name;

    if (\Auth::user()->hasActiveOffice()) :
    
        $header_user_office = \Auth::user()->getActiveOffice()->code;

    endif;

endif;

?>


            <!-- Top Bar Start -->
            <div class="topbar">

                <!-- LOGO -->
                <div class="topbar-left">
                    <div class="text-center">
                        <a href="/panel" class="logo"><i class="mdi mdi-radar"></i> <span>FORKDS</span></a>
                    </div>
                </div>

                <!-- Button mobile view to collapse sidebar menu -->
                <nav class="navbar-custom">

                    <ul class="list-inline float-right mb-0">

                        <li class="list-inline-item dropdown notification-list">
                            <a class="nav-link dropdown-toggle waves-effect waves-light nav-user" data-toggle="dropdown" href="#" role="button"
                               aria-haspopup="false" aria-expanded="false">                                <i class="mdi mdi-account-box noti-icon">&nbsp;{{ trans('nacex-analytics.HEADER_USER') . $header_user_name }}</i>
                            </a>
@if (\Auth::user())
                            <div class="dropdown-menu dropdown-menu-right profile-dropdown " aria-labelledby="Preview">

                                <a  href="{{ action ('UsuarioController@show') }}" class="dropdown-item notify-item">

                                    <i class="mdi mdi-key"></i> <span>Cambiar password</span>

                                </a>

                            </div>

@endif
                        </li>


                        <li class="list-inline-item dropdown notification-list">
                            <a class="nav-link dropdown-toggle waves-effect waves-light nav-user" data-toggle="dropdown" href="#" role="button"
                               aria-haspopup="false" aria-expanded="false">
                                <i class=" mdi mdi-home-circle noti-icon">&nbsp;{{ trans('nacex-analytics.HEADER_OFFICE') . $header_user_office }}</i>
                            </a>



@if (\Auth::user())

<?php $offices = \Auth::user()->offices()->get() ?> 

@if ($offices->isNotEmpty())
                            <form id="header-hidden-form" action="{{ action('OfficeController@set') }}" method="POST" style="display:hidden;">
                                {{ csrf_field() }}
                                <input id="header-hidden-field" type="hidden" name="id" value="0">
                            </form>

                            <div class="dropdown-menu dropdown-menu-right profile-dropdown " aria-labelledby="Preview">

                                <!-- item-->
                                <div class="dropdown-item noti-title">
                                    <h5 class="text-overflow"><small>Seleccione oficina:</small> </h5>
                                </div>

@foreach ($offices as $office)
                                <!-- item-->
                                <a  href="javascript:void(0);" 
                                    class="dropdown-item notify-item"
                                    onclick=
                                        "
                                        getElementById('header-hidden-field').value={{ $office->id }};
                                        getElementById('header-hidden-form').submit();
                                        "
                                >
                                    <i class="mdi mdi-home"></i> <span>{{ $office->code }}</span>
                                </a>
@endforeach
                            </div>
@endif                           
@endif                           
                        </li>

                    </ul>

                    <ul class="list-inline menu-left mb-0">
                        <li class="float-left">
                            <button class="button-menu-mobile open-left waves-light waves-effect">
                                <i class="mdi mdi-menu"></i>
                            </button>
                        </li>
                    </ul>

                </nav>

            </div>
            <!-- Top Bar End -->
