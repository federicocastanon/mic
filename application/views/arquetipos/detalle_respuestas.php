<section>
<div class="container">
  
  <div class="row-fluid">
  <div class="page-header">
    <h1>
        <?php echo $ejercicio->consigna ?>
      </h1>
    </div>
    
    <div class="span12">
      <a class="btn btn-large pull-right" onClick='window.print();return false;' href='#'>
        <i class="icon-print">
        </i>
        imprimir
      </a>
        <br>
        <br>
        <br>
    </div>
<?php foreach($alumnos as $alumno_id => $alumno) : 
  $i_arquetipo = 0;
  ?>    
    <div>
      <h4>
        <?php echo $alumno->nombre ?> 
      </h4>
      <p>
        <?php echo $alumno->fecha ?> 
      </p>
    </div>
    <?php foreach ($respuestas[$alumno_id] as $respsximagen) : 
          $i = 0;
    ?>
      <table class="table table-striped">
        <?php foreach ($respsximagen as $respuesta): 
              $i++;
        ?>
          <?php if ($i == 1): 
                  $i_arquetipo++;
          ?>
            <tr>
              <td width="100">
                <?php echo $imagenes[$respuesta->imagen_id]['titulo'] ?>
              </td>
              <td class="resp">
                <?php echo $respuesta->pregunta;?>        
              </td>
            </tr>
            <tr>
              <td rowspan="5">
                <img width='70px' height='70px' src="<?php echo $imagenes[$respuesta->imagen_id]['url']?>" alt="">
              </td>
              <td class='resp'>
                <?php echo $respuesta->respuesta;?>        
              </td>
            </tr>
          <?php else: ?>
            <tr>
              <td class='resp'>
                <?php echo $respuesta->pregunta;?>        
              </td>
            </tr>
            <tr>
              <td class='resp'>
                <?php echo $respuesta->respuesta;?>        
              </td>
            </tr>
          <?php endif; ?>
      <?php endforeach; ?>
      </table>
    <?php endforeach; ?>
<?php endforeach; ?>
  </div>
</div>
</section>
