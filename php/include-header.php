<?php

$nombre_completo = "";
$area = new Area();
$path = "";
   
if (isset($origen) && $origen != "index") {
    $path = "../";
}

if (isset($_SESSION['usr'])) {
    $nombre_completo = $_SESSION['nombre'];
    $area = new Area((int) $_SESSION['area']);
}
?>
<div class="row">
    <div class="col-md-12 text-center">
        <!--<p class="visible-xs">extra small devices  - Phones (<768px)</p>
        <p class="visible-sm">small devices - Tablets (≥768px)</p>
        <p class="visible-md">medium devices - Desktops (≥992px)</p>
        <p class="visible-lg">large devices - Desktops (≥1200px)</p>-->
    </div>
</div>
<div class="row">
    <div class="col-md-12" style="background-color: #2F76A7; height: 5px;">

    </div>
    <div class="col-md-12" style="background-color: #F9F9F9;">
        <div class="col-md-4">
            <a href="<?php echo($path);?><?php !$origen === "informacion-publica" ? "index.php" : "php/informacion-publica.php" ?>"><img src="<?php echo($path);?>img/logo.png" alt="Logo UTTAB" class="img-responsive visible-md visible-lg" style="float: right"/></a>
            <a href="<?php echo($path);?><?php !$origen === "informacion-publica" ? "index.php" : "php/informacion-publica.php" ?>"><img src="<?php echo($path);?>img/logo.png" alt="Logo UTTAB" class="img-responsive visible-xs visible-sm" style="margin: 0 auto"/></a>
        </div>
        <div class="col-md-8">
            <h2 class="text-uppercase text-left visible-md visible-lg">Universidad Tecnológica de Tabasco</h2>
            <h3 class="text-uppercase text-left visible-md visible-lg">Organismo Público Descentralizado</h3>
            <h2 class="text-uppercase text-center visible-sm">Universidad Tecnológica de Tabasco</h2>
            <h3 class="text-uppercase text-center visible-sm">Organismo Público Descentralizado</h3>
        </div>
    </div>
</div>
<?php
if ($origen !== "index" and $origen !== "informacion-publica") {
    ?>
    <div class="row">
        <div class="col-md-12" style="background-color: #21C9AA;min-height: 10px; padding: 5px; box-sizing: border-box ">
            <?php if (isset($_SESSION['usr'])) { ?> <p class="text-center"><strong>Usuario en sesión: <strong><?php echo($nombre_completo); ?></strong> (<?php echo($area->getDescripcion()); ?>)</strong> <a href="<?php echo($path);?>php/log-out.php?logout=1" class="btn btn-md btn-success"><span class="glyphicon glyphicon-log-in"></span> Cerrar sesión</a> </p><?php } ?>
            </div>
            <?php if ($area->getCveArea() === 1) { ?>
                <div class="col-md-12" style="background-color: #3C4A53; min-height: 52px;">
                    <ul class="topnav" id="myTopnav2" style="max-width: 1083px;margin:0 auto;">
                        <li class="menu"><a href="javascript:void(0)" class="text-uppercase">Menú administrador</a></li>
                        <li><a href="<?php echo($path);?>/php/articulos.php" class="text-uppercase" <?php echo($origen === "articulo" ? "style=\"color:#5CB85C;\"" : ""); ?>><span class="glyphicon glyphicon-tasks"></span> Artículos</a></li>
                        <li><a href="<?php echo($path);?>/php/fracciones.php" class="text-uppercase" <?php echo($origen === "fraccion" ? "style=\"color:#5CB85C;\"" : ""); ?>><span class="glyphicon glyphicon-bookmark"></span> Fracciones</a></li>
                        <li><a href="<?php echo($path);?>/php/incisos.php" class="text-uppercase" <?php echo($origen === "inciso" ? "style=\"color:#5CB85C;\"" : ""); ?>><span class="glyphicon glyphicon-certificate"></span> Incisos</a></li>
                        <li><a href="<?php echo($path);?>/php/apartados.php" class="text-uppercase" <?php echo($origen === "apartado" ? "style=\"color:#5CB85C;\"" : ""); ?>><span class="glyphicon glyphicon-th-list"></span> Apartados</a></li>                  
                        <li><a href="<?php echo($path);?>/php/usuarios.php" class="text-uppercase" <?php echo($origen === "usuario" ? "style=\"color:#5CB85C;\"" : ""); ?>><span class="glyphicon glyphicon-user"></span> Usuarios</a></li>                  
                        <li><a href="<?php echo($path);?>/php/permisos.php" class="text-uppercase" <?php echo($origen === "permisos" ? "style=\"color:#5CB85C;\"" : ""); ?>><span class="glyphicon glyphicon-wrench"></span> Permisos</a></li>                  
                        <li class="icon">
                            <a href="javascript:void(0);" onclick="myFunction('myTopnav2')">&#9776;</a>
                        </li>
                    </ul>
                </div>
            <?php } ?>
            <div class="col-md-12" style="background-color: #3C4A53; min-height: 52px;">
                <ul class="topnav" id="myTopnav" style="max-width: 1083px;margin:0 auto;">
                    <li class="menu"><a href="javascript:void(0)" class="text-uppercase">Menú</a></li>
                    <li><a href="<?php echo($path);?>/php/elegir-anio-trimestre.php" class="text-uppercase" <?php echo($origen === "anio-trimestre" ? "style=\"color:#5CB85C;\"" : ""); ?>><span class="glyphicon glyphicon-calendar"></span> Año & trimestre</a></li>
                    <li><a href="<?php echo($path);?>/php/anexos.php" class="text-uppercase" <?php echo($origen === "anexos" ? "style=\"color:#5CB85C;\"" : ""); ?>><span class="glyphicon glyphicon-paperclip"></span> Anexos</a></li>
                    <li><a href="<?php echo($path);?>/php/usuario-contrasena.php" class="text-uppercase" <?php echo($origen === "usuario-contrasena" ? "style=\"color:#5CB85C;\"" : ""); ?>><span class="glyphicon glyphicon-text-width"></span> Cambiar contraseña</a></li>                  
                    <li><a href="<?php echo($path);?>/php/informacion-publica.php" target="_blank" class="text-uppercase" <?php echo($origen === "informacion-publica" ? "style=\"color:#5CB85C;\"" : ""); ?>><span class="glyphicon glyphicon-globe"></span> Información pública</a></li>                  
                    <li class="icon">
                        <a href="javascript:void(0);" onclick="myFunction('myTopnav')">&#9776;</a>
                    </li>
                </ul>
            </div>
        </div>
    <?php } else { ?>
        <div class="row">
            <div class="col-md-12" style="background-color: #21C9AA; min-height: 10px;">

            </div>
        </div>
    <?php } ?>