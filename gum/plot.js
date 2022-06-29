function do_plot(elem, file, do_sort){
	
	
	
	
	var svg = d3.select(elem),
		margin = {top: 20, right: 20, bottom: 30, left: 180},
		width = +svg.attr("width") - margin.left - margin.right,
		height = +svg.attr("height") - margin.top - margin.bottom;
	  
	var tooltip = d3.select("body").append("div").attr("class", "toolTip");
	  
	var x = d3.scaleLinear().range([0, width]);
	var y = d3.scaleBand().range([height, 0]);

	var g = svg.append("g")
			.attr("transform", "translate(" + margin.left + "," + margin.top + ")");
	  
	d3.json(file, function(error, data) {
		if (error) throw error;


	//var colors = ["#ffffd9", "#edf8b1", "#c7e9b4", "#7fcdbb", "#41b6c4", "#1d91c0", "#225ea8", "#253494", "#081d58"];
	var colors = ["#081d58",  "#1d91c0","#41b6c4",  "#fff7bc", "#ecdc61","#ecdc61","#fdbb84","#e34a33"]// "#c7e9b4","#edf8b1",      "#ffffd9"  ];

	var colorScale = d3.scaleQuantize()
	  .domain([0, d3.max(data, function(d) {
		return d.value;
	  })])
	  .range(colors);

	var colorFixed = function(d){return "red";}
	  
	
		if (do_sort){
			data.sort(function(a, b) { return a.value - b.value; });
		}
	  
		x.domain([0, d3.max(data, function(d) { return d.value; })]);
		y.domain(data.map(function(d) { return d.key; })).padding(0.1);

		g.append("g")
			.attr("class", "x axis")
			.attr("transform", "translate(0," + height + ")")
			.call(d3.axisBottom(x));
			//.call(d3.axisBottom(x).ticks(5).tickFormat(function(d) { return parseInt(d / 1000); }).tickSizeInner([-height]));

		g.append("g")
			.attr("class", "y axis")
			.call(d3.axisLeft(y));

		g.selectAll(".bar")
			.data(data)
		  .enter().append("rect")
			.attr("class", "bar")
			.attr("x", 0)
			.attr("height", y.bandwidth())
			.attr("y", function(d) { return y(d.key); })
			.attr("width", function(d) { return x(d.value); })
			.on("mousemove", function(d){
				tooltip
				  .style("left", d3.event.pageX - 50 + "px")
				  .style("top", d3.event.pageY - 70 + "px")
				  .style("display", "inline-block")
				  .html((d.key) + "<br>" + (d.value));
			})
				.on("mouseout", function(d){ tooltip.style("display", "none");});
				
		g.selectAll("rect")
		.style("fill", function(d) {
			return colorScale(d.value);
			//return colorFixed(d);
		})

	});
}