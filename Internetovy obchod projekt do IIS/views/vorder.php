<h3>Objednávka č.&nbsp;<?= $data['id'] ?> --- <?= $data['datum'] ?></h3>

<div class="list-group">
	<div class="list-group-item">
		<h4 class="list-group-item-heading">Prodávající</h4>
		<p class="list-group-item-text">Internetový obchod Toys for Tots<br>
		Skácelova 69, Brno, bla bla</p>
	</div>
	
	<div class="list-group-item">
		<h4 class="list-group-item-heading">Kupující</h4>
		<p class="list-group-item-text"><?= $data['z_jmeno'].' '.$data['z_prijmeni']; ?><br>
		<?= $data['adresa'] ?></p>
	</div>
	
	<div class="list-group-item">
		<h4 class="list-group-item-heading">Dopravce</h4>
		<p class="list-group-item-text"><?= $data['k_jmeno'].' '.$data['k_prijmeni']; ?><br>
		<?= $data['k_telefon'] ?></p>
	</div>
</div>

<table class="table table-striped">
	<thead>
		<tr>
			<th>Název</th>
			<th>Počet</th>
			<th>Cena/ks</th>
			<th>Cena</th>
		<tr>
	</thead>
	<tbody>
		<?= $items ?>
		
		<tr>
			<th colspan="3">Celkem</th>
			<th><?= $sum ?> Kč</th>
		<tr>
	</tbody>
</table>

<? if ($zaplaceno): ?>
	<div style="text-align: center">
		<button onclick="window.print();" class="btn btn-lg btn-primary">Vytisknout</button>
	</div>
<? endif ?>