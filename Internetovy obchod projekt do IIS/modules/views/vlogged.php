<? if(isset($_SESSION['user']['id_zakaz'])): ?>
<div style="text-align: right">Přihlášen jako: <?= "$jmeno $prijmeni ($e_mail)" ?><a href="<?= Url::make(array('p' => 'logout'))?>">Odhlásit</a></div>
<? elseif (isset($_SESSION['user']['id_zam'])): ?>
<div style="text-align: right">Přihlášen jako: <?= "$jmeno $prijmeni" ?><a href="<?= Url::make(array('p' => 'logout')) ?>">Odhlásit</a></div>
<? endif; ?>