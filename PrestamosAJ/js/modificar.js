$(document).ready(function(){
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
            $('#nombre').val($(this).parent().parent().children('td:eq(0)').text());
            $('#dinero').val($(this).parent().parent().children('td:eq(1)').text());
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
                              $('#buscar').focus();
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

});///cierre del document......