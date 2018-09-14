<? if (!empty($message)): ?>
<div class="alert alert-danger"><?= $message ?></div>
<? endif; ?>

<div><?= $menu ?></div>
<div>
	<table class="table table-stripped" style="width: auto;position: relative;top: -103px;left: 150px;">
	<?= $items ?>
	</table>
</div>