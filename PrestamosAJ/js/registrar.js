$(document).ready(function(){
	/*REGISTTRAMOS LAS NOTAS DEL MENU PRINCIPAL*/

	  var pet = $('.span4 form').attr('action');
    var met = $('.span4 form').attr('method');

     $('.span4 form').on('click','#nota',function(e){
     	         e.preventDefault();
               $.ajax({
                   beforeSend: function(){

                   },
                   url: pet,
                   type: met,
                   data: $('.span4 form').serialize(),
                   success: function(resp){
                   	   console.log(resp);
                       if(resp == "Error"){
                             setTimeout(function(){ $(".span4 .alert").fadeOut(800).fadeIn(800).fadeOut(500).fadeIn(500).fadeOut(300);}, 800); 
                             var error = '<div class="alert alert-error">'+'<button type="button" class="close" data-dismiss="alert">'+'X'+'</button>'+'<strong>'+'Nombre o Contraseña Incorrecta'+'</strong>'+'<br> Intente Nuevamente '+'</div>';
                             $('.span4 .alert').remove();
                             $('#mensaje').html(error);
                       }else{
	                        $('#notas').empty();//limpiar la tabla.
	                        $('#notas').html(resp);//imprimir datos de la tabla.
	                        $('#notas').focus();///indicamos el foco al primer valor del formulario.
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
/*_______________________________________________________________________*/

/*REGISTRAMOS LOS PRECIOS EN EL MENU PRINCIPAL CON JQUERY UI*/
 //funcionalidad de formulario con jQuery Ui
      $('#registrar').dialog({
      	autoOpen: false,
      	modal: true,
      	width:260,
      	height:'auto',
      	resizable: false,
      	close:function(){
      		$('#id_registro').val('0');
      	}
      });

      //editar Registro
      $('body').on('click','#registro',function(e){
      	    e.preventDefault();
      	    //abreimos el formulario
            $('#registrar').dialog('open');
      });

      //editar registro de la tabla ajax
      var pet = $('#registrar form').attr('action');
      var met = $('#registrar form').attr('method');     

      $('#registrar form').submit(function(e){
      	    e.preventDefault();
      	    $.ajax({
      	    	beforeSend: function(){

      	    	},
      	    	url: pet,
      	    	type: met,
      	    	data: $('#registrar form').serialize(),
      	    	success: function(resp){
      	    		console.log(resp);
      	    		if(resp == "Error"){

      	    		}else{
      	    			$('#result').empty();//limpiar los datos
      	    			$('#result').html(resp);
      	    			$('#registrar').dialog('close');
      	    			setTimeout(function(){ $(".span8 .alert").fadeOut(800).fadeIn(800).fadeOut(500).fadeIn(500).fadeOut(300);}, 800); 
                              var exito = '<div class="alert alert-success">'+'<button type="button" class="close" data-dismiss="alert">'+'X'+'</button>'+'<strong>'+'Registro Exitoso '+'</strong>'+' el registro se agrego correctamente'+'</div>';
      	    			$('.mensaje').html(exito);
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
   /*_______________________________________________________________________________*/

   //funcionalidad de formulario con jQuery Ui
      $('#editarRegistro').dialog({
            autoOpen: false,
            modal: true,
            width:280,
            height:'auto',
            resizable: false,
            close:function(){
                  $('#id_registro').val('0');
            }
      });

      //editar Registro
      $('body').on('click','#result #edit',function(e){
                e.preventDefault();
                // alert($(this).attr('href'));
                $('#id_registro').val($(this).attr('href'));
                //abreimos el formulario
            $('#editarRegistro').dialog('open');
            //estraemos los campos.
            $('#concepto').val($(this).parent().parent().children('td:eq(0)').text());
            $('#precio').val($(this).parent().parent().children('td:eq(1)').text());
      });

      //editar registro de la tabla ajax
      var pet = $('#editarRegistro form').attr('action');
      var met = $('#editarRegistro form').attr('method');

      $('#editarRegistro form').submit(function(e){
                e.preventDefault();
                $.ajax({
                  beforeSend: function(){

                  },
                  url: pet,
                  type: met,
                  data: $('#editarRegistro form').serialize(),
                  success: function(resp){
                        console.log(resp);
                        if(resp == "Error"){

                        }else{
                              $('#result').empty();//limpiar los datos
                              $('#result').html(resp);
                              $('#editarRegistro').dialog('close');
                              setTimeout(function(){ $(".span8 .alert").fadeOut(800).fadeIn(800).fadeOut(500).fadeIn(500).fadeOut(300);}, 800); 
                              var exito = '<div class="alert alert-info">'+'<button type="button" class="close" data-dismiss="alert">'+'X'+'</button>'+'<strong>'+'Registro modificado '+'</strong>'+' el registro se modifico correctamente'+'</div>';
                              $('.mensaje').html(exito);
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
/*______________________________________________________________*/

/*REGISTRAR RECARGAS...........*/
      var pet = $('.span3 form').attr('action');
      var met = $('.span3 form').attr('method');

     $('.span3 form').on('click','#botonRecarga',function(e){
               e.preventDefault();
               $.ajax({
                   beforeSend: function(){

                   },
                   url: pet,
                   type: met,
                   data: $('.span3 form').serialize(),
                   success: function(resp){
                       console.log(resp);
                       if(resp == "Error"){
                             setTimeout(function(){ $("#mensaje .alert").fadeOut(800).fadeIn(800).fadeOut(500).fadeIn(500).fadeOut(300);}, 800); 
                             var error = '<div class="alert alert-error">'+'<button type="button" class="close" data-dismiss="alert">'+'X'+'</button>'+'<strong>'+'Error'+'</strong>'+'<br> No se Pudo registrar '+'</div>';
                             $('.span3 .alert').remove();
                             $('#mensaje').html(error);
                       }else{
                          $('#resul').empty();//limpiar la tabla.
                          $('#resul').html(resp);//imprimir datos de la tabla.
                          setTimeout(function(){ $("#mensaje .alert").fadeOut(800).fadeIn(800).fadeOut(500).fadeIn(500).fadeOut(300);}, 800); 
                          var exito = '<div class="alert alert-success">'+'<button type="button" class="close" data-dismiss="alert">'+'X'+'</button>'+'<strong>'+'Registro guardado '+'</strong>'+' el registro se agrego correctamente'+'</div>';
                          $('#mensaje').html(exito);//impresion del mensaje exitoso.
                         // $('.limpiar')[0].reset();///limpiamos los campos del formulario.
                          //$('#foco').focus();///indicamos el foco al primer valor del formulario. 
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
/*_____________________________________________________*/

});//cierre del document