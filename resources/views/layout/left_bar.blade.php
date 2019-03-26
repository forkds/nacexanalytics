            <div class="left side-menu">
                <div class="sidebar-inner slimscrollleft">
                    <!--- Divider -->
                    <div id="sidebar-menu">
                        <ul>

                            <li>
                                <a href="/" class="waves-effect waves-primary">
                                    <i class="ti-home"></i><span> Panel </span>
                                </a>
                            </li>


                            <li>
                                <a href="{{ action('ClientController@index') }}" class="waves-effect waves-primary">
                                    <i class="ti-stats-up"></i><span> Clientes </span>
                                </a>
                            </li>

@if (\Auth::user()->hasAnyRole(['manager', 'admin']))
                            <li>
                                <a href="{{ action('ImportController@edit') }}" class="waves-effect waves-primary">
                                    <i class="ti-upload"></i><span> Cargar Archivo </span>
                                </a>
                            </li>
@endif
<!--
                            <li>
                                <a href="{{ action('LayoutController@analysis') }}" class="waves-effect waves-primary">
                                    <i class="ti-search"></i><span> Análsis </span>
                                </a>
                            </li>

                            <li>
                                <a href="{{ action('LayoutController@profile') }}" class="waves-effect waves-primary">
                                    <i class="ti-settings"></i><span> Perfil </span>
                                </a>
                            </li>

                            <li>
                                <a href="{{ action('UsuarioController@settings') }}" class="waves-effect waves-primary">
                                    <i class="ti-settings"></i><span> Configuración </span>
                                </a>
                            </li>
-->
                            <li>
                                <a href="{{ route('logout') }}" class="waves-effect waves-primary" 
                                onclick="event.preventDefault();document.getElementById('logout-form').submit();">
                                    <i class="ti-back-left"></i><span> Logout </span>
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
