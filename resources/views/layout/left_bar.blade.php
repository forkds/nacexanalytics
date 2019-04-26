            <div class="left side-menu">
                <div class="sidebar-inner slimscrollleft">
                    <!--- Divider -->
                    <div id="sidebar-menu">
                        <ul>

                            <li>
                                <a href="/" class="waves-effect waves-primary">
                                    <i class="fa fa-home"></i><span> Panel </span>
                                </a>
                            </li>


                            <li>
                                <a href="{{ action('ClientController@index') }}" class="waves-effect waves-primary">
                                    <i class="fa fa-address-book-o"></i><span> Clientes </span>
                                </a>
                            </li>

                            <li>
                                <a href="{{ action('MonthlyClientController@index') }}" class="waves-effect waves-primary">
                                    <i class="fa fa-address-card-o"></i><span> Comparativa </span>
                                </a>
                            </li>

@if (\Auth::user()->hasAnyRole(['manager', 'admin']))
                            <li class="has_sub">
                                <a href="javascript:void(0);" class="waves-effect waves-primary">
                                    <i class="fa fa-upload"></i><span> Configuración </span> 
                                    <span class="menu-arrow"></span> 
                                </a>
                                <ul class="list-unstyled">
                                    <li><a href="{{ action('ImportController@edit') }}">Cargar facturación</a></li>
                                    <li><a href="{{ action('ImportExpressController@edit') }}">Cargar inmediatos</a></li>
                                    <li><a href="{{ action('CalendarController@edit') }}">Cargar calendario</a></li>
                                </ul>
                            </li>
@endif
                            <li>
                                <a href="{{ route('logout') }}" class="waves-effect waves-primary" 
                                onclick="event.preventDefault();document.getElementById('logout-form').submit();">
                                    <i class="fa fa-sign-out"></i><span> Logout </span>
                                </a>
                            </li>


                            <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                                {{ csrf_field() }}
                            </form>


                        </ul>

                        <div class="clearfix"></div>
                    
                    </div>
                    
                    <div class="clearfix"></div>
                
                </div>
            
            </div>
