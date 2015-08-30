$(document).ready(function(){

	    $('#editarPago').dialog({//con esto cargamos los formulario de los gastos y de los cierre no es necesario repetir el codigo
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
         $('#editarPago').dialog('close');
      });

      //editar Registro
      $('body').on('click','#editPago',function(e){
            e.preventDefault();
        // alert($(this).attr('href'));
       $('#id_registro').val($(this).attr('href'));
        //abreimos el formulario
            $('#editarPago').dialog('open');
            //estraemos los campos.
            $('#nombre').val($(this).parent().parent().children('td:eq(0)').text());
            $('#pago').val($(this).parent().parent().children('td:eq(3)').text());
            var condicion =$(this).parent().parent().children('td:eq(4)').text();
            $('#con option[value="'+condicion+'"]').attr('selected',true);
      });

      var pet = $('#editarPago form').attr('action');
      var met = $('#editarPago form').attr('method');

      $('#editarPago form').on('click','#modificarPago',function(e){
            e.preventDefault();
          $.ajax({
              beforeSend: function(){

              },
              url: pet,
              type: met,
              data: $('#editarPago form').serialize(),
              success: function(resp){
                console.log(resp);
                if(resp == "Error"){

                }else{
                  $('#verEstu').empty();//limpiamos la tabla
                  $('#verEstu').html(resp);
                  $('#editarPago').dialog('close');
                  setTimeout(function(){ $("#mensaje .alert").fadeOut(800).fadeIn(800).fadeOut(500).fadeIn(500).fadeOut(300);}, 800); 
                              var exito = '<div class="alert alert-info">'+'<button type="button" class="close" data-dismiss="alert">'+'X'+'</button>'+'<strong>'+'Modificado '+'</strong>'+' el registro se modifico correctamente'+'</div>';
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



    /*modificamos los pagos de los vencimientos de las fechas*/
    /*___________________________________________________________________*/
     $('#editarPagoVencimiento').dialog({//con esto cargamos los formulario de los gastos y de los cierre no es necesario repetir el codigo
            autoOpen: false,
            modal: true,
            width:280,
            height:'auto',
            resizable: false,
            close:function(){
                  $('#id_registroVen').val('0');
            }
      });
     
     /*cerrar ventana de modificar ventana de fechas vencimientos*/
      $('body').on('click','#cancelar',function(e){
         e.preventDefault();
         $('#editarPagoVencimiento').dialog('close');
      });

      //editar Registro
      $('body').on('click','#editPagoVen',function(e){
            e.preventDefault();
      	// alert($(this).attr('href'));
      	$('#id_registroVen').val($(this).attr('href'));
      	 //abreimos el formulario
            $('#editarPagoVencimiento').dialog('open');
            //estraemos los campos.
            $('#nombreVen').val($(this).parent().parent().children('td:eq(0)').text());
            $('#pagoVen').val($(this).parent().parent().children('td:eq(3)').text());
            var condicion =$(this).parent().parent().children('td:eq(4)').text();
            $('#conVen option[value="'+condicion+'"]').attr('selected',true);
      });
      /*__________________________________________________________________________*/
      var petVen = $('#editarPagoVencimiento form').attr('action');
      var metVen = $('#editarPagoVencimiento form').attr('method');

      $('#editarPagoVencimiento form').on('click','#modificarPagoVen',function(e){
      	    e.preventDefault();

      	    $.ajax({
      	    	beforeSend: function(){

      	    	},
      	    	url: petVen,
      	    	type: metVen,
      	    	data: $('#editarPagoVencimiento form').serialize(),
      	    	success: function(resp){
      	    		console.log(resp);
      	    		if(resp == "Error"){

      	    		}else{
                  $('#verVencimiento').empty();//limpiamos la tabla
                  $('#verVencimiento').html(resp);
      	    			$('#editarPagoVencimiento').dialog('close');
      	    			setTimeout(function(){ $("#mensaje .alert").fadeOut(800).fadeIn(800).fadeOut(500).fadeIn(500).fadeOut(300);}, 800); 
                              var exito = '<div class="alert alert-info">'+'<button type="button" class="close" data-dismiss="alert">'+'X'+'</button>'+'<strong>'+'Modificado '+'</strong>'+' el registro se modifico correctamente'+'</div>';
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



/*este es el codigo para modificar los datos personales del cliente ok*/
    /*___________________________________________________________________*/
    $('#editarDatos').dialog({
            autoOpen: false,
            modal: true,
            width:250,
            height:'auto',
            resizable: false,
            close:function(){
                  $('#id_registro').val('0');
            }
      });
     
     /*cerrar ventana de modificar ventana de fechas vencimientos*/
      $('body').on('click','#cancelar',function(e){
         e.preventDefault();
         $('#editarDatos').dialog('close');
      });

    /*editar datos personales*/
    $('body').on('click','#editEstudiante',function(e){
            e.preventDefault();
           // alert($(this).attr('href'));
            $('#id_registro').val($(this).attr('href'));
            //abreimos el formulario
            $('#editarDatos').dialog('open');
            //estraemos los campos.
            $('#nombre').val($(this).parent().parent().children('td:eq(0)').text());
            $('#direccion').val($(this).parent().parent().children('td:eq(1)').text());
            $('#telefono').val($(this).parent().parent().children('td:eq(2)').text());
            //$('#dinero').val($(this).parent().parent().children('td:eq(0)').text());
     });

    
    var peticion = $('#editarDatos form').attr('action');
    var metodo = $('#editarDatos form').attr('method');

    $('#editarDatos form').on('click','#modificarDatos',function(e){
                e.preventDefault();

                $.ajax({
                  beforeSend: function(){

                  },
                  url: peticion,
                  type: metodo,
                  data: $('#editarDatos form').serialize(),
                  success: function(resp){
                        console.log(resp);
                        if(resp == "Error"){
                             setTimeout(function(){ $("#mensaje .alert").fadeOut(1000).fadeIn(900).fadeOut(800).fadeIn(500).fadeOut(300);}, 800); 
                             var error = '<div class="alert alert-error">'+'<button type="button" class="close" data-dismiss="alert">'+'X'+'</button>'+'<strong>'+'Error'+'</strong>'+'<br> No se Pudo Editar'+'</div>';
                             $('.span3 .alert').remove();
                             $('#mensaje').html(error);
                        }else{
                              $('#verDatos').empty();//limpiamos la tabla 
                              $('#verDatos').html(resp);//mandamos los nuevos datos a la tabla
                              $('#editarDatos').dialog('close');
                              setTimeout(function(){ $("#mensaje .alert").fadeOut(800).fadeIn(800).fadeOut(500).fadeIn(500).fadeOut(300);}, 800); 
                              var exito = '<div class="alert alert-info">'+'<button type="button" class="close" data-dismiss="alert">'+'X'+'</button>'+'<strong>'+'Modificado '+'</strong>'+' el registro se modifico correctamente'+'</div>';
                              $('#mensaje').html(exito);
                              $('[data-toggle=popover]').popover({html:true});
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


/*aca va el codigo para actualizaar el tiempo de los estudiantes que ya pagaron*/
/*_______________________________________________________________________________*/

      $('#actulizarTiempo').dialog({
            autoOpen: false,
            modal: true,
            width:250,
            height:'auto',
            resizable: false,
            close:function(){
                  $('#id_registro').val('0');
            }
      });
     
     /*cerrar ventana de modificar ventana de fechas vencimientos*/
      $('body').on('click','#cancelar',function(e){
         e.preventDefault();
         $('#actulizarTiempo').dialog('close');
      });

    /*editar datos personales*/
    $('body').on('click','#tiempoEstudiante',function(e){
            e.preventDefault();
           // alert($(this).attr('href'));
            $('#id_registro').val($(this).attr('href'));
            //abreimos el formulario
            $('#actulizarTiempo').dialog('open');
            //estraemos los campos.
            $('#nombre').val($(this).parent().parent().children('td:eq(0)').text());
           // $('#pago').val($(this).parent().parent().children('td:eq(3)').text());
            //var condicion =$(this).parent().parent().children('td:eq(4)').text();
            //$('#con option[value="'+condicion+'"]').attr('selected',true);
            //$('#dinero').val($(this).parent().parent().children('td:eq(0)').text());
     });

    
    var peticionTiempo = $('#actulizarTiempo form').attr('action');
    var metodoTiempo = $('#actulizarTiempo form').attr('method');

    $('#actulizarTiempo form').on('click','#modificarTiempo',function(e){
                e.preventDefault();

                $.ajax({
                  beforeSend: function(){

                  },
                  url: peticionTiempo,
                  type: metodoTiempo,
                  data: $('#actulizarTiempo form').serialize(),
                  success: function(resp){
                        console.log(resp);
                        if(resp == "Error"){

                        }else{
                              $('#verDatos').empty();//limpiamos la tabla 
                              $('#verDatos').html(resp);//mandamos los nuevos datos a la tabla
                              $('#actulizarTiempo').dialog('close');
                              setTimeout(function(){ $("#mensaje .alert").fadeOut(800).fadeIn(800).fadeOut(500).fadeIn(500).fadeOut(300);}, 800); 
                              var exito = '<div class="alert alert-info">'+'<button type="button" class="close" data-dismiss="alert">'+'X'+'</button>'+'<strong>'+'Modificado '+'</strong>'+' el registro se modifico correctamente'+'</div>';
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











/*EDITAR NOMBRE DE USUARIOOOO*/
      $('#formulario').dialog({
            autoOpen: false,
            modal: true,
            width:280,
            height:'auto',
            resizable: false,
            close:function(){
                  $('#id_registro').val('0');
            }
      });

    $('body').on('click','#editNomUser',function(e){
            e.preventDefault();
           // alert($(this).attr('href'));
            $('#id_registro').val($(this).attr('href'));
            //abreimos el formulario
            $('#formulario').dialog('open');
            //estraemos los campos.
            $('#nombre').val($(this).parent().parent().children('td:eq(1)').text());
            //$('#dinero').val($(this).parent().parent().children('td:eq(0)').text());
     });

    $('body').on('click','#UserCancelar',function(e){
       e.preventDefault();
       $('#formulario').dialog('close');
    });

    var petNomUser = $('#formulario form').attr('action');
    var metNomUser = $('#formulario form').attr('method');

    $('#formulario form').on('click','#UserModificar',function(e){
                e.preventDefault();

                $.ajax({
                  beforeSend: function(){

                  },
                  url: petNomUser,
                  type: metNomUser,
                  data: $('#formulario form').serialize(),
                  success: function(resp){
                        console.log(resp);
                        if(resp == "Error"){

                        }else{
                              //$('#resul').empty();//quitamos los que hay
                              $('#resul').html(resp);
                              $('#formulario').dialog('close');
                              setTimeout(function(){ $(".mensaje .alert").fadeOut(800).fadeIn(800).fadeOut(500).fadeIn(500).fadeOut(300);}, 800); 
                              var exito = '<div class="alert alert-info">'+'<button type="button" class="close" data-dismiss="alert">'+'X'+'</button>'+'<strong>'+'Modificado '+'</strong>'+' el registro se modifico correctamente'+'</div>';
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
/*________________________________________________________________*/

/*MODIFICAR CONTRASEÑA DE LOS USUARIOS*/
      $('#formularioContraseña').dialog({//con esto cargamos los formulario de los gastos y de los cierre no es necesario repetir el codigo
            autoOpen: false,
            modal: true,
            width:280,
            height:'auto',
            resizable: false,
            close:function(){
                  $('#id_registro2').val('0');
            }
      });
     
      /*EDITAR NOMBRE DE USUARIOOOO*/
      $('body').on('click','#editContraUser',function(e){
            e.preventDefault();
           // alert($(this).attr('href'));
            $('#id_registro2').val($(this).attr('href'));
            //abreimos el formulario
            $('#formularioContraseña').dialog('open');
            //estraemos los campos.
           // $('#contraseña').val($(this).parent().parent().children('td:eq(1)').text());
            //$('#dinero').val($(this).parent().parent().children('td:eq(0)').text());
      });

       $('body').on('click','#UserCancelar',function(e){
             e.preventDefault();
             $('#formularioContraseña').dialog('close');
      });

      var petContra = $('#formularioContraseña form').attr('action');
      var metContra = $('#formularioContraseña form').attr('method');

      $("#contraseñaValidar").validate({
            rules:{
                  contraseñaA:{
                        required: true
                },
                contraseñaN:{
                        required: true
                  }
            },
            submitHandler: function(form){
                 // e.preventDefault();
                $.ajax({
                  beforeSend: function(){

                  },
                  url: petContra,
                  type: metContra,
                  data: $('#formularioContraseña form').serialize(),
                  success: function(resp){
                        console.log(resp);
                        if(resp == "Error"){
                              $('#formularioContraseña').dialog('close');
                              setTimeout(function(){ $(".mensaje .alert").fadeOut(800).fadeIn(800).fadeOut(500).fadeIn(500).fadeOut(300);}, 800); 
                              var exito = '<div class="alert alert-danger">'+'<button type="button" class="close" data-dismiss="alert">'+'X'+'</button>'+'<strong>'+'Error '+'</strong>'+' la contraseña actual no coincide'+'</div>';
                              $('.mensaje').html(exito);
                              $('input[type=password]').val('');//limpiamos los campos de tipo password
                        }else{
                              $('#formularioContraseña').dialog('close');
                              setTimeout(function(){ $(".mensaje .alert").fadeOut(800).fadeIn(800).fadeOut(500).fadeIn(500).fadeOut(300);}, 800); 
                              var exito = '<div class="alert alert-info">'+'<button type="button" class="close" data-dismiss="alert">'+'X'+'</button>'+'<strong>'+'Modificado '+'</strong>'+' el registro se modifico correctamente'+'</div>';
                              $('.mensaje').html(exito);
                              $('input[type=password]').val('');//limpiamos los datos de tipo password
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
            }
      });
/*_______________________________________________________________________*/

});//cierrre del document...