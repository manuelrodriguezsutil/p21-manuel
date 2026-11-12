<?php
// Página de prueba: muestra quién ejecuta el código PHP
$contenedor = gethostname();
?>

<!DOCTYPE html>
<html lang="es">
<head>
	<meta charset="utf-8">
	<title>PHP en Docker</title>
</head>
<body>
	<h1>Hola desde PHP <?= PHP_VERSION ?></h1>
	<p>Contenedor que ejecuta este código: <b><?= htmlspecialchars($contenedor) ?></b></p>
	<p>Fichero ejecutado: <?= htmlspecialchars($_SERVER['SCRIPT_FILENAME']) ?></p>
	<p>Fecha y hora: <?= date('d/m/Y H:i:s') ?></p>

	<?php phpinfo() ?>
</body>
</html>
