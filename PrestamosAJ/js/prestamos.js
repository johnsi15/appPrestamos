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

      var pet = $('#nuevoPrestamo form').attr('action');
      var met = $('#nuevoPrestamo form').attr('method');

      $('#nuevoPrestamo form').on('click','#registrarPrestamo',function(e){
            e.preventDefault();
          $.ajax({
              beforeSend: function(){

              },
              url: pet,
              type: met,
              data: $('#nuevoPrestamo form').serialize(),
              success: function(resp){
                console.log(resp);
                if(resp == "Error"){

                }else{
                  $('#verEstu').empty();//limpiamos la tabla
                  $('#verEstu').html(resp);
                  $('#nuevoPrestamo').dialog('close');
                  setTimeout(function(){ $("#mensaje .alert").fadeOut(1000).fadeIn(900).fadeOut(800).fadeIn(500).fadeOut(300);}, 800); 
                              var exito = '<div class="alert alert-info">'+'<button type="button" class="close" data-dismiss="alert">'+'X'+'</button>'+'<strong>'+'Modificado '+'</strong>'+' El prestamo se hizo correctamente'+'</div>';
                  $('#mensaje').html(exito);
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
});