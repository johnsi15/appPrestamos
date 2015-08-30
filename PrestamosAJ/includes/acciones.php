<?php
    require_once('funciones.php');
    $objeto = new funciones();
    $refres = new funciones();

   //login de usuarios
   if(isset($_POST['clave'])){
        $user = $_POST['nombre'];
        $pass = $_POST['clave'];
		//sleep(1);
        if($objeto->login($user,$pass)){
            echo "Bien";
            //header('Location: ../menu.php');
        }else{
        	echo "Error";
        }
   }


   /*registrar un nuevo cliente*/
   if(isset($_POST['registrarCliente'])){
        $codigo = $_POST['codigo'];
        $nom = $_POST['nombre'];
        $dir = $_POST['dir'];
        $tel = $_POST['tel'];
        $objeto->registrarCliente($codigo,$nom,$dir,$tel);
        $objeto->paginacionDatosPersonales();
        $objeto->verTodosClientes();
   }


   /*registrar un nuevo prestamo*/
   if(isset($_POST['registrarPrestamo'])){
      $cedula = $_POST['nombre'];
      $dinero = $_POST['dinero'];
      $NcQ = $_POST['NcuotasQ'];
      $NcM = $_POST['NcuotasM'];
      $valor = $_POST['valor'];
      $fechaP = $_POST['fechaP'];
      $interes = $_POST['interes'];
      date_default_timezone_set('America/Bogota'); 
      $fechaI = date("Y-m-d");
      $tipo = $_POST['tipo'];
      $porcentaje = $_POST['porcentaje'];
      if($objeto->registrarPrestamo($cedula,$dinero,$NcQ,$NcM,$valor,$fechaI,$fechaP,$interes,$tipo,$porcentaje)){
        $objeto->paginacionPrestamos();
        $objeto->verPrestamos();
      }
   }

   /*renovar prestamo de los clientes*/
   if(isset($_POST['renovarPrest'])){
      $codigo = $_POST['id_registro'];
      $dinero = $_POST['dinero'];
      $NcQ = $_POST['NcuotasQ'];
      $NcM = $_POST['NcuotasM'];
      $valor = $_POST['valor'];
      $fechaP = $_POST['fechaP'];
      $interes = $_POST['interes'];
      date_default_timezone_set('America/Bogota'); 
      $fechaI = date("Y-m-d");
      $tipo = $_POST['tipo'];
      $porcentaje = $_POST['porcentaje'];
      if($objeto->renovarPrestamos($codigo,$dinero,$NcQ,$NcM,$valor,$fechaI,$fechaP,$interes,$tipo,$porcentaje)){
        $objeto->verRenovar();
      }
   }

   /*refrescar prestamos del mes */
   if(isset($_POST['refrescarPrestamos'])){
      $objeto->quienesDebenPagar();
      $objeto->mesualidad();
   }

   /*eliminar credito y cliente*/
   if(isset($_POST['deletePrestamo'])){
      $cedula = $_POST['id_delete'];
      $objeto->eliminarPrestamo($cedula);
      $objeto->verRenovar();
   }

   /*actualizamos la base de la caja*/
   if(isset($_POST['modificarBase'])){
        $base = $_POST['base'];
        $tipo = $_POST['tipo'];
        if($tipo=='1'){
            $objeto->actualizarBase($base);
            $objeto->verCaja();
        }else{
          if($tipo=='2'){
            $objeto->agragarBase($base);
            $objeto->verCaja();
          }else{
            if($objeto->sacarInteres($base)){
                $objeto->verCaja();
            }
            
          }
        }
   }
   /*modificar interes de la caja solo para sacar dinero */
   if(isset($_POST['modificarInteres'])){
      $dinero = $_POST['dinteres'];
      $conp = $_POST['concepto'];
      date_default_timezone_set('America/Bogota'); 
      $fecha = date("Y-m-d");
      $objeto->gastoInteres($dinero);
      $objeto->registrarGasto($dinero,$conp,$fecha);
      $objeto->verInteres();
   }

   if(isset($_POST['registrarPago'])){
      $cedula = $_POST['nombre'];
      $prestamo = $_POST['prestamo'];
      $pago = $_POST['pago'];
      $interes = $_POST['interes'];
      date_default_timezone_set('America/Bogota'); 
      $fecha = date("Y-m-d");
      if($objeto->registrarPago($cedula,$fecha,$pago,$interes,$prestamo)){
        $objeto->paginacionPagos();
        $objeto->verPagos();
        if($objeto->saldoCero()){
              
          echo'<section id="notiRenovar" class="span5">
                            <div class="well">
                                <a class="btn btn-primary" href="renovar.php">Renovar o Eliminar</a> -
                                <a class="btn btn-warning" id="otroMomen">En otro momento</a>
                                <a class="btn btn-danger" id="cerrar">X</a>
                            </div>
                    </section>';
        }
      }//cierre del if
   }

   if(isset($_POST['registrarPago2'])){
      //$cedula = $_POST['nombre'];
      //$prestamo = $_POST['prestamo'];
      $cedula = $_REQUEST['id_registro'];
      $prestamo = $_REQUEST['prestamo'];
      $pago = $_POST['pago'];
      $interes = $_POST['interes'];
      date_default_timezone_set('America/Bogota'); 
      $fecha = date("Y-m-d");
      if($objeto->registrarPago2($cedula,$fecha,$pago,$interes,$prestamo)){
        $objeto->mesualidad();
      }
   }

   /*modificamos el pago de los que les vencio las fechas */
   if(isset($_POST['modificarPagoVen'])){
        $cod = $_POST['id_registroVen'];
        $pago = $_POST['pago'];
        $con = $_POST['condicion'];
        $objeto->modificarPago($pago,$con,$cod);
        $objeto->verVensimientos();
        $objeto->modificarPagoFechas($pago,$con,$cod);
   }


   /*refrescar la tabla al cerrar la ventana de los que se les vencio las fechas de pago*/
   if(isset($_POST['verEstu'])){
       $objeto->verEstudiantes();
       $objeto->paginacionEstudianteMenu();
   }


   if(isset($_POST['deleteCliente'])){
       $cod = $_POST['id_delete'];
       $objeto->deleteCliente($cod);
       $objeto->verTodosClientes();
       $objeto->paginacionDatosPersonales();
   }

   if(isset($_POST['deleteBaseDatos'])){
       $objeto->limpiarBaseDatos();
   }

   if(isset($_POST['deleteEstudianteTiempo'])){
       $cod = $_POST['id_delete'];
       $objeto->eliminarEstudiante($cod);
       $objeto->paginacionActulizarTiempo();
       $objeto->verActualizarTiempo();
   }


/*aca comienzo con el codigo para modificar los datos personales de los estudiantes que van al gimnacio*/
   if(isset($_POST['modificarDatos'])){
        $cod = $_POST['id_registro'];
        $nom = $_POST['nombre'];
        $dir = $_POST['direccion'];
        $tel = $_POST['telefono'];
        $objeto->actualizarDatosPersonales($cod,$nom,$dir,$tel);
        //$objeto->paginacionDatosPersonales();
        $objeto->verTodosClientes();
   }

   /*buscador en tiempo real para modificar los datos personales de los estudiantes*/
   if(isset($_POST['query'])){
       $palabra = $_POST['query'];
       $objeto->buscarCliente($palabra);
   }
   /*buscador en tiempo real prestamos*/
   if(isset($_POST['queryPrestamo'])){
       $palabra = $_POST['queryPrestamo'];
       $objeto->buscarPrestamo($palabra);
   }
   /*buscador en tiempo real para buscar los clientes en el menu principal de todas las condiciones de pago*/
   if(isset($_POST['queryMenu'])){
      $palabra = $_POST['queryMenu'];
      $objeto->buscarClientesMenu($palabra);
   }
   /*buscador en tiempo real para los pagos que se vencieron*/
   if(isset($_POST['queryPago'])){
      $palabra = $_POST['queryPago'];
      $objeto->buscarPagos($palabra);
   }


  /*actulizar el tiempo de uso del gim*/
   if(isset($_POST['modificarTiempo'])){
      $cod = $_POST['id_registro'];
      date_default_timezone_set('America/Bogota'); 
      $fechaI = date("Y-m-d");
      $nom = $_POST['nombre'];
      $fechaV = $_POST['fechaV'];
      $pago = $_POST['pago'];
      $con = $_POST['condicion'];
      $objeto->actulizarTiempo($fechaV,$pago,$con,$cod);
      $objeto->paginacionActulizarTiempo();
      $objeto->verActualizarTiempo();
      $mes = substr($fechaV,5,-3);
      $objeto->registrarFechasEstudiante($nom,$fechaI,$fechaV,$mes,$pago,$con,$codigo);
   }












/*codigo viejo_______________________________________________________________________*/

   if(isset($_POST['guardarRecar'])){
        $nombre = $_POST['nombre'];
        $dinero = $_POST['dinero'];
        $tipoConcep = $_POST['tipoConcep'];
        $objeto->registrarConcepto($nombre,$dinero,$tipoConcep);
          $objeto->totalDiaRecargas();
       // header('Location: recargas.php');
   }

   if(isset($_POST['okMinu'])){
       $base = $_POST['base'];
       $tipoB = $_POST['tipoBase'];
       date_default_timezone_set('America/Bogota');
       $fecha = date("Y-m-d");
       $objeto->actualizarBase($fecha,$base,$tipoB);
       header('Location: minutos.php');
   }

   if(isset($_POST['guardarMinu'])){
        $nombre = $_POST['nombre'];
        $dinero = $_POST['dinero'];
        $tipoConcep = $_POST['tipoConcep'];
           $objeto->registrarConcepto($nombre,$dinero,$tipoConcep);
           $objeto->totalDiaMinutos();
        //header('Location: minutos.php');
   }

   if(isset($_POST['okVitri'])){
       $base = $_POST['base'];
       $tipoB = $_POST['tipoBase'];
       date_default_timezone_set('America/Bogota');
       $fecha = date("Y-m-d");
       $objeto->actualizarBase($fecha,$base,$tipoB);
       header('Location: vitrina.php');
   }

   if(isset($_POST['guardarVitri'])){
       $nombre = $_POST['nombre'];
       $dinero = $_POST['dinero'];
       $tipoConcep = $_POST['tipoConcep'];
       $objeto->registrarConcepto($nombre,$dinero,$tipoConcep);
       $objeto->totalDiaVitrina();
      //header('Location: vitrina.php');
   }

   if(isset($_POST['guardarVitrinaMenu'])){
       $nombre = $_POST['nombre'];
       $dinero = $_POST['dinero'];
       $tipoConcep = $_POST['tipoConcep'];
       $objeto->registrarConcepto($nombre,$dinero,$tipoConcep);
       $objeto->reporteDiario();
   }

   if(isset($_POST['enviar'])){
       $tipo = $_POST['tipo'];
       $fecha = $_POST['fecha'];
       $objeto->buscarReporte($tipo,$fecha);
       //header('Location: reporte.php');
   }

   if(isset($_POST['editConcepto'])){
      $cod = $_POST['id_registro'];
      $nombre = $_POST['nombre'];
      $dinero = $_POST['dinero'];
      if($objeto->modificarConcepto($cod,$nombre,$dinero)){
            $objeto->paginacion();
            $objeto->refres();
      }
   }

   if(isset($_POST['notas'])){
      $nota = $_POST['nota'];
      $objeto->actualizarNota($nota);
      $objeto->verNota();
   }

   if(isset($_POST['registrarPrecio'])){
      $nom = $_POST['nombre'];
      $pre = $_POST['dinero'];
      $objeto->registrarPrecio($nom,$pre);
      $objeto->verPrecios();
   }

   if(isset($_POST['editPrecio'])){
      $nom = $_POST['nombre'];
      $pre = $_POST['dinero'];
      $cod = $_POST['id_registro'];
      if($objeto->modificarPrecio($cod,$nom,$pre)){
         $objeto->verPrecios();
      }
   }

   if(isset($_POST['calcular'])){
      $fecha1 = $_POST['fecha1'];
      $fecha2 = $_POST['fecha2'];
      $objeto->calcularReporte($fecha1,$fecha2);
   }

   if(isset($_POST['cierre'])){
       date_default_timezone_set('America/Bogota');
       $fecha = date("Y-m-d");
       $dia = date("l");
       if($dia=="Monday"){
       $dia = "Lunes";
       }else if($dia=="Tuesday"){
       $dia = "Martes";
       }else if($dia=="Wednesday"){
       $dia = "Miercoles";
       }else if($dia=="Thursday"){
       $dia = "Jueves";
       }else if($dia=="Friday"){
       $dia = "Viernes";
       }else if($dia=="Saturday"){
       $dia = "Sabado";
       }else if($dia=="Sunday"){
       $dia = "Domingo";
       }
      $dinero = $_POST['dinero'];
      $fecha = $_POST['fecha'];
     // $dia = $_POST['dia'];
      $objeto->cierreDia($fecha,$dinero,$dia);
       $objeto->verCierres();
   }

   if(isset($_POST['editCierre'])){
       $dia = $_POST['dia'];
       $dinero = $_POST['dinero'];
       $cod = $_POST['id_registro'];

       if($objeto->modificarCierre($dia,$dinero,$cod)){
          //$objeto->refresCierre();
          $objeto->paginacionCierre();
          $objeto->verCierres();
       }
   }

   if(isset($_POST['calcularCierre'])){
      $fecha1 = $_POST['fecha1'];
      $fecha2 = $_POST['fecha2'];
      $objeto->calcularCierre($fecha1,$fecha2);
   }

   if(isset($_POST['gasto'])){
      $gasto = $_POST['dinero'];
      $tgasto = $_POST['tipoGasto'];
      date_default_timezone_set('America/Bogota');
      $fecha = date("Y-m-d");
      $objeto->gastos($gasto,$tgasto,$fecha);
      $objeto->verGastos();
   }

   if(isset($_POST['editGasto'])){
     $gasto = $_POST['dinero'];
     $tgasto = $_POST['tipoGasto'];
     $cod = $_POST['id_registro'];
     if($objeto->modificarGasto($gasto,$tgasto,$cod)){
         $objeto->paginacionGastos();
         $objeto->verGastos();
     }
   }

   if(isset($_POST['calcularGasto'])){
      $fecha1 = $_POST['fecha1'];
      $fecha2 = $_POST['fecha2'];
      $objeto->calcularGasto($fecha1,$fecha2);
   }
  

   if(isset($_POST['buscarInternet'])){
       $palabra = $_POST['buscarInternet'];
       $objeto->buscarInternet($palabra);
   }

   if(isset($_POST['buscarVitrina'])){
       $palabra = $_POST['buscarVitrina'];
       $objeto->buscarVitrina($palabra);
   }

   /*___________________________________________*/
   //Eliminar
   if(isset($_POST['deleteConcepto'])){
      $cod = $_POST['id_delete'];
      $objeto->deleteConcepto($cod);
      $objeto->paginacion();
      $objeto->buscarReporteConcepto();
   }

   if(isset($_POST['deletePrecio'])){
      $cod = $_POST['id_delete'];
      $objeto->deletePrecio($cod);
      $objeto->verPrecios();
   }

   if(isset($_POST['deleteCierre'])){
      $cod = $_POST['id_delete'];
      $objeto->deleteCierre($cod);
      $objeto->paginacionCierre();
      $objeto->verCierres();
   }

   if(isset($_POST['deleteGasto'])){
     $cod = $_POST['id_delete'];
     $objeto->deleteGasto($cod);
     $objeto->paginacionGastos();
     $objeto->verGastos();
   }
   /*MODIFICAR DATOS DEL USURIOO Y CREAR USUARIO*/
   if(isset($_POST['editNomUser'])){
     $nom = $_POST['nombre'];
     $cod = $_POST['id_registro'];
     $objeto->editarNombreUser($nom,$cod);
   }

   if(isset($_POST['UserModificarContra'])){
     $conA = $_POST['contraseñaA'];
     $conN = $_POST['contraseñaN'];
     $cod = $_POST['id_registro2'];
     $objeto->cambiarClave($conA,$conN,$cod);
   }

   if(isset($_POST['registrarUser'])){
      $nom = $_POST['nombre'];
      $clave = $_POST['contraseña'];
      $objeto->registrarUser($nom,$clave);
   }

?>