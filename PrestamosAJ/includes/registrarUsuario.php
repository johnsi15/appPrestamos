<!DOCTYPE HTML>
<html lang="es">
<head>
	<meta charset="UTF-8">
	<title>Registrar Usuario</title>
	<link rel="stylesheet" href="../css/bootstrap.css">
	<link rel="stylesheet" href="../css/smoothness/jquery-ui.css">
	<link rel="stylesheet" href="../css/estilos.css">
	<script src="../js/jquery.js"></script>
	<script src="../js/jquery-ui.js"></script>
	<script src="../js/bootstrap.js"></script>
	<script src="../js/jquery.validate.js"></script>
	<script src="../js/funciones.js"></script>
	<!--<script src="../js/registrar.js"></script>-->
	<!--<script src="../js/eliminar.js"></script>-->
	<style>
	    h1{
	    	text-align: center;
	    }
	    th{
	    	font-size: 28px;
	    }
	    td{
	    	font-size: 24px;
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
	        margin-left: 480px;
	        position: fixed;
       	}
       	#mensajeError{
       		float: left;
	        margin-left: 680px;
	        position: fixed;
       	}
        .hero-unit{
        	margin-top: 30px;
        	text-align: center;
        	background-image: url('../img/gim2.jpg');
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

	<?php
      session_start();
      if(isset($_SESSION['id_user'])){
            $user = $_SESSION['nombre'];
            $id = $_SESSION['id_user'];
      }else{
      		header('Location: index.php');
      }
	?>
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
					<a href="../menu.php" class="brand">Nombre del Gim</a>
					<div class="nav-collapse collapse">
						<ul class="nav" >
							<li class="divider-vertical"></li>
							<li><a href="../menu.php"><i class="icon-home icon-white"></i>Inicio</a></li>
							<li class="divider-vertical"></li>
							<li id="formMenu" class="dropdown">
									<a id="menuOpen" class="dropdown-toggle" data-toggle="dropdown">
										Registrar
										<span class="caret"></span>
									</a>
								<ul class="dropdown-menu pull-right">
									<div class="span4" id="registrarNew">
										<form action="acciones.php" method="post" id="registrarEstudiante" style="margin-left: 30px;" class="limpiar">
											<label>N° Identificación:</label>
											<input type="text" name="codigo" id="foco" autofocus required>
											<label>Nombre:</label>
											<input type="text" name="nombre" id="foco" autofocus required/>
											<label>Edad:</label>
											<input type="text" name="edad" required/>
											<label>Peso - Kg:</label>
											<input type="text" name="peso" required/>
											<label>Altura - M:</label>
											<input type="text" name="altura" required/>
											<label>Fecha Vencimiento:</label>
											<input type="date" name="fecha2" required/>
											<label>Pago:</label>
											<input type="text" name="pago" value="0"/>
											<label>Condición:</label>
											<select name="condicion" id="recar">
							    				<option value="No Pago">No Pago</option>
							    				<option value="Pago">Pago</option>
							    				<option value="Abono">Abono</option>
							    			</select>
							    			<input type="hidden" name="registrarEstudiante">
							    			<button type="submit" class="btn btn-success">Registrar</button>
										</form>
									</div>
								</ul>
							</li>
							<li class="divider-vertical"></li>
							<li class="dropdown">
								<a href="#" class="dropdown-toggle" data-toggle="dropdown">
									Clientes
									<span class="caret"></span>
								</a>
								<ul class="dropdown-menu">
									<li><a href="actualizarDatos.php">Actualizar Datos Personales</a></li>
									<li><a href="actualizarTiempo.php">Actualizar Tiempo</a></li>
									<li><a href="pagoTiempo.php">Deben Pagar</a></li>
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
									<li class="active"><a href="#"><i class="icon-plus-sign"></i> Registrar Usuario</a></li>
									<li><a href="editarUsuario.php"><i class="icon-wrench"></i> Configuración de la cuenta</a></li>
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
		<div class="container" id="validate">
			<div class="hero-unit">
				<br><br><br><br><br><br><br>
			</div>
		</div>
	</section>

    <!--Primer articulo... -->
	<article class="container well" id="fondo">
		<div class="row">
			<div class="span1"></div>
			<div class="span8 well" id="fondo" style="margin-left: 8%;">
				<h1>Registrar Usuario</h1><br>
				<div class="mensaje"></div><!--mensaje de confirmacion o de error-->
				<div class="span3 well" id="registrarUser" style="margin-left: 30%;">
					<form action="acciones.php" method="post" id="validarRegistroUser">
						<div class="control-group">
							<label for="nombre">Nombre</label>
							<input type="text" name="nombre" id="foco" required autofocus MAXLENGTH=5>
							<label for="contraseña">Contraseña</label>
							<input type="password" name="contraseña">
						</div>
						<input type="hidden" name="registrarUser">
						<button type="submit" class="btn btn-primary">Registrar</button>
					</form>
				</div>
			</div>
		</div>
	</article>

	<footer>
		<h2 id="pie"><img src="../img/copyright.png" alt="Autor"> John Andrey Serrano - 2013</h2>
		<div id="pie"> <br>
			<p>Gim Version 1.0</p>
		</div>
	</footer>
</body>
</html>