<ul>
	<? foreach ($secciones[$titulo] as $s) :?>
	<li> 
         
    <table width="100%" cellpadding="5">
    <tr>
	    <td><?= $s->nombre ?></td>
	    <td width="110">
	        <a href='#editar_subseccion' class='btn btn-small' data-id='<?=$s->id?>' data-titulo='<?= $titulo?>'><i class="fa fa-pencil"></i> Modificar</a> </td>
		<td width="13"></td>
	    <td width="110">
			<a href='<?=base_url('/proyectos/borrar_subseccion/' . $s->id)?>' class='btn btn-small btn-danger' 
	        	onClick='return confirm("Por favor confirme el borrado de esta subsección")'>
	        	<i class="fa fa-trash-o"></i> Borrar
	        </a>
		</td>
    </tr>    
    <tr><td><p><?= $s->descripcion ?> </p></td>
    </tr>
    </table>        
</li>
	<? endforeach ?>
</ul>

