function $(id) {return document.getElementById(id); } 

var wpr = {
		ui : {
			toggle : function(id){
				if ($('story-'+id).style.display == 'none'){
					$('story-'+id).style.display = 'block' ;	
				}
				else {
					$('story-'+id).style.display = 'none' ;					
				} 				
			}			
		}
}