<?php
// Prueba de subida: NO guarda el fichero, solo informa de lo que ha llegado
$mensajes = [
	UPLOAD_ERR_OK => 'Subida correcta',
	UPLOAD_ERR_INI_SIZE => 'Rechazada por PHP: supera upload_max_filesize',
	UPLOAD_ERR_PARTIAL => 'El fichero llegó incompleto',
	UPLOAD_ERR_NO_FILE => 'No has elegido ningún fichero',
];

$f = $_FILES['fichero'] ?? null;
?>

<!DOCTYPE html>
<html lang="es">

<head>
	<meta charset="utf-8">
	<title>Prueba de subida</title>
</head>

<body>
	<h1>Prueba de subida</h1>
	<form method="post" enctype="multipart/form-data">
		<input type="file" name="fichero">
		<button>Subir</button>
	</form>

	<?php if ($f): ?>
		<p>Fichero: <?= htmlspecialchars($f['name']) ?> · <?= round($f['size'] / 1048576, 1) ?> MB recibidos</p>
		<p>Resultado: <b><?= $mensajes[$f['error']] ?? 'Error ' . $f['error'] ?></b></p>
	<?php endif; ?>

	<p>Límites de PHP: upload_max_filesize = <?= ini_get('upload_max_filesize') ?>, post_max_size = <?= ini_get('post_max_size') ?></p>

	<p>Temporal: <?= htmlspecialchars($f['tmp_name']) ?></p>
	<?php sleep(15); ?>	
</body>

</html>
