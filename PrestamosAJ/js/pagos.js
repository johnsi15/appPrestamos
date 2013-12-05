$(document).ready(function(){
	$('#nuevoPago').dialog({//con esto cargamos los formulario de los gastos y de los cierre no es necesario repetir el codigo
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
         $('#nuevoPago').dialog('close');
      });

      //editar Registro
      $('body').on('click','#nuevo',function(e){
            e.preventDefault();
        	//abreimos el formulario
            $('#nuevoPago').dialog('open');
      });

      //editar Registro
      $('body').on('click','#pagar',function(e){
            e.preventDefault();
         //abreimos el formulario
         $('#id_registro').val($(this).attr('href'));
         $('#codigo').val($(this).parent().parent().children('td:eq(0)').text());
         $('#prestamo').val($(this).parent().parent().children('td:eq(0)').text());
         $('#nombre').val($(this).parent().parent().children('td:eq(1)').text());
            $('#nuevoPago').dialog('open');
      });

/*metodo para refrescar los prestamos del mes*/
   var peticion = $('#refrescarPrestamos form').attr('action');
   var metodo = $('#refrescarPrestamos form').attr('method');

    $('#refrescarPrestamos form').on('click','#clickPrestamo',function(e){
                e.preventDefault();

                $.ajax({
                  beforeSend: function(){

                  },
                  url: peticion,
                  type: metodo,
                  data: $('#refrescarPrestamos form').serialize(),
                  success: function(resp){
                        console.log(resp);
                        if(resp == "Error"){
                             setTimeout(function(){ $("#mensaje .alert").fadeOut(1000).fadeIn(1000).fadeOut(900).fadeIn(800).fadeOut(600);}, 800); 
                             var error = '<div class="alert alert-error">'+'<button type="button" class="close" data-dismiss="alert">'+'X'+'</button>'+'<strong>'+'Error'+'</strong>'+'<br> No se Pudo renovar el credito'+'</div>';
                             $('#mensajeError .alert').remove();
                             $('#mensajeError').html(error);
                        }else{
                              $('#verPagos').empty();//limpiamos la tabla 
                              $('#verPagos').html(resp);//mandamos los nuevos datos a la tabla
                              //$('#renovarPrestamo').dialog('close');
                              setTimeout(function(){ $("#mensaje .alert").fadeOut(1000).fadeIn(1000).fadeOut(900).fadeIn(800).fadeOut(600);}, 800); 
                              var exito = '<div class="alert alert-info">'+'<button type="button" class="close" data-dismiss="alert">'+'X'+'</button>'+'<strong>'+'Refrescado '+'</strong>'+'Los prestamos se pueden ver'+'</div>';
                              $('#mensaje').html(exito);
                              //$('#clickPrestamo').attr('disabled',true);
                              //$('.limpiar')[0].reset();///limpiamos los campos del formulario.
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