<div class="container">
<section>
    <div class="row-fluid">
        <div class="page-header">
            <h1>Respuestas</h1>
        </div>
        <div class="span12">
            <a class="btn btn-large pull-right" href="<?php echo base_url("/arquetipos/ver_respuestas_listado/$arquetipo_id")?>">
               <i class="icon-file-text-alt"></i> detalle completo
            </a>
            <br>
            <br>
            <br>
        </div>
        <table class="table table-striped table-hover">
            <thead>
                <th width="10%">Alumno</th>
                <th width="20%">Fecha</th>
                <?php foreach ($imagenes as $img): ?>
				<th><?php echo $img->titulo ?></th>
                <?php endforeach ?>
                <th width="25%">Acci&oacute;n</th>
			</thead>
            <tbody>
                <?php foreach ($alumnos as $alumno_id => $alumno) : ?>
                <tr>
                    <td><?php echo $alumno->nombre?></td>
                    <td><?php echo $alumno->fecha ?></td>
                    <?php foreach ($imagenes as $imagen) { 
                        if (!isset($respuestas[$alumno_id][$imagen->id])) { 
                            echo '<td class="tdarq"></td>';
                        } else { 
                            $resps = explode(",",$respuestas[$alumno_id][$imagen->id]->respuestas);
                            if (count($resps) == 3) {
                                echo '<td class="tdarq"><span class="badge badge-success badge-ajuste">&nbsp;</span></td>';
                            } else { 
                                echo '<td class="tdarq"><span class="badge badge-error">&nbsp;</span></td>';
                            }
                        }
                    } 
                    ?>
                         <td>
                            <? if ($alumno->fecha): ?> 
                                <a data-toggle="modal" data-target="#modalRespuestas" class='btn'
                                href="<?php echo base_url("/arquetipos/ajax_respuestas/$arquetipo_id/$alumno_id") ?>">
                                    <i class='icon-search'></i> ver detalle
                                </a>
                                <a class='btn devolucion btn-primary' data-alumno-id="<?= $alumno_id ?>" href="#">
                                 <i class="fa fa-envelope-o"></i> devolución
                                </a>
                            <? endif ?> 
                        </td>
                    </tr>
			    <?php endforeach ?>
                    
            </tbody>
        </table>
    </div>

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
