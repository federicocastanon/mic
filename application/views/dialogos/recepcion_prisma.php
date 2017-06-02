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
        <?php if (isset($pen) && $pen): ?>
            <div class="col-md-8">
               <h4>Usted es parte de un dialogo que aún no ha finalizado</h4>
                <a class="btn btn-sm btn-success pull-left" href="<?php echo base_url('/dialogo/calificar/'. $pen)?>">Continuar</a>
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
        <div class="col-md-2">
            <a class="btn btn-lg btn-warning pull-left" href="<?php echo base_url('/dialogo/dialogosPorPrisma/' . $prisma->id)?>"><i class="fa fa-star"></i> Listado</a>
        </div>
        <div class="col-md-2">
            <a class="btn btn-lg btn-warning pull-left" href="<?php echo base_url('/dialogo/calificarLanding/' . $prisma->id)?>"><i class="fa fa-star"></i> Calificaciones</a>
        </div>

    </div>
    <input type="hidden" id="alias" name="alias" placeholder="alias" required="true" value="<?php  if (isset($_SESSION["alias"]))echo $_SESSION["alias"]?>"
    "/>
    <input type="hidden" name="dialogoId" id="dialogoId">
    <input type="hidden" name="profesional" id="profesional">
</form>