<?php
    ini_set("display_errors", "On");
	ini_set("error_reporting", E_ALL);

	session_start();

    $logged = null;
    $logout_link = $_SERVER['PHP_SELF']."?log=del";

    if(isset($_SESSION['email'])) {
		$logged = $_SESSION['email'];
	}

    if(isset($_GET) && isset($_GET['log']) && $_GET['log'] == 'del') {
		unset($_SESSION['email']);
		$logged = null;
	}
?>

<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Docente</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-9ndCyUaIbzAi2FUVXJi0CjmCapSmO7SnpJef0486qhLnuZ2cdeRhO02iuK6FUUVM" crossorigin="anonymous">
</head>
<body>
	<div class="container-xl">
	    <h1 class="pb-2 border-bottom text-center">Pagina principale del docente</h1>
		<?php if(isset($logged)) { ?>
			<div class="container mt-5">
				<div class="alert alert-success text-center" role="alert">
				<p>
					<?php echo("Benvenuto docente $logged, se vuoi effetuare il logout clicca ");?>
					<a href="<?php echo $logout_link;?>">qui</a>
				</p>
			</div>
		<?php } ?>
	</div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js" integrity="sha384-geWF76RCwLtnZ8qwWowPQNguL3RmwHVBC9FhGdlKrxdiJJigb/j/68SIy3Te4Bkz" crossorigin="anonymous"></script>
</body>
</html>