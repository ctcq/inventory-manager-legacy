<html>

<head>
</head>

<body>
<?php
//Check if there is an ongoing session
include 'checkOngoing.php';

	if($ongoing){
		echo <<<eof
		<a href='liste.php'>Bardienst fortsetzen</a>
eof;
	}else{
		echo <<<eof
		<form method='post' action='processLogin.php'>
			<label>Name und Zimmernummer</label>
			<input name='name'/>
			<input type=submit value='Bestätigen'/>
		</form>
eof;
	}
	?>
</body>

</html>