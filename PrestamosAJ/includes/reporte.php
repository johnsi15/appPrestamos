<!DOCTYPE HTML>
<html lang="es">
<head>
	<meta charset="UTF-8">
	<title>Reportes</title>
	<link rel="stylesheet" href="../css/bootstrap.css">
	<link rel="stylesheet" href="../css/estilos.css">
	<script src="../js/jquery.js"></script>
	<script src="../js/jquery.validate.js"></script>
	<script src="../js/bootstrap.js"></script>
	<script src="../js/buscar.js"></script>
	<script src="../js/calculos.js"></script>
	<script src="../js/funciones.js"></script>
	<!--<script src="../js/calcularReporte.js"></script>-->
	<?php
      session_start();
      if(isset($_SESSION['id_user'])){
         $user = $_SESSION['nombre'];
      }else{
      	header('Location: ../index.php');
      }
	?>
	<style>
	    body{
	    	font-family: "Helvetica Neue", "Helvetica", Arial, Verdana, sans-serif;
	    }
		h1{
			text-align: center;
		}
		th{
	    	font-size: 24px;
	    }
	    td{
	    	font-size: 20px;
	    }
		p{
	    	color: #df0024;
	    	font-size: 20px;
	    }
	    label.error{
			float: none; 
			color: red; 
			padding-left: .5em;
		    vertical-align: middle;
		    font-size: 12px;
		}
		#fondo{
			background: #feffff;
		}
		#fuente{
			font-size: 23px;
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
		#titulo{
			text-align: center;
			font-size: 32px;
			color: #ba0d0d;
		}
        .hero-unit{
        	margin-top: 7%;
        	height: 200px;
        	background-image: url('../img/dinero-1.jpg');
        }
	</style>	
	<script>
      $(document).ready(function() {
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
	<header class="container">
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
							<li class="active"><a href="#"><i class="icon-book icon-white"></i> Reporte</a></li>
							<li class="divider-vertical"></li>
							<li class="dropdown">
								<a href="#" class="dropdown-toggle" data-toggle="dropdown">
									<i class="icon-user icon-white"></i> <?php echo $user; ?> <!--Mostramoe el user logeado -->
								    <span class="caret"></span>
								</a>
								<ul class="dropdown-menu">
									<li><a href="registrarUsuario.php"><i class="icon-plus-sign"></i> Registrar Usuario</a></li>
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
    <section class="container">
      	<div class="hero-unit">
			
		</div>
    </section>
	<article class="container well" id="fondo">
			<div id="mensaje"></div>
			<p id="titulo">Calcular Ganancias</p><br><br>
			<div id="mensajeCalculo"></div><!--Mensaje de exito o de Error.....-->
		<div class="row">
			<div class="span3 well" id="fondo" style="margin-left: 60px;">
				<form action="acciones.php" method="post">
					<label for="fecha" id="fuente">Fecha Inicial</label>
					<input type="date" name="fecha1">
					<label for="fecha2" id="fuente">Fecha Final</label>
					<input type="date" name="fecha2">
					<input type="hidden" name="calcular"><br><br>
					<button id="calcularReporte" name="calcular" class="btn btn-success">Calcular</button>
				</form>

			</div>
				<div class="span6" id="resultado">
					<h3 class="well">Calculo: </h3>
				</div>
		</div>
		<hr>
	    <div class="row">
				<div class="span3"></div>
				<div class="span6 well" id="fondo">
					<p id="titulo">Ganancias por Mes</p><br>
					<h6>Las ganancias se calculan sobre los Interes.</h6>
					<table class="table table-hover table-bordered">
						<thead>
							<tr>
								<th>Año</th>
								<th>Mes</th>
								<th>Ganancias</th>
							</tr>
						</thead>
						<tbody>
							<?php
							  require_once('funciones.php');
							  $objeto = new funciones();
							  $objeto->calculosMes(); 
							?>
						</tbody>
					</table>
				</div>
		</div>
	</article>

	<footer>
		<h2 id="pie"><img src="../img/twitter.png">  @Jandrey15 - 2013</h2>
		<!-- <h2 id="pie"><img src="img/copyright.png" alt="Autor"> JA Serrano</h2> -->
		<div> <br>
			<p id="pie">AJ 1.0</p>
		</div>
	</footer>
</body>
</html>