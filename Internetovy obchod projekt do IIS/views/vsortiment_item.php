<form method="POST" class="list-group-item">
	<input type="hidden" name="id" value="<?= $item['id'] ?>">
<table>
	<tr><th>ID: <? if ($item['id'] <= 0) echo 'Nový'; else echo $item['id'] ?></th><th></th></tr>

	<tr>
		<th>Název</th>
		<td><input type="text" name="nazev" value="<?= $item['nazev'] ?>"></td>
	</tr>
	<tr>
		<th>Položka</th>
		<td><?= $stuff ?></td>
	</tr>
	<tr>
		<th>Cena</th>
		<td><input type="text" name="cena" value="<?= $item['cena'] ?>"></td>
	</tr>
	<tr>
		<th>Skladem</th>
		<td><input type="text" name="skladem" value="<?= $item['skladem'] ?>"></td>
	</tr>
	<tr>
		<th>Sleva</th>
		<td><input type="text" name="sleva" value="<?= $item['sleva'] ?>"></td>
	</tr>
	<tr>
		<th>Dodavatel</th>
		<td><?= $delivery ?></td>
	</tr>

	<tr>
		<th></th>
		<td>
			<? if ($item['id'] > 0): ?>
			<input type="submit" name="save_sortiment" value="Uložit">
			<input type="submit" name="remove_sortiment" value="Smazat">
			<? else: ?>
			<input type="submit" name="save_sortiment" value="Přidat">
			<? endif; ?>
		</td>
</table>
</form>