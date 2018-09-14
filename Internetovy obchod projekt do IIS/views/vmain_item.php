<tr>
	<td><?= $nazev ?>


	<? if (!is_null($val1)) echo "<td>$val1</td>" ?>
	<? if (!is_null($val2)) echo "<td>$val2</td>" ?>
	<? if (!is_null($val3)) echo "<td>$val3</td>" ?>
	<? if (!is_null($val4)) echo "<td>$val4</td>" ?>
	<? if (!is_null($val5)) echo "<td>$val5</td>" ?>

	<td><?= $cena ?> kč</td>
	<td><?= $skladem ?> ks</td>

	<td>
		<? if (isset($_SESSION['user']) && isset($_SESSION['user']['id_zakaz'])) : ?>
		<form method="POST">
			<input type="hidden" name="id" value="<?= $id ?>">
			<input type="hidden" name="warehouse" value="<?= $skladem ?>">

			<input type="text" name="count">
			<input type="submit" name="add" value="Pridat">

			<? if ($exists): ?>
				<input type="submit" name="remove" value="Odebrat">
			<? endif ?>
		</form>
		<? else: ?>
			Zboží lze přidat až po přihlášení(<a href="<?= Url::make(array('p' => 'register')) ?>">registrace</a>)
		<? endif ?>

	</td>
</tr>