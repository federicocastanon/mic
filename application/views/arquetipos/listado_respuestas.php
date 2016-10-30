<div class="container">

    <script src="//cdnjs.cloudflare.com/ajax/libs/d3/3.4.11/d3.min.js"></script>
    <script type="text/javascript" src="<?php echo assets_url('js/d3.layout.cloud.js');?>"></script>
    <script>

        $( document ).ready(function() {
        function seleccionarTodas(imgId){
            $( ".cheq" + imgId ).prop('checked', $("#tp" +imgId)[0].checked);
        }
        /* D3  */

        var width = document.body.clientWidth * 0.8;
        var height =300;

        var typeFace = 'Gorditas';
        var minFontSize = 24;
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
                .rotate(function() { return ~~(Math.random()*2) * 90;}) // 0 or 90deg
                .font(typeFace)
                .fontSize(function(d) { return d.size * minFontSize; })
                .on('end', drawCloud)
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
        function getData(){
            var data = $('#dataCruda')[0].innerText;
            calculateCloud(processData(data));
        }

            getData();
        });
    </script>
<section>
    <span id="dataCruda" style="display: none;"><?= $crudoRespuestas ?></span>
    <section style="float: left; width: 30%; height: 200px" id="cloud">
    </section>
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
    <div class="row-fluid">
        <div class="page-header">
            <h1><?= $ejercicio->consigna ?></h1>
        </div>
    </div>
    <div class='row-fluid'>
        <div class="span12 public">
            <?php
            foreach ($ejercicio->preguntas as $pregunta): ?>
                <p><strong><?= $pregunta->pregunta ?></strong> </p>
            <?php endforeach;  ?>
        </div>
    </div>
    <div class='row-fluid'>
        <div class="span12 public">
            <a class="btn btn-small btn-primary pull-left"
               href='<?php echo base_url('/arquetipos/alumno_ejercicio/' . $ejercicio->id)?>'>
                <i class="icon-list-alt"></i> Responder
            </a>
            <a class="btn pull-right" href='#' onClick='window.print();'><i class="icon-print"></i> imprimir</a><br>

        </div>
    </div>
    <?php foreach ($imagenes as $imagen_id => $imagen): ?>
        <form method="post" action='<?php echo base_url('/arquetipos/publicarTodas/' . $ejercicio->id)?>'>
        <div class="publicwell">
            <table width="100%" border="0" cellspacing="0" cellpadding="0">
                <tr>
                    <td><h4><?= $imagen['titulo'] ?></h4></td>
                    <td width="40%"><img src="<?= $imagen['url']?>" alt='foto de <?= $imagen['titulo']?>' width='280'></td>
                </tr>
            </table>
        </div>

        <div class="row-fluid">
            <div style="padding-left: 30%; float: left">
                <div style="float: left">
                    Seleccionar todas <br/>
                    <input onclick="seleccionarTodas(<?= $imagen_id ?>)" style="margin-left: 45%" type="checkbox" id="tp<?= $imagen_id ?>" name="tp" value="tp" />
                </div>
                <button style="float: right" class="btn btn-large" type="submit">Publicar las chequeadas</button>
            </div>
            <div class="span6" style="float: left; width: 60%;">
                <?php
                foreach ($ejercicio->preguntas as $pregunta): ?>
                    <div style="float: left; width: 60%"><strong><?= $pregunta->pregunta ?></strong></div>

                <?php
                if (isset($respuestas[$imagen_id][$pregunta->id])){
                    foreach ($respuestas[$imagen_id][$pregunta->id] as $resp): ?>
                        <div  style="float: left; width:97%; min-height: 40px; color: <?php if($resp->publico){?> blue <?php }else{?> green <?php }?>">
                          <div style="width:60%;  float: left">  <?= $resp->respuesta ?> <br> (<?= $resp->email ?> )</div>
                            <div style=" float: left">
                                <input type="checkbox" class="cheq<?= $imagen_id ?>" id="<?php echo $resp->respuesta_id ?>" name="pub[]" value="<?php echo $resp->respuesta_id?>"
                        <?php if($resp->publico){?>
                                checked
                        <?php }?>
                                    />
                            </div>
                        </div>
                    <?php endforeach; } ?>
                <?php endforeach;  ?>
            </div>


        </div>
       </form>
    <?php endforeach //imagenes ?>

<div id="emailModal" class="modal hide fade" tabindex="-1" role="dialog">
    <form action="<?= base_url('/arquetipos/enviar_devolucion/')?>" method="post">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
        <h3 id="myModalLabel">Enviar devolución por email</h3>
      </div>
      <div class="modal-body">
        <input type='hidden' name='alumno_id' value='' /> 
        <textarea name='texto' class='span5' rows='5'></textarea>
      </div>
      <div class="modal-footer">
        <a href='#' class="btn" data-dismiss="modal" aria-hidden="true">Cancelar</a>
        <button class="btn btn-primary">Enviar</button>
      </div>
    </form>
</div>

<script>

$('.devolucion').click(function() { 
    $("#emailModal").find("input[name=alumno_id]").val($(this).data('alumno-id'));
    $('#emailModal').modal('toggle');
})
</script>


    <footer id="footer"><p class="pull-right">
      <a href="#top">
        Arriba
    </a></p>       
    </footer>
    </section>
</div>

<div class="modal hide fade" id="modalRespuestas">
  <div class="modal-header">
    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
    <h3>Respuestas</h3>
  </div>
  <div class="modal-body">
    <p>Reemplazado por ajax</p>
  </div>
</div>
