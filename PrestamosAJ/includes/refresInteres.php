<?php
	/*codigo para combox de los clientes y los prestamos*/
	///realizamos la conexion:
  $con= mysql_connect("localhost","root","") or die ("Problemas en la conexion...");
  ///selecionamos la base de tados:
  mysql_select_db("prestamosaj") or die ("Problema en la selecion de la base de datos");
  
  $resultado = mysql_query("SELECT interesTotal FROM caja");
  $fila = mysql_fetch_array($resultado);
  echo "<h2>".number_format($fila['interesTotal'])."</h2>";
?>