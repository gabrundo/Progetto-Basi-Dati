<?php
    ini_set("display_errors", "On");
	ini_set("error_reporting", E_ALL);
	include_once('lib/dc_functions.php');

    session_start();

    $logged = null;
    $logout_link = $_SERVER['PHP_SELF']."?log=del";
    $teacher = array();

    if(isset($_SESSION['email'])) {
		$logged = $_SESSION['email'];
		$teacher = get_teacher_information($logged);
    }

    if(isset($_GET) && isset($_GET['log']) && $_GET['log'] == 'del') {
		unset($_SESSION['email']);
		$logged = null;
		sleep(1);
		http_response_code(301);
		header("Location: index.php");
		exit();
	}
?>

<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Calendario appelli d'esame</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-9ndCyUaIbzAi2FUVXJi0CjmCapSmO7SnpJef0486qhLnuZ2cdeRhO02iuK6FUUVM" crossorigin="anonymous">
</head>
<body>
	<nav class="navbar navbar-expand-lg bg-body-tertiary">
		<div class="container-fluid">
			<a class="navbar-brand" href="#">Funzionalità</a>
			<button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNavAltMarkup" aria-controls="navbarNavAltMarkup" aria-expanded="false" aria-label="Toggle navigation">
			<span class="navbar-toggler-icon"></span>
			</button>
			<div class="collapse navbar-collapse" id="navbarNavAltMarkup">
			<div class="navbar-nav">
				<a class="nav-link active" aria-current="page" href="docente.php">Home</a>
				<a class="nav-link" href="calendario.php">Calendario Esami</a>
			</div>
			</div>
		</div>
	</nav>
	<div class="container-xl mt-5 border-bottom">
		<h1 class="pb-2 border-bottom text-center">Registrazione esiti appelli d'esame</h1>
		<?php if(isset($logged)) { ?>
			<div class="container mt-5">
				<div class="alert alert-success text-center border" role="alert">
				<p>
					<?php echo("Benvenuto docente $teacher[0] $teacher[1], se vuoi effetuare il logout clicca ");?>
					<a href="<?php echo $logout_link;?>">qui</a>
				</p>
			</div>
            <form action="" method="post">
                
            </form>
        <?php } ?>
	</div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js" integrity="sha384-geWF76RCwLtnZ8qwWowPQNguL3RmwHVBC9FhGdlKrxdiJJigb/j/68SIy3Te4Bkz" crossorigin="anonymous"></script>
</body>
</html>