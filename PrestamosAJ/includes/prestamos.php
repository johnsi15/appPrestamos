<!DOCTYPE HTML>
<html lang="es">
<head>
	<meta charset="UTF-8">
	<title>Prestamos</title>
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
	<script src="../js/prestamos.js"></script>
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
	    	font-size: 1.8em;
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
      	// ver mas datos de los prestamos
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

		/*______________________________________________*/
        $("#menuOpen").mouseout(function(){
            //$("#formMenu").removeClass('open');
	    }).mouseover(function(){
	        $("#formMenu").addClass('open');
	        $("#foco").focus();
        });

	    //buscador de los prestamos____________________________
	    $('#buscar').live('keyup',function(){
		  	var data = 'queryPrestamo='+$(this).val();
		  	//console.log(data);
      	    if(data =='queryPrestamo=' ){
      	       	$.post('acciones.php',data , function(resp){
			  	   	//console.log(resp);
			  	   	$('#verPrestamos').empty();//limpiar los datos
			  	   	$('#verPrestamos').html(resp);
	      	    	//console.log('poraca paso joder....');
	      	    	$('[data-toggle=popover]').popover({html:true});
			  	},'html');
      	    }else{
      	       	$.post('acciones.php',data , function(resp){
			  	   	  //console.log(resp);
			  	   	$('.pagination').remove();
			  	   	$('#verPrestamos').empty();//limpiar los datos
			  	   	$('#verPrestamos').html(resp);
	      	    	//console.log(resp);
	      	    	$('[data-toggle=popover]').popover({html:true});
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
					  	 	$('[data-toggle=popover]').popover({html:true});
					  	}
					});
					  $('.pagination').remove();
				}
		  	}
		});

		// calculo para sacar los meses
		$('#quin').keyup(function(){
    			var quin = $(this).val();
    			var meses = quin/2;
    			$('#meses').val(meses);
    	}).keyup();


	  });/*fin del document------------------*/
		
		function calculo(){
    		//var contador = document.getElementById("totalDia");
    		for(i=0;i<document.formu.tipo.length;i++){
				if(document.formu.tipo[i].checked) {
					marcado=i;
				}
			}
			//alert("El valor seleccionado es: "+document.formu.tipo[marcado].value);
    		//var valor = document.getElementById("tipo").value;
    		if(document.formu.tipo[marcado].value == 'm'){
    			var quin = $('#quin').val();
	    		var prestamo = $('#prestamo').val();
	    		var porc = $('#porc').val();
	    		var resuPorce = (prestamo*porc)/100;
	    		var div = resuPorce/2;
	    		var interes = div * quin;
	    		var meses = quin/2;
	    		var cuota = (parseInt(prestamo) + parseInt(interes))/meses;

	    		$("#vcuota").val(Math.round(cuota));
	    		$("#interes").val(interes);
    		}else{
    			var quin = $('#quin').val();
	    		var prestamo = $('#prestamo').val();
	    		var porc = $('#porc').val();
	    		var resuPorce = (prestamo*porc)/100;
	    		var div = resuPorce/2;
	    		var interes = div * quin;
	    		var cuota = (parseInt(prestamo) + parseInt(interes))/quin;

	    		$("#vcuota").val(Math.round(cuota));
	    		$("#interes").val(interes);
    		}
    		
    	}//cierre funcion
	</script>
</head>
<body onLoad="setInterval('calculo()',1000);">
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
									<li class="active"><a href="#">Prestamos</a></li>
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
		<input type="text" name="buscar" id="buscar" class="search-query" placeholder="Buscar Nombre" autofocus>	
		<div class="row">
			<h1>Prestamos</h1> <br>
			<div class="span4">
				<a class="btn btn-large btn-success" id="nuevo">Nuevo Prestamo</a>
			</div>
			<div class="span12">
				<hr>
				<table class="table table-hover table-bordered table-condensed">
					<thead>
						<tr>
							<th>N°</th>
							<th>Nombre</th>
							<th>Prestamo</th>
							<th>Cuota</th>
							<th>Interes</th>
						</tr>
					</thead>
					<tbody id="verPrestamos">
						<?php 
						   require_once('funciones.php');
						   $objeto = new funciones();
						   $objeto->verPrestamos();
						?>
					</tbody>
				</table>
				<div id="cargando" style="display: none;"><img src="../img/loader.gif" alt=""></div>
		        <div id="paginacion">
		    	 	 <?php 
		    	 	  require_once('funciones.php');
		    	 	  $objeto = new funciones();
		    	 	  $objeto->paginacionPrestamos();
			    	 ?>
		    	</div>
			</div>
		</div>
		<div class="row">
			
		</div>
	</section>

	<!--codigo para hacer un nuevo prestamo-->
	<div class="hide" id="nuevoPrestamo" title="Nuevo Prestamo">
     	<form action="acciones.php" method="post" id="registrarPrestamo" class="limpiar" name="formu">
     			<label>Nombre:</label>
				<select id='nombre' name='nombre' autofocus>
					<?php
                        require_once('../includes/funciones.php');
                        $combo = new funciones();
                        $combo->comboClientes();
					?>
				</select>
     			<label>Prestamo:</label>
				<input type="text" name="dinero" required id="prestamo"/>
				<label>%</label>
				<input type="text" name="porcentaje" required id="porc">
				<label>Mensual <input type="radio" name="tipo" id="tipo" value="m" checked>
					   Quincenal <input type="radio" name="tipo" id="tipo" value="q">
				</label>
				<label>N-cuotas-Q:</label>
				<input type="text" name="NcuotasQ" id="quin"/>
				<label>N-cuotas-M:</label>
				<input type="text" name="NcuotasM" id="meses"/>
				<label>Valor Cuota:</label>
				<input type="text" name="valor" value="0" required id="vcuota"/>
				<label>Fecha Pago:</label>
				<input type="date" name="fechaP"/>
				<label>Interes:</label>
				<input type="text" name="interes" required id="interes">
				<input type="hidden" name="registrarPrestamo">
				<button  class="btn btn-primary" type="submit" id="registrarPrestamo">Registrar</button>
				<button  class="btn btn-danger" id="cancelar">Cancelar</button>
     	</form>
    </div>

     <!--codigo para eliminar-->
    <div class="hide" id="deleteReg" title="Eliminar Estudiante">
	    <form action="acciones.php" method="post">
	    	<fieldset id="datosOcultos">
	    		<input type="hidden" id="id_delete" name="id_delete" value="0"/>
	    	</fieldset>
	    	<div class="control-group">
	    		<label for="activoElim" class="alert alert-danger">
	    		    <strong>Esta seguro de Eliminar este estudiante</strong><br>
	    		</label>
	    		<input type="hidden" name="deleteEstudianteTiempo"/> 
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