<!DOCTYPE html>
<html lang="en-US">
	<head>
		<meta charset="utf-8">
	</head>
	<body style="padding: 25px; margin: 0; color: #272727; font-family: Verdana; font-size: 10pt;">
		<h1 style="padding: 0; margin: 0; font-size: 11pt;">WELCOME TO CatalogAPI!</h1>
		<p style="margin-top: 25px; font-size: 10pt;">
			User: {{ $user->name }} invited You to join him at CatalogAPI!<br />
			We are glad to have you with us! Please click the link below to begin.
		</p>
		<p style="margin-top: 25px;">
			<a href="{{ url( '/auth/register' ) }}" style="color: #272727;">register your account</a>
		</p>
	</body>
</html>
