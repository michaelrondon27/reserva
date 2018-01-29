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
                <img src="<?php echo base_url();?>assets/cpanel/Usuarios/images/<?php echo $this->session->userdata('avatar_usuario');?>" width="48" height="48" alt="User" />
            </div>
            <div class="info-container">
                <div class="name" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><?php echo $this->session->userdata('nombre') ?></div>
                <div class="email"><?php echo $this->session->userdata('correo_usuario') ?></div>
                <div class="btn-group user-helper-dropdown">
                    <i class="material-icons" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">keyboard_arrow_down</i>
                    <ul class="dropdown-menu pull-right">
                        <li><a href="javascript:void(0);"><i class="material-icons">person</i>Profile</a></li>
                        <li role="seperator" class="divider"></li>
                        <li><a href="<?php echo base_url();?>auth/logout"><i class="material-icons">input</i>Cerrar Sesión</a></li>
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
                        <span>Inicio</span>
                    </a>
                </li>
                <?php foreach($modulos as $modulo): ?>
                    <li id="mv<?php echo $modulo->id_modulo_vista; ?>">
                        <a href="javascript:void(0);" class="menu-toggle">
                            <span><?php echo $modulo->nombre_modulo_vista; ?></span>
                        </a>
                        <ul class="ml-menu">
                            <?php foreach($vistas as $vista): ?>
                                <?php if($modulo->id_modulo_vista == $vista->id_modulo_vista): ?>
                                    <li id="lv<?php echo $vista->id_lista_vista; ?>">
                                        <a href="<?=base_url();?><?php echo $vista->url_lista_vista; ?>"><?php echo $vista->nombre_lista_vista; ?></a>
                                    </li>
                                <?php endif ?>
                            <?php endforeach ?>
                        </ul>
                    </li>
                <?php endforeach ?>
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