<? if (!empty($message)): ?>
<div class="alert alert-danger"><?= $message ?></div>
<? endif; ?>

<h2>Sortiment nabízeného zboží</h2>
<div class="list-group">
<?= $items ?>
</div>