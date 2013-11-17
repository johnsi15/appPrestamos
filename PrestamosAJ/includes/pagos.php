<!DOCTYPE HTML>
<html lang="es">
<head>
	<meta charset="UTF-8">
	<title>pagos</title>
	<link rel="stylesheet" type="text/css" href="../css/bootstrap.css">
	<link rel="stylesheet" type="text/css" href="../css/bootstrap-responsive.css">
	<link rel="stylesheet" type="text/css" href="../css/smoothness/jquery-ui.css">
	<link rel="stylesheet" type="text/css" href="../css/estilos.css">
	<script src="../js/jquery.js"></script>
	<script src="../js/jquery-ui.js"></script>
	<script src="../js/jquery.validate.js"></script>
	<script src="../js/funciones.js"></script>
	<script src="../js/bootstrap.js"></script>
	<script src="../js/pagos.js"></script>
	<script src="../js/editar.js"></script>
	<script src="../js/eliminar.js"></script>
</head>
<body>
	<style>
		h1{
			text-align: center;
		}
		label.error{
			float: none; 
			color: red; 
			padding-left: .5em;
		    vertical-align: middle;
		    font-size: 12px;
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
		#fondo{
			background: #feffff;
		}
		#mensaje{
	         float: left;
	    	margin-left: 20%;
	    	position: fixed;
	    	top: 18%;
	    	display: block;
       	}
       	#mensajeError{
       		float: left;
	    	margin-left: 20%;
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
      	/*funcionalidad del combo box*/
      	$('#nombre').change(function(){
      		var id = $('#nombre').val();
      		$('#prestamos').load('datos.php?id='+id);
      	});
      	var id = $('#nombre').val();

      	/*-------------------------------------*/
      	var menu = $('#bloque');
		var contenedor = $('#bloque-contenedor');
		var menu_offset = menu.offset();
		  // Cada vez que se haga scroll en la página
		  // haremos un chequeo del estado del menú
		  // y lo vamos a alternar entre 'fixed' y 'static'.
		  menu.css("display", "none");
		$(window).on('scroll', function() {
		    if($(window).scrollTop() > menu_offset.top) {
		      menu.addClass('bloqueFijo');
		      menu.css("display", "block");
		    } else {
		      menu.removeClass('bloqueFijo');
		      menu.css("display", "none");
		    }
		});

		/*____________________________________________________-*/
		$('#IrInicio').click(function () {
		    $('html, body').animate({
		           scrollTop: '0px'
		    },
		    1500);
		        $('#buscar').focus();
		       //return false;
		});

		/*______________________________________________*/
        $("#menuOpen").mouseout(function(){
            //$("#formMenu").removeClass('open');
	    }).mouseover(function(){
	        $("#formMenu").addClass('open');
	        $("#foco").focus();
        });

	    /*_______________________________________________*/
	    $('#buscar').live('keyup',function(){
		  	var data = 'queryPago='+$(this).val();
		  	//console.log(data);
      	    if(data =='queryPago=' ){
      	       	$.post('acciones.php',data , function(resp){
			  	   	//console.log(resp);
			  	   	$('#verVencimiento').empty();//limpiar los datos
			  	   	$('#verVencimiento').html(resp);
	      	    	console.log('poraca paso joder....');
			  	},'html');
      	    }else{
      	       	$.post('acciones.php',data , function(resp){
			  	   	  //console.log(resp);
			  	   	$('.pagination').remove();
			  	   	$('#verVencimiento').empty();//limpiar los datos
			  	   	$('#verVencimiento').html(resp);
	      	    	console.log(resp);
			  	},'html');
      	    }
		});

		/*_________________________________________*/
		$(window).scroll(function(){
		  	if($(window).scrollTop() >= $(document).height() - $(window).height()){
		  		if($('.pagination ul li.next a').length){
			  		$('#cargando').show();
			  		 /*_____________________________________*/
					$.ajax({
					  	type: 'GET',
					  	url: $('.pagination ul li.next a').attr('href'),
					  	success: function(html){
					  	 		//console.log(html);
					  	 	var nuevosGastos = $(html).find('table tbody'),
					  	 		nuevaPag     = $(html).find('.pagination'),
					  	 		tabla        = $('table');
					  	    tabla.find('tbody').append(nuevosGastos.html());
					  	 	tabla.after(nuevaPag.hide());
					  	 	$('#cargando').hide();
					  	}
					});
					  $('.pagination').remove();
				}
		  	}
		});


	  });/*fin del document------------------*/
	</script>

	<?php
      session_start();
      if(isset($_SESSION['id_user'])){
           $user = $_SESSION['nombre'];
      }else{
      	header('Location: ../index.php');
      }
	?>

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
									<li class="active"><a href="#">Pagos</a></li>
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
		<div class="container">
			<div class="hero-unit">
				<br><br><br><br><br><br><br>
			</div>
		</div>
	</section>

	<div class="span2"> <div id="bloque"><aside class="well" id="bloque-contenedor" style="text-align: center; "><a href="#" id="IrInicio">Volver Arriba</a></aside></div></div> 

    <!--seccion principal de la pagina-->
	<section class="container well" id="fondo">
		<div class="row" id="aviso">
            <input type="text" name="buscar" id="buscar" class="search-query" placeholder="Buscar Nombre" autofocus>
				<h1 style='color: #df0024;'>Pagos</h1><br>
				<div class="span4">
					<a class="btn btn-large btn-primary" id="nuevo">Hacer Pago</a>
				</div>
				<div class="span12">
					<hr>
					<table  class="table table-hover table-bordered table-condensed">
						<thead>
							<tr>
								<th>Nombre</th>
								<th>Fecha Pago</th>
								<th>Abono</th>
								<th>Interes</th>
								<th>Saldo</th>
							</tr>
						</thead>
						<tbody id="verPagos">
							<?php 
								require_once('funciones.php');
								$objeto = new funciones();
								$objeto->verPagos();
							?>
						</tbody>
					</table>
					<div id="cargando" style="display: none;"><img src="../img/loader.gif" alt=""></div>
			        <div id="paginacion">
			    	 	 <?php 
			    	 	  require_once('funciones.php');
			    	 	  $objeto = new funciones();
			    	 	  //$objeto->paginacionVensimientos();
				    	 ?>
			    	</div>
				</div>
		</div>
	</section>

	 <!--modificamos los pagos que se vencieron-->
     <div class="hide" id="nuevoPago" title="Nuevo Pago">
     	<form action="acciones.php" method="post" id="registrarPago">
     		<input type="hidden" id="id_registroVen" name="id_registroVen" value="0">
     			<label>Nombre:</label>
				<select id='nombre' name='nombre' autofocus>
					<?php
                        require_once('../includes/funciones.php');
                        $combo = new funciones();
                        $combo->comboClientes();
					?>
				</select>
				<label>N° Prestamo</label>
				<div id="prestamos">
					<select name='prestamo'>
						<?php
                        	require_once('../includes/funciones.php');
                       	 	$combo = new funciones();
                        	$combo->comboPrestamos();
						?>
					</select>
				</div>
     			<label>Pago Capital:</label>
				<input type="text" name="pago" id="pago" required/>
     			<label>Pago Interes:</label>
				<input type="text" name="interes" id="interes" required/>
				<input type="hidden" name="registrarPago">
				<button type="submit" id="registrarPago" class="btn btn-success">Aceptar</button>
				<button id="cancelar" class="btn btn-danger">Cancelar</button>
     	</form>
     </div>
     <a href="#" id="pres" class="btn btn-large">Prestamos</a>
	<footer>
		<h2 id="pie"><img src="../img/copyright.png" alt="Autor"> John Andrey Serrano - 2013</h2>
		<div id="pie"> <br>
			<p>Prestamos AJ Version 1.0</p>
		</div>
	</footer>
</body>
</html>