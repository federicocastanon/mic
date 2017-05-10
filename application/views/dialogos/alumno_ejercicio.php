<? if (!@$ejercicio): ?>
<h1>Ejercicio no existente</h1>
<? return;endif ?> 
<script type='text/javascript'>
  if (typeof String.prototype.startsWith != 'function') {
    String.prototype.startsWith = function (str){
      return this.slice(0, str.length) == str;
    };
  }
  $(function() {
    $('#more').click(function() {
      $('#historial').toggle();
    });
  });
</script>

<div class="container">

<div class="row-fluid publichead">

    <div class="span10">
<div><img src="../../../assets/img/prismas_public.png" width="78" height="94"><span class="logosmall">Prismas</span> <span class="logolightsmall"> entramados</span></div>
    </div>
    <div class="span2">
        <div class="pull-right"><a href="http://citep.rec.uba.ar" target="_blank"><img src="../../../assets/img/citep-mic-web.png" width="103" height="53" border="0"></a></div>
    </div>

</div>


<div class="row-fluid">
    <div class="span12">
        <div class="span12"><h2><?php echo $ejercicio->consigna ?></h2> </div>
    </div>
</div>
    
    
<div class="row-fluid">
  <div class="span8 prismas_well">
    <h4>Descripción</h4>
    <?= $ejercicio->desarrollo ?>
  </div>
  
  <div class="span4 text-center">
    <h5>Tenga en cuenta:</h5>
    <br /> 
    <p class="preguntas">
      <?= $ejercicio->pregunta_1 ?>
    </p>
    <br>

    <p class="preguntas">
      <?= $ejercicio->pregunta_2 ?>
    </p> 
    <br>

    <p class="preguntas">
      <?= $ejercicio->pregunta_3 ?>
    </p>
    <br>
    <br>
    <? if ($puede_plantear_dialogo):?>
      <a class="btn btn-large" data-toggle="modal" href="#modaldialogos" ><i class="icon-comments"></i> Crear diálogo</a>
    <? else: ?>
      <h4><?= $razon_no_dialogo?></h4>
    <? endif; ?> 
    <br /> 
    <br /> 
    <? if ($respuestas): ?>
    <?php endif ?>
  </div>
  
  
  
<? if ($respuestas): ?>
  <div class="row-fluid">
      <div class="span12" id='historial'> 
        <table class='table'> 
          <thead>
            <th>Fecha</th> 
            <th>Calificación</th> 
            <th></th> 
          </thead>
          <tbody> 
            <? foreach($respuestas as $r) : ?>
              <tr>
                <td><?= date("d-m-Y",strtotime($r->updated_at)) ?></td>
                <td>
                  <? if ($r->calificacion): ?>
                    <div class='semaforo'> 
                      <?foreach (['rojo', 'amarillo', 'verde'] as $color): ?>
                        <div class='<?= ($r->calificacion == $color)?$color:'apagado' ?>'></div>
                      <? endforeach ?>
                    </div> 
                  <? else: ?>
                    No calificado
                  <? endif ?>
                </td>
                <td>
                  <a href='#reviewModal_<?= $r->id ?>' data-toggle='modal' class='btn'>
                    <? if ($r->calificacion): ?>
                      <i class="icon-search"></i> ver planteo y corrección
                    <? else :?>
                      <i class="icon-search"></i> ver planteo
                    <? endif ?> 
                  </a>
                </td>
              </tr>
              <div id="reviewModal_<?= $r->id ?>" class="modal hide fade reviewModal" style="display: none;">
                <form action="<?= base_url('/dialogos/alumno_responder/' . $hash)?>" method='post'>
                  <div class="modal-header">
                    <a class="close" data-dismiss="modal">×</a>
                  </div>
                  <div class="modal-body">    
                    <p class='mlarge'><?= $r->campo_1 ?></p>
                  </div>
                  <? if ($r->calificacion): ?>
                    <div class="modal-chat">
                      <h6>Correción del profesor</h6>
                      <div class='semaforo'> 
                        <?foreach (['rojo', 'amarillo', 'verde'] as $color): ?>
                          <div class='<?= ($r->calificacion == $color)?$color:'apagado' ?>'></div>
                        <? endforeach ?>
                      </div>
                      
                      <span> - <?= $r->calificacion_text ?> </span> 
                    </div>
                  <? endif ?>
                </form>
              </div>
            <? endforeach ?>
          </tbody>
        </table>

      </div>
  </div>
    
  </div>
<? endif; ?>
<? if ($puede_plantear_dialogo):?>
  <div id="modaldialogos" class="modal hide fade" style="display: none;">
    <form action="<?= base_url('/dialogos/alumno_responder/' . $hash)?>" method='post'>
      <div class="modal-header">
        <a class="close" data-dismiss="modal">×</a>
        <h3><?= $ejercicio->consigna ?></h3>
      </div>
      <div class="modal-body">    
        <p><textarea name='campo_1' id="textarea" class="input-xlarge globo" rows="2" placeholder="Planteo"></textarea></p>
        <div class="chat-arrowglobo"></div>
      </div>
      <div class="modal-footer">
        <a class="btn btn-large">enviar</a>
      </div>
      <? if ($respuestas): ?>
        <div class="modal-chat">
          <h6>Ultima correción del profesor</h6>
          <div class='semaforo'> 
            <?foreach (['rojo', 'amarillo', 'verde'] as $color): ?>
              <div class='<?= ($respuestas[0]->calificacion == $color)?$color:'apagado' ?>'></div>
            <? endforeach ?>
          </div> 
          <span> - <?= $respuestas[0]->calificacion_text ?> </span> 
        </div>
      <? endif; ?> 
    </form>
  </div>
<? endif; ?>