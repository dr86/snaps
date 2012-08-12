var base_url = $('#base_url').attr( 'action' );

function parseDate(date) 
{
	var months = new Array(
		"January", 
		"February", 
		"March", 
		"April", 
		"May", 
		"June", 
		"July", 
		"August", 
		"September", 
		"October", 
		"November", 
		"December"
	);

	var y = date.substr(0, 4);
	var m = date.substr(5, 1);
	var d = date.substr(6, 2);

	return d + ' ' + months[m - 1] + ' ' + y;
}

function drawBarChart() 
{
	var data = new google.visualization.DataTable();
	data.addColumn('string', 'Day');
	data.addColumn('number', 'Visits');
	$.getJSON( base_url +'/admin/ajax/analytics', function(analytics) {
		
		$('#analytics-error-message').html('');
		
		if( !analytics.exception )
		{
			data.addRows(analytics.visits.length);

			$.each(analytics.visits, function(i, key) {
				data.setValue(i, 0, parseDate(key.date));
				data.setValue(i, 1, key.visits);
			});
	
			var chart = new google.visualization.ColumnChart(document.getElementById('barchart_div'));
			chart.draw(data, {
				'width' : 900,
				'height' : 350,
				'is3D' : true,
				'title' : 'Visits'
			});
			
			$('#analytics-msg').hide();
			
		} else {
			$('#barchart_div').html( '<div class="notification attention">'+
				'<a class="close-notification" rel="tooltip" title="Hide Notification" href="#">x</a>'+
				'<p>' + analytics.exception + '&nbsp;<a href="'+ base_url +'/admin/option">setting here</a>'+
				'</p></div>');
		}

	});
}

function drawPieChart() 
{
	var data = new google.visualization.DataTable();
	var tb;
	data.addColumn('string', 'Referrer');
	data.addColumn('number', 'Visits');
	$.getJSON( base_url +'/admin/ajax/analytics', function(analytics) {

		$('#loading_piechart').hide();
		
		if( !analytics.exception )
		{
			data.addRows(analytics.referrers.length);

			tb = "<table class='zebra-striped'>" + "<tr><th style='border-top: 1px solid #DDDDDD;'>City</th>" + "<th style='border-top: 1px solid #DDDDDD;'>Visits</th></tr>";
	
			$.each(analytics.referrers, function(i, key) {
				data.setValue(i, 0, key.city);
				data.setValue(i, 1, key.visits);
	
				tb += "<tr><td style='border-top: 1px solid #DDDDDD;'>" + key.city + "</td>" + "<td style='border-top: 1px solid #DDDDDD;'>" + key.visits + "</td></tr>";
			});
	
			tb += "</table>";
	
			var chart = new google.visualization.PieChart(document.getElementById('piechart_div'));
			chart.draw(data, {
				width : 480,
				height : 300,
				is3D : true,
				title : 'Visitors by City'
			});
			$('#referrer_div').html(tb);
			
			$('.stats_overview').show();
		}
		
	});

}

google.load("visualization", "1.0", {
	packages : ["corechart"]
});
google.setOnLoadCallback(drawBarChart);
google.setOnLoadCallback(drawPieChart);
