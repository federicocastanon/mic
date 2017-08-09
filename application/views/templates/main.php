<?php 
  $error_msgs = array();
  $success_msgs = array();
  if ($this->session->flashdata('error_message')) { 
    $error_msgs[] = $this->session->flashdata('error_message');
  }
  if (isset($_error_message)) { 
    $error_msgs[] = $_error_message;
  }
  if ($this->session->flashdata('success_message')) { 
    $success_msgs[] = $this->session->flashdata('success_message');
  }
  if (isset($_success_message)) { 
    $success_msgs[] = $_success_message;
  }
?>
<!DOCTYPE html>
<html lng="en">
  <head>
    <meta charset="utf-8">
    <title><?php echo $_title?></title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="tipo_contenido"  content="text/html;" http-equiv="content-type" charset="utf-8">
    <meta name="description" content="">
    <meta name="author" content="">

    <!-- Le styles -->
    <link href="<?php echo assets_url('plugins/bootstrap/css/bootstrap.min.css')?>" rel="stylesheet" media='screen'>
    <link href="<?php echo assets_url('css/bootstrap-responsive.css')?>" rel="stylesheet">
      <link rel="stylesheet" href="<?php echo assets_url('plugins/font-awesome/css/font-awesome.css')?>">
      <link rel="stylesheet" href="<?php echo assets_url('plugins/prism/prism.css')?>">
      <link rel="stylesheet" href="<?php echo assets_url('css/styles.css')?>">
      <link rel="stylesheet" href="<?php echo assets_url('css/hamburgers.css')?>">
      <link rel="stylesheet" href="<?php echo assets_url('plugins/jquery.mmenu/jquery.mmenu.css')?>">
      <link href="https://fonts.googleapis.com/css?family=Roboto" rel="stylesheet">

    <link href="<?php echo assets_url('css/custom.css')?>" rel="stylesheet">
    <?php if (isset($_css) && $_css) {
      foreach($_css as $e) { 
        echo "<link href='$e' rel='stylesheet'>\n";
      }
    }
    ?>
    <!-- HTML5 shim, for IE6-8 support of HTML5 elements -->
    <!--[if lt IE 9]>
      <script src="<?php echo assets_url('js/html5shiv.js')?>"></script>
    <![endif]-->
      <script src="<?php echo assets_url("plugins/jquery-1.11.3.min.js")?>"></script>
      <script src="<?php echo assets_url("plugins/jquery.easing.1.3.js")?>"></script>
      <script src="<?php echo assets_url("/js/bootstrap.min.js")?>"></script>
      <script src="<?php echo assets_url("/js/bootstrap-datepicker.js")?>"></script>
      <script src="<?php echo assets_url("plugins/jquery-scrollTo/jquery.scrollTo.min.js")?>"></script>
      <script src="<?php echo assets_url("plugins/prism/prism.js")?>"></script>
      <script src="<?php echo assets_url("js/main.js")?>"></script>
      <script src="<?php echo assets_url("plugins/jquery.mmenu/jquery.mmenu.js")?>"></script>

    <link rel="icon" href="<?php echo assets_url('/img/favicon.ico')?>" type="image/x-icon" />
  </head>

  <body>


    <?php if (isset($_template_menu) and $_template_menu == 'templates/menu_lateral'){ echo $_template_menu_content ?>
<script type="text/javascript">
    $(document).ready(function(){

        var $menu = $("#menuL").mmenu({
            //   options
        });
        $('body').prepend('<div id="encabezadoMobile" class="encabezadoAppMobile"><div class="logoCitepMic"><img src="' +
        "<?= assets_url('img/citep_mic.gif')?>" +'" ></div></div>');
        var $icon = $("#my-icon");
        var API = $menu.data( "mmenu" );

        $icon.on( "click", function() {
            $("#menuL").css('display', 'block');
            API.open();
        });
        API.bind( "open:start", function() {
            $("#encabezadoMobile").addClass('encabezadoOculto');
        });
        API.bind( "close:start", function() {
           // $("#encabezadoMobile").removeClass('encabezadoOculto');
        });
        API.bind( "open:finish", function() {
            //$("#encabezadoMobile").addClass('encabezadoOculto');
                $icon.addClass( "is-active" );
        });
        API.bind( "close:finish", function() {
            $("#encabezadoMobile").removeClass('encabezadoOculto');
            $("#menuL").css('display', 'none');
                $icon.removeClass( "is-active" );
        });
    })
</script>
    <?php } ?>
<div class="wrapperTotal container-fluid">
    <div class="row">
        <div class="col-md-12 col-xs-12 sinMargenDerecho">
            <div class="clearfix encabezadoApp">
            <div class="logoCitepMic">
                <a href="<?php echo base_url('/')?>" >
                    <img src="<?= assets_url('img/citep_mic.gif')?>" >
                </a>
            </div>
            <div class="logoCitepUba">
                <a href="http://citep.rec.uba.ar" target="_blank">
                    <img  src="<?= assets_url('img/logo-citep-uba.png')?>" >
                </a>
            </div>
            </div>
        </div>


        <?php if (isset($micSeleccionada) or (isset($_template_menu)) and $_template_menu == 'templates/menu_lateral'){ ?>

            <?php if (isset($_template_menu) and $_template_menu == 'templates/menu_lateral'){ ?>
                <div class="col-md-4 col-xs-3">
                <button id="my-icon" class="hamburger hamburger--spin botonMenu" type="button">
                    <span class="hamburger-box">
                      <span class="hamburger-inner"></span>
                    </span>
                </button>
                </div>
                    <?php if (isset($micSeleccionada)){ ?>
                        <div class="col-md-4 col-xs-9">
                            <img src="<?= assets_url('/img/'. $micSeleccionada .'Large.png') ?>" class="logoMicHeader">
                        </div>
                    <?php }?>
            <?php }else{?>
                    <div class="col-md-4 col-xs-1">
                    </div>
                    <div class="col-md-4 col-xs-10">
                        <img src="<?= assets_url('/img/'. $micSeleccionada .'Large.png') ?>" class="logoMicHeader">
                    </div>
            <?php }?>
        <?php }?>
    </div>
    <div class="row cuerpoContenido">
        <?php if ($error_msgs or $success_msgs){?>
        <div class="col-md-12">
            <div class="cuerpoContenido">
              <?php foreach ($error_msgs as $msg) { ?>
              <div class="alert alert-error" style='position:relative;top:60px;'>
                <button type="button" class="close" data-dismiss="alert">&times;</button>
                <?php echo $msg ?>
              </div>
              <?php } ?>

              <?php foreach ($success_msgs as $msg) { ?>
              <div class="alert alert-success" style='position:relative;top:60px;'>
                <button type="button" class="close" data-dismiss="alert">&times;</button>
                <?php echo $msg ?>
              </div>
              <?php } ?>
            </div>
        </div>
        <?php } ?>

      <?php echo $_template_content ?>
    </div> <!-- /container -->
    <!-- FOOTER -->
    <?php if (isset($micSeleccionada)){ ?>
    <div class="row">
        <div class="col-md-12">
            <div class="footerCitep">
                <div class="col-md-12 citepMICFOOTER">
                + CitepMIC:
                </div>
                <div class="col-md-4">
                </div>
                <?php if ($micSeleccionada != 'focos'){ ?>

                    <div class="col-md-2 col-xs-6">
                        <a href="<?php echo base_url('/')?>" >
                            <span class="logoxxsmall">Focos</span>
                            <span class="logolightxxsmall"> en juego</span><br />
                            <img src="<?= assets_url('/img/foco.png')?>" class="iconoMicFooter" border="0">
                        </a>
                    </div>
                <?php } ?>
                <?php if ($micSeleccionada != 'prismas'){ ?>
                <div class="col-md-2 col-xs-6">
                    <a href="<?php echo base_url('/')?>" >
                        <span class="logoxxsmall">Prismas</span>
                        <span class="logolightxxsmall"> entramados</span><br />
                        <img src="<?= assets_url('/img/prismas.png')?>" class="iconoMicFooter" border="0">
                    </a>
                </div>
                <?php } ?>
                <?php if ($micSeleccionada != 'croquis'){ ?>
                    <div class="col-md-2 col-xs-6">
                        <a href="<?php echo base_url('/')?>" >
                            <span class="logoxxsmall">Croquis</span>
                            <span class="logolightxxsmall"> en movimiento</span><br />
                            <img src="<?= assets_url('/img/croquis.png')?>" class="iconoMicFooter" border="0">
                        </a>
                    </div>
                <?php } ?>
                <div class="col-md-4">
                </div>
                <div class="col-md-12 col-xs-12">
                <div class="logoCitepUbaFooter">
                    <a href="http://citep.rec.uba.ar" target="_blank">
                        <img  src="<?= assets_url('img/logo-citep-uba.png')?>" class="iconoMicFooter" >
                    </a>
                </div>
                </div>
            </div>
        </div>
    </div>
    <?php } ?>
    <!-- Le javascript
    ================================================== -->
    <!-- Placed at the end of the document so the pages load faster -->
</div>

  </body>
</html>
