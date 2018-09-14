<tr>
	<? if (isset($_SESSION['user']['id_zam'])): ?>
	<td><?= $id_zakaz ?></td>
	<? endif ?>

	<td><?= $id ?></td>
	<td><a href="<?= $url ?>"><?= $datum ?></a></td>
	<td><?= $cena ?> Kč</td>

	<? if (!$zaplaceno && isset($_SESSION['user']['id_zam'])): ?>
		<td><a href="<?= $urlpayed ?>">Označit jako zaplacená</a></td>
		<td><a href="<?= $urlremove ?>">Odstranit</a></td>
	<? else: ?>
		<td></td>
	<? endif ?>
</tr>