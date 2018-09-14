<ul class="nav nav-tabs" role="tablist">
	<li class="<? if ($_SESSION['page'] == 'main') echo 'active' ?>" role="presentation">
		<a href="<?= Url::base() ?>">Domů</a>
	</li>

	<? if (isset($_SESSION['user'])): ?>
	<? if (isset($_SESSION['user']['id_zam'])): ?>
		<li class="<? if ($_SESSION['page'] == 'sortiment') echo 'active' ?>" role="presentation">
			<a href="<?= Url::make(array('p' => 'sortiment')) ?>">Sortiment zboží</a>
		</li>
	<? endif ?>

	<? if (isset($_SESSION['user']['id_zakaz'])): ?>
		<li class="<? if ($_SESSION['page'] == 'cart') echo 'active' ?>" role="presentation">
			<a href="<?= Url::make(array('p' => 'cart')) ?>">Košík</a>
		</li>
	<? endif ?>

	<li class="<? if ($_SESSION['page'] == 'account') echo 'active' ?>" role="presentation">
		<a href="<?= Url::make(array('p' => 'account')) ?>">Můj účet</a>
	</li>

		<? if (isset($_SESSION['user']['id_zakaz'])): ?>
		<li class="<? if ($_SESSION['page'] == 'orders') echo 'active' ?>" role="presentation">
			<a href="<?= Url::make(array('p' => 'orders')) ?>">Moje objednávky</a>
		</li>
		<? elseif (isset($_SESSION['user']['id_zam'])): ?>
		<li class="<? if ($_SESSION['page'] == 'orders') echo 'active' ?>" role="presentation">
			<a href="<?= Url::make(array('p' => 'orders')) ?>">Objednávky</a>
		</li>
		<? endif ?>
	<? endif ?>
</ul>