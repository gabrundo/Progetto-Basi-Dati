<?php
    ini_set("display_errors", "On");
	ini_set("error_reporting", E_ALL);
	include_once('lib/dc_functions.php');

	session_start();

    $error_msg = "";
    $logged = null;
    $logout_link = $_SERVER['PHP_SELF']."?log=del";
	$psw_change = false;
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

	if(isset($logged) && isset($_POST['oldpsw']) && isset($_POST['newpsw'])) {
		$s = change_teacher_password($logged, $_POST['oldpsw'], $_POST['newpsw']);

		$error_msg = $s[1];
		$psw_change = $s[0];

		$_POST['oldpws'] = null;
		$_POST['newpsw'] = null;
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
	<nav class="navbar navbar-expand-lg bg-body-tertiary">
		<div class="container-fluid">
			<a class="navbar-brand" href="#">Funzionalità</a>
			<button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNavAltMarkup" aria-controls="navbarNavAltMarkup" aria-expanded="false" aria-label="Toggle navigation">
			<span class="navbar-toggler-icon"></span>
			</button>
			<div class="collapse navbar-collapse" id="navbarNavAltMarkup">
			<div class="navbar-nav">
				<a class="nav-link active" aria-current="page" href="docente.php">Home</a>
				<a class="nav-link" href="esiti.php">Esiti Esami</a>
				<a class="nav-link" href="calendario.php">Calendario Appelli</a>
			</div>
			</div>
		</div>
	</nav>
	<div class="container-xl mt-5 border-bottom">
		<h1 class="pb-2 border-bottom text-center">Portale del docente</h1>
		<?php if(isset($logged)) { ?>
			<div class="container mt-5">
				<div class="alert alert-success text-center border" role="alert">
				<p>
					<?php echo("Benvenuto docente $teacher[0] $teacher[1], se vuoi effetuare il logout clicca ");?>
					<a href="<?php echo $logout_link;?>">qui</a>
				</p>
			</div>
			<div class="container mt-5">
				<table class="table table-striped">
					<h3 class="text-center">Informazioni del docente</h3>
					<thead>
						<tr>
							<th scope="col">Informazione</th>
							<th scope="col">Valore</th>
						</tr>
					</thead>
					<tbody>
						<tr>
							<th scope="row">Nome</th>
							<td><?php echo $teacher[0]; ?></td>
						</tr>
						<tr>
							<th scope="row">Cognome</th>
							<td><?php echo $teacher[1]; ?></td>
						</tr>
						<tr>
							<th scope="row">Email</th>
							<td><?php echo $logged; ?></td>
						</tr>
					</tbody>
				</table>
			</div>
			<?php if(!$psw_change) { ?>
				<div class="container mt-5">
					<h3 class="text-center">Cambiare la password del docente</h3>
					<form action="" method="post">
						<div class="row g-3 mb-3">
							<div class="col">
								<input type="password" name="oldpsw" class="form-control" placeholder="Password corrente" aria-label="Password corrente">
							</div>
							<div class="col">
								<input type="password" name="newpsw" class="form-control" placeholder="Password nuova" aria-label="Password nuova">
							</div>
						</div>
						<div class="row mb-3">
							<div class="col text-center">
								<button type="submit" class="btn btn-primary">Cambia password</button>
							</div>
						</div>
					</form>
				</div>
				<?php if(!empty($error_msg)) { ?>
					<div class="alert alert-warning mt-5 text-center" role="alert">
                		<p> <?php echo $error_msg; ?> </p>
            		</div>
				<?php } ?>
			<?php } else { ?>
				<div class="container mt-5">
					<div class="alert alert-success text-center border" role="alert">
						<p><?php echo("Password cambiata con successo");?></p>
					</div>
				</div>
			<?php }?>
		<?php } ?>
	</div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js" integrity="sha384-geWF76RCwLtnZ8qwWowPQNguL3RmwHVBC9FhGdlKrxdiJJigb/j/68SIy3Te4Bkz" crossorigin="anonymous"></script>
</body>
</html>