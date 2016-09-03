	<div class="row-fluid publichead">

    <div class="span10">
      <div><img src="<?= assets_url('/img/prismas_public.png')?>" width="78" height="94"><span class="logosmall">Prismas</span> <span class="logolightsmall"> entramados</span></div>
    </div>
    <div class="span2">
        <div class="pull-right"><a href="http://citep.rec.uba.ar" target="_blank"><img src="<?= assets_url('/img/citep-mic-web.png')?>" width="110" height="54"></a></div>
    </div>

</div>

  <div class="row-fluid">
	 <div class="page-header">
	  <h1><?= $ejercicio->consigna ?></h1></div>
    
    <div class="span12 public">
		<a class="btn pull-right"><i class="icon-print"></i> imprimir</a><br>
	</div>
    
</div>

<?
$ult_alumno_id = $i =  0; 
while (@$resp = $respuestas[$i]): 
  //print_r($resp);
  if ($ult_alumno_id != $resp->dialogo_alumno_id):
?>
    <div class="row-fluid">
        <div class="span12">
          <h3><?= $resp->nombre ?></h3>
        </div>
    </div>
  <?endif;?>
  <div class="row">
    <div class="span6 offset3 prismasalumno">
      	<p><?= $resp->created_at ?></p>
      	<p><strong><?= $resp->campo_1?></strong></p>
		<div class="chat-arrow"></div>
      </div>
  </div>

  <div class="row">
    <div class="span7 prismasprofesor offset4">
      <p><?= $resp->calificacion_cuando ?></p>
      <p><div class="circ<?= $resp->calificacion ?>">.</div></p>
      <p><strong><?= $resp->calificacion_text ?></strong></p>
      <div class="chat-arrowprof"></div>
    </div>
  </div>
<?
  $ult_alumno_id = $resp->dialogo_alumno_id;
  $i++;
endwhile;
 ?>

	<footer id="footer"><p class="pull-right">
    <a href="#top">
     Arriba
    </a></p>       
	</footer>
    