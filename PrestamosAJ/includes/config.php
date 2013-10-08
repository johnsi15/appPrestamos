<?php
   class conexion{
   	  public function conectar(){
   	  	 ///realizamos la conexion:
	      $con= mysql_connect("localhost","root","") or die ("Problemas en la conexion...");
		  ///selecionamos la base de tados:
		  mysql_select_db("prestamosaj") or die ("Problema en la selecion de la base de datos");
		  
		  return $con;
   	  }
   }
?>