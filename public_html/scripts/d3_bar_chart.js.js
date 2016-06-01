var transform_format_of_data_from_top_artist = function(listOfObjectArtists, type) {
	// type is "likes" or "listeners"
	// listOfObjectArtists is a list of artist objects like:
	// [{name: "nome1", number_of_lastfm_listeners: 12, number_of_facebook_likes: 23}, {name: "nome2", number_of_lastfm_listeners: 23, number_of_facebook_likes: 53}]

	var data = {name: [],
		        series: [{label: 'interactions',
		                  values: []}]
		        };

	for (var i = 0; i < listOfObjectArtists.length; i++) {

		var artist_info = listOfObjectArtists[i];
		data.name.push(artist_info.name);

		if (type == "listeners") {
			data.series[0].values.push(artist_info.number_of_lastfm_listeners);
		} else if (type == "likes") {
			data.series[0].values.push(artist_info.number_of_facebook_likes);
		}
	}


	return data;
};


var create_top_artist_bar_chart = function(data, barcolor, dom_element) {

	var chartWidth       = 400,
	    barHeight        = 16,
	    groupHeight      = barHeight * data.series.length,
	    gapBetweenGroups = 10,
	    spaceForLabels   = 150,
	    spaceForLegend   = 100;

	// Zip the series data together (first values, second values, etc.)
	var zippedData = [];
	for (var i=0; i<data.name.length; i++) {
	  for (var j=0; j<data.series.length; j++) {
	    zippedData.push(data.series[j].values[i]);
	  }
	}

	while (spaceForLabels + chartWidth + spaceForLegend > $(window).width()) {
		chartWidth = chartWidth - (chartWidth / 10);
		spaceForLabels = spaceForLabels - (spaceForLabels / 10);
		spaceForLegend = spaceForLegend - (spaceForLegend / 10);
	}

	var chartHeight = barHeight * zippedData.length + gapBetweenGroups * data.name.length;

	var x = d3.scale.linear()
	    .domain([0.001, d3.max(zippedData)])
	    .range([0.001, chartWidth]);

	var y = d3.scale.linear()
	    .range([chartHeight + gapBetweenGroups, 0]);

	//var yAxis = d3.svg.axis()
	//    .scale(y)
	//    .tickFormat('')
	//    .tickSize(0)
	//    .orient("left");

	// Specify the chart area and dimensions
	var chart = d3.select(dom_element)
	    .attr("width", spaceForLabels + chartWidth + spaceForLegend)
	    .attr("height", chartHeight);

	// Create bars
	var bar = chart.selectAll("g")
	    .data(zippedData)
	    .enter().append("g")
	    .attr("transform", function(d, i) {
	      return "translate(" + spaceForLabels + "," + (i * barHeight + gapBetweenGroups * (0.5 + Math.floor(i/data.series.length))) + ")";
	    });

	// Create rectangles of the correct width
	bar.append("rect")
	    .attr("fill", barcolor)
	    .attr("class", "bar")
	    .attr("width", x)
	    .attr("height", barHeight - 1);

	// Add text label in bar
	bar.append("text")
	    .attr("x", function(d) { return x(d) + 65;})
	    .attr("y", barHeight / 2)
	    .attr("fill", "red")
	    .attr("dy", ".35em")
	    .text(function(d) { return d; });

	// Draw labels
	bar.append("text")
	    .attr("class", "label")
	    .attr("x", function(d) { return - 10; })
	    .attr("y", groupHeight / 2)
	    .attr("dy", ".35em")
	    .text(function(d,i) {
	      if (i % data.series.length === 0) {
	        return data.name[Math.floor(i/data.series.length)];
	      }
	      else {
	        return "";
	      }});


	chart.append("g")
	      .attr("class", "y axis")
	      .attr("transform", "translate(" + spaceForLabels + ", " + -gapBetweenGroups/2 + ")");
	      // .call(yAxis);

};

var barchart_top_artists = function(listOfObjectArtists, type, dom_element) {
	// type is "likes" or "listeners"
	// listOfObjectArtists is a list of artist objects like:
	// [{name: "nome1", number_of_lastfm_listeners: 12, number_of_facebook_likes: 23}, {name: "nome2", number_of_lastfm_listeners: 23, number_of_facebook_likes: 53}]

	var data = transform_format_of_data_from_top_artist(listOfObjectArtists, type);

	var barcolor;
	if (type == "listeners") {
		barcolor = color.lastfm;
	} else if (type == "likes") {
		barcolor = color.facebook;
	}

	create_top_artist_bar_chart(data, barcolor, dom_element);

};
