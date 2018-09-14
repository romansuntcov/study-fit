<form method="POST" class="list-group-item">
	<span><b>ID: <? if ($id <= 0) echo 'Nový'; else echo $id ?></b></span>

	<? if (!is_null($label1)): ?>
	<span><?= $label1 ?>: <input type="text" name="val1" value="<?= $val1 ?>"></span>
	<? endif; ?>

	<? if (!is_null($label2)): ?>
	<span><?= $label2 ?>: <input type="text" name="val2" value="<?= $val2 ?>"></span>
	<? endif; ?>

	<? if (!is_null($label3)): ?>
	<span><?= $label3 ?>: <input type="text" name="val3" value="<?= $val3 ?>"></span>
	<? endif; ?>

	<? if (!is_null($label4)): ?>
	<span><?= $label4 ?>: <input type="text" name="val4" value="<?= $val4 ?>"></span>
	<? endif; ?>

	<? if (!is_null($label5)): ?>
	<span><?= $label5 ?>: <input type="text" name="val5" value="<?= $val5 ?>"></span>
	<? endif; ?>

	<input type="hidden" name="id" value="<?= $id ?>">
	<input type="hidden" name="id_typ" value="<?= $id_typ ?>">

	<? if ($id > 0): ?>
	<input type="submit" name="save_stuff" value="Uložit">
	<input type="submit" name="remove_stuff" value="Smazat">
	<? else: ?>
	<input type="submit" name="save_stuff" value="Přidat">
	<? endif; ?>
</form>