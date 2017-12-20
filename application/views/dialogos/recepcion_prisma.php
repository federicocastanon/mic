<script type='text/javascript'>
    function validatealias(alias) {
        var re = /^(([^<>()\[\]\\.,;:\s@"]+(\.[^<>()\[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
        return re.test(alias);
    }

    function sentarse(dialogoId, profesional){
        var alias = $('#alias').val();
        /*
         if(!validatealias(alias)){
         $('#mensaje').text('Ingrese un alias verdadero').show();
         return;
         }*/

        $('#profesional').val(profesional);
        $('#dialogoId').val(dialogoId);

        document.getElementById("myForm").submit();
    }

</script>

<div class="col-md-12">
    <div class="col-md-12"><h1>Diálogos</h1></div>

    <div class="col-md-12">
        <div class="col-md-3">
            <b>Alias: </b> <?php  if (isset($_SESSION["alias"]))echo $_SESSION["alias"]?>
        </div>
        <div class="col-md-2">
            <a class="cambiarAlias" href="<?php echo base_url('/dialogo/cambiarAlias/' . $prisma->id)?>"><i class="fa fa-refresh" aria-hidden="true"></i> Cambiar Alias</a>
        </div>
        <?php if ($this->template_type == 'admin'): ?>
            <a class="btn btn-lg btn-default pull-right" href="<?php echo base_url('/dialogo/')?>"><i class="fa fa-arrow-left"></i> Volver</a>
        <?php else: ?>
            <a class="btn btn-lg btn-default pull-right" href="<?php echo base_url('/')?>"><i class="fa fa-arrow-left"></i> Volver</a>
        <?php endif; ?>
    </div>
</div>

<div class="spacer"></div>
<div class="col-md-12">
<form id="myForm" method='post' action='<?php echo base_url('/dialogo/sentarse/')?>'>
    <div id="mensaje" class="alert alert-error pull-left" style="display: none">Area de Mensajes</div>

    <div class="col-md-12">
        <div class="spacer"></div>
    </div>
    <div class="col-md-12">
        <b>Situación:</b>  <?php echo $prisma->descripcion ?>
    </div>
    <div class="col-md-12">
        <div class="spacer"></div>
    </div>

        <?php if (isset($pen) && $pen): ?>
            <div class="col-md-8">
               <h4>Usted es parte de un dialogo que aún no ha finalizado. Si no lo finaliza o se levanta de su puesto, no podrá ingresar a otro diálogo. </h4>
                <a class="btn btn-sm btn-success pull-left" href="<?php echo base_url('/dialogo/calificar/'. $pen)?>">Continuar con el diálogo</a>
            </div>
        <?php else: ?>
            <div class="col-md-4">
                <?php if (isset($pro) && $pro): ?>
                    <a class="btn btn-lg btn-primary pull-left" onclick="sentarse(<?php echo $pro?>, 'true')">
                <i class="fa fa-star"></i>   Ingresar como <?php echo $prisma->profesional?></a>

                <?php else: ?>
                    <h4>No hay puestos disponibles para el rol <i><?php echo $prisma->profesional?></i></h4>
                <?php endif; ?>
            </div>
            <div class="col-md-4">
                <?php if (isset($sec) && $sec): ?>

                <a class="btn btn-lg btn-info pull-left" onclick="sentarse(<?php echo $sec?>, 'false')"> <i class="fa fa-star"></i>  Ingresar como <?php echo $prisma->secundario?></a>

                <?php else: ?>
                    <h4>No hay puestos disponibles para el rol <i><?php echo $prisma->secundario?></i></h4>
                <?php endif; ?>
            </div>
        <?php endif; ?>

        <div class="col-md-4">
            <a class="btn btn-lg btn-warning pull-left" href="<?php echo base_url('/dialogo/verCalificaciones/' . $prisma->id)?>"><i class="fa fa-star"></i> Valoraciones</a>
        </div>
    <div class="col-md-12 spacer"></div>
    <?php if (isset($dialogos) && $dialogos): ?>
    <div class="col-md-12 bordeInferiorGrueso">
        <div class="col-md-1 logolightxsmall">
            id
        </div>
        <div class="col-md-4 logolightxsmall">
            ROL Profesional
        </div>
        <div class="col-md-4 logolightxsmall">
            ROL secundario
        </div>
        <div class="col-md-2 logolightxsmall">
            Estado
        </div>
    </div>
    <?php
    $rc = 0;
    foreach ($dialogos as $e):
    $rc++?>

    <div class="col-md-12 top5 <?php if($rc %2 == 1){?> filaGris <?php }else{?> filaBlanca <?php }?>">
        <div class="col-md-1 logolightxsmall">
            <span class="badge ">  <?php echo $e->etiqueta?></span>
        </div>
        <div class="col-md-4">
            <?php if ($e->evaluado): ?>
                <?php echo $e->evaluado?>
            <?php else: ?>
                <a class="vinculo" onclick="sentarse(<?php echo $e->id?>, true)">  Ingresar como <?php echo $prisma->profesional?></a>
            <?php endif; ?>
        </div>
        <div class="col-md-4">
            <?php if ($e->secundario): ?>
                <?php echo $e->secundario?>
            <?php else: ?>
                <a class="vinculo" onclick="sentarse(<?php echo $e->id?>, false)">  Ingresar como <?php echo $prisma->secundario?></a>
            <?php endif; ?>
        </div>

        <?php if ($e->terminado): ?>
        <div class="col-md-2" style="color: red; font-weight: bold">
            <i class="fa fa-window-close-o" aria-hidden="true"></i> FINALIZADO
            <?php elseif($e->evaluado && $e->secundario): ?>
            <div class="col-md-2" style="color: orange; font-weight: bold">
                <i class="fa fa-arrow-circle-right" aria-hidden="true"></i> EN CURSO
                <?php else: ?>
                <div class="col-md-2" style="color: green; font-weight: bold">
                    <i class="fa fa-unlock-alt" aria-hidden="true"></i> ABIERTO
                    <?php endif; ?>
                </div>

            </div>

    <?php endforeach ?>
    <?php else: ?>
        <div class="col-md-12" >
            <h1>No hay mesas de dialogo disponibles para usted</h1>
        </div>
    <?php endif; ?>

    <input type="hidden" id="alias" name="alias" placeholder="alias" required="true" value="<?php  if (isset($_SESSION["alias"]))echo $_SESSION["alias"]?>"
    "/>
    <input type="hidden" name="dialogoId" id="dialogoId">
    <input type="hidden" name="profesional" id="profesional">
</form>
</div>