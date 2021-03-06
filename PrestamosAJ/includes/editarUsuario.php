<!DOCTYPE HTML>
<html lang="es">
<head>
	<meta charset="UTF-8">
	<title>Configuración de la cuenta</title>
	<link rel="stylesheet" type="text/css" href="../css/bootstrap.css">
	<link rel="stylesheet" type="text/css" href="../css/bootstrap-responsive.css">
	<link rel="stylesheet" type="text/css" href="../css/smoothness/jquery-ui.css">
	<link rel="stylesheet" type="text/css" href="../css/estilos.css">
	<script src="../js/jquery.js"></script>
	<script src="../js/jquery-ui.js"></script>
	<script src="../js/bootstrap.js"></script>
	<script src="../js/jquery.validate.js"></script>
	<script src="../js/editar.js"></script>
	<script src="../js/funciones.js"></script>
	<!--<script src="../js/registrar.js"></script>-->
	<script src="../js/eliminar.js"></script>
	<?php
      session_start();
      if(isset($_SESSION['id_user'])){
            $user = $_SESSION['nombre'];
            $id = $_SESSION['id_user'];
      }else{
      		header('Location: index.php');
      }
	?>
	<style>
	    h1{
	    	text-align: center;
	    }
	    td{
	    	font-size: 2em;
	    }
	    label.error{
			float: none; 
			color: red; 
			padding-left: .5em;
		    vertical-align: middle;
		    font-size: 12px;
		}
	    p{
	    	color: #df0024;
	    	font-size: 20px;
	    }
	    textarea{
	    	/*resize: none;*/
	    	font-size: 16px;
	    	width: 250px;
	    }
	    #fondo{
	    	background: #feffff;
	    	
	       	/* box-shadow:inset -3px -2px 37px #000000; */
	    }
	    #mensaje{
	        float: left;
	    	margin-left: 45%;
	    	position: fixed;
	    	top: 18%;
	    	display: block;
       	}
       	#mensajeError{
       		float: left;
	    	margin-left: 45%;
	    	position: fixed;
	    	top: 18%;
	    	display: block;
       	}
        .hero-unit{
        	margin-top: 7%;
        	text-align: center;
        	background-image: url('../img/dinero-1.jpg');
        }
	</style>

	<script>
      $(document).ready(function(){
		    /*______________________________________________*/
        $("#menuOpen").mouseout(function(){
            //$("#formMenu").removeClass('open');
	    }).mouseover(function(){
	        $("#formMenu").addClass('open');
	        $("#foco").focus();
        });

	  });//cierre del document
	</script>
</head>
<body>
	<header>
		<div class="navbar navbar-fixed-top navbar-inverse">
			<div class="navbar-inner">
				<div class="container" >
					<a  class="btn btn-navbar" data-toggle="collapse" data-target=".nav-collapse">
						<span class="icon-bar"></span>
						<span class="icon-bar"></span>
						<span class="icon-bar"></span>
					</a>
					<a href="../menu.php" class="brand">Prestamos AJ</a>
					<div class="nav-collapse collapse">
						<ul class="nav" >
							<li class="divider-vertical"></li>
							<li><a href="../menu.php"><i class="icon-home icon-white"></i>Inicio</a></li>
							<li class="divider-vertical"></li>
							<li class="dropdown">
								<a href="#" class="dropdown-toggle" data-toggle="dropdown">
									Clientes
									<span class="caret"></span>
								</a>
								<ul class="dropdown-menu">
									<li><a href="caja.php">Caja</a></li>
									<li><a href="actualizarDatos.php">Registrar</a></li>
									<li><a href="prestamos.php">Prestamos</a></li>
									<li><a href="pagos.php">Pagos</a></li>
									<li><a href="renovar.php">Renovar Credito</a></li>
									<li><a href="mes.php">Mes</a></li>
								</ul>
							</li>
							<li class="divider-vertical"></li>
							<li><a href="reporte.php"><i class="icon-book icon-white"></i> Reporte</a></li>
							<li class="divider-vertical"></li>
							<li class="dropdown">
								<a href="#" class="dropdown-toggle" data-toggle="dropdown">
									<i class="icon-user icon-white"></i> <?php echo $user; ?> <!--Mostramoe el user logeado -->
								    <span class="caret"></span>
								</a>
								<ul class="dropdown-menu">
									<li><a href="registrarUsuario.php"><i class="icon-plus-sign"></i> Registrar Usuario</a></li>
									<li class="active"><a href="#"><i class="icon-wrench"></i> Configuración de la cuenta</a></li>
									<li class="divider"></li>
									<li><a href="cerrar.php">Cerrar Sesion</a></li>
								</ul>
							</li>
							<li class="divider-vertical"></li>
							<?php 
								date_default_timezone_set('America/Bogota'); 
						        $fecha = date("Y-m-d");
						        echo '<li><a href="#" style="font-weight: bold;">Fecha: '.$fecha.'</a></li>';
					        ?>
						</ul>
					</div>
				</div>
			</div>
		</div>
	</header>
	 <aside id="mensaje"></aside><!--menssaje de exito del registro o de error-->
	 <aside id="mensajeError"></aside><!--menssaje de exito del registro o de error-->

	<section>
		<div class="container">
			<div class="hero-unit">
				<br><br><br><br><br><br><br>
			</div>
		</div>
	</section>

    <!--Primer articulo... -->
	<article class="container well" id="fondo">
		<div class="row">
			<div class="span2"></div>
			<div class="span8 well">
				<h1>Configuración de la cuenta</h1><br>
				<div class="mensaje"></div><!--mensaje de confirmacion o de error-->
				<table class="table table-hover table-condensed">
					<tbody>
						<tr>
							<td id="userEdit">Nombre:</td>
							<td id="resul"><?php echo $user?></td>
							<td><a href=<?php echo "$id"; ?> id="editNomUser" class="btn btn-info">Editar</a></td>
						</tr>
						<tr>
							<td id="userEdit">Contraseña:</td>
							<td>********</td>
							<td><a href=<?php echo "$id"; ?> id="editContraUser" class="btn btn-info">Editar</a></td>
						</tr>
					</tbody>
				</table>
				<a class="btn btn-danger" id="limpiar">Limpiar Base de Datos</a>
			</div>
		</div>
	</article>
     <!--MOdificamos el nombre del usuariooo-->
	<div class="hide" id="formulario" title="Editar Nombre">
     	<form action="acciones.php" method="post" class="limpiar">
     		<input type="hidden" id="id_registro" name="id_registro" value="0">
     		<div class="control-group">
     			<label for="nombre" class="control-label">Nombre</label>
     			<div class="controls">
     				<input type="text" name="nombre" id="nombre" autofocus  MAXLENGTH=8>
     			</div>
     		</div>
     		<div class="control-group">
     			<div class="controls">
     				<input type="hidden" name="editNomUser">
     				<button type="submit" id="UserModificar" name="editNomUser" class="btn btn-success">Modificar</button>
     				<button id="UserCancelar" class="btn btn-danger">Cancelar</button>
     			</div>
     		</div>
     	</form>
     </div>

	<!--Modificamos la contraseña del usuairo-->
	<div class="hide" id="formularioContraseña" title="Editar Clave">
     	<form action="acciones.php" method="post" id="contraseñaValidar">
     		<input type="hidden" id="id_registro2" name="id_registro2" value="0">
     		<div class="control-group">
     			<label for="contraseñaActual" class="control-label">Contraseña Actual</label>
     			<div class="controls">
     				<input type="password" name="contraseñaA" id="contraseña"  required autofocus>
     			</div>
     			<div class="controls">
     				<label for="ontraseñaNueva">Contraseña Nueva</label>
     				<input type="password" name="contraseñaN" required>
     			</div>
     		</div>
     		<div class="control-group">
     			<div class="controls">
     				<input type="hidden" name="UserModificarContra">
     				<button type="submit" id="UserModificarContra" class="btn btn-success">Modificar</button>
     				<button id="UserCancelar" class="btn btn-danger">Cancelar</button>
     			</div>
     		</div>
     	</form>
     </div>

     <!--codigo para eliminar-->
    <div class="hide" id="deleteBase" title="Limpiar Base de Datos">
	    <form action="acciones.php" method="post">
	    	<div class="control-group">
	    		<label for="activoElim" class="alert alert-danger">
	    		    <strong>Esta seguro de Limpiar la Base de datos</strong><br>
	    		</label>
	    		<input type="hidden" name="deleteBaseDatos"/> 
			    <button type="submit" class="btn btn-success">Aceptar</button>
			    <button id="cancelar" name="cancelar" class="btn btn-danger">Cancelar</button>
	    	</div>
	    </form>
	</div>

	<footer>
		<h2 id="pie"><img src="../img/twitter.png">  @Jandrey15 - 2013</h2>
		<!-- <h2 id="pie"><img src="img/copyright.png" alt="Autor"> JA Serrano</h2> -->
		<div> <br>
			<p id="pie">AJ 1.0</p>
		</div>
	</footer>
</body>
</html>