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

    public function registrarEstudiante($codigo,$nom,$edad,$peso,$altura,$fechaI,$fechaV,$pago,$con){/*condicion el 0-> no pago el 1-> debe el 2-> abono*/
           $resultado = mysql_query("INSERT INTO estudiantes (codigo,nombre,edad,peso,altura,fechaInicial,fechaFinal,dinero,condicion)
                                      VALUES ('$codigo','$nom','$edad','$peso','$altura','$fechaI','$fechaV','$pago','$con')")
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

    public function verEstudiantes(){
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
        $resultado = mysql_query("SELECT * FROM estudiantes ORDER BY condicion, fechaFinal DESC LIMIT $inicio,$cant_reg");

        while($fila = mysql_fetch_array($resultado)){
            $codigo = $fila['codigo'];
            $result = mysql_query("SELECT sum(dias) AS total FROM fechasclientes WHERE codigoEstudiante = '$codigo' ");
            $dias = mysql_fetch_array($result);

            if($fila['condicion'] == 'Pago'){
                 echo '<tr class="success"> 
                         <td>'.$fila['nombre'].'</td>
                         <td>'.$fila['fechaInicial'].'</td>
                         <td style="font-weight: bold;">'.$fila['fechaFinal'].'</td>
                         <td>'.$fila['dinero'].'</td>
                         <td>'.$fila['condicion'].'</td>
                         <td>'.$dias['total'].'</td>
                         <td><a disabled class="btn btn-mini btn-info"><strong disabled>Editar</strong></a></td>
                         <td><a id="delete" class="btn btn-mini btn-danger" href="'.$fila['codigo'].'"><strong>Eliminar</strong></a></td>
                     </tr>';
            }else{
                if($fila['condicion'] == 'No Pago'){
                    echo '<tr class="error"> 
                         <td>'.$fila['nombre'].'</td>
                         <td>'.$fila['fechaInicial'].'</td>
                         <td style="font-weight: bold;">'.$fila['fechaFinal'].'</td>
                         <td>'.$fila['dinero'].'</td>
                         <td>'.$fila['condicion'].'</td>
                         <td>'.$dias['total'].'</td>
                         <td><a id="editPago" class="btn btn-mini btn-info" href="'.$fila['codigo'].'"><strong>Editar</strong></a></td>
                         <td><a id="delete" class="btn btn-mini btn-danger" href="'.$fila['codigo'].'"><strong>Eliminar</strong></a></td>
                     </tr>';
                }else{
                    if($fila['condicion'] == 'Abono'){
                        echo '<tr class="warning"> 
                                 <td>'.$fila['nombre'].'</td>
                                 <td>'.$fila['fechaInicial'].'</td>
                                 <td style="font-weight: bold;">'.$fila['fechaFinal'].'</td>
                                 <td>'.$fila['dinero'].'</td>
                                 <td>'.$fila['condicion'].'</td>
                                 <td>'.$dias['total'].'</td>
                                 <td><a id="editPago" class="btn btn-mini btn-info" href="'.$fila['codigo'].'"><strong>Editar</strong></a></td>
                                 <td><a id="delete" class="btn btn-mini btn-danger" href="'.$fila['codigo'].'"><strong>Eliminar</strong></a></td>
                             </tr>';
                    }
                }
            }
                          // echo $salida;
        }      
    }/*cierre del metodo*/

    public function verVensimientos(){
        /*hacer paginacion*/
        $cant_reg = 20;//definimos la cantidad de datos que deseamos tenes por pagina.

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
        date_default_timezone_set('America/Bogota'); 
        $fecha = date("Y-m-d");
        $fechaD = date("d");
        $resultado = mysql_query("SELECT * FROM estudiantes WHERE condicion= 'No Pago' OR condicion= 'Abono' ORDER BY fechaFinal ASC LIMIT $inicio,$cant_reg");   
        
        while($fila = mysql_fetch_array($resultado)){
            $dia = substr($fila['fechaFinal'],8,10); 
            $dia = $dia-3;
            if($fecha == $fila['fechaFinal']){
                if($fila['condicion'] == 'No Pago'){
                        echo '<tr class="error"> 
                             <td>'.$fila['nombre'].'</td>
                             <td>'.$fila['fechaInicial'].'</td>
                             <td style="font-weight: bold; font-size: 22px;">'.$fila['fechaFinal'].'</td>
                             <td>'.$fila['dinero'].'</td>
                             <td>'.$fila['condicion'].'</td>
                             <td><a id="editPagoVen" class="btn btn-mini btn-inverse" href="'.$fila['codigo'].'"><strong>Editar</strong></a></td>
                         </tr>';
                }else{
                    if($fila['condicion'] == 'Abono'){
                            echo '<tr class="warning"> 
                                     <td>'.$fila['nombre'].'</td>
                                     <td>'.$fila['fechaInicial'].'</td>
                                     <td style="font-weight: bold; font-size: 22px;">'.$fila['fechaFinal'].'</td>
                                     <td>'.$fila['dinero'].'</td>
                                     <td>'.$fila['condicion'].'</td>
                                     <td><a id="editPagoVen" class="btn btn-mini btn-inverse" href="'.$fila['codigo'].'"><strong>Editar</strong></a></td>
                                 </tr>';
                    }
                }
            }else{
                if($fechaD == $dia){
                    if($fila['condicion'] == 'No Pago'){
                        echo '<tr class="error"> 
                             <td>'.$fila['nombre'].'</td>
                             <td>'.$fila['fechaInicial'].'</td>
                             <td style="font-weight: bold; font-size: 22px;">'.$fila['fechaFinal'].'</td>
                             <td>'.$fila['dinero'].'</td>
                             <td>'.$fila['condicion'].'</td>
                             <td><a id="editPagoVen" class="btn btn-mini btn-inverse" href="'.$fila['codigo'].'"><strong>Editar</strong></a></td>
                         </tr>';
                    }else{
                        if($fila['condicion'] == 'Abono'){
                                echo '<tr class="warning"> 
                                         <td>'.$fila['nombre'].'</td>
                                         <td>'.$fila['fechaInicial'].'</td>
                                         <td style="font-weight: bold; font-size: 22px;">'.$fila['fechaFinal'].'</td>
                                         <td>'.$fila['dinero'].'</td>
                                         <td>'.$fila['condicion'].'</td>
                                         <td><a id="editPagoVen" class="btn btn-mini btn-inverse" href="'.$fila['codigo'].'"><strong>Editar</strong></a></td>
                                     </tr>';
                        }
                    }
                }else{
                    if($fila['condicion'] == 'No Pago'){
                        echo '<tr class="error"> 
                             <td>'.$fila['nombre'].'</td>
                             <td>'.$fila['fechaInicial'].'</td>
                             <td style="font-weight: bold;">'.$fila['fechaFinal'].'</td>
                             <td>'.$fila['dinero'].'</td>
                             <td>'.$fila['condicion'].'</td>
                             <td><a id="editPagoVen" class="btn btn-mini btn-inverse" href="'.$fila['codigo'].'"><strong>Editar</strong></a></td>
                         </tr>';
                    }else{
                        if($fila['condicion'] == 'Abono'){
                                echo '<tr class="warning"> 
                                         <td>'.$fila['nombre'].'</td>
                                         <td>'.$fila['fechaInicial'].'</td>
                                         <td style="font-weight: bold;">'.$fila['fechaFinal'].'</td>
                                         <td>'.$fila['dinero'].'</td>
                                         <td>'.$fila['condicion'].'</td>
                                         <td><a id="editPagoVen" class="btn btn-mini btn-inverse" href="'.$fila['codigo'].'"><strong>Editar</strong></a></td>
                                     </tr>';
                        }
                    }
                }
            }
        }/*cierre del while*/
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

    /*buscador en tiempo real para los clientes que deben pagar */
    public function buscarVencimientos($palabra){/*algo anda mal revisar*/
        if($palabra == ''){
            $cant_reg = 20;//definimos la cantidad de datos que deseamos tenes por pagina.

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

            $result = mysql_query("SELECT * FROM estudiantes WHERE condicion= 'No Pago' OR condicion= 'Abono' ORDER BY fechaFinal ASC");///hacemos una consulta de todos los datos de cinternet
           
            $total_registros=mysql_num_rows($result);//obtenesmos el numero de datos que nos devuelve la consulta

            $total_paginas = ceil($total_registros/$cant_reg);

            echo '<div class="pagination" style="display: none;">
                    ';
            if(($num_pag+1)<=$total_paginas){//preguntamos si el numero de la pagina es menor o = al total de paginas para que aparesca el siguiente
                
                echo "<ul><li class='next'> <a href='pagoTiempo.php?pagina=".($num_pag+1)."'> Next </a></li></ul>";
            } ;echo '
                   </div>';
            date_default_timezone_set('America/Bogota'); 
            $fecha = date("Y-m-d");
            $fechaD = date("d");

            $resultado = mysql_query("SELECT * FROM estudiantes WHERE condicion= 'No Pago' OR condicion= 'Abono' ORDER BY fechaFinal ASC LIMIT $inicio,$cant_reg");//obtenemos los datos ordenados limitado con la variable inicio hasta la variable cant_reg
           while($fila = mysql_fetch_array($resultado)){
                $dia = substr($fila['fechaFinal'],8,10); 
                $dia = $dia-3;
                if($fecha == $fila['fechaFinal']){
                    if($fila['condicion'] == 'No Pago'){
                            echo '<tr class="error"> 
                                 <td>'.$fila['nombre'].'</td>
                                 <td>'.$fila['fechaInicial'].'</td>
                                 <td style="font-weight: bold; font-size: 22px;">'.$fila['fechaFinal'].'</td>
                                 <td>'.$fila['dinero'].'</td>
                                 <td>'.$fila['condicion'].'</td>
                                 <td><a id="editPagoVen" class="btn btn-mini btn-inverse" href="'.$fila['codigo'].'"><strong>Editar</strong></a></td>
                             </tr>';
                    }else{
                        if($fila['condicion'] == 'Abono'){
                                echo '<tr class="warning"> 
                                         <td>'.$fila['nombre'].'</td>
                                         <td>'.$fila['fechaInicial'].'</td>
                                         <td style="font-weight: bold; font-size: 22px;">'.$fila['fechaFinal'].'</td>
                                         <td>'.$fila['dinero'].'</td>
                                         <td>'.$fila['condicion'].'</td>
                                         <td><a id="editPagoVen" class="btn btn-mini btn-inverse" href="'.$fila['codigo'].'"><strong>Editar</strong></a></td>
                                     </tr>';
                        }
                    }
                }else{
                    if($fechaD == $dia){
                        if($fila['condicion'] == 'No Pago'){
                            echo '<tr class="error"> 
                                 <td>'.$fila['nombre'].'</td>
                                 <td>'.$fila['fechaInicial'].'</td>
                                 <td style="font-weight: bold; font-size: 22px;">'.$fila['fechaFinal'].'</td>
                                 <td>'.$fila['dinero'].'</td>
                                 <td>'.$fila['condicion'].'</td>
                                 <td><a id="editPagoVen" class="btn btn-mini btn-inverse" href="'.$fila['codigo'].'"><strong>Editar</strong></a></td>
                             </tr>';
                        }else{
                            if($fila['condicion'] == 'Abono'){
                                    echo '<tr class="warning"> 
                                             <td>'.$fila['nombre'].'</td>
                                             <td>'.$fila['fechaInicial'].'</td>
                                             <td style="font-weight: bold; font-size: 22px;">'.$fila['fechaFinal'].'</td>
                                             <td>'.$fila['dinero'].'</td>
                                             <td>'.$fila['condicion'].'</td>
                                             <td><a id="editPagoVen" class="btn btn-mini btn-inverse" href="'.$fila['codigo'].'"><strong>Editar</strong></a></td>
                                         </tr>';
                            }
                        }
                    }else{
                        if($fila['condicion'] == 'No Pago'){
                            echo '<tr class="error"> 
                                 <td>'.$fila['nombre'].'</td>
                                 <td>'.$fila['fechaInicial'].'</td>
                                 <td style="font-weight: bold;">'.$fila['fechaFinal'].'</td>
                                 <td>'.$fila['dinero'].'</td>
                                 <td>'.$fila['condicion'].'</td>
                                 <td><a id="editPagoVen" class="btn btn-mini btn-inverse" href="'.$fila['codigo'].'"><strong>Editar</strong></a></td>
                             </tr>';
                        }else{
                            if($fila['condicion'] == 'Abono'){
                                    echo '<tr class="warning"> 
                                             <td>'.$fila['nombre'].'</td>
                                             <td>'.$fila['fechaInicial'].'</td>
                                             <td style="font-weight: bold;">'.$fila['fechaFinal'].'</td>
                                             <td>'.$fila['dinero'].'</td>
                                             <td>'.$fila['condicion'].'</td>
                                             <td><a id="editPagoVen" class="btn btn-mini btn-inverse" href="'.$fila['codigo'].'"><strong>Editar</strong></a></td>
                                         </tr>';
                            }
                        }
                    }
                }
            }/*cierre del while*/
        }else{
            date_default_timezone_set('America/Bogota'); 
            $fecha = date("Y-m-d");
            $fechaD = date("d");
            $resultado = mysql_query("SELECT * FROM estudiantes WHERE nombre LIKE'%$palabra%' AND condicion= 'No Pago' OR condicion= 'Abono'");
            //echo json_encode($resultado);
            while($fila = mysql_fetch_array($resultado)){
                $dia = substr($fila['fechaFinal'],8,10); 
                $dia = $dia-3;
                if($fecha == $fila['fechaFinal']){
                    if($fila['condicion'] == 'No Pago'){
                            echo '<tr class="error"> 
                                 <td>'.$fila['nombre'].'</td>
                                 <td>'.$fila['fechaInicial'].'</td>
                                 <td style="font-weight: bold; font-size: 22px;">'.$fila['fechaFinal'].'</td>
                                 <td>'.$fila['dinero'].'</td>
                                 <td>'.$fila['condicion'].'</td>
                                 <td><a id="editPagoVen" class="btn btn-mini btn-inverse" href="'.$fila['codigo'].'"><strong>Editar</strong></a></td>
                             </tr>';
                    }else{
                        if($fila['condicion'] == 'Abono'){
                                echo '<tr class="warning"> 
                                         <td>'.$fila['nombre'].'</td>
                                         <td>'.$fila['fechaInicial'].'</td>
                                         <td style="font-weight: bold; font-size: 22px;">'.$fila['fechaFinal'].'</td>
                                         <td>'.$fila['dinero'].'</td>
                                         <td>'.$fila['condicion'].'</td>
                                         <td><a id="editPagoVen" class="btn btn-mini btn-inverse" href="'.$fila['codigo'].'"><strong>Editar</strong></a></td>
                                     </tr>';
                        }
                    }
                }else{
                    if($fechaD == $dia){
                        if($fila['condicion'] == 'No Pago'){
                            echo '<tr class="error"> 
                                 <td>'.$fila['nombre'].'</td>
                                 <td>'.$fila['fechaInicial'].'</td>
                                 <td style="font-weight: bold; font-size: 22px;">'.$fila['fechaFinal'].'</td>
                                 <td>'.$fila['dinero'].'</td>
                                 <td>'.$fila['condicion'].'</td>
                                 <td><a id="editPagoVen" class="btn btn-mini btn-inverse" href="'.$fila['codigo'].'"><strong>Editar</strong></a></td>
                             </tr>';
                        }else{
                            if($fila['condicion'] == 'Abono'){
                                    echo '<tr class="warning"> 
                                             <td>'.$fila['nombre'].'</td>
                                             <td>'.$fila['fechaInicial'].'</td>
                                             <td style="font-weight: bold; font-size: 22px;">'.$fila['fechaFinal'].'</td>
                                             <td>'.$fila['dinero'].'</td>
                                             <td>'.$fila['condicion'].'</td>
                                             <td><a id="editPagoVen" class="btn btn-mini btn-inverse" href="'.$fila['codigo'].'"><strong>Editar</strong></a></td>
                                         </tr>';
                            }
                        }
                    }else{
                        if($fila['condicion'] == 'No Pago'){
                            echo '<tr class="error"> 
                                 <td>'.$fila['nombre'].'</td>
                                 <td>'.$fila['fechaInicial'].'</td>
                                 <td style="font-weight: bold;">'.$fila['fechaFinal'].'</td>
                                 <td>'.$fila['dinero'].'</td>
                                 <td>'.$fila['condicion'].'</td>
                                 <td><a id="editPagoVen" class="btn btn-mini btn-inverse" href="'.$fila['codigo'].'"><strong>Editar</strong></a></td>
                             </tr>';
                        }else{
                            if($fila['condicion'] == 'Abono'){
                                    echo '<tr class="warning"> 
                                             <td>'.$fila['nombre'].'</td>
                                             <td>'.$fila['fechaInicial'].'</td>
                                             <td style="font-weight: bold;">'.$fila['fechaFinal'].'</td>
                                             <td>'.$fila['dinero'].'</td>
                                             <td>'.$fila['condicion'].'</td>
                                             <td><a id="editPagoVen" class="btn btn-mini btn-inverse" href="'.$fila['codigo'].'"><strong>Editar</strong></a></td>
                                         </tr>';
                            }
                        }
                    }
                }
            }/*cierre del while*/
        }
    }
    /*paginacion de los vencimientos*/
    public function paginacionVensimientos(){
         $cant_reg = 20;//definimos la cantidad de datos que deseamos tenes por pagina.

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

            $result = mysql_query("SELECT * FROM estudiantes WHERE condicion= 'No Pago' OR condicion= 'Abono'");///hacemos una consulta de todos los datos de cinternet
           
            $total_registros=mysql_num_rows($result);//obtenesmos el numero de datos que nos devuelve la consulta

            $total_paginas = ceil($total_registros/$cant_reg);

            echo '<div class="pagination" style="display: none;">
                    ';
            if(($num_pag+1)<=$total_paginas){//preguntamos si el numero de la pagina es menor o = al total de paginas para que aparesca el siguiente
                
                echo "<ul><li class='next'> <a href='pagoTiempo.php?pagina=".($num_pag+1)."'> Next </a></li></ul>";
            } ;echo '
                   </div>';
    }

    public function verTodosEstudiantes(){
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
        $resultado = mysql_query("SELECT * FROM estudiantes LIMIT $inicio,$cant_reg");
        while($fila = mysql_fetch_array($resultado)){
              echo '<tr> 
                        <td>'.$fila['nombre'].'</td>
                        <td>'.$fila['edad'].'</td>
                        <td>'.$fila['peso'].'</td>
                        <td>'.$fila['altura'].'</td>
                        <td><a id="editEstudiante" class="btn btn-mini btn-info" href="'.$fila['codigo'].'"><strong>Editar</strong></a></td>
                        <td><a id="delete" class="btn btn-mini btn-danger" href="'.$fila['codigo'].'"><strong>Eliminar</strong></a></td>
                    </tr>';
        }
    }

    public function modificarPago($pago,$con,$cod){
        mysql_query("UPDATE estudiantes SET dinero='$pago', condicion='$con' WHERE codigo='$cod'") 
                                    or die ("Error en el update");
    }
    /*modificamos el pago en la tabla de fechas para poder llevar control del tiempo y dinero que lleva cada persona*/
    public function modificarPagoFechas($pago,$con,$cod){
         mysql_query("UPDATE fechasclientes SET dinero='$pago', condicion='$con' WHERE codigoEstudiante='$cod'") 
                                    or die ("Error en el update");
    }

     /*metodos para ELIMINAR estudiantes del gim*/
    public function eliminarEstudiante($cod){
        mysql_query("DELETE FROM estudiantes WHERE codigo='$cod'");
        mysql_query("DELETE FROM fechasclientes WHERE codigoEstudiante='$cod'");
    }

   /*aca comienzo con la partde de actulizar datos de los estudiantes que van al gim*/
    public function actualizarDatosPersonales($cod,$nom,$edad,$peso,$altura){
        mysql_query("UPDATE estudiantes SET nombre='$nom', edad='$edad', peso='$peso', altura='$altura' WHERE codigo='$cod'") 
                                    or die ("Error en el update");
    }


     //BUSCADOR EN TIEMPO REAL POR  DE CONCEPTO......
    public function buscarEstudiante($palabra){
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

            $result = mysql_query("SELECT * FROM estudiantes");///hacemos una consulta de todos los datos de cinternet
           
            $total_registros=mysql_num_rows($result);//obtenesmos el numero de datos que nos devuelve la consulta

            $total_paginas = ceil($total_registros/$cant_reg);

            echo '<div class="pagination" style="display: none;">
                    ';
            if(($num_pag+1)<=$total_paginas){//preguntamos si el numero de la pagina es menor o = al total de paginas para que aparesca el siguiente
                
                echo "<ul><li class='next'> <a href='reporteConcepto.php?pagina=".($num_pag+1)."'> Next </a></li></ul>";
            } ;echo '
                   </div>';

            $resultado = mysql_query("SELECT * FROM estudiantes LIMIT $inicio,$cant_reg");//obtenemos los datos ordenados limitado con la variable inicio hasta la variable cant_reg
            while($fila = mysql_fetch_array($resultado)){
                   $salida = '<tr> 
                        <td>'.$fila['nombre'].'</td>
                        <td>'.$fila['edad'].'</td>
                        <td>'.$fila['peso'].'</td>
                        <td>'.$fila['altura'].'</td>
                        <td><a id="editEstudiante" class="btn btn-mini btn-info" href="'.$fila['codigo'].'"><strong>Editar</strong></a></td>
                        <td><a id="delete" class="btn btn-mini btn-danger" href="'.$fila['codigo'].'"><strong>Eliminar</strong></a></td>
                    </tr>';
                              // echo $salida;
                    echo $salida;
            } 
        }else{
             $resultado = mysql_query("SELECT * FROM estudiantes WHERE nombre LIKE'%$palabra%'");
            //echo json_encode($resultado);
            while($fila = mysql_fetch_array($resultado)){
                   $salida = '<tr> 
                        <td>'.$fila['nombre'].'</td>
                        <td>'.$fila['edad'].'</td>
                        <td>'.$fila['peso'].'</td>
                        <td>'.$fila['altura'].'</td>
                        <td><a id="editEstudiante" class="btn btn-mini btn-info" href="'.$fila['codigo'].'"><strong>Editar</strong></a></td>
                        <td><a id="delete" class="btn btn-mini btn-danger" href="'.$fila['codigo'].'"><strong>Eliminar</strong></a></td>
                    </tr>';
                              // echo $salida;
                    echo $salida;
            }  
        }
    }

    /*paginacion de los datos personales..*/
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

            $result = mysql_query("SELECT * FROM estudiantes");///hacemos una consulta de todos los datos de cinternet
           
            $total_registros=mysql_num_rows($result);//obtenesmos el numero de datos que nos devuelve la consulta

            $total_paginas = ceil($total_registros/$cant_reg);

            echo '<div class="pagination" style="display: none;">
                    ';
            if(($num_pag+1)<=$total_paginas){//preguntamos si el numero de la pagina es menor o = al total de paginas para que aparesca el siguiente
                
                echo "<ul><li class='next'> <a href='actualizarDatos.php?pagina=".($num_pag+1)."'> Next </a></li></ul>";
            } ;echo '
                   </div>';
    }

/*codigo para actulizar el tiempo del estudiante en el gim...... VA VERDATOS, PAGINACION DE LOS DATOS*/
    public function verActualizarTiempo(){
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
        $resultado = mysql_query("SELECT * FROM estudiantes WHERE condicion='Pago' ORDER BY fechaFinal DESC LIMIT $inicio,$cant_reg");

        while($fila = mysql_fetch_array($resultado)){
                 echo '<tr class="success"> 
                         <td>'.$fila['nombre'].'</td>
                         <td>'.$fila['fechaInicial'].'</td>
                         <td>'.$fila['fechaFinal'].'</td>
                         <td>'.$fila['dinero'].'</td>
                         <td>'.$fila['condicion'].'</td>
                         <td><a id="tiempoEstudiante" class="btn btn-mini btn-info" href="'.$fila['codigo'].'"><strong>Editar</strong></a></td>
                         <td><a id="delete" class="btn btn-mini btn-danger" href="'.$fila['codigo'].'"><strong>Eliminar</strong></a></td>
                     </tr>';
                          // echo $salida;
        }      
    }

    public function paginacionActulizarTiempo(){
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

            $result = mysql_query("SELECT * FROM estudiantes WHERE condicion='Pago'");///hacemos una consulta de todos los datos de cinternet
           
            $total_registros=mysql_num_rows($result);//obtenesmos el numero de datos que nos devuelve la consulta

            $total_paginas = ceil($total_registros/$cant_reg);

            echo '<div class="pagination" style="display: none;">
                    ';
            if(($num_pag+1)<=$total_paginas){//preguntamos si el numero de la pagina es menor o = al total de paginas para que aparesca el siguiente
                
                echo "<ul><li class='next'> <a href='actualizarTiempo.php?pagina=".($num_pag+1)."'> Next </a></li></ul>";
            } ;echo '
                   </div>';
    }

    /*buscador en tiempo real para actulizar el tiempo de uso de los estudiantes que ya pagaron*/
    public function buscarEstudiantePago($palabra){
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

            $result = mysql_query("SELECT * FROM estudiantes WHERE condicion='Pago'");///hacemos una consulta de todos los datos de cinternet
           
            $total_registros=mysql_num_rows($result);//obtenesmos el numero de datos que nos devuelve la consulta

            $total_paginas = ceil($total_registros/$cant_reg);

            echo '<div class="pagination" style="display: none;">
                    ';
            if(($num_pag+1)<=$total_paginas){//preguntamos si el numero de la pagina es menor o = al total de paginas para que aparesca el siguiente
                
                echo "<ul><li class='next'> <a href='actualizarTiempo.php?pagina=".($num_pag+1)."'> Next </a></li></ul>";
            } ;echo '
                   </div>';

            $resultado = mysql_query("SELECT * FROM estudiantes WHERE condicion='Pago' LIMIT $inicio,$cant_reg");//obtenemos los datos ordenados limitado con la variable inicio hasta la variable cant_reg
            while($fila = mysql_fetch_array($resultado)){
                   $salida = '<tr class="success"> 
                        <td>'.$fila['nombre'].'</td>
                        <td>'.$fila['fechaInicial'].'</td>
                        <td>'.$fila['fechaFinal'].'</td>
                        <td>'.$fila['dinero'].'</td>
                        <td>'.$fila['condicion'].'</td>
                        <td><a id="tiempoEstudiante" class="btn btn-mini btn-info" href="'.$fila['codigo'].'"><strong>Editar</strong></a></td>
                        <td><a id="delete" class="btn btn-mini btn-danger" href="'.$fila['codigo'].'"><strong>Eliminar</strong></a></td>
                    </tr>';
                              // echo $salida;
                    echo $salida;
            } 
        }else{
             $resultado = mysql_query("SELECT * FROM estudiantes WHERE condicion='Pago' AND nombre LIKE'%$palabra%'");
            //echo json_encode($resultado);
            while($fila = mysql_fetch_array($resultado)){
                   $salida = '<tr class="success"> 
                        <td>'.$fila['nombre'].'</td>
                        <td>'.$fila['fechaInicial'].'</td>
                        <td>'.$fila['fechaFinal'].'</td>
                        <td>'.$fila['dinero'].'</td>
                        <td>'.$fila['condicion'].'</td>
                        <td><a id="tiempoEstudiante" class="btn btn-mini btn-info" href="'.$fila['codigo'].'"><strong>Editar</strong></a></td>
                        <td><a id="delete" class="btn btn-mini btn-danger" href="'.$fila['codigo'].'"><strong>Eliminar</strong></a></td>
                    </tr>';
                              // echo $salida;
                    echo $salida;
            }  
        }
    }

    /*buscador en tiempo real de los estudiantes o clientes en el menu principal */
    public function buscarEstudianteMenu($palabra){
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

            $result = mysql_query("SELECT * FROM estudiantes");///hacemos una consulta de todos los datos de cinternet
           
            $total_registros=mysql_num_rows($result);//obtenesmos el numero de datos que nos devuelve la consulta

            $total_paginas = ceil($total_registros/$cant_reg);

            echo '<div class="pagination" style="display: none;">
                    ';
            if(($num_pag+1)<=$total_paginas){//preguntamos si el numero de la pagina es menor o = al total de paginas para que aparesca el siguiente
                
                echo "<ul><li class='next'> <a href='menu.php?pagina=".($num_pag+1)."'> Next </a></li></ul>";
            } ;echo '
                   </div>';

            $resultado = mysql_query("SELECT * FROM estudiantes ORDER BY condicion, fechaFinal DESC LIMIT $inicio,$cant_reg");//obtenemos los datos ordenados limitado con la variable inicio hasta la variable cant_reg
            while($fila = mysql_fetch_array($resultado)){
                    if($fila['condicion'] == 'Pago'){
                             echo '<tr class="success"> 
                                     <td>'.$fila['nombre'].'</td>
                                     <td>'.$fila['fechaInicial'].'</td>
                                     <td style="font-weight: bold;">'.$fila['fechaFinal'].'</td>
                                     <td>'.$fila['dinero'].'</td>
                                     <td>'.$fila['condicion'].'</td>
                                     <td><a disabled class="btn btn-mini btn-info"><strong disabled>Editar</strong></a></td>
                                     <td><a id="delete" class="btn btn-mini btn-danger" href="'.$fila['codigo'].'"><strong>Eliminar</strong></a></td>
                                 </tr>';
                    }else{
                        if($fila['condicion'] == 'No Pago'){
                                echo '<tr class="error"> 
                                     <td>'.$fila['nombre'].'</td>
                                     <td>'.$fila['fechaInicial'].'</td>
                                     <td style="font-weight: bold;">'.$fila['fechaFinal'].'</td>
                                     <td>'.$fila['dinero'].'</td>
                                     <td>'.$fila['condicion'].'</td>
                                     <td><a id="editPago" class="btn btn-mini btn-info" href="'.$fila['codigo'].'"><strong>Editar</strong></a></td>
                                     <td><a id="delete" class="btn btn-mini btn-danger" href="'.$fila['codigo'].'"><strong>Eliminar</strong></a></td>
                                 </tr>';
                        }else{
                            if($fila['condicion'] == 'Abono'){
                                        echo '<tr class="warning"> 
                                                 <td>'.$fila['nombre'].'</td>
                                                 <td>'.$fila['fechaInicial'].'</td>
                                                 <td style="font-weight: bold;">'.$fila['fechaFinal'].'</td>
                                                 <td>'.$fila['dinero'].'</td>
                                                 <td>'.$fila['condicion'].'</td>
                                                 <td><a id="editPago" class="btn btn-mini btn-info" href="'.$fila['codigo'].'"><strong>Editar</strong></a></td>
                                                 <td><a id="delete" class="btn btn-mini btn-danger" href="'.$fila['codigo'].'"><strong>Eliminar</strong></a></td>
                                             </tr>';
                            }
                        }
                    }
                                      // echo $salida;
            }/*cierre del while*/
        }else{
             $resultado = mysql_query("SELECT * FROM estudiantes WHERE nombre LIKE'%$palabra%'");
            //echo json_encode($resultado);
           while($fila = mysql_fetch_array($resultado)){
                    if($fila['condicion'] == 'Pago'){
                         echo '<tr class="success"> 
                                 <td>'.$fila['nombre'].'</td>
                                 <td>'.$fila['fechaInicial'].'</td>
                                 <td style="font-weight: bold;">'.$fila['fechaFinal'].'</td>
                                 <td>'.$fila['dinero'].'</td>
                                 <td>'.$fila['condicion'].'</td>
                                 <td><a disabled class="btn btn-mini btn-info"><strong disabled>Editar</strong></a></td>
                                 <td><a id="delete" class="btn btn-mini btn-danger" href="'.$fila['codigo'].'"><strong>Eliminar</strong></a></td>
                             </tr>';
                    }else{
                        if($fila['condicion'] == 'No Pago'){
                            echo '<tr class="error"> 
                                 <td>'.$fila['nombre'].'</td>
                                 <td>'.$fila['fechaInicial'].'</td>
                                 <td style="font-weight: bold;">'.$fila['fechaFinal'].'</td>
                                 <td>'.$fila['dinero'].'</td>
                                 <td>'.$fila['condicion'].'</td>
                                 <td><a id="editPago" class="btn btn-mini btn-info" href="'.$fila['codigo'].'"><strong>Editar</strong></a></td>
                                 <td><a id="delete" class="btn btn-mini btn-danger" href="'.$fila['codigo'].'"><strong>Eliminar</strong></a></td>
                             </tr>';
                        }else{
                            if($fila['condicion'] == 'Abono'){
                                echo '<tr class="warning"> 
                                         <td>'.$fila['nombre'].'</td>
                                         <td>'.$fila['fechaInicial'].'</td>
                                         <td style="font-weight: bold;">'.$fila['fechaFinal'].'</td>
                                         <td>'.$fila['dinero'].'</td>
                                         <td>'.$fila['condicion'].'</td>
                                         <td><a id="editPago" class="btn btn-mini btn-info" href="'.$fila['codigo'].'"><strong>Editar</strong></a></td>
                                         <td><a id="delete" class="btn btn-mini btn-danger" href="'.$fila['codigo'].'"><strong>Eliminar</strong></a></td>
                                     </tr>';
                            }
                        }
                    }
                                  // echo $salida;
                }/*cierre del while*/
        }
    }

    /*paginacion de los clientes en el MENU principal */
    public function paginacionEstudianteMenu(){
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

            $result = mysql_query("SELECT * FROM estudiantes");///hacemos una consulta de todos los datos de cinternet
           
            $total_registros=mysql_num_rows($result);//obtenesmos el numero de datos que nos devuelve la consulta

            $total_paginas = ceil($total_registros/$cant_reg);

            echo '<div class="pagination" style="display: none;">
                    ';
            if(($num_pag+1)<=$total_paginas){//preguntamos si el numero de la pagina es menor o = al total de paginas para que aparesca el siguiente
                
                echo "<ul><li class='next'> <a href='menu.php?pagina=".($num_pag+1)."'> Next </a></li></ul>";
            } ;echo '
                   </div>';
    }

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