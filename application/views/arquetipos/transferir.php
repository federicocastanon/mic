<h1>Transferencia de ejercicios</h1>
<p> Usted está por transferir el ejercicio titulado: "<i><?php echo $ejercicio->nombre?></i>" con identificador  <b><?php echo $ejercicio->id?></b></p>
<form method='post'>
    <select id="user_id" name="user_id" title="Elija un usuario para transferirle el ejercicio">
        <?php foreach ($users as $u): ?>
            <option value="<?php echo $u->id?>"> <?php echo $u->name?></option>

        <?php endforeach; ?>
    </select>

    <button type="submit" class="btn btn-primary btn-large">TRANSFERIR</button>
</form>