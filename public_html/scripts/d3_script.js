var create_artist_circle_graphs = function(max_facebook, current_facebook, facebook_url, max_lastfm, current_lastfm, lastfm_url) {

    var color_facebook = '#3b5998';
    var color_lastfm = '#AD434A';

    data = [
            {name: 'Lastfm', name_of_interaction: 'listeners', max_size: max_lastfm, current_size: current_lastfm, color: color_lastfm, url: lastfm_url},
            {name: 'Facebook', name_of_interaction: 'likes', max_size: max_facebook, current_size: current_facebook, color: color_facebook, url: facebook_url},
            ];

    max_circle_radius = 100;

    margin = {top: 10, right: 0, bottom: 0, left: 0};

    svg_height = 410;
    svg_width = 200;

    svg = d3.select('#artist_d3_circle_graphs')
            .append('svg')
            // .attr('id', "artist_d3_circle_graphs")
            .attr('height', svg_height)
            .attr('width', svg_width);

    svg.selectAll("a")
       .data(data)
       .enter()
       // .append('g')
       .append('a')
       .each(function(d, i) {

            var scale = d3.scale.log().range([0, max_circle_radius]).domain([0.00001, d.max_size]);

            var a = d3.select(this);

            a.attr('xlink:href', d.url);

            a.append('g').attr('width', max_circle_radius * 2)
                         .attr('height', max_circle_radius * 2)
                         .append('circle')
                         .attr('cx', max_circle_radius)
                         .attr('class', 'artist_circle_graph')
                         .attr('cy', max_circle_radius + (max_circle_radius * 2 * i) + (i * margin.top))
                         .attr('r', scale(d.current_size))
                         .attr('fill', d.color);

            var g = a.select('g');

            g.append('text')
              .attr("text-anchor", "middle")
              .attr('x', max_circle_radius)
              .attr('y', max_circle_radius + (max_circle_radius * 2 * i))
              .attr('fill', 'white')
              .attr('opacity', '0.8')
              .attr('class', 'circle_graphs_text_header')
              .text(d.name);

            g.append('text')
              .attr("text-anchor", "middle")
              .attr('x', max_circle_radius)
              .attr('y', max_circle_radius + (max_circle_radius * 2 * i) + 20)
              .attr('fill', 'white')
              .attr('opacity', '0.8')
              .attr('class', 'circle_graphs_text_interactions')
              .text(d.current_size + ' ' + d.name_of_interaction);
        });


};
