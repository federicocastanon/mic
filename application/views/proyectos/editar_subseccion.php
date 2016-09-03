<script type="text/javascript" src="<?php echo assets_url('js/ckeditor/ckeditor.js');?>"></script>
<section>
				<div class="row-fluid">
			 		<div class="pull-right">
			 			<a href="<?= base_url('/proyectos/visualizar/' . $subseccion->id_proyecto) ?>" target="_self">
			 				<img src="<?=assets_url('/img/btn_gestor.png')?>" width="51" height="61" border="0">
			 			</a>
			 		</div>
				</div>
	<div class='row'>
		<div class='span8'>
			<form method='post' class='form-horizontal'>
	<div class="row-fluid">
			    	<div class="page-header">
					    <h1>Editar - <?= ucfirst($subseccion->nombre) ?></h1>
					</div>
                    <div>
					    <h4><?= $subseccion->descripcion ?></h4><br />
					</div>
				</div>	
				<div class="row-fluid">
					<div>    
					    <textarea name='contenido' style='height:500px;'> <?php echo set_value('contenido', @$subseccion->contenido)?> </textarea>
					</div>
				</div>
				<div class="row-fluid">
					<div class='control-group'> 
						<span class='pull-left' id='cartel_lock'></span>
					   <br />
						 <button class="btn btn-primary btn-large pull-right" href="#" id='bot_continuar'>Continuar</button>
						 <a href='<?=base_url("/proyectos/visualizar/{$subseccion->id_proyecto}")?>' class='btn btn-large pull-right'>
						 	Volver sin guardar
						 </a>
					</div>
				</div>
			</form>
		</div>
		<div class='span4'>
			<div class='page-header'>
				<h2>Comentarios</h2>
			</div>
			<? foreach ($comentarios as $c): ?>
				<span class="help-block"><?= $c->name ?> <?= $c->created_at?></span>
				<p><?= $c->comentario ?></p>
				<hr>
			<? endforeach; ?>
			<form method='post' id='comentario' action='<?= base_url("/proyectos/enviar_comentario/{$subseccion->id}") ?>'> 
				<textarea  rows=3 width='100%'placeholder='Ingrese su comentario' name='comentario'></textarea>
				<input type='submit' class="btn" value='Enviar' />
			</form> 
		</div>
	</div>
</section>
<script type='text/javascript'>
    CKEDITOR.replace( 'contenido', {filebrowserUploadUrl: "<?php echo base_url('/arquetipos/upload_from_editor')?>"} );
    (function worker() {
	  $.ajax({
	    url: '<?= base_url('/proyectos/ajax_send_heartbeat/' . $subseccion->id) ?>', 
	    success: function(data) {
	      console.log(data);
	      if (data['allowed']) { 
	      	$('#bot_continuar').show();
	      	$('#cartel_lock').hide();
	      } else { 
			$('#bot_continuar').hide();
			$('#cartel_lock').text('Guardado desactivado - Esta subsección esta siendo editada por ' + data['name'] + ' ' + data['email']).show();
	      }
	    },
	    complete: function() {
	      // Schedule the next request when the current one's complete
	      setTimeout(worker, 15000);
	    }
	  });
	})();
</script>
<script src="<?php echo assets_url('js/vendor/jquery.ui.widget.js');?>"></script>
<script src="<?php echo assets_url('/js/jquery.fileupload.js');?>"></script>
