<script type='text/javascript'> 
  $(document).ready(function() {
    $(".borrar").click(function() { 
      var id = $(this).data('id');
      $('#' + id).removeClass('oculto');

    })
      $(".cerrar").click(function() {
          var id = $(this).data('id');
          $('#' + id).addClass('oculto');

      })


  });
</script> 
<div class="container">
<section>
       <div class="row-fluid">
            <div class="page-header">
              <h1 id="tables">Usuarios</h1></div>
              <a href='<?php echo base_url("/usuarios/add")?>' class='btn btn-large pull-right'><i class="icon-user"></i> nuevo usuario</a>
              <table class="table table-striped table-hover">
                <thead>                
                <tr>
                  <th width="12%">Usuario</th>
                  <th width="30%">Email</th>
                  <th width="15%">Permisos</th>
                  <th width="40%">Acciones</th>
                </tr>
                </thead>
                <tbody>
                  <?php foreach ($users as $u): ?>
                  <tr>

                    <td><?php echo $u->name?></td>
                    <td><?php echo $u->email?></td>
                    <td><?php foreach(explode(',',$u->permissions) as $e) { 
						echo '<span class="label label-info">' . $e . '</span> ';
					}
					?></td>
                    <td>
                        <div class="col-md-4">
                      <a class='btn btn-small' href='<?php echo base_url("/usuarios/edit/" . $u->id)?>'>
                        <i class="icon-edit"></i> editar
                      </a>
                        </div>
                        <div class="col-md-4">
                      <a class='btn btn-small borrar' data-id="<?php echo $u->id?>" href='#passwordModal'>
                        <i class="icon-edit"></i> cambiar clave
                      </a>
                        <form id="<?php echo $u->id?>" class="form-horizontal oculto" method='post' action="<?php echo base_url("/usuarios/cambiar_password/")?>">
                                   <input type='hidden' name='id_user' value='<?php echo $u->id?>' />
                            <div class="control-group">
                                <label class="control-label" for="inputPassword">Nueva clave</label>
                                <div class="controls">
                                    <input type="password" name='password' id="inputPassword">
                                </div>
                            </div>
                                <a href="#" class="btn btn-primary cerrar" data-id="<?php echo $u->id?>">Cerrar</a>
                                <input type='submit' class="btn"  value="Guardar" />
                        </form>
                        </div>
                        <div class="col-md-4">
                      <a onClick='return confirm("Confirme que desea eliminar el usuario");' class='btn btn-small btn-danger' 
                        href='<?php echo base_url("/usuarios/delete/" . $u->id)?>'>
                        <i class="icon-remove icon-white"></i> eliminar
                      </a>
                        </div>
                    </td>
                  </tr>
                  <?php endforeach; ?>
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


<div id='passwordModal' class="modal hide fade">
  <form class="form-horizontal" method='post' action="<?php echo base_url("/usuarios/cambiar_password/")?>">
    <div class="modal-header">
      <h3>Cambiar clave</h3>
    </div>
    <div class="modal-body">
        <input type='hidden' name='id_user' value='' />
        <div class="control-group">
          <label class="control-label" for="inputPassword">Nueva clave</label>
          <div class="controls">
            <input type="password" name='password' id="inputPassword">
          </div>
        </div>
    </div>
    <div class="modal-footer">
      <a href="#" class="btn btn-primary" data-dismiss="modal">Cerrar</a>
      <input type='submit' class="btn"  value="Guardar" />
    </div>
  </form>
</div>