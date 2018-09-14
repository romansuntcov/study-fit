<!DOCTYPE html>
<html lang="cs">
<head>
	<title><?= $title ?></title>
	<!-- <meta charset="iso-8859-2"> -->
	<meta charset="UTF-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Bootstrap -->
    <link href="css/bootstrap.min.css" rel="stylesheet">
    <link href="css/bootstrap-theme.min.css" rel="stylesheet">
    <link href="css/base.css" rel="stylesheet">

    <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
    <!--[if lt IE 9]>
      <script src="js/html5shiv.min.js"></script>
      <script src="js/respond.min.js"></script>
    <![endif]-->
</head>
<body>
	<!-- Hlavicka -->
	<div id="header"><?= $header ?></div>

	<!-- Telo -->
	<div id="body">

		<!-- Formular pro prihlaseni -->
		<? if ($_SESSION['page'] != 'register'): ?>
		<?= $login ?>
		<? endif; ?>

		<? if (isset($_SESSION['user']) && isset($_SESSION['user']['id_zakaz'])): ?>
		<?= $cart ?>
		<? endif; ?>

		<!-- Menu -->
		<?= $menu ?>

		<!-- Telo -->
		<?= $body ?>
	</div>

	<!-- Spodek -->
	<div style="text-align: center" id="footer"><?= $footer ?></div>
</body>
</html>