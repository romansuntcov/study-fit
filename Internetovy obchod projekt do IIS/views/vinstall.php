<div style="width:500px; margin: 0 auto;">
	<? if (!empty($msg)): ?>
	<div><?= $msg ?></div>
	<? endif ?>
	
	<form method="POST">
	<table class="table table-striped">
		<tr>
			<td>Host</td>
			<td><input type="text" name="host" value="<?= $host ?>"></td>
		</tr>
		<tr>
			<td>Name</td>
			<td><input type="text" name="name" value="<?= $name ?>"></td>
		</tr>
		<tr>
			<td>User</td>
			<td><input type="text" name="user" value="<?= $user ?>"></td>
		</tr>
		<tr>
			<td>Password</td>
			<td><input type="password" name="pass"></td>
		</tr>
	</table>
		<div style="text-align: center">
			<input type="submit" value="Create new tables in database" name="btnSubmit" class="btn btn-sm btn-success">
		</div>
	</form>
</div>