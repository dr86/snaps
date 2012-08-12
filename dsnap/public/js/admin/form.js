$(function()
{
	var useImage = true;
	
	$("input").blur(function()
	{
		var formElementId = $(this).attr('id');
		var formElementRequired = $(this).attr('use');
		var ajaxUrl = $(this).closest('form').attr('ajax-url');		
		
		if(formElementRequired != undefined && formElementRequired)
			useImage = false;
		
		doValidation(formElementId, ajaxUrl);
	});
	
	function doValidation(id, url)
	{
		var data = {};
		$("input").each(function()
		{
			data[$(this).attr('name')] = $(this).val();
		});
		
		$.post(url, data, function(response)
		{
			if(id == "image"){
				var resImg = validateImage(id);
				if(!resImg.ok){
					response[id] = validateImage(id);
				}
			}
			
			$("#" + id).parent().find('.notification').remove();
			$("#" + id).parent().append(getErrorHtml(response[id], id));
			
		}, 'json');
	}

	function getErrorHtml(formErrors, id)
	{		
		var html = '';
		for(key in formErrors)
		{
			html = '<div class="notification attention medium">';
			html += '<p>' + formErrors[key] + '</p>';
			html += '</div>';
		}
		return html;
	}

	function validateImage(id)
	{
		var imageFile = $("#"+id).val();
		var imageLength = imageFile.length;		
		var extensions = new Array("jpeg", "jpg", "gif", "png");
		
		var response = new Object();
		
		if (imageLength > 0)
		{
			var pos = imageFile.lastIndexOf('.') + 1;
			var ext = imageFile.substring(pos, imageLength);
			var final_ext = ext.toLowerCase();
			for (i = 0; i < extensions.length; i++)
			{
				if(extensions[i] == final_ext)
				{
					response.ok = true;
				}
			}
			response.error = "Anda harus meng-upload file gambar dengan ekstensi berikut: "+ extensions.join(', ');
			
		} else if (useImage) {			
			response.empty = "Gambar tidak boleh kosong";
		}

		return response;
	}
});

