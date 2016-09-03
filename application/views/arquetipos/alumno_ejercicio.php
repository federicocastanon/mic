<?php if (!$ejercicio->consigna): ?>
<h1>Ejercicio no existente</h1>
<?php return;endif ?>
<script type='text/javascript'>
  if (typeof String.prototype.startsWith != 'function') {
    String.prototype.startsWith = function (str){
      return this.slice(0, str.length) == str;
    };
  }
  $(document).ready(function() {
    $('a.enviar').click(function() { 
      var img = $(this);
      if (img.attr('disabled')) return false;
      var completed = true;
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
      
      var form = $('form').serializeArray();
      var post_data = {
        'img_id': img.data('rel')
      };
      if (img.data('rel')) { 
        var key = 'respuesta[' + img.data('rel') + ']';

        for (i in form) { 
          var elem = form[i];
          if (elem['name'].startsWith(key)) { 
            post_data[elem['name']] = elem['value']
          }
        } 
      } else { 
        respuestas = form; 
      }
      $(this).text('Enviando...');
      $(this).addClass('disabled');
      var xhr = $.ajax('<?php echo base_url("/arquetipos/ajax_respuesta/$hash")?>', 
        {
          data: post_data,
          type:'POST',
        })
        .done(function(data) {
            if (data['ok'] == true) {
              $("#img_" + img.data('rel')).addClass('grayscale'); 
              $("#img_small_" + img.data('rel')).addClass('grayscale'); 
            }
            //var anchor = $('a[href=#' + img.parents('div.modalResponder').attr('id') + ']');
            div.find('textarea').attr('disabled',1);
            img.attr('disabled',1);
            img.text('Respuestas enviadas');
            div.find('div.alert').addClass('hide');
        })
        .fail(function () { 
            div.find('div.alert').text('Problemas enviando respuestas, pruebe nuevamente').removeClass('hide');
            img.text('Enviar respuestas');
            img.removeClass('disabled');
        })
        .always(function() { 
            div.find('a[href=#close]')[0].click();
        });
        
      return false;
    })
  $('#infoModal').on('hidden', function () {
    setTimeout(function() {
      $("div#infoModal > div.infoModal_body").html('');
    }, 1000);
  });

  $('#infoModal').on('show', function () {
    $("div#infoModal > div.infoModal_body").html('<i class="icon-spinner icon-spin icon-large"></i>');
    $.get("<?php echo base_url('/arquetipos/ejercicio_get_description/' . $hash) ?>", "", function (html) {
      $("div#infoModal > div.infoModal_body").html(html);
    });
  })
  $('#infoModal').modal('show');
});

</script>


<div class="row-fluid">
    <div class="span12"><h2><?php echo $ejercicio->consigna ?></h2> </div>
</div>



<form method='post'> 
<?php foreach ($imagenes as $i=>$img): 
  $disabled = (isset($respuestas[$img->id]));
  ?>  
  <div id="openModal<?php echo $i?>" class="modalResponder"> <!-- modal 5-->
    <div>
      <a href="#close" title="Close" class="closemodal"><i class="icon-remove icon-white"></i></a>
      <div class='row-fluid'>
        <h3>
          <?php echo $img->titulo ?>
        </h3>
      </div>
      <div class="row-fluid">
        <div class="span4">
          <img id="img_small_<?php echo $img->id?>" <?php if ($disabled):?> class='grayscale' <?php endif;?>
            src="<?php echo $img->imagen_ubicacion?>" width='150px' height='150px' alt="">
            <img id="img_small_<?php echo $img->id?>" <?php if ($disabled):?> class='grayscale' <?php endif;?>
                 src="<?php echo assets_url('img/alert.gif')?>" width='150px' height='150px' alt="">
        </div>
        <div class="span8">
          <?php foreach($preguntas as $preg): ?>
            <p><?php echo $preg->pregunta?></p>          
            <div class="control-group">
              <div class="controls">
                <textarea name="respuesta[<?php echo $img->id?>][<?php echo $preg->id?>]" rows="2" 
                  class="input-xlarge" id="textarea2"
                  <?php if ($disabled):?>disabled<?php endif?>><?php echo @$respuestas[$img->id][$preg->id]?></textarea>
              </div>
            </div>   
          <?php endforeach; ?>
        </div>
        <a <?php if ($disabled):?>disabled<?php endif?> href='#' class='btn btn-large pull-right enviar' data-rel='<?php echo $img->id ?>'>
          enviar respuestas
        </a>
        <br /><br />
        <div class="alert alert-error pull-left hide">
            Complete las 3 preguntas para continuar.
        </div>
      </div>
    </div>

  </div>
<?php endforeach ?>
</form>
<div class="container gallery">  
<?php foreach ($imagenes as $i=>$img): ?>  
  <?php if ($i == 0 or $i == 3): ?>
    <div class="row">
  <?php endif ?>
        <div class="col-lg-4">
          <a class="thumbnail" href="#openModal<?php echo $i?>">
            <img <?php if (isset($respuestas[$img->id])): ?> class='grayscale' <?php endif;?> id='img_<?php echo $img->id?>'
              src="<?php echo $img->imagen_ubicacion?>" width='280px' height='280px' alt="" />
          </a>
          <p class="text-info"><?php echo $img->titulo ?></p>
        </div>
  <?php if ($i == 2 or $i == 5): ?>
    </div>
  <?php endif ?>
<?php endforeach ?>
</div>
