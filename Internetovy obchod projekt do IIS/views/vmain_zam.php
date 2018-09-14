<? if (!empty($message)): ?>
<div class="alert alert-danger"><?= $message ?></div>
<? endif; ?>



<h2>Skupiny zboží</h2>
<div class="list-group">
	<?= $groups ?>
</div>
<?= $selection ?>

<h2>Zboží položky (pro vybranou kategorii)</h2>
<div class="list-group">
<?= $items ?>
</div>