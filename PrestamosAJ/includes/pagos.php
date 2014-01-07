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
	<?php
      session_start();
      if(isset($_SESSION['id_user'])){
           $user = $_SESSION['nombre'];
      }else{
      	header('Location: ../index.php');
      }
	?>
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
      	// ver mas detalles del pago
      	$('[data-toggle=popover]').popover({html:true});
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
			  	   	//cuando mandamos datos vacios pasa poraca
      	       	$.post('acciones.php',data , function(resp){
			  	   	$('#verPagos').empty();//limpiar los datos
			  	   	$('#verPagos').html(resp);
			  	   	$('[data-toggle=popover]').popover({html:true});
	      	    	console.log('poraca paso joder....');
			  	},'html');
      	    }else{
      	       	$.post('acciones.php',data , function(resp){
			  	   	  console.log('porque pasa poraca en que momento');
			  	   	$('.pagination').remove();
			  	   	$('#verPagos').empty();//limpiar los datos
			  	   	$('#verPagos').html(resp);
			  	   	$('[data-toggle=popover]').popover({html:true});
	      	    	//console.log(resp);
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
					  	 	var nuevosGastos = $(html).find('#pagos tbody'),
					  	 		nuevaPag     = $(html).find('.pagination'),
					  	 		tabla        = $('#pagos');
					  	    tabla.find('#verPagos').append(nuevosGastos.html());
					  	 	tabla.after(nuevaPag.hide());
					  	 	$('#cargando').hide();
					  	 	$('[data-toggle=popover]').popover({html:true});
					  	}
					});
					  $('.pagination').remove();
				}
		  	}
		});

		$('#otroMomen').click(function(){
			$('#notiRenovar').remove();
		});

		$('#cerrar').click(function(){
			$('#notiRenovar').remove();
		});

		// codigo de los detalles de los prestamos
		$('#pres').click(function(){
			$('#detallesPrestamos').toggle("fast");
			$('#pres').css('display','none');
		});

		$('#minimizar').click(function(){
			$('#detallesPrestamos').css('display','none');
			$('#pres').css('display','block');
		});


	  });/*fin del document------------------*/
	</script>
</head>
<body>
	<header>
		<div class="navbar navbar-fixed-top navbar-inverse">
			<div class="navbar-inner">
				<div class="container">
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
									<li><a href="editarUsuario.php"><i class="icon-wrench"></i> Configuración de la cuenta</a></li>
									<li class="divider"></li>
									<li><a href="cerrar.php">Cerrar Sesion</a></li>
								</ul>
							</li>
							<li class="divider-vertical"></li>
							<li class="dropdown">
								<a href="#" class="dropdown-toggle" data-toggle="dropdown">
									<i class="icon-globe" id="conteo">
										<!--notificamos en el menu cuantos clientes deben pagar-->
									<?php 
										require_once('funciones.php');
										$objeto = new funciones();
            							$objeto->notificarFecha();
										$conteo = $objeto->verificar();
										if($conteo > '0'){
											echo "<h4 id='aviso'>$conteo</h4>";
											?>
										<script>
										 	$(document).ready(function(){
										 		$('#conteo').addClass('icon-white');
										 		$('#conteo').click(function(){
										 			$('#conteo').removeClass('icon-white');
										 			// $('#aviso').css('background','rgba(255,0,0,0.8)');
										 			$('#aviso').remove();
										 		});
										 	});
										</script>
									<?php
										}
									?>
									</i>
								</a>
								<ul class="dropdown-menu">
									<div id="scrollNotif">
										<table class="table table-hover table-bordered table-condensed">
											<thead>
												<tr>
													<th>N°</th>
													<th>Nombre</th>
													<th>Cuota</th>
												</tr>
											</thead>
											<tbody id="noticaciones">
												<?php 
												   require_once('funciones.php');
												   $objeto = new funciones();
												   $objeto->verClientesDias();
												?>
											</tbody>
										</table>
							    	</div>
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
		<div class="row">
            <input type="text" name="buscar" id="buscar" class="search-query" placeholder="Buscar" autofocus>
				<h1 style='color: #df0024;'>Pagos</h1><br>
				<div class="span4">
					<a class="btn btn-large btn-primary" id="nuevo">Hacer Pago</a>
				</div>
				<div class="span12">
					<hr>
					<table  id="pagos" class="table table-hover table-bordered table-condensed">
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
			    	 	  $objeto->paginacionPagos();
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

     <!-- detalles de prestamos -->
     <div class="hide" id="detallesPrestamos">
     		<a class="btn btn-small btn-primary" id="minimizar"> - </a>
     	<div class="span3 well" id="scroll">
     		<table  class="table table-hover table-bordered table-condensed">
				<thead>
					<tr>
						<th>N°</th>
						<th>Cut</th>
						<th>Cpt</th>
						<th>Int</th>
					</tr>
				</thead>
				<tbody>
					<?php 
						require_once('funciones.php');
						$objeto = new funciones();
						$objeto->verDetallesPrestamos();
					?>
				</tbody>
			</table>
		</div>
     </div>
     <a href="#detalles" id="pres" class="btn btn-large btn-primary">Prestamos</a>
     
	<footer>
		<h2 id="pie"><img src="../img/twitter.png">  @Jandrey15 - 2013</h2>
		<!-- <h2 id="pie"><img src="img/copyright.png" alt="Autor"> JA Serrano</h2> -->
		<div> <br>
			<p id="pie">AJ 1.0</p>
		</div>
	</footer>
</body>
</html>