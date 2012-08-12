$(function() {
	
	var ajax_url = '/dsnap/admin/ajax/menu';
	
	$( ".portlet" ).addClass( "ui-widget ui-widget-content ui-helper-clearfix" )
				   .find( ".portlet-header" )
				   .addClass( "ui-widget-header ui-corner-all" )
				   .prepend( "<a class='ui-icon ui-icon-triangle-1-s'></a>")
				   .end();
				   
	$('#menus-list ol.menu-sortable').nestedSortable({
        handle: '.portlet-header',
		forcePlaceholderSize : true,
		helper : 'clone',
		items : 'li',
		cursor: 'move',
		maxLevels : 3,
		opacity : .6,
		placeholder : 'menu-placeholder',
		revert : 250,
		tabSize : 25,
		tolerance : 'pointer'
	});
	
	 /* AKSI HAPUS */
	$("a.menu-control-remove").live("click", function () {
		var a = $(this).parent().find('.menu-control-close'),
			li = $(this).parents('li:first');
		li.animate({ backgroundColor: "#fbc7c7" }, "fast").animate({ opacity: "hide" }, "fast", function() {
			a.click();
			li.remove();
		});
    	return false;
    });
	/* AKSI CLOSE, VIEW MENU, CLOSE MENU */
	$( 'a.ui-icon, a.menu-control-close, a.menu-view, a.menu-close' ).live("click", function () {
		var parent = $( this ).parents( ".portlet:first" );
		$( this ).toggleClass( "ui-icon-triangle-1-s" ).toggleClass( "ui-icon-triangle-1-n" );
		parent.find( ".portlet-inside:first" ).toggle();
		parent.find( ".menu-save-top" ).toggle();
        return false;
	});
	 /* AKSI CHECK ALL */
	$('a.page-check-all').live('click', function(){
		pageCheck('form-page', 1);
		return false;
	});
	 /* AKSI UNCHECK ALL */
	$('a.page-uncheck-all').live('click', function(){
		pageCheck('form-page', 0);
		return false;
	});
	
	function pageCheck( id, flag )
	{
	    if (flag == 0) {
	        $("form#" + id + " INPUT[type='checkbox']").attr('checked', false);
	    }else {
	        $("form#" + id + " INPUT[type='checkbox']").attr('checked', true);
	    }
	}	
	
	/* SET NAVIGATION */
	$('input.set-navigation').live('click', function(){
		var parent= $(this).parents('.portlet-inside'),
			data = $(this).closest('form').serialize();
			
		// parameter act	
		param = { 
			act: 'navigation',
		};
		// set parameter
		data += "&" + $.param(param);
		
		$('.ajax-loader-widget', parent).css("visibility", "visible");
		$.ajax({
			url: ajax_url,
			type: 'POST',
			data: data,
			dataType: 'json',
			success: function( response ){
				if( response )
					$('.ajax-loader-widget', parent).css("visibility", "hidden");
			}
		});
				
		return false;
	});
	
	/* TAMBAH PAGE CUSTOM LINK */
	$('input.add-page-link').live('click', function(){
		var parent= $(this).parents('.portlet-inside'),
			data = parent.find('form').serialize(),
			menu = parent.find('select.select-menu').val(),
			item_menu;
			
		// parameter act	
		param = { 
			act: 'add-page-link',
		};
		// set parameter
		data += "&" + $.param(param);
			
		if( menu != '' )
		{
			$('.ajax-loader-widget', parent).css("visibility", "visible");
			$.ajax({
				url: ajax_url,
				type: 'POST',
				data: data,
				dataType: 'html',
				success: function( item ){
					item_menu = $('div#'+menu);
					
					$('.ajax-loader-widget', parent).css("visibility", "hidden");
					
					inside = item_menu.find('.portlet-inside:first');
					if( inside.is(":hidden") )
						item_menu.find('a.ui-icon:first').click();
					
					item_menu.find('.info').hide();
					item_menu.find('ol.menu-sortable').prepend( item );
					item_menu.find('.menu-save-bottom').show();
				}
			});
		}
		
		return false;
	})
	
	/* TAMBAH PAGE */
	$('input.add-page').live('click', function(){
		var parent= $(this).parents('.portlet-form'),
			data = parent.find('form').serialize(),
			menu = parent.find('select.select-menu').val(),
			item_menu;
			
		// parameter act	
		param = { 
			act: 'add-page',
		};
		// set parameter
		data += "&" + $.param(param);
		
		if( menu != '' && data != '' )
		{
			$('.ajax-loader-widget', parent).css("visibility", "visible");
			$.ajax({
				url: ajax_url,
				type: 'POST',
				data: data,
				dataType: 'html',
				success: function( item ){
					// uncheck all
					pageCheck('form-page', 0);
					
					item_menu = $('div#'+menu);
					
					$('.ajax-loader-widget', parent).css("visibility", "hidden");
					
					inside = item_menu.find('.portlet-inside:first');
					if( inside.is(":hidden") )
						item_menu.find('a.ui-icon:first').click();
						
					item_menu.find('.info').hide();
					item_menu.find('ol.menu-sortable').prepend( item );
					item_menu.find('.menu-save-bottom').show();
				}
			})
		}
		
		return false;
	})
	
	/* SAVE MENU */
	$('.menu-save-bottom input[type=submit]').live('click', function() {
		var menu = $(this).parents('.portlet:first').attr('id'),
			data = $(this).closest('form').serialize(),
			sort = $(this).closest('ol.menu-sortable').nestedSortable('serialize'),
			img = $(this).parent();
		// tambah parameter	
		param = { 
			act: 'save',
			menu: menu 
		};
		// set parameter
		data += "&" + sort + "&" + $.param(param);
		
		//console.log(data);
		
		$('.ajax-loader-widget', img).css("visibility", "visible");
		
		$.ajax({
			url: ajax_url,
			type: 'POST',
			data: data,
			dataType: 'json',
			success: function( response ){
				if( response )
					$('.ajax-loader-widget', img).css("visibility", "hidden");
			}
		});

		return false;
	})
	/* UPDATE MENU NAME */
	$('.menu-save-top input[type=submit]').live('click', function() {
		var parent = $(this).parents('.portlet:first'),
			menu = parent.attr('id'),
			name = parent.find('input#menu-name').val(),
			img = $(this).parent();
			
		$('.ajax-loader-widget', img).css("visibility", "visible");
		$.ajax({
			url: ajax_url,
			type: 'POST',
			data: { act:'update', name:name, menu:menu },
			dataType: 'json',
			success: function( response ){
				if( response.ok )
				{
					$('.ajax-loader-widget', img).css("visibility", "hidden");
					// ubah Title
					parent.find('h4:first').html(response.name);
					// ubah select option text
					$("select.select-menu").find("option").filter(function() {
						return $(this).val() == menu;
					}).text(response.name);
				}
				
			}
		});
		
		return false;
	})
	/* REMOVE MENU */
	$('.menu-save-top a.menu-remove').live('click', function() {
		var parent = $(this).parents('.portlet:first'),
			menu = parent.attr('id')
			a = parent.find('a.ui-icon'),
			img = $(this).parent().parent();
			
		$('.ajax-loader-widget', img).css("visibility", "visible");
		$.ajax({
			url: ajax_url,
			type: 'POST',
			data: { act:'remove', menu:menu },
			dataType: 'json',
			success: function( response ){
				if( response )
				{
					$('.ajax-loader-widget', img).css("visibility", "hidden");
					// toogle
					a.click();
					// animate remove item
					parent.animate({ backgroundColor: "#fbc7c7" }, "fast").animate({ opacity: "hide" }, "slow");
					// remove select menu
					$("select.select-menu").find("option").filter(function() {
						return $(this).val() == menu;
					}).remove();
				}
				
			}
		});
		
		return false;
	})
})
