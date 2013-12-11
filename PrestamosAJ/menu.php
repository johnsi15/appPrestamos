<!DOCTYPE HTML>
<html lang="es">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>Menu</title>
	<link rel="stylesheet" type="text/css" href="css/bootstrap.css">
	<link rel="stylesheet" type="text/css" href="css/bootstrap-responsive.css">
	<link rel="stylesheet" type="text/css" href="css/smoothness/jquery-ui.css">
	<link rel="stylesheet" type="text/css" href="css/estilos.css">
	<link rel="stylesheet" type="text/css" href="css/estilosMenu.css">
	<script src="js/jquery.js"></script>
	<script src="js/jquery-ui.js"></script>
	<script src="js/bootstrap.js"></script>
	<script src="js/jquery.validate.js"></script>
	<script src="js/funciones.js"></script>
	<script src="js/editar.js"></script>
	<script src="js/eliminar.js"></script>
	<!--<script src="js/registrarPrecios.js"></script>-->
	<!--<script src="js/notas.js"></script>-->
	<?php
	      session_start();
	      if(isset($_SESSION['id_user'])){
	            $user = $_SESSION['nombre'];
	      }else{
	      	header('Location: index.php');
	      }
	?>
	<style>
	  
	</style>
		
	<script>
      $(document).ready(function(){
      	//activar el ver mas
      	$('[data-toggle=popover]').popover({html:true});
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


         /*____________________________________________-*/
         $('.cerrar').click(function(){
         	 //$('#aviso').css("display","none");
         	$('#aviso').fadeOut("slow");

         	/*var data = 'verEstu='+'bien';
             console.log(data);
      	    $.post('includes/acciones.php',data , function(resp){
			  	   	  //console.log(resp);
			  	$('#verEstu').empty();//limpiar los datos
			  	$('#verEstu').html(resp);
			  	console.log(resp);
	      	    console.log('poraca paso joder....');
			},'html');
         	// alert("Bien");*/
         });

           /*_______________________________________________*/
	    $('#buscar').live('keyup',function(){
		  	var data = 'queryMenu='+$(this).val();
		  	//console.log(data);
      	    if(data =='queryMenu=' ){
      	       	$.post('includes/acciones.php',data , function(resp){
			  	   	//console.log(resp);
			  	   	$('#verClien').empty();//limpiar los datos
			  	   	$('#verClien').html(resp);
	      	    	// console.log('poraca paso joder....');
	      	    	$('[data-toggle=popover]').popover({html:true});
			  	},'html');
      	    }else{
      	       	$.post('includes/acciones.php',data , function(resp){
			  	   	  //console.log(resp);
			  	   	$('.pagination').remove();
			  	   	$('#verClien').empty();//limpiar los datos
			  	   	$('#verClien').html(resp);
	      	    	// console.log(resp);
	      	    	$('[data-toggle=popover]').popover({html:true});
			  	},'html');
      	    }
		});
	   /*________________________________________________________________________*/
		$(window).scroll(function(){
		  	if($(window).scrollTop() >= $(document).height() - $(window).height()){
		  		if($('.pagination ul li.next a').length){
			  		$('#cargando').show();
			  		 /*_____________________________________*/
					$.ajax({
					  	type: 'GET',
					  	url: $('.pagination ul li.next a').attr('href'),
					  	success: function(html){
					  	 		console.log(html);
					  	 	var nuevosGastos = $(html).find('#clientes tbody'),
					  	 		nuevaPag     = $(html).find('.pagination'),
					  	 		tabla        = $('#clientes');
					  	    tabla.find('#verClien').append(nuevosGastos.html());
					  	 	tabla.after(nuevaPag.hide());
					  	 	$('#cargando').hide();
					  	 	$('[data-toggle=popover]').popover({html:true});
					  	}
					});
					  $('.pagination').remove();
				}
		  	}
		});

		var ventana_ancho = $(window).width();

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
					<a href="menu.php" class="brand">Prestamos AJ</a>
					<div class="nav-collapse collapse">
						<ul class="nav" >
							<li class="divider-vertical"></li>
							<li class="active"><a href="menu.php"><i class="icon-home icon-white"></i>Inicio</a></li>
							<li class="divider-vertical"></li>
							<li class="dropdown" id="espacio">
								<a href="#" class="dropdown-toggle" data-toggle="dropdown">
									Clientes
									<span class="caret"></span
								</a>
								<ul class="dropdown-menu">
									<li><a href="includes/caja.php">Caja</a></li>
									<li><a href="includes/actualizarDatos.php">Registrar</a></li>
									<li><a href="includes/prestamos.php">Prestamos</a></li>
									<li><a href="includes/pagos.php">Pagos</a></li>
									<li><a href="includes/renovar.php">Renovar Credito</a></li>
									<li><a href="includes/mes.php">Mes</a></li>
								</ul>
							</li>
							<li class="divider-vertical"></li>
							<li><a href="includes/reporte.php"><i class="icon-book icon-white"></i> Reporte</a></li>
							<li class="divider-vertical"></li>
							<li class="dropdown">
								<a href="#" class="dropdown-toggle" data-toggle="dropdown">
									<i class="icon-user icon-white"></i> <?php echo $user; ?> <!--Mostramoe el user logeado -->
								    <span class="caret"></span>
								</a>
								<ul class="dropdown-menu">
									<li><a href="includes/registrarUsuario.php"><i class="icon-plus-sign"></i> Registrar Usuario</a></li>
									<li><a href="includes/editarUsuario.php"><i class="icon-wrench"></i> Configuración de la cuenta</a></li>
									<li class="divider"></li>
									<li><a href="includes/cerrar.php">Cerrar Sesion</a></li>
								</ul>
							</li>
							<li class="divider-vertical"></li>
								<li class="dropdown">
								<a href="#" class="dropdown-toggle" data-toggle="dropdown">
									<i class="icon-globe" id="conteo">
										<!--notificamos en el menu cuantos clientes deben pagar-->
									<?php 
										require_once('includes/funciones.php');
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
												   require_once('includes/funciones.php');
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
						        $fecha = date("Y-M-d");
						        echo '<li><a href="#" style="font-weight: bold;">Fecha: '.$fecha.'</a></li>';
					        ?>
						</ul>
					</div>
				</div>
			</div>
		</div>
	</header>
		<aside id="mensaje"></aside><!--menssaje de exito del registro o de error-->
		<aside id="mensajeError"></aside><!--menssaje  de error-->
		
	<section>
		<div class="container">
			<div class="hero-unit">
				<br><br><br><br><br><br><br>
			</div>
		</div>
	</section>

   <div class="span2"> <div id="bloque"><aside class="well" id="bloque-contenedor" style="text-align: center; "><a href="#" id="IrInicio">Volver Arriba</a></aside></div></div> 

    <!--Primer articulo... -->
	<article class="container well" id="fondo">
		<input type="text" name="buscar" id="buscar" class="search-query" placeholder="Buscar" autofocus>
		<div class="row">         
			<h1>Prestamos AJ Clientes</h1><br>
			<div class="span12">
				<table id="clientes" class="table table-hover table-striped table-bordered table-condensed">
					<thead>
						<tr>
							<th>N°</th>
							<th>Nombre</th>
							<th>Direccion</th>
							<th>Telefono</th>
							<th>Saldo</th>
						</tr>
					</thead>
					<tbody id="verClien">
						<?php 
						   require_once('includes/funciones.php');
						   $objeto = new funciones();
						   $objeto->verClientes();
						?>
					</tbody>
				</table>
				<div id="cargando" style="display: none;"><img src="img/loader.gif" alt=""></div>
		        <div id="paginacion">
		    	 	 <?php 
		    	 	  require_once('includes/funciones.php');
		    	 	  $objeto = new funciones();
		    	 	  $objeto->paginacionClientesMenu();
			    	 ?>
		    	</div>
			</div>
		</div>
		<div class="row">
			
		</div>
	</article>

     <!--Codigo para modificar pago-->
     <div class="hide" id="editarPago" title="Editar Registro">
     	<form action="includes/acciones.php" method="post">
     		<input type="hidden" id="id_registro" name="id_registro" value="0">
     			<label>Nombre:</label>
				<input type="text" name="nombre" id="nombre" disabled/>
     			<label>Pago:</label>
				<input type="text" name="pago" id="pago" autofocus/>
				<label>Condición:</label>
				<select name="condicion" id="con">
					<option value="No Pago">No Pago</option>
					<option value="Pago">Pago</option>
					<option value="Abono">Abono</option>
				</select>
				<input type="hidden" name="modificarPago">
				<button id="modificarPago" class="btn btn-success">Modificar</button>
				<button id="cancelar" class="btn btn-danger">Cancelar</button>
     	</form>
     </div>

    <!--Aca va el codigo para eliminar-->
    <div class="hide" id="deleteReg" title="Eliminar Estudiante">
	    <form action="includes/acciones.php" method="post">
	    	<fieldset id="datosOcultos">
	    		<input type="hidden" id="id_delete" name="id_delete" value="0"/>
	    	</fieldset>
	    	<div class="control-group">
	    		<label for="activoElim" class="alert alert-danger">
	    		    <strong>Esta seguro de Eliminar este estudiante</strong><br>
	    		</label>
	    		<input type="hidden" name="deleteEstudianteMenu"/> 
			    <button type="submit" class="btn btn-success">Aceptar</button>
			    <button id="cancelar" name="cancelar" class="btn btn-danger">Cancelar</button>
	    	</div>
	    </form>
	</div>
	
	<footer>
		<h2 id="pie"><img src="img/twitter.png">  @Jandrey15 - 2013</h2>
		<!-- <h2 id="pie"><img src="img/copyright.png" alt="Autor"> JA Serrano</h2> -->
		<div> <br>
			<p id="pie">AJ 1.0</p>
		</div>
	</footer>
</body>
</html>