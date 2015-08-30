<?php
	/*codigo para combox de los clientes y los prestamos*/
	///realizamos la conexion:
  $con= mysql_connect("host","nombreHost","clave") or die ("Problemas en la conexion...");
  ///selecionamos la base de tados:
  mysql_select_db("nombreBaseD") or die ("Problema en la selecion de la base de datos");
  
  $resultado = mysql_query("SELECT * FROM prestamos WHERE cedula=".$_GET['id']."");
  echo "<select name='prestamo'>";
    while ($fila = mysql_fetch_array($resultado)) {
          echo "<option value='".$fila['codigo']."'>".$fila['codigo']."
                  </option>";
    }
  echo "</select>";
?>