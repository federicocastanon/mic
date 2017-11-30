
<script type='text/javascript'>
    var cantidadPreguntas = 1;
    function agregarPregunta(cantidadP){
        if(cantidadPreguntas < 2 && cantidadP > 0){
            cantidadPreguntas = cantidadP +1;
        }else{
            cantidadPreguntas++;
        }


        $( "#preguntas" ).append( '<div id="'+ cantidadPreguntas+ '"><input  name="pregunta[]" class="input-xxlarge" type="text" placeholder="Pregunta '+cantidadPreguntas + '" value=""><a  data-toggle="tooltip" title="Eliminar" onclick="borrarPregunta('+ cantidadPreguntas+')"><i class="linkListado fa fa-trash-o"></i></a><br><br></div>' );

    }
    var idPreguntaBorrar;
    function borrarPregunta(id){
        if(!(""+id).includes('pregunta')){
            //pregunta no persistida
            $('#'+id).remove();
        }else{
            idPreguntaBorrar = id.substring(8);
            $('#confirmar' + idPreguntaBorrar).removeClass('oculto');
            $('#borrar' + idPreguntaBorrar).addClass('oculto');
            //Al hacer click, oculto el icono del tacho y muestro un texto con dos botones
            //uno revierte al estado anterior, el otro ejecuta el borrarPreguntaGuardada
        }
    }

    function cancelarBorrado(id){
        $('#confirmar' + id).addClass('oculto');
        $('#borrar' + id).removeClass('oculto');
    }

    function borrarPreguntaGuardada(id){
        $.ajax('<?php echo base_url("/arquetipos/ajax_borrar_pregunta/")?>'+'/' +id,
            {
                data: '',
                type:'POST',
            })
            .done(function(data) {
                $('#pregunta'+id).remove();
            })
            .fail(function () {
                alert('Ocurrió un error. Volvé a intentarlo más tarde.');
            })
            .always(function() {

            });
    }

    function borrarImagen(id){
        alert('¡ATENCIÓN! Estás por borrar esta imagen, todas las respuestas asociadas también serán borradas')
        $('#confirmarImagen' + id).addClass('enLinea');
        $('#confirmarImagen' + id).removeClass('oculto');
        $('#borrarImagen' + id).addClass('oculto');
    }
    function cancelarBorradoImagen(id){
        $('#confirmarImagen' + id).addClass('oculto');
        $('#confirmarImagen' + id).removeClass('enLinea');
        $('#borrarImagen' + id).removeClass('oculto');
    }

    function borrarImagenGuardada(id){
        $.ajax('<?php echo base_url("/arquetipos/ajax_borrar_imagen/")?>'+'/' +id,
            {
                data: '',
                type:'POST',
            })
            .done(function(data) {
                $('#borrarImagen'+id).siblings('.thumbnail_selected').removeClass('thumbnail_selected');
                $('#borrarImagen'+id).remove();
                $('#confirmarImagen' + id).remove();
                refresh_selected_images();
            })
            .fail(function () {
                alert('Ocurrió un error. Volvé a intentarlo más tarde.');
            })
            .always(function() {

            });
    }

    function continuar(){
        if(estaCompleto()){
            $('.nav-tabs').find('a[href=#tab2]').click();return false;
        }else{
            $('#mensajes').html("Complete los campos obligatorios antes de continuar. Todos deben tener al menos 5 carácteres de longitud");
        }

    }

    function estaCompleto(){
        var nombre = $('#nombre').val();
        var consigna = $('#consigna').val();
        var desarrollo = $('iframe').contents().find('.cke_editable_themed > p').html();
        if(nombre.length > 4 && consigna.length > 4 && desarrollo.length > 4){
            return true;
        }
        return false;
    }

    $(document).ready(function() { 
        $('form').click(refresh_selected_images);
        $(document).on('click', 'a.thumbnail' ,function() {
            $(this).toggleClass('thumbnail_selected');
            refresh_selected_images();
            return false;
            //var img = $(this).find('img');
            //var src = img.attr('src'));
        });
        $('#fileupload').fileupload({
            dataType: 'json',
            add: function (e, data) {
                data.submit();
            },
            done: function (e, data) {
                //console.log(data);
                $('.alert').remove()
                if (data.result && (0 in data.result) && ('error' in data.result[0])) { 
                    alert = '<div class="alert"><button type="button" class="close" data-dismiss="alert">&times;</button>';
                    alert = alert + data.result[0]['error'] + '</div>';
                    $("#fileupload").after(alert);
                } else if ('name' in data.result){ 
                    $('#uploaded').append(
                    '<li> ' +
                    '<a class="thumbnail thumbnail_selected" href="#">' + 
                    '<img height="150px" width="150px" src="' + data.result['url'] + '" data-source="custom" />' + 
                    '</a><input type="text" name="" style="width:150px;" placeholder="Titulo del arquetipo" data-rel="' + data.result['url'] + '"/>' +
                    '</li>');
                    refresh_selected_images();
                }
            },

        });
    });

    function refresh_selected_images() { 
        var tmp = new Array();
        var sels = $('a.thumbnail').each(function() {
            var img = $(this).find('img');
            var title = '';
            if ($(this).hasClass('thumbnail_selected')) { 
                title = $(this).parent().find('input').val();
            }
            tmp.push({'url':img.attr('src'), 
                          'source': img.attr('data-source'), 
                          'selected': $(this).hasClass('thumbnail_selected'),
                          'titulo': title
                        });
        });
        $("input[name=imgs]").val(JSON.stringify(tmp))
    };

    

</script> 


<style>
    .thumbnail_selected { border:3px solid red;padding:2px;}
</style>




<section>
    <div class="container">
    <div class="row-fluid">
        <div id="mensajes" style="color: red;"></div>
                <?php echo validation_errors(); ?><?php echo $extra_errors?> 
        <form method='post'>
            <input type='hidden' name='imgs' value='<?php echo json_encode($imgs) ?>'> 
            <div class="page-header tituloWrapper" >
                <h1 class="pull-left titulo" ">Editar / Nuevo</h1>
                <a class="btn btn-lg btn-default pull-right" href="<?php echo base_url('/arquetipos')?>"><i class="fa fa-arrow-left"></i> Volver</a>
            </div>
            <div class="row-fluid">
                <label>Nombre</label>
                <input name='nombre' id="nombre" class="input-xxlarge" type="text" placeholder="Nombre de la actividad - No se muestra al alumno" value="<?php echo set_value('nombre', @$arquetipo->nombre)?>">
            </div>
            <div class="spacer"></div>
            <div class="tabbable">
                <ul class="nav nav-tabs">
                    <li class="active"><a href="#tab1" data-toggle="tab">Consigna</a></li>
                    <li class=""><a href="#tab2" data-toggle="tab">Arquetipos</a></li>
                </ul>
                <div class="tab-content">
                    <div class="tab-pane active" id="tab1"><!-- CONSIGNA -->
                        <div class="tab-content">
                            <input name='consigna' id="consigna" class="input-xxlarge" type="text" placeholder="Título de la actividad" value="<?php echo set_value('consigna', @$arquetipo->consigna)?>">
                            <div class="spacer"></div>
                            <h4>Descripción </h4>
                            <textarea name='desarrollo' style='height:500px;' id="desarrollo"> <?php echo set_value('desarrollo', @$arquetipo->desarrollo)?> </textarea>
                        </div>
                        <br /><br />
                        <a class="btn btn-primary btn-large pull-right" href="#" onclick="continuar()">Continuar</a><br>
                    </div>
                    <div class="tab-pane" id="tab2"><!-- ARQUETIPOS -->
                        <div class="span12">
                    
	<div class="well">
      Seleccionar y/o cargar hasta 9 arquetipos.
      </div>
                        
<h4>Seleccionar arquetipos</h4>
                        </div>
                        <div class="row-fluid">
                        <ul class="thumbnails">
                            <?php foreach ($imgs as $e):
                                if ($e['source'] != 'stock') continue; 
                                $class = ($e['selected'])?' thumbnail_selected':'';
                            ?>
                                <li>
                                    <a class="thumbnail <?php echo $class?>" href="#">
                                        <img src="<?php echo $e['url']?>" data-source="stock"/>
                                    </a>
                                    <?php echo $e['titulo'] ?>
                                    <?php if ($e['selected']){ ?>
                                    <a id="borrarImagen<?= $e['id'] ?>" data-toggle="tooltip" title="Eliminar" onclick="borrarImagen(<?=$e['id'] ?>)"><i class="linkListado fa fa-trash-o"></i></a>
                                    <div class="oculto" id="confirmarImagen<?= $e['id'] ?>">
                                        <a  onclick="borrarImagenGuardada(<?= $e['id'] ?>)"><i class="linkListado fa fa-check"></i></a>
                                        <a  onclick="cancelarBorradoImagen(<?= $e['id'] ?>)"><i class="linkListado fa fa-times"></i></a>
                                    </div>
                                    <?php } ?>
                                </li>
                            <?php endforeach ?>
                        </ul>
                        </div>
                        <br>
                        <div class="row-fluid">
                            <h4>Cargar nuevo arquetipo</h4>
                        </div>
                        <div class="row-fluid">
                                <p>Solamente JPG / GIF / PNG. Im&aacute;genes de tama&ntilde;o 280 x 280 p&iacute;xeles a 72 dpi de resoluci&oacute;n funcionan mejor.</p>
                                <input id="fileupload" type="file" name="file" data-url="<?php echo base_url('arquetipos/do_upload')?>">
                                <ul class="thumbnails" id='uploaded'>
                                    <?php foreach ($imgs as $e):
                                        #echo '<pre>';print_r($e);echo '</pre>';
                                        if ($e['source'] != 'custom') continue;
                                        $class = ($e['selected'])?' thumbnail_selected':'';
                                    ?>
                                        <li>
                                            <a class="thumbnail <?php echo $class?>" href="#">
                                                <img src="<?php echo $e['url']?>" data-source="custom" width='150px' height='150px'/>
                                            </a>
                                            <input type="text" name="titulo_imagen" style="width:150px;" placeholder="Titulo del arquetipo" value="<?php echo $e['titulo']?>" />
                                            <a id="borrarImagen<?= $e['id'] ?>" data-toggle="tooltip" title="Eliminar" onclick="borrarImagen(<?=$e['id'] ?>)"><i class="linkListado fa fa-trash-o"></i></a>
                                            <div class="oculto" id="confirmarImagen<?= $e['id'] ?>">
                                                <a  onclick="borrarImagenGuardada(<?= $e['id'] ?>)"><i class="linkListado fa fa-check"></i></a>
                                                <a  onclick="cancelarBorradoImagen(<?= $e['id'] ?>)"><i class="linkListado fa fa-times"></i></a>
                                            </div>
                                        </li>
                                    <?php endforeach ?>
                                </ul>
                        </div>
                        <br>
                        <h4>Ingresar preguntas</h4><br>
                        <div id="preguntas">

                        <?php foreach ($preguntas as $indice=>$pregunta): ?>
                            <div id="pregunta<?= $pregunta->id ?>">
                            <input name="pregunta[<?= $pregunta->id ?>]" class="input-xxlarge" type="text" placeholder="Pregunta <?= $indice ?>" value="<?php echo set_value('pregunta['+$pregunta->id+']', $pregunta->pregunta)?>">
                            <a id="borrar<?= $pregunta->id ?>" data-toggle="tooltip" title="Eliminar" onclick="borrarPregunta('pregunta' + <?= $pregunta->id ?>)"><i class="linkListado fa fa-trash-o"></i></a>
                                <div class="oculto" id="confirmar<?= $pregunta->id ?>"><i>Estás por borrar esta pregunta, todas las respuestas asociadas a esta pregunta también serán borradas.¿querés continuar?</i>
                                    <a  onclick="borrarPreguntaGuardada(<?= $pregunta->id ?>)"><i class="linkListado fa fa-check"></i></a>
                                    <a  onclick="cancelarBorrado(<?= $pregunta->id ?>)"><i class="linkListado fa fa-times"></i></a>
                                </div>
                            </div>
                            <br><br>
                        <?php endforeach ?>
                            <?php if(!$preguntas){?>
                                <input name="pregunta[]" class="input-xxlarge" type="text" placeholder="Pregunta 1" value="" ><br><br>
                            <?php }?>
                        </div>
                        <a class="btn btn-small " style="float: left"
                            onclick="agregarPregunta(<?= count($preguntas) ?>)">
                            <i class="fa fa-plus"></i>
                            Agregar Pregunta
                        </a>
                        <button class="btn btn-primary btn-large pull-right" href="#">Continuar</button><br>
                        <br>
                        <br>
                        <br>
                    </div>
                </div>  
            </div>
        </form>
    </div>

</div>
</section>

<script type="text/javascript" src="<?php echo assets_url('js/ckeditor/ckeditor.js');?>"></script>
<script type="text/javascript" src="<?php echo assets_url('js/vendor/jquery.ui.widget.js');?>"></script>
<script type="text/javascript" src="<?php echo assets_url('js/jquery.fileupload.js');?>"></script>
<script type='text/javascript'>
    CKEDITOR.replace( 'desarrollo', {filebrowserUploadUrl: "<?php echo base_url('/arquetipos/upload_from_editor')?>"} );
</script>