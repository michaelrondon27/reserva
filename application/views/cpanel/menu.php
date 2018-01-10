<!-- Overlay For Sidebars -->
<div class="overlay"></div>
<!-- #END# Overlay For Sidebars -->
<!-- Top Bar -->
<nav class="navbar">
    <div class="container-fluid">
        <div class="navbar-header">
            <a href="javascript:void(0);" class="bars"></a>
            <a class="navbar-brand" href="index.html">ADMINBSB - MATERIAL DESIGN</a>
        </div>
    </div>
</nav>
<!-- #Top Bar -->
<section>
    <!-- Left Sidebar -->
    <aside id="leftsidebar" class="sidebar">
        <!-- User Info -->
        <div class="user-info">
            <div class="image">
                <img src="images/user.png" width="48" height="48" alt="User" />
            </div>
            <div class="info-container">
                <div class="name" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">John Doe</div>
                <div class="email">john.doe@example.com</div>
                <div class="btn-group user-helper-dropdown">
                    <i class="material-icons" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">keyboard_arrow_down</i>
                    <ul class="dropdown-menu pull-right">
                        <li><a href="javascript:void(0);"><i class="material-icons">person</i>Profile</a></li>
                        <li role="seperator" class="divider"></li>
                        <li><a href="javascript:void(0);"><i class="material-icons">group</i>Followers</a></li>
                        <li><a href="javascript:void(0);"><i class="material-icons">shopping_cart</i>Sales</a></li>
                        <li><a href="javascript:void(0);"><i class="material-icons">favorite</i>Likes</a></li>
                        <li role="seperator" class="divider"></li>
                        <li><a href="javascript:void(0);"><i class="material-icons">input</i>Sign Out</a></li>
                    </ul>
                </div>
            </div>
        </div>
        <!-- #User Info -->
        <!-- Menu -->
        <div class="menu">
            <ul class="list">
                <li class="header">Barra de Navegación</li>
                <li id="inicio">
                    <a href="<?=base_url();?>">
                        <i class="material-icons">home</i>
                        <span>Inicio</span>
                    </a>
                </li>
                <li id="perfiles">
                    <a href="javascript:void(0);" class="menu-toggle">
                        <i class="material-icons">people</i>
                        <span>Perfiles</span>
                    </a>
                    <ul class="ml-menu">
                        <li id="usuarios">
                            <a href="<?=base_url();?>Usuarios">Usuarios</a>
                        </li>
                    </ul>
                </li>
                <li id="configuracion">
                    <a href="javascript:void(0);" class="menu-toggle">
                        <i class="material-icons">settings</i>
                        <span>Configuración</span>
                    </a>
                    <ul class="ml-menu">
                        <li id="banco">
                            <a href="<?=base_url();?>Bancos">Bancos</a>
                        </li>
                        <li id="listaValor">
                            <a href="<?=base_url();?>listaValores">Lista de Valores</a>
                        </li>
                        <li id="miCorreo">
                            <a href="<?=base_url();?>MiCorreo">Mi Correo</a>
                        </li>
                        <li id="miEmpresa">
                            <a href="<?=base_url();?>MiEmpresa">Mi Empresa</a>
                        </li>
                        <li id="plaza">
                            <a href="<?=base_url();?>Plaza">Plazas Bancarias</a>
                        </li>
                        <li id="sepomex">
                            <a href="<?=base_url();?>Sepomex">Sepomex</a>
                        </li>
                    </ul>
                </li>
            </ul>
        </div>
        <!-- #Menu -->
        <!-- Footer -->
        <div class="legal">
            <div class="copyright">
                &copy; 2016 - 2017 <a href="javascript:void(0);">AdminBSB - Material Design</a>.
            </div>
            <div class="version">
                <b>Version: </b> 1.0.5
            </div>
        </div>
        <!-- #Footer -->
    </aside>
    <!-- #END# Left Sidebar -->
</section>