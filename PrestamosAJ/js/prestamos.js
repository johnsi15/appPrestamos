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

});