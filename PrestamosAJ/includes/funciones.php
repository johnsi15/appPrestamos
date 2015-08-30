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
         $truco=sha1($pass);
         $resultado = mysql_query("SELECT * FROM usuarios WHERE nombre='$user' AND clave='$truco'");
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
        mysql_query("INSERT INTO clientes (cedulaCliente,nombre,direccion,telefono,nPrestamos)
                                      VALUES ('$codigo','$nom','$dir','$tel','0')")
                                      or die ("Error");
    }

    /*funcion para registrar prestamo */
    public function registrarPrestamo($cedula,$prestamo,$NcQ,$NcM,$Vcuota,$fechaPrestamo,$fechaPago,$interes,$tipo,$porcentaje){
        /*actualizando la caja despues del prestamo*/                           
        $resultado2 = mysql_query("SELECT baseTotal FROM caja");
        $fila2 = mysql_fetch_array($resultado2);
        if($fila2['baseTotal'] <= $prestamo){
            echo "Error";
            return false;
        }else{
            $saldo = $prestamo;
            $saldoIn = $interes;
            mysql_query("INSERT INTO prestamos (cedula,monto,saldo,NcuotasQ,NcuotasM,Vcuota,fechaPrestamo,fechaPago,interes,saldoInteres,inicio,notificacion,mes,tipo,porcentaje)
                                          VALUES ('$cedula','$prestamo','$saldo','$NcQ','$NcM','$Vcuota','$fechaPrestamo','$fechaPago','$interes','$saldoIn','0','0','1','$tipo','$porcentaje')")
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

    public function saldoCero(){
        $resultado = mysql_query("SELECT * FROM prestamos WHERE saldo='0'");
        while($fila = mysql_fetch_array($resultado)){
            return true;
        }
    }

    /*funcion para registrar los pagos de los prestamos */
    public function registrarPago($cedula,$fecha,$pago,$interes,$nPrestamo){

        $resultado = mysql_query("SELECT * FROM prestamos WHERE codigo='$nPrestamo'");
        $fila = mysql_fetch_array($resultado);
        if($fila['saldo'] == '0'){
             echo "Error";
             return false;
        }else{
            $nuevoInteres = $fila['saldoInteres'] - $interes;
            $nuevoSaldo = $fila['saldo'] - $pago;

            mysql_query("INSERT INTO pagos (cedulaPagos,fecha,abonoCapital,abonoInteres,saldo,numeroPresta)
                                          VALUES ('$cedula','$fecha','$pago','$interes','$nuevoSaldo','$nPrestamo')")
                                          or die ("Error");

            mysql_query("UPDATE prestamos SET saldo='$nuevoSaldo', saldoInteres='$nuevoInteres',notificacion='1',mes='0' WHERE codigo='$nPrestamo'") 
                                        or die ("Error");

            $resultado2 = mysql_query("SELECT * FROM caja");
            $fila2 = mysql_fetch_array($resultado2);
            $nuevaBase = $fila2['baseTotal'] + $pago;
            $nuevoInteres = $fila2['interesTotal'] + $interes;

            mysql_query("UPDATE caja SET interesTotal='$nuevoInteres', baseTotal='$nuevaBase'") 
                                        or die ("Error");
            //volvemos hacer la consulta para revisar si el saldo esta en cero
            $resultado = mysql_query("SELECT * FROM prestamos WHERE codigo='$nPrestamo'");
            $fila = mysql_fetch_array($resultado);

            if($fila['saldo'] == '0'){
                mysql_query("UPDATE prestamos SET notificacion='3' WHERE codigo='$nPrestamo'") 
                                        or die ("Error");
            }
            return true;
        } 
    }

    public function registrarPago2($cedula,$fecha,$pago,$interes,$nPrestamo){
        $resultado = mysql_query("SELECT * FROM prestamos WHERE codigo='$nPrestamo'");
        $fila = mysql_fetch_array($resultado);
        if($fila['saldo'] == '0'){
             echo "Error";
             return false;
        }else{
            $nuevoInteres = $fila['saldoInteres'] - $interes;
            $nuevoSaldo = $fila['saldo'] - $pago;

            mysql_query("INSERT INTO pagos (cedulaPagos,fecha,abonoCapital,abonoInteres,saldo,numeroPresta)
                                          VALUES ('$cedula','$fecha','$pago','$interes','$nuevoSaldo','$nPrestamo')")
                                          or die ("Error");

            mysql_query("UPDATE prestamos SET saldo='$nuevoSaldo', saldoInteres='$nuevoInteres',notificacion='1',mes='0' WHERE codigo='$nPrestamo'") 
                                        or die ("Error en el update");

            $resultado2 = mysql_query("SELECT * FROM caja");
            $fila2 = mysql_fetch_array($resultado2);
            $nuevaBase = $fila2['baseTotal'] + $pago;
            $nuevoInteres = $fila2['interesTotal'] + $interes;

            mysql_query("UPDATE caja SET interesTotal='$nuevoInteres', baseTotal='$nuevaBase'") 
                                        or die ("Error en el update");

            //volvemos hacer la consulta para revisar si el saldo esta en cero
            $resultado = mysql_query("SELECT * FROM prestamos WHERE codigo='$nPrestamo'");
            $fila = mysql_fetch_array($resultado);

            if($fila['saldo'] == '0'){
                mysql_query("UPDATE prestamos SET notificacion='3' WHERE codigo='$nPrestamo'") 
                                        or die ("Error");
                //echo'cero';
            }
            return true;
        } 
    }

    /*actualizar la base de la caja si es la primera vez lo registramos*/
    public function actualizarBase($base){
        $resultado = mysql_query("SELECT * FROM caja");
        if($fila = mysql_fetch_array($resultado)){
            mysql_query("UPDATE caja SET baseTotal='$base'") 
                                    or die ("Error en el update");
        }else{
            mysql_query("INSERT INTO caja (baseTotal,interesTotal) VALUES ('$base','0')") or die ("Error");
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

        $resultado = mysql_query("SELECT * FROM prestamos,clientes WHERE prestamos.cedula=clientes.cedulaCliente ORDER BY codigo ASC LIMIT $inicio,$cant_reg");
   
        while($fila = mysql_fetch_array($resultado)){
            if($fila['saldo'] == '0'){

            }else{ 
                 if($fila['tipo'] == 'm'){
                     echo '<tr> 
                        <td>'.$fila['codigo'].'</td>
                        <td>'.$fila['nombre'].'</td>
                        <td>'.$fila['direccion'].'</td>
                        <td>'.$fila['telefono'].'</td>
                        <td>'.number_format($fila['saldo']).'</td>
                        <td><a id="info" class="btn btn-mini btn-info" 
                                 data-toggle="popover" data-placement="top" 
                                 data-content="Cedula: '.$fila['cedulaCliente'].'<br>
                                               ValorCuota: '.number_format($fila['Vcuota']).'  <br>
                                               NcuotasM: '.$fila['NcuotasM'].'   <br>
                                               TipoPago: '.$fila['tipo'].'   <br>
                                               Prestamo:'.number_format($fila['monto']).'<br>
                                               FechaInicio:  '.$fila['fechaPrestamo'].'  <br>
                                               FechaFin: '.$fila['fechaPago'].' <br>
                                               N° Prestamos: '.$fila['nPrestamos'].'"

                                 data-original-title="'.$fila['nombre'].'" href="#vermas"><strong>Ver Mas</strong>
                                </a>
                            </td>
                    </tr>';
                }else{
                    echo '<tr> 
                        <td>'.$fila['codigo'].'</td>
                        <td>'.$fila['nombre'].'</td>
                        <td>'.$fila['direccion'].'</td>
                        <td>'.$fila['telefono'].'</td>
                        <td>'.number_format($fila['saldo']).'</td>
                        <td><a id="info" class="btn btn-mini btn-info" 
                                 data-toggle="popover" data-placement="top" 
                                 data-content="Cedula: '.$fila['cedulaCliente'].'<br>
                                               ValorCuota: '.number_format($fila['Vcuota']).'  <br>
                                               NcuotasQ: '.$fila['NcuotasQ'].'   <br>
                                               TipoPago: '.$fila['tipo'].'   <br>
                                               Prestamo:'.number_format($fila['monto']).'<br>
                                               FechaInicio:  '.$fila['fechaPrestamo'].'  <br>
                                               FechaFin: '.$fila['fechaPago'].' <br>
                                               N° Prestamos: '.$fila['nPrestamos'].'"

                                 data-original-title="'.$fila['nombre'].'" href="#vermas"><strong>Ver Mas</strong>
                                </a>
                            </td>
                    </tr>';
                }
            }
        }      
    }/*cierre del metodo*/

    /*paginacion de los clientes en el MENU principal */
    public function paginacionClientesMenu(){
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

            $result = mysql_query("SELECT * FROM prestamos,clientes WHERE prestamos.cedula=clientes.cedulaCliente ORDER BY codigo ASC");///hacemos una consulta de todos los datos de cinternet
           
            $total_registros=mysql_num_rows($result);//obtenesmos el numero de datos que nos devuelve la consulta

            $total_paginas = ceil($total_registros/$cant_reg);

            echo '<div class="pagination" style="display: none;">
                    ';
            if(($num_pag+1)<=$total_paginas){//preguntamos si el numero de la pagina es menor o = al total de paginas para que aparesca el siguiente
                
                echo "<ul><li class='next'> <a href='menu.php?pagina=".($num_pag+1)."'> Next </a></li></ul>";
            } ;echo '
                   </div>';
    }

    /*buscador en tiempo real de los clientes del menu principal */
    public function buscarClientesMenu($palabra){
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

            $result = mysql_query("SELECT * FROM prestamos,clientes WHERE prestamos.cedula=clientes.cedulaCliente ORDER BY codigo ASC");///hacemos una consulta de todos los datos de cinternet
           
            $total_registros=mysql_num_rows($result);//obtenesmos el numero de datos que nos devuelve la consulta

            $total_paginas = ceil($total_registros/$cant_reg);

            echo '<div class="pagination" style="display: none;">
                    ';
            if(($num_pag+1)<=$total_paginas){//preguntamos si el numero de la pagina es menor o = al total de paginas para que aparesca el siguiente
                
                echo "<ul><li class='next'> <a href='menu.php?pagina=".($num_pag+1)."'> Next </a></li></ul>";
            } ;echo '
                   </div>';

            $resultado = mysql_query("SELECT * FROM prestamos,clientes WHERE prestamos.cedula=clientes.cedulaCliente ORDER BY codigo ASC LIMIT $inicio,$cant_reg");//obtenemos los datos ordenados limitado con la variable inicio hasta la variable cant_reg
            while($fila = mysql_fetch_array($resultado)){
                if($fila['saldo'] == '0'){

                }else{
                    if($fila['tipo'] == 'm'){
                         echo '<tr> 
                            <td>'.$fila['codigo'].'</td>
                            <td>'.$fila['nombre'].'</td>
                            <td>'.$fila['direccion'].'</td>
                            <td>'.$fila['telefono'].'</td>
                            <td>'.number_format($fila['saldo']).'</td>
                            <td><a id="info" class="btn btn-mini btn-info" 
                                     data-toggle="popover" data-placement="top" 
                                     data-content="Cedula: '.$fila['cedulaCliente'].'<br>
                                                   ValorCuota: '.number_format($fila['Vcuota']).'  <br>
                                                   NcuotasM: '.$fila['NcuotasM'].'   <br>
                                                   TipoPago: '.$fila['tipo'].'   <br>
                                                   Prestamo:'.number_format($fila['monto']).'<br>
                                                   FechaInicio:  '.$fila['fechaPrestamo'].'  <br>
                                                   FechaFin: '.$fila['fechaPago'].' <br>
                                                   N° Prestamos: '.$fila['nPrestamos'].'"

                                     data-original-title="'.$fila['nombre'].'" href="#vermas"><strong>Ver Mas</strong>
                                    </a>
                                </td>
                        </tr>';
                    }else{
                        echo '<tr> 
                            <td>'.$fila['codigo'].'</td>
                            <td>'.$fila['nombre'].'</td>
                            <td>'.$fila['direccion'].'</td>
                            <td>'.$fila['telefono'].'</td>
                            <td>'.number_format($fila['saldo']).'</td>
                            <td><a id="info" class="btn btn-mini btn-info" 
                                     data-toggle="popover" data-placement="top" 
                                     data-content="Cedula: '.$fila['cedulaCliente'].'<br>
                                                   ValorCuota: '.number_format($fila['Vcuota']).'  <br>
                                                   NcuotasQ: '.$fila['NcuotasQ'].'   <br>
                                                   TipoPago: '.$fila['tipo'].'   <br>
                                                   Prestamo:'.number_format($fila['monto']).'<br>
                                                   FechaInicio:  '.$fila['fechaPrestamo'].'  <br>
                                                   FechaFin: '.$fila['fechaPago'].' <br>
                                                   N° Prestamos: '.$fila['nPrestamos'].'"

                                     data-original-title="'.$fila['nombre'].'" href="#vermas"><strong>Ver Mas</strong>
                                    </a>
                                </td>
                        </tr>';
                    }
                }
            }/*cierre del while*/
        }else{
             $resultado = mysql_query("SELECT * FROM prestamos,clientes WHERE (prestamos.cedula=clientes.cedulaCliente AND nombre LIKE'%$palabra%') OR (prestamos.cedula=clientes.cedulaCliente AND cedulaCliente LIKE'$palabra%') OR (prestamos.cedula=clientes.cedulaCliente AND fechaPrestamo LIKE'%$palabra%')");
            //echo json_encode($resultado);
            while($fila = mysql_fetch_array($resultado)){
                if($fila['saldo'] == '0'){

                }else{
                    if($fila['tipo'] == 'm'){
                         echo '<tr> 
                            <td>'.$fila['codigo'].'</td>
                            <td>'.$fila['nombre'].'</td>
                            <td>'.$fila['direccion'].'</td>
                            <td>'.$fila['telefono'].'</td>
                            <td>'.number_format($fila['saldo']).'</td>
                            <td><a id="info" class="btn btn-mini btn-info" 
                                     data-toggle="popover" data-placement="top" 
                                     data-content="Cedula: '.$fila['cedulaCliente'].'<br>
                                                   ValorCuota: '.number_format($fila['Vcuota']).'  <br>
                                                   NcuotasM: '.$fila['NcuotasM'].'   <br>
                                                   TipoPago: '.$fila['tipo'].'   <br>
                                                   Prestamo:'.number_format($fila['monto']).'<br>
                                                   FechaInicio:  '.$fila['fechaPrestamo'].'  <br>
                                                   FechaFin: '.$fila['fechaPago'].' <br>
                                                   N° Prestamos: '.$fila['nPrestamos'].'"

                                     data-original-title="'.$fila['nombre'].'" href="#vermas"><strong>Ver Mas</strong>
                                    </a>
                                </td>
                        </tr>';
                    }else{
                        echo '<tr> 
                            <td>'.$fila['codigo'].'</td>
                            <td>'.$fila['nombre'].'</td>
                            <td>'.$fila['direccion'].'</td>
                            <td>'.$fila['telefono'].'</td>
                            <td>'.number_format($fila['saldo']).'</td>
                            <td><a id="info" class="btn btn-mini btn-info" 
                                     data-toggle="popover" data-placement="top" 
                                     data-content="Cedula: '.$fila['cedulaCliente'].'<br>
                                                   ValorCuota: '.number_format($fila['Vcuota']).'  <br>
                                                   NcuotasQ: '.$fila['NcuotasQ'].'   <br>
                                                   TipoPago: '.$fila['tipo'].'   <br>
                                                   Prestamo:'.number_format($fila['monto']).'<br>
                                                   FechaInicio:  '.$fila['fechaPrestamo'].'  <br>
                                                   FechaFin: '.$fila['fechaPago'].' <br>
                                                   N° Prestamos: '.$fila['nPrestamos'].'"

                                     data-original-title="'.$fila['nombre'].'" href="#vermas"><strong>Ver Mas</strong>
                                    </a>
                                </td>
                        </tr>';
                    }
                }
            }/*cierre del while*/
        }
    }

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

        $resultado = mysql_query("SELECT * FROM prestamos,clientes WHERE prestamos.cedula=clientes.cedulaCliente ORDER BY codigo ASC LIMIT $inicio,$cant_reg");
       
        while($fila = mysql_fetch_array($resultado)){
            if($fila['saldo']=='0'){
                
            }else{
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
                                       TipoPago: '.$fila['tipo'].'   <br>
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

            $result = mysql_query("SELECT * FROM prestamos,clientes WHERE prestamos.cedula=clientes.cedulaCliente ORDER BY codigo ASC");///hacemos una consulta de todos los datos de cinternet
           
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

            $resultado = mysql_query("SELECT * FROM prestamos,clientes WHERE prestamos.cedula=clientes.cedulaCliente ORDER BY codigo ASC LIMIT $inicio,$cant_reg");
            while($fila = mysql_fetch_array($resultado)){
                if($fila['saldo']=='0'){
                    
                }else{
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
                                           TipoPago: '.$fila['tipo'].'   <br>
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
             $resultado = mysql_query("SELECT * FROM prestamos,clientes WHERE (prestamos.cedula=clientes.cedulaCliente AND nombre LIKE'%$palabra%') OR (prestamos.cedula=clientes.cedulaCliente AND cedulaCliente LIKE'$palabra%') ");
            //echo json_encode($resultado);
            while($fila = mysql_fetch_array($resultado)){
                   if($fila['saldo']=='0'){
                       
                    }else{
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
                                               TipoPago: '.$fila['tipo'].'   <br>
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
            if($fila['tipo'] == 'm'){
                //$resuPorce = ($fila['monto'] * $fila['porcentaje'])/100;
                //$valorCuota = ($fila['monto'] + $fila['interes'])/$fila['NcuotasM'];
                $capital = $fila['monto']/$fila['NcuotasM'];
                $interes = $fila['interes']/$fila['NcuotasM'];
                echo '<tr class="success"> 
                    <td>'.$fila['codigo'].'</td>
                    <td>'.number_format($fila['Vcuota']).'</td>
                    <td>'.number_format($capital).'</td>
                    <td>'.number_format($interes).'</td>
                </tr>';
            }else{
                //$resuPorce = ($fila['monto'] * $fila['porcentaje'])/100;
                //$valorCuota = ($fila['monto'] + $fila['interes'])/$fila['NcuotasQ'];
                $capital = $fila['monto']/$fila['NcuotasQ'];
                $interes = $fila['interes']/$fila['NcuotasQ'];
                echo '<tr class="success"> 
                    <td>'.$fila['codigo'].'</td>
                    <td>'.number_format($fila['Vcuota']).'</td>
                    <td>'.number_format($capital).'</td>
                    <td>'.number_format($interes).'</td>
                </tr>';
            }
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

    /*renovar prestamos de los clientes*/
    public function renovarPrestamos($codigo,$prestamo,$NcQ,$NcM,$Vcuota,$fechaPrestamo,$fechaPago,$interes,$tipo,$porcentaje){
        /*actualizando la caja despues del prestamo*/                           
        $resultado2 = mysql_query("SELECT baseTotal FROM caja");
        $fila2 = mysql_fetch_array($resultado2);
        if($fila2['baseTotal'] <= $prestamo){
            echo "Error";
            return false;
        }else{
            $saldo = $prestamo;
            $saldoIn = $interes;
            mysql_query("UPDATE prestamos SET monto='$prestamo', saldo='$saldo', NcuotasQ='$NcQ',
                                              NcuotasM='$NcM', Vcuota='$Vcuota',fechaPrestamo='$fechaPrestamo',
                                              fechaPago='$fechaPago',interes='$interes',saldoInteres='$saldoIn',
                                              inicio='0', notificacion='0', mes='1', tipo='$tipo', porcentaje='$porcentaje'
                                              WHERE codigo='$codigo'")
                                          or die ("Error");

            $nuevaCaja = $fila2['baseTotal'] - $prestamo;
            mysql_query("UPDATE caja SET baseTotal='$nuevaCaja'") 
                                        or die ("Error en el update");
            return true;
        }
    }

    /*ver los prestamos que necesito renovar o eliminar*/
    public function verRenovar(){
        $saldo = 0;
        $resultado = mysql_query("SELECT * FROM prestamos,clientes WHERE (prestamos.cedula=clientes.cedulaCliente AND saldo='$saldo')");
        while($fila = mysql_fetch_array($resultado)){
            echo '<tr> 
                <td>'.$fila['codigo'].'</td>
                <td>'.$fila['nombre'].'</td>
                <td>'.number_format($fila['monto']).'</td>
                <td><a id="renovar" class="btn btn-mini btn-info" href="'.$fila['codigo'].'"><strong>Renovar</strong></a></td>
                <td><a id="delete" class="btn btn-mini btn-danger" href="'.$fila['cedula'].'"><strong>Eliminar</strong></a></td>
            </tr>';
        }
    }

    /*eliminar credito de los clientes*/
    public function eliminarPrestamo($cedula){
        mysql_query("DELETE FROM clientes WHERE cedulaCliente='$cedula'");
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
        $resultado = mysql_query("SELECT * FROM clientes,pagos WHERE pagos.cedulaPagos=clientes.cedulaCliente ORDER BY id DESC LIMIT $inicio,$cant_reg");   
        $resultado2 = mysql_query("SELECT * FROM prestamos");
        $nPrestamo = 0;
        while($fila = mysql_fetch_array($resultado)){
            $contador = mysql_query("SELECT count(*) FROM pagos WHERE cedulaPagos=".$fila['cedulaPagos']."");
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
            if($fila['saldo'] == '0'){
                $fila2 = mysql_fetch_array($resultado2);
                $nPrestamo = $fila2['codigo'];
                mysql_query("UPDATE prestamos SET notificacion='3' WHERE codigo='$nPrestamo'") 
                                        or die ("Error en el update");
            }
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

            $result = mysql_query("SELECT * FROM clientes,pagos WHERE pagos.cedulaPagos=clientes.cedulaCliente ORDER BY id DESC");///hacemos una consulta de todos los datos de cinternet
           
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

            $result = mysql_query("SELECT * FROM clientes,pagos WHERE pagos.cedulaPagos=clientes.cedulaCliente ORDER BY id DESC");///hacemos una consulta de todos los datos
           
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

            $resultado = mysql_query("SELECT * FROM clientes,pagos WHERE pagos.cedulaPagos=clientes.cedulaCliente ORDER BY id DESC LIMIT $inicio,$cant_reg");//obtenemos los datos ordenados limitado con la variable inicio hasta la variable cant_reg
           while($fila = mysql_fetch_array($resultado)){
                 $contador = mysql_query("SELECT count(*) FROM pagos WHERE cedulaPagos=".$fila['cedulaPagos']."");
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
            $resultado = mysql_query("SELECT * FROM clientes,pagos WHERE (pagos.cedulaPagos=clientes.cedulaCliente AND (cedulaCliente LIKE '$palabra%') )  OR (pagos.cedulaPagos=clientes.cedulaCliente AND nombre LIKE'%$palabra%') OR (pagos.cedulaPagos=clientes.cedulaCliente AND fecha LIKE'%$palabra%')");
            //echo json_encode($resultado);
            while($fila = mysql_fetch_array($resultado)){
                $contador = mysql_query("SELECT count(*) FROM pagos WHERE cedulaPagos=".$fila['cedulaCliente']."");
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

    /*verificamos si hay clientes que deben pagar notificamoscuantos*/
    public function verificar(){
        date_default_timezone_set('America/Bogota'); 
        $fecha = date("Y-m-d");//fecha actual bien 
        $fechaD = date("d");
        $fechaM = date("m");
        $resultado = mysql_query("SELECT * FROM prestamos,clientes WHERE prestamos.cedula=clientes.cedulaCliente ORDER BY codigo DESC");//obtenemos los datos ordenados limitado con la variable inicio hasta la variable cant_reg
        $con = 0;
        while($fila = mysql_fetch_array($resultado)){
            $dia = substr($fila['fechaPrestamo'],8,10);
            $diaNoti = $dia -3;
            $diaNoti2 = $dia -2;
            $diaNoti3 = $dia -1;
            $diaNoti4 = $dia + 1;

            $diaP = substr($fila['fechaPago'],8,10);
            $diaP = $diaP+15; //aunmentamos 15 para saber la otras fecha de pago
            if($diaP <= 31){
                $diaPnot = $diaP -3;
                $diaPnot2 = $diaP -2;
                $diaPnot3 = $diaP -1;
                $diaPnot4 = $diaP +1;
            }else{
                $diaP = substr($fila['fechaPago'],8,10);
                $diaP = $diaP-15;
                $diaPnot = $diaP -3;
                $diaPnot2 = $diaP -2;
                $diaPnot3 = $diaP -1;
                $diaPnot4 = $diaP +1;
            }
           
            if($fila['tipo'] == 'm'){
                $diaP = substr($fila['fechaPago'],8,10);
                $diaPnot = $diaP -3;
                $diaPnot2 = $diaP -2;
                $diaPnot3 = $diaP -1;
                $diaPnot4 = $diaP +1;
                if($diaP == $fechaD or $diaPnot == $fechaD or $diaPnot2 == $fechaD or $diaPnot3 == $fechaD or $diaPnot4 ==$fechaD){
                    if($fila['inicio'] == '1'){
                        if($fila['notificacion'] == '0'){
                            $con = $con +1;
                        }
                    } 
                }
            }else{//quincenal muestra los que son quincenal 
                 if($dia == $fechaD or $diaNoti == $fechaD or $diaNoti2 == $fechaD or $diaNoti3 == $fechaD or $diaNoti4 ==$fechaD){
                    if($fila['inicio'] == '1'){
                        if($fila['notificacion'] == '0'){
                            $con = $con +1;
                        }
                    } 
                }else{
                    if($diaP == $fechaD or $diaPnot == $fechaD or $diaPnot2 == $fechaD or $diaPnot3 == $fechaD or $diaPnot4 ==$fechaD){
                        if($fila['inicio'] == '1'){
                            if($fila['notificacion'] == '0'){
                                $con = $con +1;
                            }
                        } 
                    }
                } 
            }
        }/*cierre del while*/ 
            return $con;
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
                        <td><a id="info" class="btn btn-mini btn-info"
                             data-toggle="popover" data-placement="top" 
                             data-content="N° Cedula: '.$fila['cedulaCliente'].'"

                             data-original-title="'.$fila['nombre'].'" href="#vermas"><strong>Ver Mas</strong>
                            </a>
                        </td>
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
                        <td><a id="info" class="btn btn-mini btn-info"
                             data-toggle="popover" data-placement="top" 
                             data-content="N° Cedula: '.$fila['cedulaCliente'].'"

                             data-original-title="'.$fila['nombre'].'" href="#vermas"><strong>Ver Mas</strong>
                            </a>
                        </td>
                    </tr>';
            } 
        }else{
             $resultado = mysql_query("SELECT * FROM clientes WHERE nombre LIKE'%$palabra%' OR cedulaCliente LIKE'$palabra%'");
            //echo json_encode($resultado);
            while($fila = mysql_fetch_array($resultado)){
                   echo '<tr> 
                        <td>'.$fila['nombre'].'</td>
                        <td>'.$fila['direccion'].'</td>
                        <td>'.$fila['telefono'].'</td>
                        <td><a id="editEstudiante" class="btn btn-mini btn-info" href="'.$fila['cedulaCliente'].'"><strong>Editar</strong></a></td>
                        <td><a id="delete" class="btn btn-mini btn-danger" href="'.$fila['cedulaCliente'].'"><strong>Eliminar</strong></a></td>
                        <td><a id="info" class="btn btn-mini btn-info"
                             data-toggle="popover" data-placement="top" 
                             data-content="N° Cedula: '.$fila['cedulaCliente'].'"

                             data-original-title="'.$fila['nombre'].'" href="#vermas"><strong>Ver Mas</strong>
                            </a>
                        </td>
                    </tr>';
            }  
        }
    } //cierre de la function

    public function verClientesDias(){
        date_default_timezone_set('America/Bogota'); 
        $fecha = date("Y-m-d");//fecha actual bien 
        $fechaD = date("d");
        $fechaM = date("m");
        $resultado = mysql_query("SELECT * FROM prestamos,clientes WHERE prestamos.cedula=clientes.cedulaCliente ORDER BY codigo DESC");//obtenemos los datos ordenados limitado con la variable inicio hasta la variable cant_reg
        while($fila = mysql_fetch_array($resultado)){
            $dia = substr($fila['fechaPrestamo'],8,10);
            $diaNoti = $dia -3;
            $diaNoti2 = $dia -2;
            $diaNoti3 = $dia -1;
            $diaNoti4 = $dia + 1;

            $diaP = substr($fila['fechaPago'],8,10);
            $diap = $diap+15; //aunmentamos 15 para saber la otras fecha de pago
            if($diap <= 31){
                $diaPnot = $diaP -3;
                $diaPnot2 = $diaP -2;
                $diaPnot3 = $diaP -1;
                $diaPnot4 = $diaP +1;
            }else{
                $diaP = substr($fila['fechaPago'],8,10);
                $diap = $diap-15;
                $diaPnot = $diaP -3;
                $diaPnot2 = $diaP -2;
                $diaPnot3 = $diaP -1;
                $diaPnot4 = $diaP +1;
            }

            if($fila['tipo'] == 'm'){
                $diaP = substr($fila['fechaPago'],8,10);
                $diaPnot = $diaP -3;
                $diaPnot2 = $diaP -2;
                $diaPnot3 = $diaP -1;
                $diaPnot4 = $diaP +1;
                if($diaP == $fechaD or $diaPnot == $fechaD or $diaPnot2 == $fechaD or $diaPnot3 == $fechaD or $diaPnot4 ==$fechaD){
                    if($fila['inicio'] == '1'){
                        if($fila['notificacion'] == '0'){
                            echo '<tr class="success"> 
                                 <td>'.$fila['codigo'].'</td>
                                <td><a href="includes/pagos.php">'.$fila['nombre'].'</td>
                                <td>'.number_format($fila['Vcuota']).'</td>
                            </tr>';
                        }
                    } 
                }
            }else{//quincenal muestra los que son quincenal 
                 if($dia == $fechaD or $diaNoti == $fechaD or $diaNoti2 == $fechaD or $diaNoti3 == $fechaD or $diaNoti4 ==$fechaD){
                    if($fila['inicio'] == '1'){
                        if($fila['notificacion'] == '0'){
                            echo '<tr class="success"> 
                                <td>'.$fila['codigo'].'</td>
                                <td><a href="includes/pagos.php">'.$fila['nombre'].'</a></td>
                                <td>'.number_format($fila['Vcuota']).'</td>
                            </tr>';
                        }
                    } 
                }else{
                    if($diaP == $fechaD or $diaPnot == $fechaD or $diaPnot2 == $fechaD or $diaPnot3 == $fechaD or $diaPnot4 ==$fechaD){
                        if($fila['inicio'] == '1'){
                            if($fila['notificacion'] == '0'){
                                echo '<tr class="success"> 
                                     <td>'.$fila['codigo'].'</td>
                                    <td><a href="includes/pagos.php">'.$fila['nombre'].'</td>
                                    <td>'.number_format($fila['Vcuota']).'</td>
                                </tr>';
                            }
                        } 
                    }
                } 
            }
        }/*cierre del while*/
    }

    public function notificarFecha(){
        date_default_timezone_set('America/Bogota'); 
        $fecha = date("Y-m-d");//fecha actual bien 
        $fechaD = date("d");
        $fechaM = date("m");
        $fechaA = date("Y");
        $resultado = mysql_query("SELECT * FROM pagos,prestamos WHERE prestamos.codigo=pagos.numeroPresta and notificacion='1'");
        while($fila = mysql_fetch_array($resultado)){
                $dia = substr($fila['fecha'],8,10);
                $mes = substr($fila['fecha'],5,-3);
                $año = substr($fila['fecha'],0,4);
                $dia2 = $dia + 5;
                if($fechaD >= $dia2 or $fechaM > $mes or $fechaA > $año){
                    $nPrestamo = $fila['codigo'];
                    mysql_query("UPDATE prestamos SET notificacion='0' WHERE codigo='$nPrestamo'") 
                                        or die ("Error en el update");
                }
                
        }

        $resultado = mysql_query("SELECT * FROM prestamos WHERE inicio='0'");
        while($fila = mysql_fetch_array($resultado)){
            $dia = substr($fila['fechaPrestamo'],8,10);
            $mes = substr($fila['fechaPrestamo'],5,-3);
            $año = substr($fila['fechaPrestamo'],0,4);
            $dia2 = $dia + 3;
            if($fila['tipo'] == 'm'){
                if($mes < $fechaM or $año < $fechaA){
                    if($fila['inicio'] == '0'){
                            $nPrestamo = $fila['codigo'];
                            mysql_query("UPDATE prestamos SET inicio='1' WHERE codigo='$nPrestamo'") 
                                                or die ("Error en el update");
                    } 
                }
            }else{//es quincenal
                if($fila['inicio'] == '0'){
                    if($fechaD >= $dia2 or $fechaM > $mes){
                        $nPrestamo = $fila['codigo'];
                        mysql_query("UPDATE prestamos SET inicio='1' WHERE codigo='$nPrestamo'") 
                                         or die ("Error en el update");
                    }
                } 
            }
        }
    }//cierre metodo 

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
        /*$resultado = mysql_query("SELECT * FROM clientes");
        while($fila = mysql_fetch_array($resultado)){
            $cod = $fila['cedulaCliente'];
            mysql_query("DELETE FROM clientes WHERE cedulaCliente='$cod'");
        }*/
        $numero = 0;
        while($numero < 5){
            if($numero == 0){
                 mysql_query("DROP  TABLE caja");
                 mysql_query("CREATE TABLE IF NOT EXISTS `caja` (
                             `baseTotal` int(11) NOT NULL,
                                  `interesTotal` int(11) NOT NULL
                            ) ENGINE=InnoDB DEFAULT CHARSET=utf8");
                 $numero++;
            }else{
                if($numero == 1){
                    mysql_query("DROP TABLE prestamos");
                    mysql_query("CREATE TABLE IF NOT EXISTS `prestamos` (
                      `codigo` int(11) NOT NULL AUTO_INCREMENT,
                      `cedula` int(11) NOT NULL,
                      `monto` int(11) NOT NULL,
                      `saldo` int(11) NOT NULL,
                      `NcuotasQ` varchar(15) NOT NULL,
                      `NcuotasM` varchar(15) NOT NULL,
                      `Vcuota` int(11) NOT NULL,
                      `fechaPrestamo` date NOT NULL,
                      `fechaPago` date NOT NULL,
                      `interes` int(11) NOT NULL,
                      `saldoInteres` int(11) NOT NULL,
                      `inicio` varchar(2) NOT NULL,
                      `notificacion` int(11) NOT NULL,
                      `mes` int(11) NOT NULL,
                      `tipo` varchar(5) NOT NULL,
                      `porcentaje` int(11) NOT NULL,
                      PRIMARY KEY (`codigo`),
                      KEY `cedula` (`cedula`)
                    ) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=3");
                    $numero++;
                }else{
                    if($numero == 2){
                        mysql_query("DROP TABLE clientes");
                        mysql_query("CREATE TABLE IF NOT EXISTS `clientes` (
                                      `cedulaCliente` int(11) NOT NULL,
                                      `nombre` varchar(60) NOT NULL,
                                      `direccion` varchar(60) NOT NULL,
                                      `telefono` varchar(20) NOT NULL,
                                      `nPrestamos` int(11) NOT NULL,
                                      PRIMARY KEY (`cedulaCliente`)
                                    ) ENGINE=InnoDB DEFAULT CHARSET=utf8");
                        $numero++;
                    }else{
                        if($numero == 3){
                            mysql_query("DROP TABLE pagos");
                            mysql_query("CREATE TABLE IF NOT EXISTS `pagos` (
                              `id` int(11) NOT NULL AUTO_INCREMENT,
                              `cedulaPagos` int(11) NOT NULL,
                              `fecha` date NOT NULL,
                              `abonoCapital` int(11) NOT NULL,
                              `abonoInteres` int(11) NOT NULL,
                              `saldo` int(11) NOT NULL,
                              `numeroPresta` int(11) NOT NULL,
                              PRIMARY KEY (`id`),
                              KEY `cedula` (`cedulaPagos`),
                              KEY `numeroPresta` (`numeroPresta`)
                            ) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=8 ");
                            $numero++;
                        }else{
                            if($numero == 4){
                                mysql_query("TRUNCATE TABLE gastos");
                                mysql_query("ALTER TABLE `pagos`
                                  ADD CONSTRAINT `pagos_ibfk_2` FOREIGN KEY (`numeroPresta`) REFERENCES `prestamos` (`codigo`) ON DELETE CASCADE ON UPDATE CASCADE,
                                  ADD CONSTRAINT `pagos_ibfk_1` FOREIGN KEY (`cedulaPagos`) REFERENCES `clientes` (`cedulaCliente`) ON DELETE CASCADE ON UPDATE CASCADE");
                                mysql_query("ALTER TABLE `prestamos`
                                ADD CONSTRAINT `prestamos_ibfk_1` FOREIGN KEY (`cedula`) REFERENCES `clientes` (`cedulaCliente`) ON DELETE CASCADE ON UPDATE CASCADE");
                                $numero++;
                            }
                        }
                    }
                }
            }
        }
    }

   /*aca comienzo con la partde de actulizar datos de los estudiantes que van al gim*/
    public function actualizarDatosPersonales($cod,$nom,$dir,$tel){
        mysql_query("UPDATE clientes SET nombre='$nom', direccion='$dir', telefono='$tel' WHERE cedulaCliente='$cod'") 
                                    or die ("Error");
    }

    public function fechasMes(){
        $arrayMeses = array('Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio',
                    'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre');
        echo '<h2 id="mes"> Mes Actual - '.$arrayMeses[date('m')-1].'</h2>';

        date_default_timezone_set('America/Bogota'); 
        $fecha = date("Y-m-d");//fecha actual bien 
        $m = date("m");
    }//cierre metodo

    public function quienesDebenPagar(){
        $resultado = mysql_query("SELECT * FROM prestamos");
        while($fila = mysql_fetch_array($resultado)){
            if($fila['mes'] == '0'){
                        $nPrestamo = $fila['codigo'];
                        mysql_query("UPDATE prestamos SET mes='1' WHERE codigo='$nPrestamo'") 
                                            or die ("Error en el update");
            }
        }
    }

    public function mesualidad(){
        date_default_timezone_set('America/Bogota'); 
        $fecha = date("Y-m-d");//fecha actual bien 
        $año = date("Y");
        $fechaD = date("d");
        $fechaM = date("m");
        $resultado = mysql_query("SELECT * FROM prestamos,clientes WHERE prestamos.cedula=clientes.cedulaCliente ORDER BY codigo DESC");//obtenemos los datos ordenados limitado con la variable inicio hasta la variable cant_reg
        while($fila = mysql_fetch_array($resultado)){
            $mes = substr($fila['fechaPrestamo'],5,-3);
            $año = substr($fila['fechaPrestamo'],0,4);
            if($fila['saldo'] != '0'){
                if($fila['inicio'] == '1'){
                    if($fila['mes'] == '1'){
                        if($mes < $fechaM or $año < $fechaA){
                            echo '<tr>
                                  <td>'.$fila['codigo'].'</td>
                                  <td>'.$fila['nombre'].'</td>
                                  <td>'.number_format($fila['saldo']).'</td>
                                  <td><a id="pagar" class="btn btn-mini btn-success" href="'.$fila['cedulaCliente'].'"><strong>Pagar</strong></a></td>
                            </tr>';
                        }
                    }
                }
            }
        }/*cierre del while*/
    }

    public function actulizarTiempo($fechaV,$pago,$con,$cod){
        date_default_timezone_set('America/Bogota'); 
        $fechaI = date("Y-m-d");
        mysql_query("UPDATE estudiantes SET fechaInicial='$fechaI', fechaFinal='$fechaV', dinero='$pago', condicion='$con' WHERE codigo='$cod'") 
                                    or die ("Error en el update");
    }


    /*CALCULO DE LOS REPORTES DE GANANCIAS POR FECHA*/
    public function calcularReporte($fecha1,$fecha2){
        $resultado = mysql_query("SELECT sum(abonoCapital) AS total FROM pagos WHERE fecha AND fecha between'$fecha1' AND '$fecha2'");
        $resultado2 = mysql_query("SELECT sum(abonoInteres) AS total2 FROM pagos WHERE fecha AND fecha between'$fecha1' AND '$fecha2'");
        $fila = mysql_fetch_array($resultado);
        $fila2 = mysql_fetch_array($resultado2);
            $salida = '<h3 class="well"> Calculo Prestamos: $'.number_format($fila['total']).'</h3>';
             $salida2 = '<h3 class="well"> Calculo Interes: $'.number_format($fila2['total2']).'</h3>';
            echo $salida;
            echo $salida2;     
    }


    public function calculosMes(){
        $resultado = mysql_query("SELECT * FROM pagos");
         $conE = 0; $conF=0; $conM=0; $conA=0; $conMy=0; $conJ=0;
         $conJl=0; $conAg=0; $conS=0; $conO=0; $conN=0; $conD=0;
        while($fila = mysql_fetch_array($resultado)){
                    $fecha1 = $fila['fecha'];
                $dia = substr($fila['fecha'],8,10);
                $mes = substr($fecha1,5,-3);
                $año = substr($fecha1,0,4);
                $fecha2 = $año.'-'.$mes.'-'.'31'; 
                    $resul = mysql_query("SELECT sum(abonoInteres) AS total FROM pagos WHERE fecha AND fecha between'$fecha1' AND '$fecha2'");
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
        $result = mysql_query("SELECT cedulaCliente,nombre FROM clientes");
        while ($fila = mysql_fetch_array($result)) {
            echo "<option value='".$fila['cedulaCliente']."'>".$fila['nombre']."
                     </option>";
        }
    }

    /*combox para mostrar los prestamos del cliente*/
    public function comboPrestamos(){
        $resultado = mysql_query("SELECT cedulaCliente FROM clientes LIMIT 1");
        $dato = mysql_fetch_array($resultado);
        $cedula = $dato['cedulaCliente'];
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
        $resultado = mysql_query("SELECT clave FROM usuarios WHERE id='$cod'");
        
        if($row = mysql_fetch_array($resultado)){
            echo "Bien";
            $hash=sha1($conN);//incriptamos la contraseña
            mysql_query("UPDATE usuarios SET clave='$hash' WHERE id='$cod'");
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