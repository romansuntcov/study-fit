<form method="POST">
	<input type="hidden" name="id" value="<?= $id ?>">

<table class="table table-stripped">
	<tr>
		<th>ID</th>
		<td><?= $id ?></td>
	</tr>
	<tr>
		<th>Název</th>
		<td><input type="text" name="nazev" value="<?= $nazev ?>"></td>
	</tr>
	<tr>
		<th>Titulek 1</th>
		<td><input type="text" name="label1" value="<?= $label1 ?>"></td>
	</tr>
	<tr>
		<th>Titulek 2</th>
		<td><input type="text" name="label2" value="<?= $label2 ?>"></td>
	</tr>
	<tr>
		<th>Titulek 3</th>
		<td><input type="text" name="label3" value="<?= $label3 ?>"></td>
	</tr>
	<tr>
		<th>Titulek 4</th>
		<td><input type="text" name="label4" value="<?= $label4 ?>"></td>
	</tr>
	<tr>
		<th>Titulek 5</th>
		<td><input type="text" name="label5" value="<?= $label5 ?>"></td>
	</tr>
	<tr>
		<th></th>
		<td><input type="submit" name="save_category" value="Uložit"></td>
	</tr>
</table>
</form>