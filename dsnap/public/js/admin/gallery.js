$(function() 
{
	var ajax_url = $('#ajax-action').attr('url');
	/**
	 * Gallery sortable
	 */
	$('.gallery').sortable({
		placeholder : 'ui-state-highlight',
		connectWith : '.gallery',
		cursor : 'move',
		stop: function(event, ui)
		{
			var sortorder='';
			$('.gallery').each(function(){
				var itemorder = $(this).sortable('toArray');
				var columnId = $(this).attr('id');
				sortorder += columnId + '=' + itemorder.toString() + '&';
			});
			
			// hapus "&" awal dan akhir
			// ex : available=gallery-3,gallery-2&dropped=gallery-1,gallery-4
			// test : alert( sortorder );
			sortorder = sortorder.substring( 2, sortorder.length - 1 );
			
			// ajax action add
			$.post( ajax_url + '/thunder/public/admin/ajax/gallery/?act=add&' + sortorder,
				function( response ){
					showMessage( response.status, response.message );
				}, 
				"json"
			);
		}
	}).disableSelection();
	
	/**
	 * fungsi tombol delete
	 * jika aksi delete berada di "available" adalah aksi delete gallery
	 * apabila aksi delete berada di "gallery home" adalah ajax delete item option gallery_home,
	 * kemudian animasi hapus item dari gallery home
	 */
	$('.delete').click(function() {
		var id = $( this ).closest( 'div' ).find( 'ul' ).attr( 'id' );
		if( id == "available" ){
			if( confirm( "Are you sure delete this gallery ?" ) ){
				alert( 'delete' );
			}
		} else {
			var element = $( this ).parent().parent();
			$( element ).animate_from_to( "#available", {
				initial_css: {
           			'background': '#AE432E'
       			},
				callback : function() {
					var id = element.attr( 'id' );
					// ajax action delete option gallery
					$.post( ajax_url + '?act=delete&gallery=' + id,
						function( response ){
							if( response.status == 'success' ){
								$( '#available' ).append(element);
							}
							showMessage( response.status, response.message );
						}, 
						"json"
					);
				}
			});
		}
	    return false;
	})
	
	/**
	 * showMessage
	 * tampilkan notifikasi ajax response
	 * 
	 * @param {String} status
	 * @param {String} text
	 */
	function showMessage( status, text ){
		var msg = $('#ajax-message');
		msg.addClass('ajax-' + status);
	    msg.text( text );
	    msg.fadeIn('slow');
	    // fade out in 3 sec
		setTimeout(function(){
	    	msg.fadeOut('slow');
	    }, 3000);
	};
}); 