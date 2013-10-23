

$(document).ready(function()
{


	// Changer la forme du curseur quand il est pointé sur l'icone corbeille
	$(".icon-remove").hover(function() {
	    $(this).css('cursor','pointer');
	}, function() {
	    $(this).css('cursor','auto');
	});
	
  $('.icon-remove').live('click',function(){
	 
	id = $(this).parent().parent().attr('id');
    url = $(this).attr('id');
     $('#myModal').modal();
     $('.modal-body p').html("Voulez vous réellement supprimer la ressource : "+ $('tr[id='+id+'] > td').eq('0').text());

 	 $('#suppButton').live('click',function(){
 		 $('#myModal').modal('hide');
		  
			 $.ajax({
		         type: 'POST',
		         url: url,
		                      data: {
		                             id: id
		                             },
		         dataType : 'json',
		         success: function (data,status) {
		             if(data.status == "200")
		                 {
		            	 $('tr[id='+id+']').fadeOut(500, function(){ $(this).remove(); 
		            	 $('html, body').animate({
		                     scrollTop: $(".row-fluid").offset().top
		                      }, 2000);});
		                  
		                   if(data.result == "1")

		                   {
		                       $("#msgstate").html('<div class="alert alert-success"><button type="button" class="close " data-dismiss="alert"></button><strong>Succes :</strong>&nbsp;&nbsp;&nbsp; La suppression a &eacute;t&eacute; bien effectu&eacute;e !</div>');
		                   }

		                   else if(data.result == "0")

		                   {
		                	   $("#msgstate").html('<div class="alert alert-success"><button type="button" class="close " data-dismiss="alert"></button><strong>Succes :</strong>&nbsp;&nbsp;&nbsp;Aucune ligne affect&eacute;e (v&eacute;rifiez id) !</div>');
		                   }

		                  }
		               else if(data.status == "500")
		                 {

		            	   $("#msgstate").html('<div class="alert"><button type="button" class="close " data-dismiss="alert"></button><strong>Alerte :</strong>&nbsp;&nbsp;&nbsp;Erreur lors de la suppression !</div>');

		                 }
		                      
		
		                 },
		        error: function(){

		        	$("#msgstate").html('<div class="alert"><button type="button" class="close " data-dismiss="alert"></button><strong>Alerte :</strong>&nbsp;&nbsp;&nbsp;Erreur lors de la suppression !</div>');

		        } });


	  });
 	 // si annulation (on disparition du modal window
 	

  });
	
}
);



