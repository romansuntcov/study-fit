<? if (!empty($msg)): ?>
<div><?= $msg ?></div>
<? endif; ?>


<form method="POST"">
	<span style="position:relative;left:25px">Email/ID: <input type="text" name="email"></span>
	<span style="position:relative;left:25px">Heslo: <input type="password" name="heslo"></span>
	<input type="submit" class="btn btn-xs btn-success" style="position:relative;left:50px" value="Zákazník" name="zakaznik">
	<input type="submit" class="btn btn-xs btn-danger" style="position:relative;left:50px" value="Zaměstnanec" name="zamestnanec">
	<input type="button" class="btn btn-xs btn-default" style="position:relative;left:50px" value="Registrovat se"
		onclick="window.location='<?= Url::make(array('p' => 'register')); ?>'">
</form>