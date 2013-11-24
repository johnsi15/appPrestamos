<?php
  class funciones{
     private $bd;
     function __construct(){
         require_once('config.php');
         $bd = new conexion();
         $bd->conectar();
     }

    public function login($user,$pass){
         session_start();
         //$truco=sha1($pass);
         $resultado = mysql_query("SELECT * FROM usuarios WHERE nombre='$user' AND clave='$pass'");
         $fila = mysql_fetch_array($resultado);
         if($fila>0){
         	$id_user=$fila['id'];
            $user = $fila['nombre'];
         	$_SESSION['id_user']=$id_user;
            $_SESSION['nombre'] = $user;
         	return true;
         }else{
         	return false;
         }
    }

    /*funcion para registrar los clientes */
    public function registrarCliente($codigo,$nom,$dir,$tel){/**/
        mysql_query("INSERT INTO clientes (cedulaCliente,nombre,direccion,telefono)
                                      VALUES ('$codigo','$nom','$dir','$tel')")
                                      or die ("Error");
    }

    /*funcion para registrar prestamo */
    public function registrarPrestamo($cedula,$prestamo,$NcQ,$NcM,$Vcuota,$fechaPrestamo,$fechaPago,$interes,$condicion){
        /*actualizando la caja despues del prestamo*/                           
        $resultado2 = mysql_query("SELECT baseTotal FROM caja");
        $fila2 = mysql_fetch_array($resultado2);
        if($fila2['baseTotal'] <= $prestamo){
            echo "Error";
            return false;
        }else{
            $saldo = $prestamo;
            $saldoIn = $interes;
            mysql_query("INSERT INTO prestamos (cedula,monto,saldo,NcuotasQ,NcuotasM,Vcuota,fechaPrestamo,fechaPago,interes,saldoInteres,condicion)
                                          VALUES ('$cedula','$prestamo','$saldo','$NcQ','$NcM','$Vcuota','$fechaPrestamo','$fechaPago','$interes','$saldoIn','$condicion')")
                                          or die ("Error");
            $resultado = mysql_query("SELECT * FROM clientes WHERE cedulaCliente='$cedula' ");
            $fila = mysql_fetch_array($resultado);
            $np = $fila['nPrestamos'];
            $tp = $np + 1;
            mysql_query("UPDATE clientes SET nPrestamos='$tp' WHERE cedulaCliente='$cedula'") 
                                        or die ("Error en el update");

            $nuevaCaja = $fila2['baseTotal'] - $prestamo;
            mysql_query("UPDATE caja SET baseTotal='$nuevaCaja'") 
                                        or die ("Error en el update");
            return true;
        }
    }

    /*funcion para registrar los pagos de los prestamos */
    public function registrarPago($cedula,$fecha,$pago,$interes,$nPrestamo){
        $resultado = mysql_query("SELECT * FROM prestamos WHERE codigo='$nPrestamo'");
        $fila = mysql_fetch_array($resultado);
        if($fila['saldo'] == '0'){

        }else{
            $nuevoInteres = $fila['saldoInteres'] - $interes;
            $nuevoSaldo = $fila['saldo'] - $pago;

            mysql_query("INSERT INTO pagos (cedula,fecha,abonoCapital,abonoInteres,saldo)
                                          VALUES ('$cedula','$fecha','$pago','$interes','$nuevoSaldo')")
                                          or die ("Error");

            mysql_query("UPDATE prestamos SET saldo='$nuevoSaldo', saldoInteres='$nuevoInteres' WHERE codigo='$nPrestamo'") 
                                        or die ("Error en el update");

            $resultado2 = mysql_query("SELECT * FROM caja");
            $fila2 = mysql_fetch_array($resultado2);
            $nuevaBase = $fila2['baseTotal'] + $pago;
            $nuevoInteres = $fila2['interesTotal'] + $interes;

            mysql_query("UPDATE caja SET interesTotal='$nuevoInteres', baseTotal='$nuevaBase'") 
                                        or die ("Error en el update");
        } 
    }

    /*actualizar la base de la caja si es la primera vez lo registramos*/
    public function actualizarBase($base){
        $resultado = mysql_query("SELECT * FROM caja");
        if($fila = mysql_fetch_array($resultado)){
            mysql_query("UPDATE caja SET baseTotal='$base'") 
                                    or die ("Error en el update");
        }else{
            mysql_query("INSERT INTO caja (baseTotal)
                                      VALUES ('$base')")
                                      or die ("Error");
        }
    }

    /*agregar mas dinero a la base*/
    public function agragarBase($base){
        $resultado = mysql_query("SELECT baseTotal FROM caja");
        if($fila = mysql_fetch_array($resultado)){
            $baseTotal = $fila['baseTotal'];
            $nvBase = $baseTotal + $base;
            mysql_query("UPDATE caja SET baseTotal='$nvBase'") 
                                    or die ("Error en el update");
        }
    }

    /*sacar de interes y agregar a base*/
    public function sacarInteres($base){
        $resultado = mysql_query("SELECT interesTotal FROM caja");
        if($fila = mysql_fetch_array($resultado)){
            if($base <= $fila['interesTotal']){  
                $nvInteres = $fila['interesTotal'] - $base;
                mysql_query("UPDATE caja SET interesTotal='$nvInteres'") 
                                        or die ("Error en el update");

                $resultado2 = mysql_query("SELECT baseTotal FROM caja");
                $fila2 = mysql_fetch_array($resultado2);

                $nvBase = $fila2['baseTotal'] + $base; 
                mysql_query("UPDATE caja SET baseTotal='$nvBase'") 
                                        or die ("Error en el update");
                return true;
            }else{
                echo "Error";
                return false;
            }
        }
    }

    /*gastos sacados de interes*/
    public function gastoInteres($gasto){
        $resultado = mysql_query("SELECT interesTotal FROM caja");
        if($fila = mysql_fetch_array($resultado)){
            $nvInteres = $fila['interesTotal'] - $gasto;
            mysql_query("UPDATE caja SET interesTotal='$nvInteres'") 
                                    or die ("Error en el update");
        }
    }

    public function registrarGasto($gasto,$concepto,$fecha){
        mysql_query("INSERT INTO gastos (dinero,concepto,fecha)
                                      VALUES ('$gasto','$concepto','$fecha')")
                                      or die ("Error");
    }

    public function registrarFechasEstudiante($nom,$fechaI,$fechaV,$mes,$pago,$con,$codigo){
            
            $mes1 = substr($fechaI,5,-3);
            $año1 = substr($fechaI,0,4);
            $dia1 = substr($fechaI, 8,10);
            /*________________________________________*/
            $mes2 = substr($fechaV, 5, -3);
            $año2 = substr($fechaV, 0,4);
            $dia2 = substr($fechaV, 8,10);
            //calculo timestam de las dos fechas 
            $timestamp1 = mktime(0,0,0,$mes1,$dia1,$año1); 
            $timestamp2 = mktime(0,0,0,$mes2,$dia2,$año2);
            //resto a una fecha la otra 
            $segundos_diferencia = $timestamp1 - $timestamp2; 
            //echo $segundos_diferencia; 

            //convierto segundos en días 
            $dias_diferencia = $segundos_diferencia / (60 * 60 * 24); 
            //obtengo el valor absoulto de los días (quito el posible signo negativo) 
            $dias_diferencia = abs($dias_diferencia); 
            //quito los decimales a los días de diferencia 
            $dias_diferencia = floor($dias_diferencia);

            $resultado = mysql_query("INSERT INTO fechasclientes (nombre,fechaInicial,fechaFinal,mes,dias,dinero,condicion,codigoEstudiante)
                                      VALUES ('$nom','$fechaI','$fechaV','$mes','$dias_diferencia','$pago','$con','$codigo')")
                                      or die ("problemas con el insert de concepto de internet".mysql_error());
    }

    /*ver base de la caja */
    public function verCaja(){
        $resultado = mysql_query("SELECT * FROM caja");
   
        if($fila = mysql_fetch_array($resultado)){
            echo "<h2>".number_format($fila['baseTotal'])."</h2>";
        }else{
            echo "<h2>0</h2>";
        }
    }

    /*ver interes de la caja*/
    public function verInteres(){
        $resultado = mysql_query("SELECT * FROM caja");
   
        if($fila = mysql_fetch_array($resultado)){
            echo "<h2>".number_format($fila['interesTotal'])."</h2>";
        }else{
            echo "<h2>0</h2>";
        }
    }

    /*funcion para ver los clientes activos*/
    public function verClientes(){
        /*$cant_reg = 10;//definimos la cantidad de datos que deseamos tenes por pagina.

        if(isset($_GET["pagina"])){
            $num_pag = $_GET["pagina"];//numero de la pagina
        }else{
            $num_pag = 1;
        }

        if(!$num_pag){//preguntamos si hay algun valor en $num_pag.
            $inicio = 0;
            $num_pag = 1;
        }else{//se activara si la variable $num_pag ha resivido un valor oasea se encuentra en la pagina 2 o ha si susecivamente 
            $inicio = ($num_pag-1)*$cant_reg;//si la pagina seleccionada es la numero 2 entonces 2-1 es = 1 por 10 = 10 empiesa a contar desde la 10 para la pagina 2 ok.
        }
        */
        $resultado = mysql_query("SELECT * FROM prestamos,clientes WHERE prestamos.cedula=clientes.cedulaCliente ORDER BY codigo DESC");
   
        while($fila = mysql_fetch_array($resultado)){
          //  $codigo = $fila['codigo'];
          //  $result = mysql_query("SELECT sum(dias) AS total FROM fechasclientes WHERE codigoEstudiante = '$codigo' ");
          //  $dias = mysql_fetch_array($result);
         //style="font-weight: bold;
            
            echo '<tr> 
                <td>'.$fila['codigo'].'</td>
                <td>'.$fila['nombre'].'</td>
                <td>'.$fila['direccion'].'</td>
                <td>'.$fila['telefono'].'</td>
                <td>'.number_format($fila['saldo']).'</td>
                <td><a id="info" class="btn btn-mini btn-info" 
                         data-toggle="popover" data-placement="top" 
                         data-content="ValorCuota: '.number_format($fila['Vcuota']).'  <br>
                                       NcuotasM: '.$fila['NcuotasM'].'   <br>
                                       Prestamo:'.number_format($fila['monto']).'<br>
                                       FechaInicio:  '.$fila['fechaPrestamo'].'  <br>
                                       FechaFin: '.$fila['fechaPago'].' <br>
                                       N° Prestamos: '.$fila['nPrestamos'].'"

                         data-original-title="'.$fila['nombre'].'" href="#vermas"><strong>Ver Mas</strong>
                        </a>
                    </td>
            </tr>';
            
           
                          // echo $salida;
        }      
    }/*cierre del metodo*/

    public function verPrestamos(){
        $cant_reg = 10;//definimos la cantidad de datos que deseamos tenes por pagina.

        if(isset($_GET["pagina"])){
            $num_pag = $_GET["pagina"];//numero de la pagina
        }else{
            $num_pag = 1;
        }

        if(!$num_pag){//preguntamos si hay algun valor en $num_pag.
            $inicio = 0;
            $num_pag = 1;
        }else{//se activara si la variable $num_pag ha resivido un valor oasea se encuentra en la pagina 2 o ha si susecivamente 
            $inicio = ($num_pag-1)*$cant_reg;//si la pagina seleccionada es la numero 2 entonces 2-1 es = 1 por 10 = 10 empiesa a contar desde la 10 para la pagina 2 ok.
        }

        $resultado = mysql_query("SELECT * FROM prestamos,clientes WHERE prestamos.cedula=clientes.cedulaCliente ORDER BY codigo DESC LIMIT $inicio,$cant_reg");
       
        while($fila = mysql_fetch_array($resultado)){
            if($fila['condicion']=='nopago'){
                echo '<tr class="error"> 
                <td>'.$fila['codigo'].'</td>
                <td>'.$fila['nombre'].'</td>
                <td>'.number_format($fila['monto']).'</td>
                <td>'.number_format($fila['Vcuota']).'</td>
                <td>'.number_format($fila['interes']).'</td>
                <td><a id="info" class="btn btn-mini btn-info" 
                         data-toggle="popover" data-placement="top" 
                         data-content="NcuotasQ: '.$fila['NcuotasQ'].'  <br>
                                       NcuotasM: '.$fila['NcuotasM'].'   <br>
                                       FechaInicio:  '.$fila['fechaPrestamo'].'  <br>
                                       FechaFin: '.$fila['fechaPago'].' <br>
                                       N° Prestamos: '.$fila['nPrestamos'].'"

                         data-original-title="'.$fila['nombre'].'" href="#vermas"><strong>Ver Mas</strong>
                        </a>
                    </td>
                </tr>';
            }else{
                echo '<tr class="success"> 
                <td>'.$fila['codigo'].'</td>
                <td>'.$fila['nombre'].'</td>
                <td>'.number_format($fila['monto']).'</td>
                <td>'.number_format($fila['Vcuota']).'</td>
                <td>'.number_format($fila['interes']).'</td>
                <td><a id="info" class="btn btn-mini btn-info" 
                         data-toggle="popover" data-placement="top" 
                         data-content="NcuotasQ: '.$fila['NcuotasQ'].'  <br>
                                       NcuotasM: '.$fila['NcuotasM'].'   <br>
                                       FechaInicio:  '.$fila['fechaPrestamo'].'  <br>
                                       FechaFin: '.$fila['fechaPago'].' <br>
                                       N° Prestamos: '.$fila['nPrestamos'].'"

                         data-original-title="'.$fila['nombre'].'" href="#vermas"><strong>Ver Mas</strong>
                        </a>
                    </td>
                </tr>';
            }
        }      
    }//cierre funcion

    // paginacion de los prestamos
    public function paginacionPrestamos(){
        $cant_reg = 10;//definimos la cantidad de datos que deseamos tenes por pagina.

            if(isset($_GET["pagina"])){
                $num_pag = $_GET["pagina"];//numero de la pagina
            }else{
                $num_pag = 1;
            }

            if(!$num_pag){//preguntamos si hay algun valor en $num_pag.
                $inicio = 0;
                $num_pag = 1;

            }else{//se activara si la variable $num_pag ha resivido un valor oasea se encuentra en la pagina 2 o ha si susecivamente 
                $inicio = ($num_pag-1)*$cant_reg;//si la pagina seleccionada es la numero 2 entonces 2-1 es = 1 por 10 = 10 empiesa a contar desde la 10 para la pagina 2 ok.
            }

            $result = mysql_query("SELECT * FROM prestamos,clientes WHERE prestamos.cedula=clientes.cedulaCliente ORDER BY codigo DESC");///hacemos una consulta de todos los datos de cinternet
           
            $total_registros=mysql_num_rows($result);//obtenesmos el numero de datos que nos devuelve la consulta

            $total_paginas = ceil($total_registros/$cant_reg);

            echo '<div class="pagination" style="display: none;">
                    ';
            if(($num_pag+1)<=$total_paginas){//preguntamos si el numero de la pagina es menor o = al total de paginas para que aparesca el siguiente
                
                echo "<ul><li class='next'> <a href='prestamos.php?pagina=".($num_pag+1)."'> Next </a></li></ul>";
            } ;echo '
                   </div>';
    }

     /*buscador en tiempo real buscamos los prestamos*/
    public function buscarPrestamo($palabra){
        if($palabra == ''){
            $cant_reg = 10;//definimos la cantidad de datos que deseamos tenes por pagina.

        if(isset($_GET["pagina"])){
            $num_pag = $_GET["pagina"];//numero de la pagina
        }else{
            $num_pag = 1;
        }

        if(!$num_pag){//preguntamos si hay algun valor en $num_pag.
            $inicio = 0;
            $num_pag = 1;
        }else{//se activara si la variable $num_pag ha resivido un valor oasea se encuentra en la pagina 2 o ha si susecivamente 
            $inicio = ($num_pag-1)*$cant_reg;//si la pagina seleccionada es la numero 2 entonces 2-1 es = 1 por 10 = 10 empiesa a contar desde la 10 para la pagina 2 ok.
        }

            $result = mysql_query("SELECT * FROM prestamos,clientes WHERE prestamos.cedula=clientes.cedulaCliente");
            $total_registros=mysql_num_rows($result);//obtenesmos el numero de datos que nos devuelve la consulta

            $total_paginas = ceil($total_registros/$cant_reg);

            echo '<div class="pagination" style="display: none;">
                    ';
            if(($num_pag+1)<=$total_paginas){//preguntamos si el numero de la pagina es menor o = al total de paginas para que aparesca el siguiente
                
                echo "<ul><li class='next'> <a href='prestamos.php?pagina=".($num_pag+1)."'> Next </a></li></ul>";
            } ;echo '
                   </div>';

            $resultado = mysql_query("SELECT * FROM prestamos,clientes WHERE prestamos.cedula=clientes.cedulaCliente ORDER BY codigo DESC LIMIT $inicio,$cant_reg");
            while($fila = mysql_fetch_array($resultado)){
                if($fila['condicion']=='nopago'){
                    echo '<tr class="error"> 
                    <td>'.$fila['codigo'].'</td>
                    <td>'.$fila['nombre'].'</td>
                    <td>'.number_format($fila['monto']).'</td>
                    <td>'.number_format($fila['Vcuota']).'</td>
                    <td>'.number_format($fila['interes']).'</td>
                    <td><a id="info" class="btn btn-mini btn-info" 
                             data-toggle="popover" data-placement="top" 
                             data-content="NcuotasQ: '.$fila['NcuotasQ'].'  <br>
                                           NcuotasM: '.$fila['NcuotasM'].'   <br>
                                           FechaInicio:  '.$fila['fechaPrestamo'].'  <br>
                                           FechaFin: '.$fila['fechaPago'].' <br>
                                           N° Prestamos: '.$fila['nPrestamos'].'"

                             data-original-title="'.$fila['nombre'].'" href="#vermas"><strong>Ver Mas</strong>
                            </a>
                        </td>
                    </tr>';
                }else{
                    echo '<tr class="success"> 
                    <td>'.$fila['codigo'].'</td>
                    <td>'.$fila['nombre'].'</td>
                    <td>'.number_format($fila['monto']).'</td>
                    <td>'.number_format($fila['Vcuota']).'</td>
                    <td>'.number_format($fila['interes']).'</td>
                    <td><a id="info" class="btn btn-mini btn-info" 
                             data-toggle="popover" data-placement="top" 
                             data-content="NcuotasQ: '.$fila['NcuotasQ'].'  <br>
                                           NcuotasM: '.$fila['NcuotasM'].'   <br>
                                           FechaInicio:  '.$fila['fechaPrestamo'].'  <br>
                                           FechaFin: '.$fila['fechaPago'].' <br>
                                           N° Prestamos: '.$fila['nPrestamos'].'"

                             data-original-title="'.$fila['nombre'].'" href="#vermas"><strong>Ver Mas</strong>
                            </a>
                        </td>
                    </tr>';
                }
            }   
        }else{
             $resultado = mysql_query("SELECT * FROM prestamos,clientes WHERE prestamos.cedula=clientes.cedulaCliente AND nombre LIKE'%$palabra%' ");
            //echo json_encode($resultado);
            while($fila = mysql_fetch_array($resultado)){
                   if($fila['condicion']=='nopago'){
                        echo '<tr class="error"> 
                        <td>'.$fila['codigo'].'</td>
                        <td>'.$fila['nombre'].'</td>
                        <td>'.number_format($fila['monto']).'</td>
                        <td>'.number_format($fila['Vcuota']).'</td>
                        <td>'.number_format($fila['interes']).'</td>
                        <td><a id="info" class="btn btn-mini btn-info" 
                                 data-toggle="popover" data-placement="top" 
                                 data-content="NcuotasQ: '.$fila['NcuotasQ'].'  <br>
                                               NcuotasM: '.$fila['NcuotasM'].'   <br>
                                               FechaInicio:  '.$fila['fechaPrestamo'].'  <br>
                                               FechaFin: '.$fila['fechaPago'].' <br>
                                               N° Prestamos: '.$fila['nPrestamos'].'"

                                 data-original-title="'.$fila['nombre'].'" href="#vermas"><strong>Ver Mas</strong>
                                </a>
                            </td>
                        </tr>';
                    }else{
                        echo '<tr class="success"> 
                        <td>'.$fila['codigo'].'</td>
                        <td>'.$fila['nombre'].'</td>
                        <td>'.number_format($fila['monto']).'</td>
                        <td>'.number_format($fila['Vcuota']).'</td>
                        <td>'.number_format($fila['interes']).'</td>
                        <td><a id="info" class="btn btn-mini btn-info" 
                                 data-toggle="popover" data-placement="top" 
                                 data-content="NcuotasQ: '.$fila['NcuotasQ'].'  <br>
                                               NcuotasM: '.$fila['NcuotasM'].'   <br>
                                               FechaInicio:  '.$fila['fechaPrestamo'].'  <br>
                                               FechaFin: '.$fila['fechaPago'].' <br>
                                               N° Prestamos: '.$fila['nPrestamos'].'"

                                 data-original-title="'.$fila['nombre'].'" href="#vermas"><strong>Ver Mas</strong>
                                </a>
                            </td>
                        </tr>';
                    }
            } //cierre while
        }
    }

    public function verDetallesPrestamos(){
        $resultado = mysql_query("SELECT * FROM prestamos");
        while($fila = mysql_fetch_array($resultado)){
             echo '<tr class="success"> 
                <td>'.$fila['codigo'].'</td>
                <td>'.number_format($fila['monto']).'</td>
            </tr>';
        }
    }

    /*ver gastos sacados de los intereses*/
    public function verGastos(){
        $resultado = mysql_query("SELECT * FROM gastos");
   
        while($fila = mysql_fetch_array($resultado)){
            echo '<tr> 
                <td>'.number_format($fila['dinero']).'</td>
                <td>'.$fila['concepto'].'</td>
                <td>'.$fila['fecha'].'</td>
            </tr>';
        }
    }

    public function verPagos(){
        /*hacer paginacion*/
        $cant_reg = 10;//definimos la cantidad de datos que deseamos tenes por pagina.

        if(isset($_GET["pagina"])){
            $num_pag = $_GET["pagina"];//numero de la pagina
        }else{
            $num_pag = 1;
        }

        if(!$num_pag){//preguntamos si hay algun valor en $num_pag.
            $inicio = 0;
            $num_pag = 1;
        }else{//se activara si la variable $num_pag ha resivido un valor oasea se encuentra en la pagina 2 o ha si susecivamente 
            $inicio = ($num_pag-1)*$cant_reg;//si la pagina seleccionada es la numero 2 entonces 2-1 es = 1 por 10 = 10 empiesa a contar desde la 10 para la pagina 2 ok.
        }
        $resultado = mysql_query("SELECT * FROM clientes,pagos WHERE pagos.cedula=clientes.cedulaCliente ORDER BY codigo DESC LIMIT $inicio,$cant_reg");   
        
        while($fila = mysql_fetch_array($resultado)){
            $contador = mysql_query("SELECT count(*) FROM pagos WHERE cedula=".$fila['cedula']."");
            $fila2 = mysql_fetch_array($contador);
            echo '<tr class="success"> 
                    <td><a id="info"
                         data-toggle="popover" data-placement="top" 
                         data-content="#CuotasPagas: '.$fila2['0'].'"

                         data-original-title="'.$fila['nombre'].'" href="#vermas"><strong>'.$fila['nombre'].'</strong>
                        </a>
                    </td>
                    <td>'.$fila['fecha'].'</td>
                    <td>'.number_format($fila['abonoCapital']).'</td>
                    <td>'.number_format($fila['abonoInteres']).'</td>
                    <td>'.number_format($fila['saldo']).'</td>
            </tr>';
        }/*cierre del while*/
    }

     /*paginacion de los pagos*/
    public function paginacionPagos(){
         $cant_reg = 10;//definimos la cantidad de datos que deseamos tenes por pagina.

            if(isset($_GET["pagina"])){
                $num_pag = $_GET["pagina"];//numero de la pagina
            }else{
                $num_pag = 1;
            }

            if(!$num_pag){//preguntamos si hay algun valor en $num_pag.
                $inicio = 0;
                $num_pag = 1;

            }else{//se activara si la variable $num_pag ha resivido un valor oasea se encuentra en la pagina 2 o ha si susecivamente 
                $inicio = ($num_pag-1)*$cant_reg;//si la pagina seleccionada es la numero 2 entonces 2-1 es = 1 por 10 = 10 empiesa a contar desde la 10 para la pagina 2 ok.
            }

            $result = mysql_query("SELECT * FROM clientes,pagos WHERE pagos.cedula=clientes.cedulaCliente ORDER BY codigo DESC");///hacemos una consulta de todos los datos de cinternet
           
            $total_registros=mysql_num_rows($result);//obtenesmos el numero de datos que nos devuelve la consulta

            $total_paginas = ceil($total_registros/$cant_reg);

            echo '<div class="pagination" style="display: none;">
                    ';
            if(($num_pag+1)<=$total_paginas){//preguntamos si el numero de la pagina es menor o = al total de paginas para que aparesca el siguiente
                
                echo "<ul><li class='next'> <a href='pagos.php?pagina=".($num_pag+1)."'> Next </a></li></ul>";
            } ;echo '
                   </div>';
    }

    /*buscador en tiempo real los pagos realizados */
    public function buscarPagos($palabra){/*algo anda mal revisar*/
        if($palabra == ''){
            $cant_reg = 10;//definimos la cantidad de datos que deseamos tenes por pagina.

            if(isset($_GET["pagina"])){
                $num_pag = $_GET["pagina"];//numero de la pagina
            }else{
                $num_pag = 1;
            }

            if(!$num_pag){//preguntamos si hay algun valor en $num_pag.
                $inicio = 0;
                $num_pag = 1;
            }else{//se activara si la variable $num_pag ha resivido un valor oasea se encuentra en la pagina 2 o ha si susecivamente 
                $inicio = ($num_pag-1)*$cant_reg;//si la pagina seleccionada es la numero 2 entonces 2-1 es = 1 por 10 = 10 empiesa a contar desde la 10 para la pagina 2 ok.
            }

            $result = mysql_query("SELECT * FROM clientes,pagos WHERE pagos.cedula=clientes.cedulaCliente ORDER BY codigo DESC");///hacemos una consulta de todos los datos
           
            $total_registros=mysql_num_rows($result);//obtenesmos el numero de datos que nos devuelve la consulta

            $total_paginas = ceil($total_registros/$cant_reg);

            echo '<div class="pagination" style="display: none;">
                    ';
            if(($num_pag+1)<=$total_paginas){//preguntamos si el numero de la pagina es menor o = al total de paginas para que aparesca el siguiente
                
                echo "<ul><li class='next'> <a href='pagos.php?pagina=".($num_pag+1)."'> Next </a></li></ul>";
            } ;echo '
                   </div>';
            date_default_timezone_set('America/Bogota'); 
            $fecha = date("Y-m-d");
            $fechaD = date("d");

            $resultado = mysql_query("SELECT * FROM clientes,pagos WHERE pagos.cedula=clientes.cedulaCliente ORDER BY codigo DESC LIMIT $inicio,$cant_reg");//obtenemos los datos ordenados limitado con la variable inicio hasta la variable cant_reg
           while($fila = mysql_fetch_array($resultado)){
                 $contador = mysql_query("SELECT count(*) FROM pagos WHERE cedula=".$fila['cedula']."");
                 $fila2 = mysql_fetch_array($contador);
                echo '<tr class="success"> 
                    <td><a id="info"
                         data-toggle="popover" data-placement="top" 
                         data-content="#CuotasPagas: '.$fila2['0'].'"

                         data-original-title="'.$fila['nombre'].'" href="#vermas"><strong>'.$fila['nombre'].'</strong>
                        </a>
                    </td>
                    <td>'.$fila['fecha'].'</td>
                    <td>'.number_format($fila['abonoCapital']).'</td>
                    <td>'.number_format($fila['abonoInteres']).'</td>
                    <td>'.number_format($fila['saldo']).'</td>
                </tr>';
            }/*cierre del while*/
        }else{
            $resultado = mysql_query("SELECT * FROM clientes,pagos WHERE (pagos.cedula=clientes.cedulaCliente) AND (nombre LIKE'%$palabra%') OR (cedulaCliente='$palabra')");
            //echo json_encode($resultado);
            while($fila = mysql_fetch_array($resultado)){
                $contador = mysql_query("SELECT count(*) FROM pagos WHERE cedula=".$fila['cedula']."");
                $fila2 = mysql_fetch_array($contador);
               echo '<tr class="success"> 
                    <td><a id="info"
                         data-toggle="popover" data-placement="top" 
                         data-content="#CuotasPagas: '.$fila2['0'].'"

                         data-original-title="'.$fila['nombre'].'" href="#vermas"><strong>'.$fila['nombre'].'</strong>
                        </a>
                    </td>
                    <td>'.$fila['fecha'].'</td>
                    <td>'.number_format($fila['abonoCapital']).'</td>
                    <td>'.number_format($fila['abonoInteres']).'</td>
                    <td>'.number_format($fila['saldo']).'</td>
                </tr>';
            }/*cierre del while*/
        }
    }

    /*verificamos si hay personas que se les cumplio la fecha de pago*/
    public function verificar(){
        date_default_timezone_set('America/Bogota'); 
        $fecha = date("Y-m-d");//fecha actual bien 
        $fechaD = date("d");

        $resultado = mysql_query("SELECT * FROM estudiantes WHERE condicion='No Pago' OR condicion='Abono'");
        
        while($fila = mysql_fetch_array($resultado)){
            $dia = substr($fila['fechaFinal'],8,10); 
            $dia = $dia-3;
            if($fechaD == $dia){
                return true;
            }else{
                if($fecha == $fila['fechaFinal']){
                    return true;
                }
            }
        }   
    }

   
    // con esta funcion mostramos los clientes en la parte de registro podemos modificar los datos tambien 
    public function verTodosClientes(){
        $cant_reg = 10;//definimos la cantidad de datos que deseamos tenes por pagina.

        if(isset($_GET["pagina"])){
            $num_pag = $_GET["pagina"];//numero de la pagina
        }else{
            $num_pag = 1;
        }

        if(!$num_pag){//preguntamos si hay algun valor en $num_pag.
            $inicio = 0;
            $num_pag = 1;
        }else{//se activara si la variable $num_pag ha resivido un valor oasea se encuentra en la pagina 2 o ha si susecivamente 
            $inicio = ($num_pag-1)*$cant_reg;//si la pagina seleccionada es la numero 2 entonces 2-1 es = 1 por 10 = 10 empiesa a contar desde la 10 para la pagina 2 ok.
        }
        $resultado = mysql_query("SELECT * FROM clientes LIMIT $inicio,$cant_reg");
        while($fila = mysql_fetch_array($resultado)){
              echo '<tr> 
                        <td>'.$fila['nombre'].'</td>
                        <td>'.$fila['direccion'].'</td>
                        <td>'.$fila['telefono'].'</td>
                        <td><a id="editEstudiante" class="btn btn-mini btn-info" href="'.$fila['cedulaCliente'].'"><strong>Editar</strong></a></td>
                        <td><a id="delete" class="btn btn-mini btn-danger" href="'.$fila['cedulaCliente'].'"><strong>Eliminar</strong></a></td>
                    </tr>';
        }
    }


    /*paginacion de los clientes */
    public function paginacionDatosPersonales(){
            $cant_reg = 10;//definimos la cantidad de datos que deseamos tenes por pagina.

            if(isset($_GET["pagina"])){
                $num_pag = $_GET["pagina"];//numero de la pagina
            }else{
                $num_pag = 1;
            }

            if(!$num_pag){//preguntamos si hay algun valor en $num_pag.
                $inicio = 0;
                $num_pag = 1;

            }else{//se activara si la variable $num_pag ha resivido un valor oasea se encuentra en la pagina 2 o ha si susecivamente 
                $inicio = ($num_pag-1)*$cant_reg;//si la pagina seleccionada es la numero 2 entonces 2-1 es = 1 por 10 = 10 empiesa a contar desde la 10 para la pagina 2 ok.
            }

            $result = mysql_query("SELECT * FROM clientes");///hacemos una consulta de todos los datos de cinternet
           
            $total_registros=mysql_num_rows($result);//obtenesmos el numero de datos que nos devuelve la consulta

            $total_paginas = ceil($total_registros/$cant_reg);

            echo '<div class="pagination" style="display: none;">
                    ';
            if(($num_pag+1)<=$total_paginas){//preguntamos si el numero de la pagina es menor o = al total de paginas para que aparesca el siguiente
                
                echo "<ul><li class='next'> <a href='actualizarDatos.php?pagina=".($num_pag+1)."'> Next </a></li></ul>";
            } ;echo '
                   </div>';
    }

    //BUSCADOR EN TIEMPO REAL POR  DE CONCEPTO......
    public function buscarCliente($palabra){
        if($palabra == ''){
            $cant_reg = 10;//definimos la cantidad de datos que deseamos tenes por pagina.

            if(isset($_GET["pagina"])){
                $num_pag = $_GET["pagina"];//numero de la pagina
            }else{
                $num_pag = 1;
            }

            if(!$num_pag){//preguntamos si hay algun valor en $num_pag.
                $inicio = 0;
                $num_pag = 1;
            }else{//se activara si la variable $num_pag ha resivido un valor oasea se encuentra en la pagina 2 o ha si susecivamente 
                $inicio = ($num_pag-1)*$cant_reg;//si la pagina seleccionada es la numero 2 entonces 2-1 es = 1 por 10 = 10 empiesa a contar desde la 10 para la pagina 2 ok.
            }

            $result = mysql_query("SELECT * FROM clientes");///hacemos una consulta de todos los datos de cinternet
           
            $total_registros=mysql_num_rows($result);//obtenesmos el numero de datos que nos devuelve la consulta

            $total_paginas = ceil($total_registros/$cant_reg);

            echo '<div class="pagination" style="display: none;">
                    ';
            if(($num_pag+1)<=$total_paginas){//preguntamos si el numero de la pagina es menor o = al total de paginas para que aparesca el siguiente
                
                echo "<ul><li class='next'> <a href='actualizarDatos.php?pagina=".($num_pag+1)."'> Next </a></li></ul>";
            } ;echo '
                   </div>';

            $resultado = mysql_query("SELECT * FROM clientes LIMIT $inicio,$cant_reg");//obtenemos los datos ordenados limitado con la variable inicio hasta la variable cant_reg
            while($fila = mysql_fetch_array($resultado)){
                  echo '<tr> 
                        <td>'.$fila['nombre'].'</td>
                        <td>'.$fila['direccion'].'</td>
                        <td>'.$fila['telefono'].'</td>
                        <td><a id="editEstudiante" class="btn btn-mini btn-info" href="'.$fila['cedulaCliente'].'"><strong>Editar</strong></a></td>
                        <td><a id="delete" class="btn btn-mini btn-danger" href="'.$fila['cedulaCliente'].'"><strong>Eliminar</strong></a></td>
                    </tr>';
            } 
        }else{
             $resultado = mysql_query("SELECT * FROM clientes WHERE nombre LIKE'%$palabra%'");
            //echo json_encode($resultado);
            while($fila = mysql_fetch_array($resultado)){
                   echo '<tr> 
                        <td>'.$fila['nombre'].'</td>
                        <td>'.$fila['direccion'].'</td>
                        <td>'.$fila['telefono'].'</td>
                        <td><a id="editEstudiante" class="btn btn-mini btn-info" href="'.$fila['cedulaCliente'].'"><strong>Editar</strong></a></td>
                        <td><a id="delete" class="btn btn-mini btn-danger" href="'.$fila['cedulaCliente'].'"><strong>Eliminar</strong></a></td>
                    </tr>';
            }  
        }
    } //cierre de la function

    public function modificarPago($pago,$con,$cod){
        mysql_query("UPDATE estudiantes SET dinero='$pago', condicion='$con' WHERE codigo='$cod'") 
                                    or die ("Error en el update");
    }
    /*modificamos el pago en la tabla de fechas para poder llevar control del tiempo y dinero que lleva cada persona*/
    public function modificarPagoFechas($pago,$con,$cod){
         mysql_query("UPDATE fechasclientes SET dinero='$pago', condicion='$con' WHERE codigoEstudiante='$cod'") 
                                    or die ("Error en el update");
    }

     /*metodos para ELIMINAR clientes*/
    public function deleteCliente($cod){
        mysql_query("DELETE FROM clientes WHERE cedulaCliente='$cod'");
        //mysql_query("DELETE FROM fechasclientes WHERE codigoEstudiante='$cod'");
    }

    // metodo para limpiar la base de datos
    public function limpiarBaseDatos(){
        mysql_query("TRUNCATE caja");
        mysql_query("TRUNCATE clientes");
        mysql_query("TRUNCATE gastos");
        mysql_query("TRUNCATE pagos");
        mysql_query("TRUNCATE prestamos");
    }

   /*aca comienzo con la partde de actulizar datos de los estudiantes que van al gim*/
    public function actualizarDatosPersonales($cod,$nom,$dir,$tel){
        mysql_query("UPDATE clientes SET nombre='$nom', direccion='$dir', telefono='$tel' WHERE cedulaCliente='$cod'") 
                                    or die ("Error");
    }


    /*codigo para actulizar el tiempo del estudiante en el gim...... VA VERDATOS, PAGINACION DE LOS DATOS*/
    // public function verActualizarTiempo(){
    //     $cant_reg = 10;//definimos la cantidad de datos que deseamos tenes por pagina.

    //     if(isset($_GET["pagina"])){
    //         $num_pag = $_GET["pagina"];//numero de la pagina
    //     }else{
    //         $num_pag = 1;
    //     }

    //     if(!$num_pag){//preguntamos si hay algun valor en $num_pag.
    //         $inicio = 0;
    //         $num_pag = 1;
    //     }else{//se activara si la variable $num_pag ha resivido un valor oasea se encuentra en la pagina 2 o ha si susecivamente 
    //         $inicio = ($num_pag-1)*$cant_reg;//si la pagina seleccionada es la numero 2 entonces 2-1 es = 1 por 10 = 10 empiesa a contar desde la 10 para la pagina 2 ok.
    //     }
    //     $resultado = mysql_query("SELECT * FROM estudiantes WHERE condicion='Pago' ORDER BY fechaFinal DESC LIMIT $inicio,$cant_reg");

    //     while($fila = mysql_fetch_array($resultado)){
    //              echo '<tr class="success"> 
    //                      <td>'.$fila['nombre'].'</td>
    //                      <td>'.$fila['fechaInicial'].'</td>
    //                      <td>'.$fila['fechaFinal'].'</td>
    //                      <td>'.$fila['dinero'].'</td>
    //                      <td>'.$fila['condicion'].'</td>
    //                      <td><a id="tiempoEstudiante" class="btn btn-mini btn-info" href="'.$fila['codigo'].'"><strong>Editar</strong></a></td>
    //                      <td><a id="delete" class="btn btn-mini btn-danger" href="'.$fila['codigo'].'"><strong>Eliminar</strong></a></td>
    //                  </tr>';
    //                       // echo $salida;
    //     }      
    // }

    // public function paginacionActulizarTiempo(){
    //         $cant_reg = 10;//definimos la cantidad de datos que deseamos tenes por pagina.

    //         if(isset($_GET["pagina"])){
    //             $num_pag = $_GET["pagina"];//numero de la pagina
    //         }else{
    //             $num_pag = 1;
    //         }

    //         if(!$num_pag){//preguntamos si hay algun valor en $num_pag.
    //             $inicio = 0;
    //             $num_pag = 1;

    //         }else{//se activara si la variable $num_pag ha resivido un valor oasea se encuentra en la pagina 2 o ha si susecivamente 
    //             $inicio = ($num_pag-1)*$cant_reg;//si la pagina seleccionada es la numero 2 entonces 2-1 es = 1 por 10 = 10 empiesa a contar desde la 10 para la pagina 2 ok.
    //         }

    //         $result = mysql_query("SELECT * FROM estudiantes WHERE condicion='Pago'");///hacemos una consulta de todos los datos de cinternet
           
    //         $total_registros=mysql_num_rows($result);//obtenesmos el numero de datos que nos devuelve la consulta

    //         $total_paginas = ceil($total_registros/$cant_reg);

    //         echo '<div class="pagination" style="display: none;">
    //                 ';
    //         if(($num_pag+1)<=$total_paginas){//preguntamos si el numero de la pagina es menor o = al total de paginas para que aparesca el siguiente
                
    //             echo "<ul><li class='next'> <a href='actualizarTiempo.php?pagina=".($num_pag+1)."'> Next </a></li></ul>";
    //         } ;echo '
    //                </div>';
    // }


    /*buscador en tiempo real de los estudiantes o clientes en el menu principal */
    // public function buscarEstudianteMenu($palabra){
    //     if($palabra == ''){
    //         $cant_reg = 10;//definimos la cantidad de datos que deseamos tenes por pagina.

    //         if(isset($_GET["pagina"])){
    //             $num_pag = $_GET["pagina"];//numero de la pagina
    //         }else{
    //             $num_pag = 1;
    //         }

    //         if(!$num_pag){//preguntamos si hay algun valor en $num_pag.
    //             $inicio = 0;
    //             $num_pag = 1;
    //         }else{//se activara si la variable $num_pag ha resivido un valor oasea se encuentra en la pagina 2 o ha si susecivamente 
    //             $inicio = ($num_pag-1)*$cant_reg;//si la pagina seleccionada es la numero 2 entonces 2-1 es = 1 por 10 = 10 empiesa a contar desde la 10 para la pagina 2 ok.
    //         }

    //         $result = mysql_query("SELECT * FROM estudiantes");///hacemos una consulta de todos los datos de cinternet
           
    //         $total_registros=mysql_num_rows($result);//obtenesmos el numero de datos que nos devuelve la consulta

    //         $total_paginas = ceil($total_registros/$cant_reg);

    //         echo '<div class="pagination" style="display: none;">
    //                 ';
    //         if(($num_pag+1)<=$total_paginas){//preguntamos si el numero de la pagina es menor o = al total de paginas para que aparesca el siguiente
                
    //             echo "<ul><li class='next'> <a href='menu.php?pagina=".($num_pag+1)."'> Next </a></li></ul>";
    //         } ;echo '
    //                </div>';

    //         $resultado = mysql_query("SELECT * FROM estudiantes ORDER BY condicion, fechaFinal DESC LIMIT $inicio,$cant_reg");//obtenemos los datos ordenados limitado con la variable inicio hasta la variable cant_reg
    //         while($fila = mysql_fetch_array($resultado)){
    //                 if($fila['condicion'] == 'Pago'){
    //                          echo '<tr class="success"> 
    //                                  <td>'.$fila['nombre'].'</td>
    //                                  <td>'.$fila['fechaInicial'].'</td>
    //                                  <td style="font-weight: bold;">'.$fila['fechaFinal'].'</td>
    //                                  <td>'.$fila['dinero'].'</td>
    //                                  <td>'.$fila['condicion'].'</td>
    //                                  <td><a disabled class="btn btn-mini btn-info"><strong disabled>Editar</strong></a></td>
    //                                  <td><a id="delete" class="btn btn-mini btn-danger" href="'.$fila['codigo'].'"><strong>Eliminar</strong></a></td>
    //                              </tr>';
    //                 }else{
    //                     if($fila['condicion'] == 'No Pago'){
    //                             echo '<tr class="error"> 
    //                                  <td>'.$fila['nombre'].'</td>
    //                                  <td>'.$fila['fechaInicial'].'</td>
    //                                  <td style="font-weight: bold;">'.$fila['fechaFinal'].'</td>
    //                                  <td>'.$fila['dinero'].'</td>
    //                                  <td>'.$fila['condicion'].'</td>
    //                                  <td><a id="editPago" class="btn btn-mini btn-info" href="'.$fila['codigo'].'"><strong>Editar</strong></a></td>
    //                                  <td><a id="delete" class="btn btn-mini btn-danger" href="'.$fila['codigo'].'"><strong>Eliminar</strong></a></td>
    //                              </tr>';
    //                     }else{
    //                         if($fila['condicion'] == 'Abono'){
    //                                     echo '<tr class="warning"> 
    //                                              <td>'.$fila['nombre'].'</td>
    //                                              <td>'.$fila['fechaInicial'].'</td>
    //                                              <td style="font-weight: bold;">'.$fila['fechaFinal'].'</td>
    //                                              <td>'.$fila['dinero'].'</td>
    //                                              <td>'.$fila['condicion'].'</td>
    //                                              <td><a id="editPago" class="btn btn-mini btn-info" href="'.$fila['codigo'].'"><strong>Editar</strong></a></td>
    //                                              <td><a id="delete" class="btn btn-mini btn-danger" href="'.$fila['codigo'].'"><strong>Eliminar</strong></a></td>
    //                                          </tr>';
    //                         }
    //                     }
    //                 }
    //                                   // echo $salida;
    //         }/*cierre del while*/
    //     }else{
    //          $resultado = mysql_query("SELECT * FROM estudiantes WHERE nombre LIKE'%$palabra%'");
    //         //echo json_encode($resultado);
    //        while($fila = mysql_fetch_array($resultado)){
    //                 if($fila['condicion'] == 'Pago'){
    //                      echo '<tr class="success"> 
    //                              <td>'.$fila['nombre'].'</td>
    //                              <td>'.$fila['fechaInicial'].'</td>
    //                              <td style="font-weight: bold;">'.$fila['fechaFinal'].'</td>
    //                              <td>'.$fila['dinero'].'</td>
    //                              <td>'.$fila['condicion'].'</td>
    //                              <td><a disabled class="btn btn-mini btn-info"><strong disabled>Editar</strong></a></td>
    //                              <td><a id="delete" class="btn btn-mini btn-danger" href="'.$fila['codigo'].'"><strong>Eliminar</strong></a></td>
    //                          </tr>';
    //                 }else{
    //                     if($fila['condicion'] == 'No Pago'){
    //                         echo '<tr class="error"> 
    //                              <td>'.$fila['nombre'].'</td>
    //                              <td>'.$fila['fechaInicial'].'</td>
    //                              <td style="font-weight: bold;">'.$fila['fechaFinal'].'</td>
    //                              <td>'.$fila['dinero'].'</td>
    //                              <td>'.$fila['condicion'].'</td>
    //                              <td><a id="editPago" class="btn btn-mini btn-info" href="'.$fila['codigo'].'"><strong>Editar</strong></a></td>
    //                              <td><a id="delete" class="btn btn-mini btn-danger" href="'.$fila['codigo'].'"><strong>Eliminar</strong></a></td>
    //                          </tr>';
    //                     }else{
    //                         if($fila['condicion'] == 'Abono'){
    //                             echo '<tr class="warning"> 
    //                                      <td>'.$fila['nombre'].'</td>
    //                                      <td>'.$fila['fechaInicial'].'</td>
    //                                      <td style="font-weight: bold;">'.$fila['fechaFinal'].'</td>
    //                                      <td>'.$fila['dinero'].'</td>
    //                                      <td>'.$fila['condicion'].'</td>
    //                                      <td><a id="editPago" class="btn btn-mini btn-info" href="'.$fila['codigo'].'"><strong>Editar</strong></a></td>
    //                                      <td><a id="delete" class="btn btn-mini btn-danger" href="'.$fila['codigo'].'"><strong>Eliminar</strong></a></td>
    //                                  </tr>';
    //                         }
    //                     }
    //                 }
    //                               // echo $salida;
    //             }/*cierre del while*/
    //     }
    // }

    /*paginacion de los clientes en el MENU principal */
    // public function paginacionEstudianteMenu(){
    //         $cant_reg = 10;//definimos la cantidad de datos que deseamos tenes por pagina.

    //         if(isset($_GET["pagina"])){
    //             $num_pag = $_GET["pagina"];//numero de la pagina
    //         }else{
    //             $num_pag = 1;
    //         }

    //         if(!$num_pag){//preguntamos si hay algun valor en $num_pag.
    //             $inicio = 0;
    //             $num_pag = 1;

    //         }else{//se activara si la variable $num_pag ha resivido un valor oasea se encuentra en la pagina 2 o ha si susecivamente 
    //             $inicio = ($num_pag-1)*$cant_reg;//si la pagina seleccionada es la numero 2 entonces 2-1 es = 1 por 10 = 10 empiesa a contar desde la 10 para la pagina 2 ok.
    //         }

    //         $result = mysql_query("SELECT * FROM estudiantes");///hacemos una consulta de todos los datos de cinternet
           
    //         $total_registros=mysql_num_rows($result);//obtenesmos el numero de datos que nos devuelve la consulta

    //         $total_paginas = ceil($total_registros/$cant_reg);

    //         echo '<div class="pagination" style="display: none;">
    //                 ';
    //         if(($num_pag+1)<=$total_paginas){//preguntamos si el numero de la pagina es menor o = al total de paginas para que aparesca el siguiente
                
    //             echo "<ul><li class='next'> <a href='menu.php?pagina=".($num_pag+1)."'> Next </a></li></ul>";
    //         } ;echo '
    //                </div>';
    // }

    public function actulizarTiempo($fechaV,$pago,$con,$cod){
        date_default_timezone_set('America/Bogota'); 
        $fechaI = date("Y-m-d");
        mysql_query("UPDATE estudiantes SET fechaInicial='$fechaI', fechaFinal='$fechaV', dinero='$pago', condicion='$con' WHERE codigo='$cod'") 
                                    or die ("Error en el update");
    }


    /*CALCULO DE LOS REPORTES DE GANANCIAS POR FECHA*/
    public function calcularReporte($fecha1,$fecha2){
        $resultado = mysql_query("SELECT sum(dinero) AS total FROM fechasclientes WHERE condicion='Pago' AND fechaInicial AND fechaFinal between'$fecha1' AND '$fecha2'");
        $fila = mysql_fetch_array($resultado);
            $salida = '<h3 class="well"> Calculo: $'.number_format($fila['total']).'</h3>';
            echo $salida;     
    }


    public function calculosMes(){
        $resultado = mysql_query("SELECT * FROM fechasclientes WHERE condicion='Pago'");
         $conE = 0; $conF=0; $conM=0; $conA=0; $conMy=0; $conJ=0;
         $conJl=0; $conAg=0; $conS=0; $conO=0; $conN=0; $conD=0;
        while($fila = mysql_fetch_array($resultado)){
                    $fecha1 = $fila['fechaInicial'];
                    $fecha2 = $fila['fechaFinal'];

                $mes = substr($fecha1,5,-3);

                /*tener en cuenta la fecha2 por el mes que se pasa*/
                $año = substr($fecha1,0,4);

                    $resul = mysql_query("SELECT sum(dinero) AS total FROM fechasclientes
                                        WHERE condicion='Pago' AND mes='$mes'");
                    $fila2 = mysql_fetch_array($resul);

                if($mes=='01' and $conE==0){
                    $mes="Enero";
                      echo '<tr> 
                                <td>'.$año.'</td>
                                <td>'.$mes.'</td>
                                <td>$'.number_format($fila2['total']).'</td>
                            </tr>';
                            $conE++;
                }else{
                    if($mes=='02' and $conF==0){
                        $mes="Febrero";
                        echo '<tr>
                                <td>'.$año.'</td> 
                                <td>'.$mes.'</td>
                                <td>$'.number_format($fila2['total']).'</td>
                            </tr>';
                        $conF++;
                    }
                    else{
                        if($mes=='03' and $conM==0){
                            $mes="Marzo";
                            echo '<tr>
                                    <td>'.$año.'</td> 
                                    <td>'.$mes.'</td>
                                    <td>$'.number_format($fila2['total']).'</td>
                                </tr>';
                            $conM++;
                        }else{
                            if($mes=='04' and $conA==0){
                                $mes="Abril";
                                echo '<tr>
                                        <td>'.$año.'</td> 
                                        <td>'.$mes.'</td>
                                        <td>$'.number_format($fila2['total']).'</td>
                                    </tr>';
                                $conA++;
                            }else{
                                if($mes=='05' and $conMy==0){
                                    $mes='Mayo';
                                    echo '<tr>
                                            <td>'.$año.'</td> 
                                            <td>'.$mes.'</td>
                                            <td>$'.number_format($fila2['total']).'</td>
                                        </tr>';
                                    $conMy++;
                                }else{
                                    if($mes=='06' and $conJ==0){
                                        $mes = 'Junio';
                                        echo '<tr>
                                                  <td>'.$año.'</td> 
                                                  <td>'.$mes.'</td>
                                                  <td>$'.number_format($fila2['total']).'</td>
                                            </tr>';
                                        //$conJ++;
                                    }else{
                                        if($mes=='07' and $conJl==0){
                                            $mes = 'Julio';
                                            echo '<tr>
                                                      <td>'.$año.'</td> 
                                                      <td>'.$mes.'</td>
                                                      <td>$'.number_format($fila2['total']).'</td>
                                                   </tr>';
                                            $conJl++;
                                        }else{
                                            if($mes=='08' and $conAg==0){
                                                $mes = 'Agosto';
                                                echo '<tr>
                                                          <td>'.$año.'</td> 
                                                          <td>'.$mes.'</td>
                                                          <td>$'.number_format($fila2['total']).'</td>
                                                       </tr>';
                                                $conAg++;
                                            }else{
                                                if($mes=='09' and $conS==0){
                                                    $mes = 'Septiembre';
                                                    echo '<tr>
                                                              <td>'.$año.'</td> 
                                                              <td>'.$mes.'</td>
                                                              <td>$'.number_format($fila2['total']).'</td>
                                                           </tr>';
                                                    $conS++;
                                                }else{
                                                    if($mes=='10' and $conO==0){
                                                        $mes = 'Octubre';
                                                        echo '<tr>
                                                                  <td>'.$año.'</td> 
                                                                  <td>'.$mes.'</td>
                                                                  <td>$'.number_format($fila2['total']).'</td>
                                                               </tr>';
                                                        $conO++;
                                                    }else{
                                                        if($mes=='11' and $conN==0){
                                                            $mes = 'Noviembre';
                                                            echo '<tr>
                                                                      <td>'.$año.'</td> 
                                                                      <td>'.$mes.'</td>
                                                                      <td>$'.number_format($fila2['total']).'</td>
                                                                   </tr>';
                                                            $conN++;
                                                        }else{
                                                            if($mes=='12' and $conD==0){
                                                                $mes = 'Diciembre';
                                                                echo '<tr>
                                                                          <td>'.$año.'</td> 
                                                                          <td>'.$mes.'</td>
                                                                          <td>$'.number_format($fila2['total']).'</td>
                                                                       </tr>';
                                                                $conD++;
                                                            }
                                                        }
                                                    }
                                                }
                                            }
                                        }
                                    }
                                }
                            }
                        }
                    }
                }
        }
        
    }/*cierre de la funcion*/


    /*Codigo de combox para mostrar nombres de los clientes*/
    public function comboClientes(){
        $result = mysql_query("SELECT cedula,nombre FROM clientes");
        while ($fila = mysql_fetch_array($result)) {
            echo "<option value='".$fila['cedula']."'>".$fila['nombre']."
                     </option>";
        }
    }

    /*combox para mostrar los prestamos del cliente*/
    public function comboPrestamos(){
        $resultado = mysql_query("SELECT cedula FROM clientes LIMIT 1");
        $dato = mysql_fetch_array($resultado);
        $cedula = $dato['cedula'];
        $result = mysql_query("SELECT codigo FROM prestamos WHERE cedula='$cedula'");
        while ($fila = mysql_fetch_array($result)) {
            echo "<option value='".$fila['codigo']."'>".$fila['codigo']."
                     </option>";
        }
    }


    /*MODIFICAR DATOS DEL USUAIRO Y CREAR....*/
    public function editarNombreUser($nom,$cod){
        $nom = strtolower($nom);
        mysql_query("UPDATE usuarios SET nombre='$nom' WHERE id='$cod'") or die('problemas en el update de nombre'.mysql_error());
        session_start();
         if($_SESSION['id_user']){
             session_destroy();
         }
        $resultado=mysql_query("SELECT * FROM usuarios WHERE id='$cod'")
              or die('Problemas en el select de nombre usuarios'.mysql_error());
        $row=mysql_fetch_array($resultado);
        echo $row['nombre'];
        /*_______________________________*/
        session_start();
        $id_user=$row['id'];
        $user = $row['nombre'];
        $_SESSION['id_user']=$id_user;
        $_SESSION['nombre'] = $user;
    }

    public function cambiarClave($conA,$conN,$cod){
        $resultado = mysql_query("SELECT clave FROM usuarios WHERE clave='$conA'");
        
        if($row = mysql_fetch_array($resultado)){
            echo "Bien";
            $hash=sha1($conN);//incriptamos la contraseña
            mysql_query("UPDATE usuarios SET clave='$hash' WHERE clave='$conA'");
        }else{
            echo "Error";
        }
    }

    public function registrarUser($nom,$pass){
        $hash=sha1($pass);
         mysql_query("INSERT INTO usuarios (nombre,clave) VALUES ('$nom','$hash')") 
                       or die ("Error"); 
    }

  }//cierre de la clase
?>