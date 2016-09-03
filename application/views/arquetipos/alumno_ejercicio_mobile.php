<? if (!@$ejercicio->consigna): ?>
<h1>Ejercicio no existente</h1>
<? return;endif ?> 
<script type='text/javascript'>
  if (typeof String.prototype.startsWith != 'function') {
    String.prototype.startsWith = function (str){
      return this.slice(0, str.length) == str;
    };
  }
  $(document).ready(function() {
    $("a.openForm").click(function() {
      $("#preg_" + $(this).data('id')).toggle();
      return false;
    });  
    
    var div = img.parents('.modalResponder');
      div.find('textarea').each(function() {
        if ($(this).val().length <= 10 || completed == false) {
          completed = false;
        }
      })
      if (!completed) {
        div.find('div.alert').text('Complete las 3 preguntas con al menos 10 caracteres').removeClass('hide');
        return false;
      } else { 
        div.find('div.alert').addClass('hide');
      }  
  });

</script>


<div class="row">
  <div class="span12">
    <h2>
      <?php echo $ejercicio->consigna ?>
      <a class="btn btn-primary" href="<?= base_url("/arquetipos/alumno_consigna/$hash")?>" role="button" data-toggle="modal">
        <span class="i">i</span>
      </a>
    </h2>
  </div>
</div>

<div class="container">  
<?php foreach ($imagenes as $i=>$img):
        $disabled = (isset($respuestas[$img->id])); ?>  
    <div class="row-fluid">
      <div class="col-lg-4">
        <a class="thumbnail openForm" data-id='<?= $img->id?>' href="#">
          <img <?php if (isset($respuestas[$img->id])): ?> class='grayscale' <?php endif;?> id='img_<?php echo $img->id?>'
            src="<?php echo $img->imagen_ubicacion?>" width='140px' height='140px' alt="" />
        </a>
        <p class="text-info"><?php echo $img->titulo ?></p>
      </div>
    </div>
    <div class='row-fluid' style="display:none;" id='preg_<?= $img->id ?>'>
      <form method='post'> 
        <input type='hidden' name='img_id' value='<?= $img->id?>' />
        <?php foreach($preguntas as $preg): ?>
          <p><?php echo $preg->pregunta?></p>          
          <div class="control-group">
            <div class="controls">
              <textarea name="respuesta[<?php echo $preg->id?>]" rows="2" 
                class="input-xlarge" id="textarea2"
                <?php if ($disabled):?>disabled<?php endif?>><?php echo @$respuestas[$img->id][$preg->id]?></textarea>
            </div>
            <div class="alert alert-error pull-left hide">
              Complete las 3 preguntas para continuar.
            </div>
          </div>   
        <?php endforeach; ?>
        <?php if (!$disabled):?>
          <button href='#' class='btn btn-large pull-right enviar'>
            enviar respuestas
          </button>
        <?php endif?>
      </form>
    </div>
<?php endforeach ?>
</div>
