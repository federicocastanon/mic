
<div class="container">
<section>
    <div class="row-fluid">
        <div class="page-header">
            <h1>Respuestas</h1>
        </div>
        <div class="span12">
            <a class="btn btn-large pull-right" href="<?php echo base_url("/dialogos/ver_respuestas/$dialogo_id")?>">
               <i class="icon-file-text-alt"></i> detalle completo
            </a>
            <br>
            <br>
            <br>
        </div>
        <table class="table table-striped table-hover">
            <thead>
                <th width="12%">Alumno</th>
                <th width="10%">Cantidad</th>
                <th width="3%"><div class="circverde">.</div></th>
                <th width="3%"><div class="circamarillo">.</div></th>
                <th width="3%"><div class="circrojo">.</div></th> 
                <th width="11%">Pendientes</th>
                <th width="15%">Ultima calificación</th> 
                <th width="8%">Estado</th> 
                <th width="34%">Acci&oacute;n</th>
			<td width="1%"></thead>
            <tbody>
                <?php foreach ($respuestas as $r) : ?>
                <tr>
                    <td><?php echo $r->nombre?></td>
                    <td><?php echo $r->cant ?></td>
                    <td><?php echo $r->verde ?></td>
                    <td><?php echo $r->amarillo ?></td>
                    <td><?php echo $r->rojo ?></td>
                    <td><?php echo $r->pendiente ?></td>
                    <td><?php echo $r->fecha ?></td>
                    <td><?php echo $r->status ?></td>
                    <td>
                        <? if ($r->cant): ?>
                            <a class='btn' href="<?= base_url("/dialogos/ver_respuestas/$dialogo_id/$r->alumno_id") ?>">
                                <i class='icon-search'></i> ver detalle
                            </a>
                            <? if ($r->pendiente): ?>
                                <a class='btn devolucion btn-primary' data-alumno-id="<?= $r->alumno_id ?>" href="#">
                                    <i class="fa fa-envelope-o"></i> devolución
                                </a>
                            <? endif; ?> 
                        <? endif; ?> 
                        <? if ($r->status == 'abierto'): ?>
                            <a class='btn btn-danger' href="<?= base_url("/dialogos/cerrar_ejercicio/$dialogo_id/$r->alumno_id") ?>"
                                onClick='return confirm("Esto impedirá al alumno ingresar nuevas respuestas.");'>
                                <i class="fa fa-stop"></i> finalizar 
                            </a>
                        <? endif ?>
                    </td>
                </tr>
			    <?php endforeach ?>    
            </tbody>
        </table>
    </div>

    <footer id="footer"><p class="pull-right">
      <a href="#top">
        Arriba
    </a></p>       
    </footer>
    </section>
</div>
<div id="emailModal" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <form action="<?= base_url('/dialogos/enviar_devolucion/')?>" method="post">
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

