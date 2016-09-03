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
    <link href="<?php echo assets_url('css/bootstrap.min.css')?>" rel="stylesheet" media='screen'>
    <link href="<?php echo assets_url('css/bootstrap-responsive.css')?>" rel="stylesheet">
    <link href="//netdna.bootstrapcdn.com/font-awesome/3.2.1/css/font-awesome.css" rel="stylesheet">
    <link href="//netdna.bootstrapcdn.com/font-awesome/4.0.3/css/font-awesome.css" rel="stylesheet">
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
    <script src="<?php echo assets_url("/js/jquery-1.10.2.min.js")?>"></script>
    <link rel="icon" href="<?php echo assets_url('/img/favicon.ico')?>" type="image/x-icon" />
  </head>

  <body>
    <?php if (!(isset($_hide_menu) && $_hide_menu)) echo $_template_menu_content ?>

    <div class="container">
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
      <?php echo $_template_content ?>
    </div> <!-- /container -->
    <?php if (!(isset($_hide_footer) && $_hide_footer)) { ?> 
    <!-- FOOTER -->
    
    <?php } ?>
    <!-- Le javascript
    ================================================== -->
    <!-- Placed at the end of the document so the pages load faster -->
    <script src="<?php echo assets_url("/js/bootstrap.min.js")?>"></script>
    <script src="<?php echo assets_url("/js/bootstrap-datepicker.js")?>"></script>
  </body>
</html>
