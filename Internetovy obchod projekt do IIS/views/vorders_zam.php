<h3>Nezaplacené objednávky</h3>
<table class="table table-striped">
	<thead>
		<tr>
			<th>Zákazník</th>
			<th>Číslo</th>
			<th>Datum</th>
			<th>Cena</th>
			<th></th>
		</tr>
	</thead>
	<tbody>
		<?= $not_payed_orders ?>
	</tbody>
</table>

<h3>Zaplacené objednávky</h3>
<table class="table table-striped">
	<thead>
		<tr>
			<th>Zákazník</th>
			<th>Číslo</th>
			<th>Datum</th>
			<th>Cena</th>
			<th></th>
		</tr>
	</thead>
	<tbody>
		<?= $payed_orders ?>
	</tbody>
</table>