<? if (!empty($msg)): ?>
<div class="alert alert-danger"><?= $msg ?></div>
<? endif ?>

<form method="POST">
	<? if (isset($_SESSION['user']['id_zakaz'])): ?>
	<input type="hidden" name="id_zakaz" value="<?= $_SESSION['user']['id_zakaz']?>">
	<? elseif (isset($_SESSION['user']['id_zam'])): ?>
	<input type="hidden" name="id_zam" value="<?= $_SESSION['user']['id_zam']?>">
	<? endif ?>

	<table class="table table-striped" style="width: auto">
		<tr>
			<td>Jméno: </td>
			<td><input type="text" name="jmeno" value="<?= $_SESSION['user']['jmeno'] ?>"></td>
		</tr>
		<tr>
			<td>Příjmení: </td>
			<td><input type="text" name="prijmeni" value="<?= $_SESSION['user']['prijmeni'] ?>"></td>
		</tr>
		<tr>
			<td>Adresa: </td>
			<td><input type="text" name="adresa" value="<?= $_SESSION['user']['adresa'] ?>"></td>
		</tr>

		<? if (isset($_SESSION['user']['id_zam'])): ?>
		<tr>
			<td>Telefon: </td>
			<td><input type="text" name="telefon" value="<?= $_SESSION['user']['telefon'] ?>"></td>
		</tr>
		<? endif ?>
		
		<tr><td colspan="2">
			<input type="submit" class="btn btn-sm btn-success" name="aktualizovat" value="Aktualizovat">
		</td></tr>
	</table>
</form>