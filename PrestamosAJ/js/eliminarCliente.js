$(document).ready(function(){
  /*ELIMINAR REGISTROS DE LA TABLA CONCEPTO INTERNET*/
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

         $('body').on('click','#cancelar',function(e){
               e.preventDefault();
               $('#deleteReg').dialog('close');
               $('#buscar').focus();
         });


      var pet = $('#deleteReg form').attr('action');
      var met = $('#deleteReg form').attr('method');

     $('#deleteReg form').submit(function(e){
       //console.log("poraca PAsooooo");
               e.preventDefault();
               $.ajax({
                   beforeSend: function(){

                   },
                   url: pet,
                   type: met,
                   data: $('#deleteReg form').serialize(),
                   success: function(resp){
                        console.log(resp);
                       if(resp == "Error"){
                             setTimeout(function(){ $("#mensaje .alert").fadeOut(1000).fadeIn(900).fadeOut(800).fadeIn(500).fadeOut(300);}, 800); 
                             var error = '<div class="alert alert-warning">'+'<button type="button" class="close" data-dismiss="alert">'+'X'+'</button>'+'<strong>'+'No se Pudo Eliminar el registro'+'</strong>'+' Intente nuevamente'+'</div>';
                             $('#mensaje .alert').remove();
                             $('#mensaje').html(error);
                       }else{
                             /*____________________________*/
                             $('#verDatos').empty();
                             $('#verDatos').html(resp);
                             $('#deleteReg').dialog('close');
                             setTimeout(function(){ $("#mensaje .alert").fadeOut(1000).fadeIn(900).fadeOut(800).fadeIn(500).fadeOut(300);}, 1000); 
                             var exito = '<div class="alert alert-success">'+'<button type="button" class="close" data-dismiss="alert">'+'X'+'</button>'+'<strong>'+'Registro Eliminado '+'</strong>'+' el registro se Elimino correctamente'+'</div>';
                             $('#mensaje .alert').remove();
                             $('#mensaje').html(exito);
                       }
                   },
                   error: function(jqXHR,estado,error){
                       console.log(estado);
                       console.log(error);
                   },
                   complete: function(jqXHR,estado){
                       console.log(estado);
                   },
                   timeout: 10000//10 segundos.
               });
     }); 
});