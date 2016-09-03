<section>
<div class="row-fluid">
	<div class="span8">
	  <h3><?= $proyecto->nombre ?></h3>
      </div>
      	<div class="span2">
	       <a href="<?= base_url("/proyectos/fichas/{$proyecto->id}") ?>" class="links"><i class="fa fa-long-arrow-left"></i> ver proyecto</a>
        </div>
        <? if ($this->user->has_permission('proyectos')) :?>
            <div class="span2">
                <a href="<?= base_url("/proyectos/editar/{$proyecto->id}") ?>" class="links"><i class="fa fa-long-arrow-left"></i> ver secciones</a>
            </div>
        <? endif ?>
</div>


    <div id='graph' class='span12'></div>
</section>
<script src='http://d3js.org/d3.v3.min.js'></script>
<script>
var width = 1170,
    height = 650,
    radius = 200;

var handleClick = function(d) { 
    window.location = '<?= base_url("/proyectos/editar_subseccion/") ?>/' + d.data.id;
    //console.log(d.data.id);
}

var data = <?= json_encode($subsecciones) ?>;

var arc = d3.svg.arc().outerRadius(radius);

var pie = d3.layout.pie().value(function(d) { return 1; });

var svg = d3.select("#graph").append("svg")
    .datum(data)
    .attr("width", width)
    .attr("height", height)
    .append("g")
    .attr("transform", "translate(" + width / 2 + "," + height / 2 + ")");

var arcs = svg.selectAll("g.arc")
    .data(pie)
    .enter().append("g")
    .attr("class", "arc")
    .on('click', handleClick);

arcs.append("path")
    .transition()
    .ease("bounce")
    .duration(1500)
    .attr("fill", function(d, i) { return d.data.color })
    .transition()
    .attrTween("d", tweenDonut);

function tweenDonut(b) {
    if (b.data.completed) { 
       b.innerRadius = radius * .5
    } else { 
       b.innerRadius = radius * .7
    }
    var i = d3.interpolate({innerRadius: 0}, b);
    return function(t) { return arc(i(t)); };
}

/*
var title = d3.select("svg").append("text")
        .transition()
        .delay(2500)
		//.text("<?= $percentage?>%")
		.attr("x", "50%")
		.attr("y", "50%")
		.attr("text-anchor", "middle")
		.attr("font-size", "64px")
		.attr("font-weight", "300")
		.attr("fill", "black");
*/  
    
arcs.append("svg:text")                                     //add a label to each slice
        .transition()
        .delay(2500)
        .attr("transform", function(d) {                    //set the label's origin to the center of the arc
            //we have to make sure to set these before calling arc.centroid
            d.innerRadius = 400;
            d.outerRadius = 500;
            return "translate(" + arc.centroid(d) + ")";        //this gives us a pair of coordinates like [50, 50]
        })
        .attr("text-anchor", "middle")                          //center the text on it's origin
        .attr("font-size", "13px")
        .attr("class", "title")
        .attr("font-weight", "300")
        .text(function(d, i) { return data[i].name; });


</script>

