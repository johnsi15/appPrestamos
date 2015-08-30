$(document).ready(function(){
	$('#nuevoPrestamo').dialog({//con esto cargamos los formulario de los gastos y de los cierre no es necesario repetir el codigo
            autoOpen: false,
            modal: true,
            width:280,
            height:'auto',
            resizable: false,
            close:function(){
                  $('#id_registro').val('0');
            }
      });
     
     /*cerrar ventana de modificar ventana de fechas vencimientos*/
      $('body').on('click','#cancelar',function(e){
         e.preventDefault();
         $('#nuevoPrestamo').dialog('close');
      });

      //editar Registro
      $('body').on('click','#nuevo',function(e){
            e.preventDefault();
        	//abreimos el formulario
            $('#nuevoPrestamo').dialog('open');
      });
      /*__________________________*/

      // renovar credito o prestamos de los clientes
      $('#renovarPrestamo').dialog({//con esto cargamos los formulario de los gastos y de los cierre no es necesario repetir el codigo
            autoOpen: false,
            modal: true,
            width:280,
            height:'auto',
            resizable: false,
            close:function(){
                  $('#id_registro').val('0');
            }
      });
     
     /*cerrar ventana de modificar ventana de fechas vencimientos*/
      $('body').on('click','#cancelar',function(e){
         e.preventDefault();
         $('#renovarPrestamo').dialog('close');
      });

      //editar Registro
      $('body').on('click','#renovar',function(e){
            e.preventDefault();
            $('#id_registro').val($(this).attr('href'));
            $('#nombre').val($(this).parent().parent().children('td:eq(1)').text());
         //abreimos el formulario
            $('#renovarPrestamo').dialog('open');
      });

   var peticion = $('#renovarPrestamo form').attr('action');
   var metodo = $('#renovarPrestamo form').attr('method');

    $('#renovarPrestamo form').on('click','#renovarPrest',function(e){
                e.preventDefault();

                $.ajax({
                  beforeSend: function(){

                  },
                  url: peticion,
                  type: metodo,
                  data: $('#renovarPrestamo form').serialize(),
                  success: function(resp){
                        console.log(resp);
                        if(resp == "Error"){
                             setTimeout(function(){ $("#mensaje .alert").fadeOut(1000).fadeIn(1000).fadeOut(900).fadeIn(800).fadeOut(600);}, 800); 
                             var error = '<div class="alert alert-error">'+'<button type="button" class="close" data-dismiss="alert">'+'X'+'</button>'+'<strong>'+'Error'+'</strong>'+'<br> No se Pudo renovar el credito'+'</div>';
                             $('#mensajeError .alert').remove();
                             $('#mensajeError').html(error);
                        }else{
                              $('#verRenovar').empty();//limpiamos la tabla 
                              $('#verRenovar').html(resp);//mandamos los nuevos datos a la tabla
                              $('#renovarPrestamo').dialog('close');
                              setTimeout(function(){ $("#mensaje .alert").fadeOut(1000).fadeIn(1000).fadeOut(900).fadeIn(800).fadeOut(600);}, 800); 
                              var exito = '<div class="alert alert-info">'+'<button type="button" class="close" data-dismiss="alert">'+'X'+'</button>'+'<strong>'+'Renovado '+'</strong>'+' El credito se renovo exitosamente'+'</div>';
                              $('#mensaje').html(exito);
                              $('.limpiar')[0].reset();///limpiamos los campos del formulario.
                             // $('#paginacion').empty();//limpiar los datos
                              //$('#paginacion').load('paginacion.php');
                        }
                  },
                  error: function(jqXHR,estado,error){
                        console.log(estado);
                        console.log(error);
                  },
                  complete: function(jqXHR,estado){
                        console.log(estado);
                  },
                  timeout: 10000 //10 segundos.
                });
    });


      $('#deleteReg').dialog({
            autoOpen: false,
            modal:true,
            width:380,
            height:'auto',
            resizable: false,
            close:function(){
               $('#id_delete').val('0');
            }
      });

      $('body').on('click','#cancelar',function(e){
         e.preventDefault();
         $('#deleteReg').dialog('close');
      });

      ///Edicion de Registros.
      $('body').on('click','#delete',function(e){
               e.preventDefault();
               //alert($(this).attr('href'));
              
               $('#id_delete').val($(this).attr('href'));

               //abreimos el formulario
               $('#deleteReg').dialog('open');
               
               //selecccion de un combo box
               //$('#algo option[value='+$(this).parent().parent().children('td:eq(3)').text()+']').attr('selected',true);
      });

   /*eliminar creditos con saldo en cero*/
    $('#deleteReg form').on('click','#eliminarPrest',function(e){
                e.preventDefault();

                $.ajax({
                  beforeSend: function(){

                  },
                  url: peticion,
                  type: metodo,
                  data: $('#deleteReg form').serialize(),
                  success: function(resp){
                        console.log(resp);
                        if(resp == "Error"){
                             setTimeout(function(){ $("#mensaje .alert").fadeOut(1000).fadeIn(1000).fadeOut(900).fadeIn(800).fadeOut(600);}, 800); 
                             var error = '<div class="alert alert-error">'+'<button type="button" class="close" data-dismiss="alert">'+'X'+'</button>'+'<strong>'+'Error'+'</strong>'+'<br> No se Pudo eliminar el credito'+'</div>';
                             $('#mensajeError .alert').remove();
                             $('#mensajeError').html(error);
                        }else{
                              $('#verRenovar').empty();//limpiamos la tabla 
                              $('#verRenovar').html(resp);//mandamos los nuevos datos a la tabla
                              $('#deleteReg').dialog('close');
                              setTimeout(function(){ $("#mensaje .alert").fadeOut(1000).fadeIn(1000).fadeOut(900).fadeIn(800).fadeOut(600);}, 800); 
                              var exito = '<div class="alert alert-info">'+'<button type="button" class="close" data-dismiss="alert">'+'X'+'</button>'+'<strong>'+'Eliminado '+'</strong>'+' El credito se elimino exitosamente'+'</div>';
                              $('#mensaje').html(exito);
                              $('.limpiar')[0].reset();///limpiamos los campos del formulario.
                             // $('#paginacion').empty();//limpiar los datos
                              //$('#paginacion').load('paginacion.php');
                        }
                  },
                  error: function(jqXHR,estado,error){
                        console.log(estado);
                        console.log(error);
                  },
                  complete: function(jqXHR,estado){
                        console.log(estado);
                  },
                  timeout: 10000 //10 segundos.
                });
    });

}); //fin del document