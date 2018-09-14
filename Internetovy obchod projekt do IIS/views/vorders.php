<h3 style="text-align: center">Nezaplacené/Nezpracované objednávky</h3>
<table class="table table-striped">
	<thead>
		<tr>
			<th>Číslo</th>
			<th>Datum</th>
			<th>Cena</th>
		</tr>
	</thead>
	<tbody>
		<?= $not_payed_orders ?>
	</tbody>
</table>

<h3 style="text-align: center">Zaplacené/Staré objednávky</h3>
<table class="table table-striped">
	<thead>
		<tr>
			<th>Číslo</th>
			<th>Datum</th>
			<th>Cena</th>
		</tr>
	</thead>
	<tbody>
		<?= $payed_orders ?>
	</tbody>
</table>