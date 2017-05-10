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

<div class="row-fluid">
        <div class="page-header"><h1>Diálogos</h1></div>

        <div class="span12">
            <?php if ($this->template_type == 'admin'): ?>
                <a class="btn btn-lg btn-default pull-right" href="<?php echo base_url('/dialogo/')?>"><i class="fa fa-arrow-left"></i> Volver</a>
            <?php else: ?>
                <a class="btn btn-lg btn-default pull-right" href="<?php echo base_url('/')?>"><i class="fa fa-arrow-left"></i> Volver</a>
            <?php endif; ?>


        </div>

    </div>

    <div class="spacer"></div>
    <form id="myForm" method='post' action='<?php echo base_url('/dialogo/sentarse/')?>'>
    <div id="mensaje" class="alert alert-error pull-left" style="display: none">Area de Mensajes</div>
        <div id="ret">
            <p><b>Situación:</b>  <?php echo $prisma->descripcion ?></p>
        </div>
    <div class="row"> Con este alias entrás a los dialogos <b><?php  if (isset($_SESSION["alias"]))echo $_SESSION["alias"]?></b>
        <a class="btn btn-large" href="<?php echo base_url('/dialogo/cambiarAlias/' . $prisma->id)?>"> Cambiar Alias</a>
    </div>
        <div class="row">
            <a class="btn btn-lg btn-warning pull-left" href="<?php echo base_url('/dialogo/verCalificaciones/' . $prisma->id)?>"><i class="fa fa-star"></i> Ver Calificaciones</a>
        </div>
        <input type="hidden" id="alias" name="alias" placeholder="alias" required="true" value="<?php  if (isset($_SESSION["alias"]))echo $_SESSION["alias"]?>"
               "/>
        <input type="hidden" name="dialogoId" id="dialogoId">
        <input type="hidden" name="profesional" id="profesional">
        </form>
    <?php if (isset($dialogos) && $dialogos): ?>
        <div class="row bordeInferiorGrueso">
            <div class="col-sm-1 logolightxsmall">
                id
            </div>
            <div class="col-sm-4 logolightxsmall">
                ROL Profesional
            </div>
            <div class="col-sm-3 logolightxsmall">
                ROL secundario
            </div>
            <div class="col-sm-2 logolightxsmall">
                Estado
            </div>
            <div class="col-sm-2 logolightxsmall">
                Calificar
            </div>
        </div>
        <?php foreach ($dialogos as $e): ?>

            <div class="row top30">
                <div class="col-sm-1 logolightxsmall">
                  <span class="badge ">  <?php echo $e->id?></span>
                </div>
                <div class="col-sm-4">
                    <?php if ($e->evaluado): ?>
                        <?php echo $e->evaluado?>
                    <?php else: ?>
                     <a class="vinculo" onclick="sentarse(<?php echo $e->id?>, true)">  Ingresar como <?php echo $prisma->profesional?></a>
                    <?php endif; ?>
                </div>
                <div class="col-sm-3">
                    <?php if ($e->secundario): ?>
                        <?php echo $e->secundario?>
                    <?php else: ?>
                    <a class="vinculo" onclick="sentarse(<?php echo $e->id?>, false)">  Ingresar como <?php echo $prisma->secundario?></a>
                    <?php endif; ?>
                </div>

                    <?php if ($e->terminado): ?>
                <div class="col-sm-2" style="color: red">
                        FINALIZADO
                    <?php elseif($e->evaluado && $e->secundario): ?>
                    <div class="col-sm-2" style="color: green">
                        EN CURSO
                    <?php else: ?>
                    <div class="col-sm-2" style="color: orange">
                        ABIERTO
                    <?php endif; ?>
                </div>
            <?php if ($this->template_type == 'admin'): ?>
                <div class="col-sm-2">
                    <a href="<?php echo base_url('/dialogo/calificar/'. $e->id)?>">Calificar</a>
                </div>
            <?php else: ?>
                <?php if ($_SESSION["alias"] != $e->evaluado and $_SESSION["alias"] != $e->secundario ): ?>
                    <div class="col-sm-2">
                        <a href="<?php echo base_url('/dialogo/calificar/'. $e->id)?>">Calificar</a>
                    </div>
                <?php else: ?>
                    <div class="col-sm-2">
                        <a href="<?php echo base_url('/dialogo/calificar/'. $e->id)?>">Continuar diálogo</a>
                    </div>
                <?php endif; ?>
            <?php endif; ?>

            </div>

        <?php endforeach ?>
    <?php else: ?>
        <h1>No hay dialogos para este PRISMA cargados todavia</h1>
    <?php endif; ?>