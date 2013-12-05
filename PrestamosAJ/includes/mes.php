<!DOCTYPE HTML>
<html lang="es">
<head>
	<meta charset="UTF-8">
	<title>Mensualidad</title>
	<link rel="stylesheet" type="text/css" href="../css/bootstrap.css">
	<link rel="stylesheet" type="text/css" href="../css/bootstrap-responsive.css">
	<link rel="stylesheet" type="text/css" href="../css/smoothness/jquery-ui.css">
	<link rel="stylesheet" type="text/css" href="../css/estilos.css">
	<script src="../js/jquery.js"></script>
	<script src="../js/jquery-ui.js"></script>
	<script src="../js/jquery.validate.js"></script>
	<script src="../js/funciones.js"></script>
	<script src="../js/bootstrap.js"></script>
	<script src="../js/editar.js"></script>
	<script src="../js/pagos.js"></script>
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
	    	font-size: 2em;
	    }
	    td{
	    	font-size: 1.3em;
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

	    /*_______________________________________________
	    $('#buscar').live('keyup',function(){
		  	var data = 'queryTiempo='+$(this).val();
		  	//console.log(data);
      	    if(data =='queryTiempo=' ){
      	       	$.post('acciones.php',data , function(resp){
			  	   	//console.log(resp);
			  	   	$('#verDatos').empty();//limpiar los datos
			  	   	$('#verDatos').html(resp);
	      	    	console.log('poraca paso joder....');
			  	},'html');
      	    }else{
      	       	$.post('acciones.php',data , function(resp){
			  	   	  //console.log(resp);
			  	   	$('.pagination').remove();
			  	   	$('#verDatos').empty();//limpiar los datos
			  	   	$('#verDatos').html(resp);
	      	    	console.log(resp);
			  	},'html');
      	    }
		});*/

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
									<li class="active"><a href="mes.php">Mes</a></li>
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
		<!-- <input type="text" name="buscar" id="buscar" class="search-query" placeholder="Buscar Nombre" autofocus>	 -->
		<div class="row">
			<h1>Mensualidad</h1> <br>
			<aside id="mes">
				<?php
					require_once('funciones.php');
					$objeto = new funciones();
					$objeto->fechasMes();
				?>
			</aside>
			<?php
				date_default_timezone_set('America/Bogota'); 
		        $fecha = date("Y-m-d  H:i:s");//fecha actual bien 
		        $d = date("d");
		        if($d == '02' or $d == '03' or $d == '04' or $d == '05' or $d == '06'){
		    ?>
		    <div id="refrescarPrestamos">
			    <form action="acciones.php" method="post">
			    	<input type="hidden" name="refrescarPrestamos">
					<button type="submit" class="btn btn-success" id="clickPrestamo">Activar Prestamos</button>
			    </form>
		    </div>
		    <?php
		        }else{
		    	?>
		    	<button class="btn btn-success" id="clickPrestamo" disabled>Activar Prestamos</button>
		    <?php
		        }

			?>
			<div class="span12">
			<hr>
				<table class="table table-hover table-bordered table-striped table-condensed">
					<thead>
						<tr>
							<th>N°</th>
							<th>Nombre</th>
							<th>Saldo</th>
						</tr>
					</thead>
					<tbody id="verPagos">
						<?php 
						   require_once('funciones.php');
						   $objeto = new funciones();
						   $objeto->mesualidad();
						?>
					</tbody>
				</table>
			</div>
		</div>
		<div class="row">
			
		</div>
	</section>

	 <!--modificamos los pagos que se vencieron-->
     <div class="hide" id="nuevoPago" title="Nuevo Pago">
     	<form action="acciones.php" method="post" id="registrarPago2">
     		<input type="hidden" id="id_registro" name="id_registro" value="0">
     			<label>N° Prestamo</label>
     			<input type="text" id="codigo" disabled>
				<input type="hidden" name="nombre" id="nombre">
				<input type="hidden" name="prestamo" id="prestamo">
     			<label>Pago Capital:</label>
				<input type="text" name="pago" id="pago" required autofocus/>
     			<label>Pago Interes:</label>
				<input type="text" name="interes" id="interes" required/>
				<input type="hidden" name="registrarPago2">
				<button type="submit" id="registrarPago2" class="btn btn-success">Aceptar</button>
				<button id="cancelar" class="btn btn-danger">Cancelar</button>
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