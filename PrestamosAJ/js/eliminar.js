$(document).ready(function(){
	/*_______________________________________________________________________*/

  // codigo para limpiar la base de datos
   $('#deleteBase').dialog({
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
         $('body').on('click','#limpiar',function(e){
               e.preventDefault();
               //alert($(this).attr('href'));
              
               $('#id_delete').val($(this).attr('href'));

               //abreimos el formulario
               $('#deleteBase').dialog('open');
               
               //selecccion de un combo box
               //$('#algo option[value='+$(this).parent().parent().children('td:eq(3)').text()+']').attr('selected',true);
         });

         $('body').on('click','#cancelar',function(e){
               e.preventDefault();
               $('#deleteBase').dialog('close');
               $('#buscar').focus();
         });


      var pet = $('#deleteBase form').attr('action');
      var met = $('#deleteBase form').attr('method');

     $('#deleteBase form').submit(function(e){
       //console.log("poraca PAsooooo");
               e.preventDefault();
               $.ajax({
                   beforeSend: function(){

                   },
                   url: pet,
                   type: met,
                   data: $('#deleteBase form').serialize(),
                   success: function(resp){
                        console.log(resp);
                       if(resp == "Error"){
                             setTimeout(function(){ $("#mensaje .alert").fadeOut(1000).fadeIn(900).fadeOut(800).fadeIn(500).fadeOut(300);}, 800); 
                             var error = '<div class="alert alert-warning">'+'<button type="button" class="close" data-dismiss="alert">'+'X'+'</button>'+'<strong>'+'No se Pudo limpiar'+'</strong>'+' Intente nuevamente'+'</div>';
                             $('#mensaje .alert').remove();
                             $('#mensaje').html(error);
                       }else{
                             $('#deleteBase').dialog('close');
                             setTimeout(function(){ $("#mensaje .alert").fadeOut(1000).fadeIn(900).fadeOut(800).fadeIn(500).fadeOut(300);}, 1000); 
                             var exito = '<div class="alert alert-success">'+'<button type="button" class="close" data-dismiss="alert">'+'X'+'</button>'+'<strong>'+'Base de Datos '+'</strong>'+' Limpia'+'</div>';
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

});//cierre del document....