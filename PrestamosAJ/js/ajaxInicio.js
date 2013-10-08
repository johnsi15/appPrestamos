$(document).ready(function(){
      //inicio de sesion....
     ///BUSCAMOS LOS CAMPOS EN TODAS LAS TABLAS-> cuando le den buscar
	    var pet = $('.span4 form').attr('action');
      var met = $('.span4 form').attr('method');

     $('.span4 form').submit(function(e){
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
                             setTimeout(function(){ $("#mensaje .alert").fadeOut(800).fadeIn(800).fadeOut(500).fadeIn(500).fadeOut(300);}, 1000); 
                             var error = '<div class="alert alert-error">'+'<button type="button" class="close" data-dismiss="alert">'+'X'+'</button>'+'<strong>'+'Nombre o Contraseña Incorrecta'+'</strong>'+'<br> Intente Nuevamente '+'</div>';
                             $('#mensaje .alert').remove();
                             $('#mensaje').html(error);
                             $('#limpiar')[0].reset();///limpiamos los campos del formulario.
                             $('#foco').focus();
                       }else{
                            self.location = "menu.php";
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

}); //final de document