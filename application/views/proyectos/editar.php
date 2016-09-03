<script>
$(function() { 
    $('a[href=#editar_subseccion]').click(function() {
        var that = this;
        $.get('<?= base_url("/proyectos/ajax_editar_subseccion/" . $proyecto->id)?>/' +  $(that).data('titulo') + '/' + $(that).data('id')  , function(data) {
            $("#editar_subseccion").html(data);
            $("#editar_subseccion").modal('show');
        });
    });
    var url = document.location.toString();
    if (url.match('#')) {
        $('.nav-tabs a[href=#'+url.split('#')[1]+']').tab('show') ;
    } 

    // Change hash for page-reload
    $('.nav-tabs a').on('shown', function (e) {
        window.location.hash = e.target.hash;
    })
});
// Javascript to enable link to tab

</script>
<div class="container">
<section>
    <div class="row-fluid">
        <?php echo validation_errors(); ?>
        <form method='post' class='form-horizontal'>
            <div class="page-header">
                <h1><?= ($proyecto->nombre)?$proyecto->nombre . ' - ':'' ?>Editar</h1>
            </div>
            <div class="control-group">
                <label class="control-label" for="nombre">Nombre</label>
                <div class="controls">
                    <input name='nombre' id="nombre" class="input-xxlarge" type="text" placeholder="Nombre del proyecto" value="<?php echo set_value('nombre', @$proyecto->nombre)?>">
                </div>
            </div>
                
            <div class="tabbable"> 
                <ul class="nav nav-tabs">
                    <li class="active"><a href="#tab1" data-toggle="tab">Diseño</a></li>
                    <li class=""><a href="#tab2" data-toggle="tab">Gestión</a></li>
                    <li class=""><a href="#tab3" data-toggle="tab">Contexto</a></li>
                </ul>
                <div class="tab-content">
                    <div class="tab-pane active" id="tab1"><!-- CONSIGNA -->
                        <div class="tab-content">
                            <p class="help-block">Cantidad máxima de secciones: 10</p><br />
                            <? if (isset($secciones['diseño'])) {
                                $this->load->view('/proyectos/subseccion_parcial', ['secciones'=>$secciones, 'titulo' => 'diseño']); 
                            }?>
                            <? if (count($secciones['diseño']) < 10): ?>
                                <a href='#editar_subseccion' data-id='0' data-titulo='diseño' class='btn'><i class="fa fa-plus"></i> Agregar</a>
                            <? endif ?>
                        </div>
                    </div>
                    <div class="tab-pane" id="tab2">
                        <div class="tab-content">
                            <p class="help-block">Cantidad máxima de secciones: 8</p><br />
                            <?if (isset($secciones['gestión'])) {
                                $this->load->view('/proyectos/subseccion_parcial', ['secciones'=>$secciones, 'titulo'=>'gestión']);
                            } ?>
                            <? if (count($secciones['gestión']) < 8): ?>
                               <a href='#editar_subseccion' data-id='0' data-titulo='gestión' class='btn'><i class="fa fa-plus"></i> Agregar</a>
                            <? endif ?>
                        </div>
                    </div>
                    <div class="tab-pane" id="tab3">
                        <div class="tab-content">
                            <p class="help-block">Cantidad máxima de secciones: 8</p><br />
                            <?if (isset($secciones['contexto'])) {
                                $this->load->view('/proyectos/subseccion_parcial', ['secciones'=>$secciones, 'titulo' => 'contexto']);  
                            } ?>
                            <? if (count($secciones['contexto']) < 8): ?>
                                <a href='#editar_subseccion' data-id='0' data-titulo='contexto' class='btn'><i class="fa fa-plus"></i> Agregar</a>
                            <? endif ?>
                        </div>
                    </div>
                </div>
            </div>
            <button class="btn btn-primary btn-large pull-right"> Continuar</button>      
        </form>
    </div>
</section>
</div>
<div class="modal hide fade" id='editar_subseccion'>
</div>
<br /> 
<br />
