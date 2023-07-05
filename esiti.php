<?php
    ini_set("display_errors", "On");
	ini_set("error_reporting", E_ALL);
	include_once('lib/dc_functions.php');

    session_start();

	$error_msg = '';
    $logged = null;
    $logout_link = $_SERVER['PHP_SELF']."?log=del";
    $teacher = array();
	$teachings = array();
	$valued = false;

    if(isset($_SESSION['email'])) {
		$logged = $_SESSION['email'];
		$teacher = get_teacher_information($logged);
		$teachings = get_teacher_teachings($logged);
		$to_evalueate = get_teaching_to_evalueate($logged);

		if(isset($_POST) && isset($_POST['studente']) && isset($_POST['corso']) && isset($_POST['codice']) && isset($_POST['data']) && isset($_POST['voto'])) {
			$error_msg = update_teaching_value($_POST['studente'], $_POST['corso'], $_POST['codice'], $_POST['data'], $_POST['voto']);
			$valued = true;
			$to_evalueate = get_teaching_to_evalueate($logged);
		}
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
			<h3 class="text-center">Appelli da valutare</h3>
			<div class="container mt-5 border-bottom">
				<h4 class="text-center">Elenco degli appelli che possono essere registrati</h3>
				<table class="table table-striped border mt-3">
					<thead>
						<th class="col">Studente</th>
						<th class="col">Corso di laurea</th>
						<th class="col">Codice dell'insegnamento</th>
						<th class="col">Data</th>
					</thead>
					<tbody>
						<?php foreach($to_evalueate as $values) { ?>
							<tr>
								<td>
									<?php echo $values[0]; ?>
								</td>
								<td>
									<?php echo $values[1]; ?>
								</td>
								<td>
									<?php echo $values[2]; ?>
								</td>
								<td>
									<?php echo $values[3]; ?>
								</td>
							</tr>
						<?php }	?>
					</tbody>
				</table>
			</div>
			<div class="container mt-5 border">
				<form method="post">
					<h4 class="text-center">Form per iscrizione esame</h4>
					<div class="mb-3 w-50 mx-auto">
						<label for="studente" class="form-label">Matricola dello studente</label>
						<input type="text" name="studente" class="form-control">
					</div>
					<div class="mb-3 w-50 mx-auto">
						<label for="corso" class="form-label">Corso di laurea</label>
						<input type="text" name="corso" class="form-control">
					</div>
					<div class="mb-3 w-50 mx-auto">
						<label for="codice" class="form-label">Codice dell'insegnamento</label>
						<input type="text" name="codice" class="form-control">
					</div>
					<div class="mb-3 w-50 mx-auto">
						<label for="data" class="form-label">Data dell'appello</label>
						<input type="text" name="data" class="form-control">
					</div>
					<div class="mb-3 w-50 mx-auto">
						<label for="voto" class="form-label">Voto dell'insegnamento</label>
						<input type="number" name="voto" class="form-control">
					</div>
					<div class="mb-3 w-50 mx-auto text-center">
						<button type="submit" class="btn btn-primary">Registra esito esami</button>
					</div>
				</form>
			</div>
        <?php } ?>

		<div class="container mt-5">
			<?php if($valued) { ?>
				<?php if(empty($error_msg)) {?>
					<div class="alert alert-success text-center border" role="alert">
						<p><?php echo("Valutazione dell'appello confermata");?></p>
					</div>
				<?php } else { ?>
					<div class="alert alert-warning mt-5 text-center" role="alert">
						<p><?php echo $error_msg; ?></p>
					</div>
				<?php } ?>
			<?php } ?>
		</div>
	</div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js" integrity="sha384-geWF76RCwLtnZ8qwWowPQNguL3RmwHVBC9FhGdlKrxdiJJigb/j/68SIy3Te4Bkz" crossorigin="anonymous"></script>
</body>
</html>