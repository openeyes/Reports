function OE_BulletGraph() {if (this.init) this.init.apply(this, arguments); }

OE_BulletGraph.prototype = {
	init : function(params) {
		this.margin = {top: 5, right: 40, bottom: 20, left: 220};
		this.width = 1060 - this.margin.left - this.margin.right;
		this.height = 50 - this.margin.top - this.margin.bottom;
		this.refreshRate = 0;
		this.percentage = false;
		this.show_scale = true;

		for (var i in params) {
			this[i] = params[i];
		}

		var ticks_fn = function(d, scale){
			return scale.ticks(8);
		};

		this.chart = d3.chart.bullet().width(this.width).height(this.height).ticks(ticks_fn).percentage(this.percentage);

		this.svg = d3.select("#"+this.id)
			.selectAll('svg')
				.data(this.initialData)
			.enter().append('svg:svg')
				.attr("class", "bullet")
				.attr("width", this.width + this.margin.left + this.margin.right)
				.attr("height", this.height + this.margin.top + this.margin.bottom)
			.append('svg:g')
				.attr("transform", "translate(" + this.margin.left + "," + this.margin.top + ")")
			.call(this.chart);

		var i=0;
		var initialData = this.initialData;
		var height = this.height + this.margin.top + this.margin.bottom;

		d3.select("#"+this.id).selectAll('svg').map(function() {
			if (!initialData[i].show_scale) {
				$(this).attr('height',height-20);
			}
			i += 1;
		});

		this.title = this.svg.append("svg:g")
				.style("text-anchor", "end")
				.attr("transform", "translate(-6," + this.height / 2 + ")");

		this.title.append("svg:text")
				.attr("class", "title")
				.text(function(d) { return d.title; });

		this.title.append("svg:text")
				.attr("class", "subtitle")
				.attr("dy", "1em")
				.text(function(d) { return d.subtitle; });

		if (this.refreshRate >0) {
			var thiz = this;
			setTimeout(function(){thiz.update();},1);
		} else {
			var thiz = this;
			if (typeof(this.uri) != 'undefined') {
				d3.json(this.uri, function(data) {
					thiz.svg.map(function() {
						return data.shift();
					}).call(thiz.chart.duration(1000));
				});
			}
		}
	},

	update : function() {
		if (typeof(this.uri) != 'undefined') {
			var thiz = this;
			d3.json(this.uri, function(data) {
				if (data != thiz.lastData) {
					thiz.svg.map(function() {
						return data.shift();
					}).call(thiz.chart.duration(1000));
				}
			});
			setTimeout(function(){thiz.update();},this.refreshRate);
		}
	}
};
