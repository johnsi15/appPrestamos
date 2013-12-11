$(document).ready(function(){

  /*_____________________________________________*/
	$("#registrarCliente").validate({
		rules:{
      codigo:{
        required: true,
        number: true
      },
			nombre:{
				required: true
		    },
      tel: {
        required: true
      },
      dir: {
        required: true
      }
		},
		submitHandler: function(form){
			///BUSCAMOS LOS CAMPOS EN TODAS LAS TABLAS-> cuando le den buscar
		    var pet = $('#registrarDatos form').attr('action');
	      var met = $('#registrarDatos form').attr('method');
        console.log(pet);
        console.log(met);
	         $.ajax({
                   beforeSend: function(){

                   },
                   url: pet,
                   type: met,
                   data: $('#registrarDatos form').serialize(),
                   success: function(resp){
                   	   console.log(resp);
                       if(resp == "Error"){
                             setTimeout(function(){ $("#mensajeError .alert").fadeOut(1000).fadeIn(1000).fadeOut(800).fadeIn(500).fadeOut(300);}, 1000); 
                             var error = '<div class="alert alert-error">'+'<button type="button" class="close" data-dismiss="alert">'+'X'+'</button>'+'<strong>'+'Error'+'</strong>'+'<br> No se Pudo registrar verifique el N° de identificacion'+'</div>';
                             $('#mensajeError .alert').remove();
                             $('#mensajeError').html(error);
                       }else{
                          $('#verDatos').empty();//limpiar la tabla.
	                        $('#verDatos').html(resp);//imprimir datos de la tabla.
	                        setTimeout(function(){ $("#mensaje .alert").fadeOut(1000).fadeIn(900).fadeOut(800).fadeIn(500).fadeOut(300);}, 1000); 
	                        var exito = '<div class="alert alert-success">'+'<button type="button" class="close" data-dismiss="alert">'+'X'+'</button>'+'<strong>'+'Registro guardado '+'</strong>'+' el registro se agrego correctamente'+'</div>';
	                        $('#mensaje').html(exito);//impresion del mensaje exitoso.
	                        $('.limpiar')[0].reset();///limpiamos los campos del formulario.
                          $("#formMenu").removeClass('open');//cerramos el sub menu del registro
	                        $('#buscar').focus();///indicamos el foco al primer valor del formulario.
                          $('#registrarDatos').dialog('close');
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
  

  /*registro de los prestamos */
  /*_____________________________________________*/
  $("#registrarPrestamo").validate({
    rules:{
      dinero:{
        required: true,
        number: true
      },
      valor:{
        required: true,
        number: true
        },
      interes: {
        required: true,
        number: true
      }
    },
    submitHandler: function(form){

        var pet = $('#nuevoPrestamo form').attr('action');
        var met = $('#nuevoPrestamo form').attr('method');
        //console.log(pet);
        //console.log(met);
           $.ajax({
                   beforeSend: function(){

                   },
                   url: pet,
                   type: met,
                   data: $('#nuevoPrestamo form').serialize(),
                   success: function(resp){
                       //console.log(resp);
                       if(resp == "Error"){
                             setTimeout(function(){ $("#mensajeError .alert").fadeOut(1000).fadeIn(1000).fadeOut(900).fadeIn(800).fadeOut(300);}, 1000); 
                             var error = '<div class="alert alert-error">'+'<button type="button" class="close" data-dismiss="alert">'+'X'+'</button>'+'<strong>'+'Error'+'</strong>'+'<br> No se pudo procesar el prestamo Revise la Caja'+'</div>';
                             $('#mensajeError .alert').remove();
                             $('#mensajeError').html(error);
                             $('#nuevoPrestamo').dialog('close');
                       }else{
                          $('#verPrestamos').empty();//limpiar la tabla.
                          $('#verPrestamos').html(resp);//imprimir datos de la tabla.
                          setTimeout(function(){ $("#mensaje .alert").fadeOut(1000).fadeIn(1000).fadeOut(900).fadeIn(800).fadeOut(300);}, 1000); 
                          var exito = '<div class="alert alert-success">'+'<button type="button" class="close" data-dismiss="alert">'+'X'+'</button>'+'<strong>'+'Registro guardado '+'</strong>'+' El prestamo se hizo correctamente'+'</div>';
                          $('#mensaje').html(exito);//impresion del mensaje exitoso.
                          $('#registrarPrestamo')[0].reset();///limpiamos los campos del formulario.
                          $("#formMenu").removeClass('open');//cerramos el sub menu del registro
                          $('#foco').focus();///indicamos el foco al primer valor del formulario.
                          $('#nuevoPrestamo').dialog('close');
                          $('[data-toggle=popover]').popover({html:true});
                       }
                   },
                   error: function(jqXHR,estado,error){
                       console.log(estado);
                       console.log(error);
                   },
                   complete: function(jqXHR,estado){
                       //console.log(estado);
                   },
                   timeout: 10000//10 segundos.
               });
    }//cierre del submitHandler
  });
  
  /*registro de la caja base */
  /*_____________________________________________*/
  $("#modificarBase").validate({
    rules:{
      base:{
        required: true,
        number: true
      }
    },
    submitHandler: function(form){

        var pet = $('#nuevaBase form').attr('action');
        var met = $('#nuevaBase form').attr('method');
        //console.log(pet);
       // console.log(met);
           $.ajax({
                   beforeSend: function(){

                   },
                   url: pet,
                   type: met,
                   data: $('#nuevaBase form').serialize(),
                   success: function(resp){
                       //console.log(resp);
                       if(resp == "Error"){
                             setTimeout(function(){ $("#mensajeError .alert").fadeOut(1000).fadeIn(1000).fadeOut(1000).fadeIn(800).fadeOut(300);}, 1000); 
                             var error = '<div class="alert alert-error">'+'<button type="button" class="close" data-dismiss="alert">'+'X'+'</button>'+'<strong>'+'Error'+'</strong>'+'<br> No se pudo realizar la acción'+'</div>';
                             $('#mensajeError .alert').remove();
                             $('#mensajeError').html(error);
                             $('#nuevaBase').dialog('close');
                       }else{
                          $('#verCaja').empty();//limpiar la tabla.
                          $('#verCaja').html(resp);//imprimir datos de la tabla.
                          setTimeout(function(){ $("#mensaje .alert").fadeOut(1000).fadeIn(1000).fadeOut(1000).fadeIn(800).fadeOut(300);}, 1000); 
                          var exito = '<div class="alert alert-success">'+'<button type="button" class="close" data-dismiss="alert">'+'X'+'</button>'+'<strong>'+'Registro guardado '+'</strong>'+' La caja fue actualizada'+'</div>';
                          $('#mensaje').html(exito);//impresion del mensaje exitoso.
                          $('#modificarBase')[0].reset();///limpiamos los campos del formulario.
                          $("#formMenu").removeClass('open');//cerramos el sub menu del registro
                          $('#foco').focus();///indicamos el foco al primer valor del formulario.
                          $('#nuevaBase').dialog('close');
                          $('#verInteres').load('refresInteres.php');
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

/*registro de la caja interes */
  /*_____________________________________________*/
  $("#modificarInteres").validate({
    rules:{
      dinteres:{
        required: true,
        number: true
      }
    },
    submitHandler: function(form){

        var pet = $('#nuevoInteres form').attr('action');
        var met = $('#nuevoInteres form').attr('method');
        console.log(pet);
        console.log(met);
           $.ajax({
                   beforeSend: function(){

                   },
                   url: pet,
                   type: met,
                   data: $('#nuevoInteres form').serialize(),
                   success: function(resp){
                       console.log(resp);
                       if(resp == "Error"){
                             setTimeout(function(){ $("#mensajeError .alert").fadeOut(1000).fadeIn(1000).fadeOut(800).fadeIn(500).fadeOut(300);}, 1000); 
                             var error = '<div class="alert alert-error">'+'<button type="button" class="close" data-dismiss="alert">'+'X'+'</button>'+'<strong>'+'Error'+'</strong>'+'<br> No se pudo realizar la acción'+'</div>';
                             $('#mensajeError .alert').remove();
                             $('#mensajeError').html(error);
                             $('#nuevoInteres').dialog('close');
                       }else{
                          $('#verInteres').empty();//limpiar la tabla.
                          $('#verInteres').html(resp);//imprimir datos de la tabla.
                          setTimeout(function(){ $("#mensaje .alert").fadeOut(1000).fadeIn(1000).fadeOut(900).fadeIn(800).fadeOut(300);}, 1000); 
                          var exito = '<div class="alert alert-success">'+'<button type="button" class="close" data-dismiss="alert">'+'X'+'</button>'+'<strong>'+'Registro guardado '+'</strong>'+' La caja fue actualizada'+'</div>';
                          $('#mensaje').html(exito);//impresion del mensaje exitoso.
                          $('#modificarInteres')[0].reset();///limpiamos los campos del formulario.
                          $("#formMenu").removeClass('open');//cerramos el sub menu del registro
                          $('#foco').focus();///indicamos el foco al primer valor del formulario.
                          $('#nuevoInteres').dialog('close');
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

/*registro de los pagos de los prestamos*/
  /*_____________________________________________*/
  $("#registrarPago").validate({
    rules:{
      pago:{
        required: true,
        number: true
      },
      interes:{
        required: true,
        number: true
      }
    },
    submitHandler: function(form){

        var pet = $('#nuevoPago form').attr('action');
        var met = $('#nuevoPago form').attr('method');
        //console.log(pet);
        //console.log(met);
           $.ajax({
                   beforeSend: function(){

                   },
                   url: pet,
                   type: met,
                   data: $('#nuevoPago form').serialize(),
                   success: function(resp){
                       console.log(resp);
                       if(resp == "Error"){
                             setTimeout(function(){ $("#mensajeError .alert").fadeOut(1000).fadeIn(1000).fadeOut(800).fadeIn(500).fadeOut(300);}, 1000); 
                             var error = '<div class="alert alert-error">'+'<button type="button" class="close" data-dismiss="alert">'+'X'+'</button>'+'<strong>'+'Renovar o Eliminar Credito'+'</strong>'+'<br> No se pudo realizar el pago'+'</div>';
                             $('#mensajeError .alert').remove();
                             $('#mensajeError').html(error);
                             $('#nuevoPago').dialog('close');
                       }else{
                            $('#verPagos').empty();//limpiar la tabla.
                            $('#verPagos').html(resp);//imprimir datos de la tabla.
                            setTimeout(function(){ $("#mensaje .alert").fadeOut(1000).fadeIn(1000).fadeOut(900).fadeIn(800).fadeOut(600);}, 1000); 
                            var exito = '<div class="alert alert-success">'+'<button type="button" class="close" data-dismiss="alert">'+'X'+'</button>'+'<strong>'+'Registro guardado '+'</strong>'+' El pago se hizo correctamente'+'</div>';
                            $('#mensaje').html(exito);//impresion del mensaje exitoso.
                            $('#registrarPago')[0].reset();///limpiamos los campos del formulario.
                            $("#formMenu").removeClass('open');//cerramos el sub menu del registro
                            $('#foco').focus();///indicamos el foco al primer valor del formulario.
                            $('#nuevoPago').dialog('close');
                            // ver mas detalles del pago
                            $('[data-toggle=popover]').popover({html:true});  
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

  
  /*registro de los pagos de los prestamos*/
  /*_____________________________________________*/
  $("#registrarPago2").validate({
    rules:{
      pago:{
        required: true,
        number: true
      },
      interes:{
        required: true,
        number: true
      }
    },
    submitHandler: function(form){

        var pet = $('#nuevoPago form').attr('action');
        var met = $('#nuevoPago form').attr('method');
        //console.log(pet);
        //console.log(met);
           $.ajax({
                   beforeSend: function(){

                   },
                   url: pet,
                   type: met,
                   data: $('#nuevoPago form').serialize(),
                   success: function(resp){
                       console.log(resp);
                       if(resp == "Error"){
                             setTimeout(function(){ $("#mensajeError .alert").fadeOut(1000).fadeIn(1000).fadeOut(800).fadeIn(500).fadeOut(300);}, 1000); 
                             var error = '<div class="alert alert-error">'+'<button type="button" class="close" data-dismiss="alert">'+'X'+'</button>'+'<strong>'+'Renovar o Eliminar Credito'+'</strong>'+'<br> No se pudo realizar el pago'+'</div>';
                             $('#mensajeError .alert').remove();
                             $('#mensajeError').html(error);
                             $('#nuevoPago').dialog('close');
                       }else{
                          $('#verPagos').empty();//limpiar la tabla.
                          $('#verPagos').html(resp);//imprimir datos de la tabla.
                          setTimeout(function(){ $("#mensaje .alert").fadeOut(1000).fadeIn(1000).fadeOut(900).fadeIn(800).fadeOut(600);}, 1000); 
                          var exito = '<div class="alert alert-success">'+'<button type="button" class="close" data-dismiss="alert">'+'X'+'</button>'+'<strong>'+'Registro guardado '+'</strong>'+' El pago se hizo correctamente'+'</div>';
                          $('#mensaje').html(exito);//impresion del mensaje exitoso.
                          $('#registrarPago2')[0].reset();///limpiamos los campos del formulario.
                          $("#formMenu").removeClass('open');//cerramos el sub menu del registro
                          $('#foco').focus();///indicamos el foco al primer valor del formulario.
                          $('#nuevoPago').dialog('close');
                          // ver mas detalles del pago
                          $('[data-toggle=popover]').popover({html:true});
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
                          $('#verInteres').empty();//limpiar la tabla.
                          $('#verInteres').html(resp);//imprimir datos de la tabla.
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