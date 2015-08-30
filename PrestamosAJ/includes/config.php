<?php
   class conexion{
   	  public function conectar(){
   	  	 ///realizamos la conexion:
	      $con= mysql_connect("host","host_nombre","clave") or die ("Problemas en la conexion...");
		  ///selecionamos la base de tados:
		  mysql_select_db("nombre_baseD") or die ("Problema en la selecion de la base de datos");
		  
		  return $con;
   	  }
   }
?>