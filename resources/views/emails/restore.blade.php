<!DOCTYPE html>
<html lang="en-US">
	<head>
		<meta charset="utf-8">
	</head>
	<body style="padding: 25px; margin: 0; color: #272727; font-family: Verdana; font-size: 10pt;">
		<h1 style="padding: 0; margin: 0; font-size: 11pt;">Restore password</h1>
		<p style="margin-top: 25px; font-size: 10pt;">
			Hi {{ $user->name }}!
			To reset your password, visit the following URL.
		</p>
		<p style="margin-top: 25px;">
			<a href="{{ url( '/auth/restore/confirm/'.$token ) }}" style="color: #272727;">restore password</a>
		</p>
	</body>
</html>
