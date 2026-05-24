<!DOCTYPE html>
<html lang="es">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>Contraseña actualizada</title>
</head>
<body style="margin:0;padding:0;background:#f5f1ea;font-family:Georgia, 'Times New Roman', serif;color:#1f2937;">
	<div style="max-width:640px;margin:0 auto;padding:40px 24px;">
		<div style="background:#fffaf2;border:1px solid #e5dccf;border-radius:20px;padding:40px 32px;box-shadow:0 18px 40px rgba(31,41,55,0.08);">
			<p style="margin:0 0 12px;font-size:12px;letter-spacing:0.24em;text-transform:uppercase;color:#9a3412;">Topblex</p>
			<h1 style="margin:0 0 20px;font-size:24px;line-height:1.15;color:#111827;">Hola, {{ $user->name }}</h1>
			<p style="margin:0 0 16px;font-size:16px;line-height:1.7;">
				Te confirmamos que tu contraseña en TopBlex ha sido actualizada correctamente. Si no fuiste tú, contacta con soporte inmediatamente.
			</p>
			<p style="margin:24px 0 0;font-size:14px;line-height:1.7;color:#4b5563;">
				Si necesitas ayuda, responde este correo o visita nuestro centro de soporte.
			</p>
		</div>
	</div>
</body>
</html>
