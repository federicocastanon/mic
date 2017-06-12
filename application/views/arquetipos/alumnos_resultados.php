<div class="container">
    <script src="<?php echo assets_url('js/d3.min.js');?>"></script>
    <script type="text/javascript" src="<?php echo assets_url('js/d3.layout.cloud.js');?>"></script>
    <script>
        function seleccionarTodas(imgId){
            $( ".cheq" + imgId ).prop('checked', $("#tp" +imgId)[0].checked);
        }
        $( document ).ready(function() {

            /* D3  */

            var width = Math.floor(document.body.clientWidth * 0.8);
            var height =300;

            var typeFace = 'Gorditas';
            var minFontSize = 6;
            var colors = d3.scale.category20b();

            var svg = d3.select('#cloud').append('svg')
                .attr('width', width)
                .attr('height', height)
                .append('g')
                .attr('transform', 'translate('+width/2+', '+height/2+')');


            function calculateCloud(wordCount) {
                d3.layout.cloud()
                    .size([width, height])
                    .words(wordCount)
                    .rotate(function() {
                        //var rot = (Math.floor(Math.random()*2)) * 90;
                        var rot = 0;
                        return rot;}
                ) // 0 or 90deg
                    .font(typeFace)
                    .fontSize(function(d) {
                        var tam = d.size * minFontSize;
                        return tam }).on('end', drawCloud)
                    .start();
            }

            function drawCloud(words) {
                var vis = svg.selectAll('text').data(words);

                vis.enter().append('text')
                    .style('font-size', function(d) { return d.size + 'px'; })
                    .style('font-family', function(d) { return d.font; })
                    .style('fill', function(d, i) { return colors(i); })
                    .attr('text-anchor', 'middle')
                    .attr('transform', function(d) {
                        return 'translate(' + [d.x, d.y] + ')rotate(' + d.rotate + ')';
                    })
                    .text(function(d) { return d.text; });
            }

            /* convert the raw data into a proper form of key/value obj to pass to d3.layout.cloud
             it should return [{text: 'str', size: n},...]
             */

            function processData(strings) {
                if(!strings) return;

                // convert the array to a long string
                // strings = strings.join(' ');

                // strip stringified objects and punctuations from the string
                strings = strings.toLowerCase().replace(/object Object/g, '').replace(/[\+\.,\/#!$%\^&\*{}=_`~]/g,'');

                // convert the str back in an array
                strings = strings.split(' ');

                // Count frequency of word occurance
                var wordCount = {};

                for(var i = 0; i < strings.length; i++) {
                    if(excluirComunes(strings[i])){
                        continue;
                    }
                    if(!wordCount[strings[i]])
                        wordCount[strings[i]] = 0;

                    wordCount[strings[i]]++; // {'hi': 12, 'foo': 2 ...}
                }

                //console.log(wordCount);

                var wordCountArr = [];
                for(var prop in wordCount) {
                    wordCountArr.push({text: prop, size: wordCount[prop]});
                }


                return wordCountArr;
            }

            var excluidas = ["", " "];

            function excluirComunes(palabra){

                if(excluidas.indexOf(palabra) > -1){
                    return true;
                }


                return false;
            }

            function getData(){
                var data = $('#dataCruda')[0].innerText;
                calculateCloud(processData(data));
            }

            getData();
        });
    </script>
	<div class="row-fluid publichead">
        <div class="span10">
            <div>
                <img src="<?= assets_url('/img/foco_public.png') ?>" width="76" height="92">
                <span class="logosmall">Focos</span> <span class="logolightsmall"> en juego</span>
            </div>
        </div>
        <div class="span2">
            <div class="pull-right">
                <a href="http://citep.rec.uba.ar" target="_blank">
                    <img src="<?= assets_url('/img/citep-mic-web.png')?>" width="110" height="54">
                </a>
            </div>
        </div>
    </div>
    <span id="dataCruda" style="display: none;"><?= $crudoRespuestas ?></span>
    <?php if($ejercicio->nube && strlen($crudoRespuestas) > 2): ?>
    <section style="float: left; width: 30%; height: 200px" id="cloud">
    </section>
    <?php endif;?>
    <div class="row-fluid">
	   <div class="page-header tituloWrapper">
	       <div class="row"><h1 class="pull-left titulo"><?= $ejercicio->consigna ?></h1></div>
            <div class="row"><h4 class="pull-left titulo"><?= $ejercicio->desarrollo ?></h4></div>
           <a class="btn btn-lg btn-default pull-right" href="<?php echo base_url('/arquetipos/alumno_ejercicio/' . $ejercicio->public_id)?>"><i class="fa fa-arrow-left"></i> Volver</a>

        </div>
    </div>

    <?php foreach ($imagenes as $imagen_id => $imagen): ?>
        <div class="publicwell">
            <table width="100%" border="0" cellspacing="0" cellpadding="0">
                <tr>
                    <td><h4><?= $imagen['titulo'] ?></h4></td>
                    <td width="40%"><img src="<?= $imagen['url']?>" alt='foto de <?= $imagen['titulo']?>' width='280'></td>
                </tr>
            </table>
        </div>

        <div class="row-fluid">
                <div class="span6">

                    <?php
                    foreach ($ejercicio->preguntas as $pregunta): ?>
                        <div style="float: left; width: 100%"><strong><?= $pregunta->pregunta ?></strong></div>
                        <?php
                        if (isset($respuestas[$imagen_id][$pregunta->id])){
                            foreach ($respuestas[$imagen_id][$pregunta->id] as $resp): ?>
                                <div  style="float: left; width:100%; min-height: 40px; color: <?php if($resp->publico){?> blue <?php }else{?> green <?php }?>">
                                    <div style="width:100%;  float: left">  <?= $resp->respuesta ?> </div>
                                </div>
                        <?php endforeach; }else{ ?>
                            <div  style="float: left; width:100%; min-height: 40px; color: lightslategrey">
                                <div style="width:100%;  float: left"> Todavía no hay respuestas publicadas </div>
                            </div>
                        <?php }?>
                    <?php endforeach;  ?>
                </div>

        </div>
    <?php endforeach //imagenes ?>

	<footer id="footer">
        <p class="pull-right"><a href="#top">Arriba</a></p>       
	</footer>    
</div>
