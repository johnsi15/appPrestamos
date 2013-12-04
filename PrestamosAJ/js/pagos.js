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

});