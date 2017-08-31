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
            <div class="col-md-4">
                <b>Alias: </b> <?php  if (isset($_SESSION["alias"]))echo $_SESSION["alias"]?>
            </div>
            <div class="col-md-2">
                <a class="cambiarAlias" href="<?php echo base_url('/dialogo/cambiarAlias/' . $prisma->id)?>"><i class="fa fa-refresh" aria-hidden="true"></i> Cambiar Alias</a>
            </div>

           <a class="btn btn-lg btn-default pull-right" href="<?php echo base_url('/dialogo/recepcionPrisma/' . $prisma->id)?>"><i class="fa fa-arrow-left"></i> Volver</a>

        </div>

    </div>
<div class="col-md-12">
    <div class="spacer"></div>
</div>
    <form id="myForm" method='post' action='<?php echo base_url('/dialogo/sentarse/')?>'>
    <div id="mensaje" class="alert alert-error pull-left" style="display: none">Area de Mensajes</div>
        <div class="col-md-12" id="ret">
            <p><b>Situación:</b>  <?php echo $prisma->descripcion ?></p>
        </div>
        <div class="col-md-12">
            <div class="spacer"></div>
        </div>
        <input type="hidden" id="alias" name="alias" placeholder="alias" required="true" value="<?php  if (isset($_SESSION["alias"]))echo $_SESSION["alias"]?>"
               "/>
        <input type="hidden" name="dialogoId" id="dialogoId">
        <input type="hidden" name="profesional" id="profesional">
        </form>
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
        <?php foreach ($dialogos as $e): ?>

            <div class="col-md-12 top30">
                <div class="col-md-1 logolightxsmall">
                  <span class="badge ">  <?php echo $e->id?></span>
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
                    <div class="col-md-2" style="color: red">
                        <i class="fa fa-window-close-o" aria-hidden="true"></i> FINALIZADO
                    <?php elseif($e->evaluado && $e->secundario): ?>
                    <div class="col-md-2" style="color: green">
                        <i class="fa fa-arrow-circle-right" aria-hidden="true"></i> EN CURSO
                    <?php else: ?>
                    <div class="col-md-2" style="color: orange">
                       <i class="fa fa-unlock-alt" aria-hidden="true"></i> ABIERTO
                    <?php endif; ?>
                </div>

            </div>

        <?php endforeach ?>
    <?php else: ?>
        <h1>No hay dialogos para este PRISMA cargados todavia</h1>
    <?php endif; ?>