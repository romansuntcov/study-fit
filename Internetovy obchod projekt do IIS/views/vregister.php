<? if (!empty($msg)): ?>
<div><?= $msg ?></div>
<? endif ?>

<form method="POST">
	<table class="table table-condensed" style="width: auto">
		<thead>
			<tr>
				<th colspan="2">Registrační fomulář</th>
			</tr>
		</thead>
		
		<tbody>
			<tr>
				<td>Email:</td><td><input type="text" name="email" value="<?= $email ?>"></td>
			</tr>
			<tr>
				<td>Jméno:</td><td><input type="text" name="jmeno" value="<?= $jmeno ?>"></td>
			</tr>
			<tr>
				<td>Příjmení:</td><td><input type="text" name="prijmeni" value="<?= $prijmeni ?>"></td>
			</tr>
			<tr>
				<td>Adresa:</td><td><input type="text" name="adresa" value="<?= $adresa ?>"></td>
			</tr>
			<tr>
				<td>Heslo:</td><td><input type="password" name="heslo"></td>
			</tr>
			<tr>
				<td></td>
				<td><input type="submit" class="btn btn-sm btn-success" value="Registrovat" name="registrovat"></td>
			</tr>
		</tbody>
	</table>
</form>