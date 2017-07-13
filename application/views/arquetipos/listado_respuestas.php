
<link href="<?php echo assets_url('css/jquery-ui.css')?>" media="all" rel="stylesheet" type="text/css" />
<script src="<?php echo assets_url('js/jquery-ui.js')?>"></script>
    <script src="<?php echo assets_url('js/d3.min.js');?>"></script>
    <script type="text/javascript" src="<?php echo assets_url('js/d3.layout.cloud.js');?>"></script>
    <script src="<?php echo assets_url('js/jquery-ui.js')?>"></script>
    <script>
        function seleccionarTodas(imgId){
            $( ".cheq" + imgId ).prop('checked', $("#tp" +imgId)[0].checked);
        }
        $( document ).ready(function() {
            $("#accordion").accordion({
                header: 'div.panelGrisClaro',
                active: false,
                collapsible: true,
            });
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
            if(!strings) {
                $('#nubecontainer').css('display', 'none');
                return;
            }

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

            var excluidas = ["", " "," a ", " ante "," bajo "," cabe "," con "," contra "," de "," desde "," en "," entre "," hacia "," hasta "," para "," por "," según "," sin "," so"," sobre "," tras "," la "," las "," el "," los "," este "," esto "," estos "," esta "," estas "," esa "," esas "," ese "," eso "," esos "," ella "," ellas "," ellos "," tu "," vos "," yo "," vosotros "," vosotras "," nosotros "," nosotros "," mi "," mio "," mia "," mios "," mias "," tuyo "," tuya "," tuyas "," tuyos "," vuestro "," vuesta "," vuestras "," vuestros "," suyo "," suyos "," suyas "," nuestro "," nuestra "," nuestros "," nuestras "," su "," tu "," algún "," alguna "," algunos "," algunas "," ningún "," ninguna "," ninguno "," una "," unas"];

        function excluirComunes(palabra){

            if(excluidas.indexOf(" " + palabra + " " ) > -1){
                return true;
            }


            return false;
        }

        function getData(){
            var data = $('#dataCruda')[0].innerText;
            data = data.trim();
            calculateCloud(processData(data));
        }

            getData();
        });
    </script>
<section>


    <span id="dataCruda" style="display: none;"><?= $crudoRespuestas ?></span>
    <div class="row"  id="nubecontainer">
        <div class="col-md-12">
            <section id="cloud">
            </section>
        </div>
    </div>
    <div class="col-md-12">
        <h2><?php echo $ejercicio->consigna ?></h2>
    </div>
    <div class="col-md-12">
        <h4><?php echo $ejercicio->desarrollo ?></h4>

        <div class="spacer"></div>
    </div>
    <div class="col-md-12">
        <?php if ($ejercicio->nube) :?>
            <a href='<?php echo base_url('/arquetipos/ocultar_nube/' . $ejercicio->id)?>' class="btn btn-default ">Ocultar nube</a>
        <?php else:?>
            <a href='<?php echo base_url('/arquetipos/publicar_nube/' . $ejercicio->id)?>' class="btn btn-default ">Publicar nube</a>
        <?php endif;?>
    </div>

            <div class="col-md-6 botonera">
                <a class="btn btn-primary pull-left"
                   href='<?php echo base_url('/arquetipos/alumno_ejercicio/' . $ejercicio->id)?>'>
                    <i class="icon-list-alt"></i> Responder
                </a>
            </div>
            <div class="col-md-6 botonera">
                <a class="btn btn-default pull-right" href="<?php echo base_url('/arquetipos')?>"><i class="fa fa-arrow-left"></i> Volver</a>
            </div>
    <div id="accordion">
    <?php

    foreach ($imagenes as $imagen_id => $imagen): ?>

        <form method="post" action='<?php echo base_url('/arquetipos/publicarTodas/' . $ejercicio->id)?>'>
            <input type="hidden" id="<?= $imagen_id ?>" name="imgId" value="<?= $imagen_id ?>"/>
            <div class="col-md-12 panelGrisClaro">
                <div class="col-md-12">
                    <h4 class="tituloFoco"><?= $imagen['titulo'] ?></h4>
                </div>
                <div class="col-md-12">

                    <div class="col-md-3">
                        <img id="img_small_<?php echo $imagen_id?>" class='imagenModal'
                             src="<?= $imagen['url']?>"  alt="">
                    </div>
                    <div class="col-md-9">
                        <?php
                        foreach ($ejercicio->preguntas as $preg): ?>
                            <h5 class="preguntaFoco"><?= $preg->pregunta?></h5>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>
            <div class="col-md-12">
                <div class="col-md-6">
                </div>
                <div class="col-md-6" >
                    <input onclick="seleccionarTodas(<?= $imagen_id ?>)"
                                type="checkbox" id="tp<?= $imagen_id ?>" name="tp" value="tp"  /> <b>Seleccionar todas </b>
                    <button class="btn btn-large" type="submit">Publicar las chequeadas</button>
                </div>
                <div class="col-md-12 contenedorRespuestasPorPublicar" >
                <?php

                foreach ($ejercicio->preguntas as $pregunta): ?>
                    <div class="col-md-12 preguntaFoco"><?= $pregunta->pregunta ?></div>

                    <?php
                        $rc = 0;
                    if (isset($respuestas[$imagen_id][$pregunta->id])){
                        foreach ($respuestas[$imagen_id][$pregunta->id] as $resp):
                            $rc++;?>
                            <div class="col-md-12" style="color: <?php if($resp->publico){?> blue <?php }else{?> green <?php }?>;
                                background-color: <?php if($rc %2 == 1){?> #EFF0F1 <?php }else{?> #FFFFFF <?php }?>">
                              <div class="col-md-6" >
                                  <?= $resp->respuesta ?> <br> <i>(<?= $resp->email ?> )</i>
                              </div>
                                <div class="col-md-6">
                                    <input type="checkbox" class="cheq<?= $imagen_id ?>" id="<?php echo $resp->respuesta_id ?>" name="pub[]" value="<?php echo $resp->respuesta_id ?>"
                                        <?php if($resp->publico){?>
                                                checked
                                        <?php }?>
                                                    />
                                </div>
                            </div>
                    <?php endforeach; } //respuestas?>
                <?php endforeach; //preguntas ?>
                </div>
                <div class="col-md-12">
                    <p class="pull-right"><a href="#top" class="subir">Arriba</a></p>
                </div>
            </div>
                </form>


    <?php endforeach //imagenes ?>
</div> <!-- accordion-->
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


<div class="modal hide fade" id="modalRespuestas">
  <div class="modal-header">
    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
    <h3>Respuestas</h3>
  </div>
  <div class="modal-body">
    <p>Reemplazado por ajax</p>
  </div>
</div>
