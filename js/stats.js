(function () {
  'use strict';

  var margin = {
    top: 20, 
    right: 20, 
    bottom: 30, 
    left: 40
  };

  var width = 500 - margin.left - margin.right;
  var height = 500 - margin.top - margin.bottom;

  var x = d3.scale.ordinal().rangeRoundBands([0, width], 0.1);
  var y = d3.scale.linear().range([height, 0]);

  var xAxis = d3.svg.axis().scale(x).orient('bottom');
  var yAxis = d3.svg.axis().scale(y).orient('left').ticks(5);

  var svg = d3.select('.statsActivated')
      .append('svg')
      .attr('width', width + margin.left + margin.right)
      .attr('height', height + margin.top + margin.bottom)
      .append('g')
      .attr('transform', 'translate(' + margin.left + ',' + margin.top + ')');

  d3.json('get-stats', function(error, data) {
    if (error) {
      throw error;
    }

    x.domain(data.map(function(d) { return d.name; }));
    y.domain([0, d3.max(data, function(d) { return d.value; })]);

    svg.append('g')
       .attr('class', 'x axis')
       .attr('transform', 'translate(0,' + height + ')')
       .call(xAxis);

    svg.append('g')
       .attr('class', 'y axis')
       .call(yAxis)
       .append('text')
       .attr('transform', 'rotate(-90)')
       .attr('y', 6)
       .attr('dy', '.71em')
       .style('text-anchor', 'end')
       .text('Number of items');

    svg.selectAll('.bar')
       .data(data)
       .enter()
       .append('rect')
       .attr('class', 'bar')
       .attr('x', function(d) { return x(d.name); })
       .attr('width', x.rangeBand())
       .attr('y', function(d) { return y(d.value); })
       .attr('height', function(d) { return height - y(d.value); })
       .append('title')
       .text(function(d) { return d.value; });
  });
}());
