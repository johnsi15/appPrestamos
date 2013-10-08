$(document).ready(function(){

  /*_____________________________________________*/
	$("#registrarEstudiante").validate({
		rules:{
      codigo:{
        required: true,
        number: true
      },
			nombre:{
				required: true
		    },
		  pago:{
				required: true,
				number: true
			},
      edad:{
        required: true,
        number: true,
        maxlength: 2,
        minlength: 2
      },
      peso:{
        required: true,
        number: true,
        maxlength: 2
      },
      altura:{
        required: true,
        number: true,
        maxlength: 3
      },
      fecha2:{
        required: true
      },
      condicion: {
        required: true
      }
		},
		submitHandler: function(form){
			///BUSCAMOS LOS CAMPOS EN TODAS LAS TABLAS-> cuando le den buscar
		    var pet = $('#registrarNew form').attr('action');
	      var met = $('#registrarNew form').attr('method');
        console.log(pet);
        console.log(met);
	         $.ajax({
                   beforeSend: function(){

                   },
                   url: pet,
                   type: met,
                   data: $('#registrarNew form').serialize(),
                   success: function(resp){
                   	   console.log(resp);
                       if(resp == "Error"){
                             setTimeout(function(){ $("#mensajeError .alert").fadeOut(1000).fadeIn(800).fadeOut(500).fadeIn(500).fadeOut(300);}, 1000); 
                             var error = '<div class="alert alert-error">'+'<button type="button" class="close" data-dismiss="alert">'+'X'+'</button>'+'<strong>'+'Error'+'</strong>'+'<br> No se Pudo registrar verifique el N° de identificacion'+'</div>';
                             $('#mensajeError .alert').remove();
                             $('#mensajeError').html(error);
                       }else{
                          $('#verEstu').empty();//limpiar la tabla.
	                        $('#verEstu').html(resp);//imprimir datos de la tabla.
	                        setTimeout(function(){ $("#mensaje .alert").fadeOut(800).fadeIn(800).fadeOut(500).fadeIn(500).fadeOut(300);}, 1000); 
	                        var exito = '<div class="alert alert-success">'+'<button type="button" class="close" data-dismiss="alert">'+'X'+'</button>'+'<strong>'+'Registro guardado '+'</strong>'+' el registro se agrego correctamente'+'</div>';
	                        $('#mensaje').html(exito);//impresion del mensaje exitoso.
	                        $('.limpiar')[0].reset();///limpiamos los campos del formulario.
                          $("#formMenu").removeClass('open');//cerramos el sub menu del registro
	                        $('#foco').focus();///indicamos el foco al primer valor del formulario. 
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
		}//cierre del submitHandler
	});

  /*__________________________________________________*/
	$("#validate3").validate({
		rules:{
			nombre:{
				required: true,
				number: true
			},
			dinero:{
				required: true,
				number: true
			}
		},
		submitHandler: function(form){
			///BUSCAMOS LOS CAMPOS EN TODAS LAS TABLAS-> cuando le den buscar
		    var pet = $('.span3 form').attr('action');
	        var met = $('.span3 form').attr('method');
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
	                        $('.limpiar')[0].reset();///limpiamos los campos del formulario.
	                        $('#foco').focus();///indicamos el foco al primer valor del formulario. 
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
        }///cierre del submitHandler...
	});

    /*_____________________________________________*/
    $("#cierre").validate({
    	rules:{
    		dinero:{
    			required: true,
    			number: true
    		}
    	},
    	submitHandler: function(form){
			///BUSCAMOS LOS CAMPOS EN TODAS LAS TABLAS-> cuando le den buscar
		    var pet = $('.span3 form').attr('action');
	      var met = $('.span3 form').attr('method');
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
                             var error = '<div class="alert alert-error">'+'<button type="button" class="close" data-dismiss="alert">'+'X'+'</button>'+'<strong>'+'Error'+'</strong>'+' Ese Cierre Ya se hizo '+'</div>';
                             $('.span6 .alert').remove();
                             $('#mensaje').html(error);
                       }else{
                         	$('#resul').empty();//limpiar la tabla.
	                        $('#resul').html(resp);//imprimir datos de la tabla.
	                        setTimeout(function(){ $("#mensaje .alert").fadeOut(800).fadeIn(800).fadeOut(500).fadeIn(500).fadeOut(300);}, 800); 
	                        var exito = '<div class="alert alert-success">'+'<button type="button" class="close" data-dismiss="alert">'+'X'+'</button>'+'<strong>'+'Cierre Exitoso '+'</strong>'+' el cierre se hizo correctamente'+'</div>';
	                        $('#mensaje').html(exito);//impresion del mensaje exitoso.
	                        $('.limpiar')[0].reset();///limpiamos los campos del formulario.
	                        $('#foco').focus();///indicamos el foco al primer valor del formulario. 
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
        }///cierre del submitHandler...
    });

    /*________________________________________________________*/
    $("#gasto").validate({
      rules:{
        dinero:{
          required: true,
          number: true
        }
      },
      submitHandler: function(form){
      ///BUSCAMOS LOS CAMPOS EN TODAS LAS TABLAS-> cuando le den buscar
        var pet = $('.span3 form').attr('action');
        var met = $('.span3 form').attr('method');
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
                             var error = '<div class="alert alert-error">'+'<button type="button" class="close" data-dismiss="alert">'+'X'+'</button>'+'<strong>'+'Error'+'</strong>'+' El Gasto no se pudo realizar '+'</div>';
                             $('.span6 .alert').remove();
                             $('#mensaje').html(error);
                       }else{
                          $('#resul').empty();//limpiar la tabla.
                          $('#resul').html(resp);//imprimir datos de la tabla.
                          setTimeout(function(){ $("#mensaje .alert").fadeOut(800).fadeIn(800).fadeOut(500).fadeIn(500).fadeOut(300);}, 800); 
                          var exito = '<div class="alert alert-success">'+'<button type="button" class="close" data-dismiss="alert">'+'X'+'</button>'+'<strong>'+'Gasto Exitoso '+'</strong>'+' El Gasto se hizo correctamente'+'</div>';
                          $('#mensaje').html(exito);//impresion del mensaje exitoso.
                          $('.limpiar')[0].reset();///limpiamos los campos del formulario.
                          $('#foco').focus();///indicamos el foco al primer valor del formulario. 
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
        }///cierre del submitHandler...
    });
/*____________________________________________________________*/
     $("#vitrinaInternet").validate({
        rules:{
           nombre:{
            required: true
            },
            dinero:{
            required: true,
            number: true
          }
        },
        submitHandler: function(form){
          ///BUSCAMOS LOS CAMPOS EN TODAS LAS TABLAS-> cuando le den buscar
            var pet = $('#vitrina form').attr('action');
            var met = $('#vitrina form').attr('method');
            console.log(pet);
            console.log(met);
               $.ajax({
                       beforeSend: function(){

                       },
                       url: pet,
                       type: met,
                       data: $('#vitrina form').serialize(),
                       success: function(resp){
                           console.log(resp);
                           if(resp == "Error"){
                                 setTimeout(function(){ $("#mensaje .alert").fadeOut(800).fadeIn(800).fadeOut(500).fadeIn(500).fadeOut(300);}, 800); 
                                 var error = '<div class="alert alert-error">'+'<button type="button" class="close" data-dismiss="alert">'+'X'+'</button>'+'<strong>'+'Error'+'</strong>'+'<br> No se Pudo registrar '+'</div>';
                                 $('#mensaje .alert').remove();
                                 $('#mensaje').html(error);
                           }else{
                              $('#resulVitrina').empty();//limpiar la tabla.
                              $('#resulVitrina').html(resp);//imprimir datos de la tabla.
                              setTimeout(function(){ $("#mensaje .alert").fadeOut(800).fadeIn(800).fadeOut(500).fadeIn(500).fadeOut(300);}, 800); 
                              var exito = '<div class="alert alert-success">'+'<button type="button" class="close" data-dismiss="alert">'+'X'+'</button>'+'<strong>'+'Registro guardado '+'</strong>'+' el registro se agrego correctamente'+'</div>';
                              $('#mensaje').html(exito);//impresion del mensaje exitoso.
                              $('#vitrinaInternet')[0].reset();///limpiamos los campos del formulario.
                              $('#foco').focus();///indicamos el foco al primer valor del formulario. 
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
        }//cierre del submitHandler
      });
   /*__________________________________________________________________*/
   /*REGISTRAR USUARIOS..............*/
   $("#validarRegistroUser").validate({
        rules:{
           nombre:{
            required: true
            }
        },
        submitHandler: function(form){
          ///BUSCAMOS LOS CAMPOS EN TODAS LAS TABLAS-> cuando le den buscar
            var pet = $('#registrarUser form').attr('action');
            var met = $('#registrarUser form').attr('method');

               $.ajax({
                       beforeSend: function(){

                       },
                       url: pet,
                       type: met,
                       data: $('#registrarUser form').serialize(),
                       success: function(resp){
                           console.log(resp);
                           if(resp == "Error"){
                                 setTimeout(function(){ $("#mensaje .alert").fadeOut(800).fadeIn(800).fadeOut(500).fadeIn(500).fadeOut(300);}, 800); 
                                 var error = '<div class="alert alert-error">'+'<button type="button" class="close" data-dismiss="alert">'+'X'+'</button>'+'<strong>'+'Error'+'</strong>'+'<br> No se Pudo registrar '+'</div>';
                                 $('#mensaje .alert').remove();
                                 $('#mensaje').html(error);
                           }else{
                              //$('#resulVitrina').empty();//limpiar la tabla.
                              //$('#resulVitrina').html(resp);//imprimir datos de la tabla.
                              setTimeout(function(){ $(".mensaje .alert").fadeOut(800).fadeIn(800).fadeOut(500).fadeIn(500).fadeOut(300);}, 1000); 
                              var exito = '<div class="alert alert-success">'+'<button type="button" class="close" data-dismiss="alert">'+'X'+'</button>'+'<strong>'+'Correcto '+'</strong>'+' el usuario se creo correctamente'+'</div>';
                              $('.mensaje').html(exito);//impresion del mensaje exitoso.
                              $('#validarRegistroUser')[0].reset();///limpiamos los campos del formulario.
                              $('#foco').focus();///indicamos el foco al primer valor del formulario. 
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
        }//cierre del submitHandler
      });

});//cierre del document...