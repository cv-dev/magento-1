$jq(document).ready(function(){ 
	    $jq('#attr_code').live('change',function(){
			var attr_code = $jq(this).val();
			get_attr_list_by_id(attr_code); 
			return false; 
		})
		
		function get_attr_list_by_id(attr_id) { 
			var ajax_url = $jq('input[name=ajax_url]').attr('value');
			var brand_id = $jq('input[name=brand_id]').attr('value');
			new Ajax.Request(ajax_url, {
				 parameters: {attr_id: attr_id,brand_id:brand_id},
				 method:'get',
				 requestHeaders: {Accept: 'application/json'},
				 onSuccess: function(transport) {
				   var object = transport.responseText.evalJSON();
				   var option = "";
				   var attr_id = 0;
				   for(var index1 in object) { 
						if(index1=='attr_id') {
							attr_id = object[index1];
						}
				   }
				   for(var index in object) { 
					   if (object.hasOwnProperty(index)) {
						   var attr = object[index];
						   
						   if(index == attr_id) {
								option +="<option selected='selected' value='"+index+"'>"+attr+"</option>";
						   } else {
								if(index!='attr_id')  {	
									option +="<option value='"+index+"'>"+attr+"</option>";
								}
						   }
					   }
					}
					
					if(option!=""){
						$jq('#value').html(option);
					}else {
						option = "<option value=0>None</option>";
						$jq('#value').html(option);
					}
				  }
			   });
		}
		
		var attr_current = $jq('#attr_code').val();
			get_attr_list_by_id(attr_current); 
		
})