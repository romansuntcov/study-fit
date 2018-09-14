<form method="POST">

<table class="table table-striped">
	<thead>
		<tr>
			<th>Název</th>
			<th>Cena/ks</th>
			<th>Počet</th>
			<th>Celkem</th>
			<th></th>
		</tr>
	</thead>
	<tbody>
		<?= $items ?>
		<tr>
			<th>Celkem</th>
			<th></th>
			<th><?= $count ?></th>
			<th><?= $sum ?></th>
			<th></th>
		</tr>
	</tbody>
</table>

<!-- Vyber kuryra -->
<?= $couriers ?>

<table class="table table-striped" style="width: auto">
	<thead>
		<tr>
			<th colspan="2">Doručovací údaje</th>
		</tr>
	</thead>
	<tbody>
		<tr><td>Ulice: </td><td><input type="text" name="ulice"></td></tr>
		<tr><td>Město: </td><td><input type="text" name="mesto"></td></tr>
		<tr><td>PSČ: </td><td><input type="text" name="psc"></td></tr>
	</tbody>
</table>

<input type="submit" name="objednat" value="Odeslat objednavku" class="btn btn-sm btn-success">
</form>