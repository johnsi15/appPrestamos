$(document).ready(function(){
	var pet = $('.span9 form').attr('action');
	var met = $('.span9 form').attr('method');

	$('.span9 form').submit(function(e){
           e.preventDefault();///evitamos que la pagina se recargue
           $.ajax({
           	    beforeSend: function(){

           	    },
           	    url: pet,
           	    type: met,
           	    data: $('.span9 form').serialize(),
           	    success: function(resp){
           	    	console.log(resp);
           	    	if(resp == ''){
                             setTimeout(function(){ $(".span6 .alert").fadeOut(800).fadeIn(800).fadeOut(500).fadeIn(500).fadeOut(300);}, 800); 
                             var error = '<div class="alert alert-warning">'+'<button type="button" class="close" data-dismiss="alert">'+'X'+'</button>'+'<strong>'+'No hay datos '+'</strong>'+' de esa Fecha'+'</div>';
                             $('.span6 .alert').remove();
                             $('#mensaje').html(error);
           	    	}else{
                       $('#resul').html(resp);
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
});///cierre del document...