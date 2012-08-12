$(function() 
{
	
	function getBaseURL() 
	{
	    var url = location.href;  // entire url including querystring - also: window.location.href;
	    var baseURL = url.substring(0, url.indexOf('/', 14));
	
	
	    if (baseURL.indexOf('http://localhost') != -1) 
	    {
	        // Base Url for localhost
	        var url = location.href;  // window.location.href;
	        var pathname = location.pathname;  // window.location.pathname;
	        var index1 = url.indexOf(pathname);
	        var index2 = url.indexOf("/", index1 + 1);
	        var baseLocalUrl = url.substr(0, index2);
	
	        return baseLocalUrl + "/";
	    }
	    else {
	        // Root Url for domain name
	        return baseURL + "/";
	    }
	
	}
	
	$('ul.about a').click(function()
	{
		var title = $(this).attr('id');

		$('ul.about').find('li').removeClass('active');
		$(this).parent().addClass('active');
		
		$('#response').fadeTo(500,0.5);
		
		$.post( getBaseURL() + title,
			function( response ){
				$('#response').html( response );
				$('#response').fadeTo(500,1);
			}
		);
	});
	
	$('ul.people a').click(function()
	{
		var title = $(this).attr('id');

		$('ul.people').find('li').removeClass('active');
		$(this).parent().addClass('active');
		
		$('#response').fadeTo(500,0.5);
		
		$.post( getBaseURL() + '/about/people/' + title,
			function( response ){
				$('#response').html( response );
				$('#response').fadeTo(500,1);
			}
		);
	});
});