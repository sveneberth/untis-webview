<!DOCTYPE html>
<html lang="de">
	<head>
		<meta charset="UTF-8">
		<title>{{errorcode}} {{status}}</title>
	</head>
	<body>
		<h1>{{errorcode}} {{status}}</h1>
		<p>{{message}}</p>
		<p><a href="{{MAIN_URL}}"><?= __('back to home') ?></a></p>
		<hr>
		<?= $_SERVER['SERVER_SIGNATURE'] ?>
	</body>
</html>
