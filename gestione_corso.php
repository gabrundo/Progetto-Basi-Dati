<?php
    ini_set("display_errors", "On");
	ini_set("error_reporting", E_ALL);
	include_once('lib/sg_functions.php');

	session_start();

	$added = false;
    $error_msg = "";
    $logged = null;
    $logout_link = $_SERVER['PHP_SELF']."?log=del";
	$tipologia = null;
	$segretary = array();

    if(isset($_SESSION['email'])) {
		$logged = $_SESSION['email'];
		$segretary = get_segretary_information($logged);

		if(isset($_POST)) {
			if(isset($_POST['cbutton'])) {
				switch ($_POST['ctipo']) {
					case "tr":
						$tipologia = 'Triennale';
						break;
					case "mg":
						$tipologia = 'Magistrale';
						break;
				}
				$error_msg = insert_new_course($_POST['cnome'], $tipologia, $segretary[2]);
				$added = true;
			} else if(isset($_POST['ibutton'])) {
				$error_msg = insert_new_teaching($_POST['icorso'], substr($_POST['icodice'], 0, 3), $_POST['inome'], $_POST['ianno'], $_POST['idesc'], $_POST['iresp']);
				$added = true;
			} else {
				$error_msg = "Aggiornamento corso di laurea non riuscito";
			}
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
    <title>Segretario</title>
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
				<a class="nav-link active" aria-current="page" href="segretario.php">Home</a>
				<a class="nav-link" href="gestione_utenti.php">Gestione utenti</a>
			</div>
			</div>
		</div>
	</nav>
	<div class="container-xl mt-5 border-bottom">
		<h1 class="pb-2 border-bottom text-center">Gestione corso di laurea ed insegnamento</h1>
		<?php if(isset($logged)) { ?>
			<div class="container mt-5">
				<div class="alert alert-success text-center border" role="alert">
				<p>
					<?php echo("Benvenuto segretario $segretary[0] $segretary[1], se vuoi effetuare il logout clicca ");?>
					<a href="<?php echo $logout_link;?>">qui</a>
				</p>
			</div>
			<div class="container mt-5">
				<h3 class="text-center">Form per gestione corso e insegnamento</h3>
				<div class="row">
					<div class="col border mt-3">
						<h4 class="text-center">Inserimento di un nuovo corso di laurea</h4>
						<form action="" method="post">
							<div class="mb-3 mx-auto">
								<label for="cnome" class="form-label">Nome del corso di laurea</label>
								<input type="text" name="cnome" class="form-control">
							</div>
							<div class="mb-3 mx-auto">
								<label for="ctipo" class="form-label">Tipologia del corso di laurea</label>
								<select name="ctipo" class="form-select" aria-label="Selezione tipoligia corso">
									<option value="tr">Triennale</option>
									<option value="mg">Magistrale</option>
								</select>
							</div>
							<div class="mb-3 mx-auto">
								<button type="submit" class="btn btn-primary" name="cbutton">Aggiungi nuovo corso</button>
							</div>
						</form>
					</div>
					<div class="col border mt-3">
						<h4 class="text-center">Inserimento di un nuovo insegnamento</h4>
						<form action="" method="post">
							<div class="mb-3 mx-auto">
								<label for="icorso" class="form-label">Corso di laura dell'insegnamento</label>
								<input type="text" name="icorso" class="form-control">
							</div>
							<div class="mb-3 mx-auto">
								<label for="icodice" class="form-label">Codice identificativo dell'insegnamento</label>
								<input type="text" name="icodice" class="form-control">
							</div>
							<div class="mb-3 mx-auto">
								<label for="inome" class="form-label">Nome dell'insegnamento</label>
								<input type="text" name="inome" class="form-control">
							</div>
							<div class="mb-3 mx-auto">
								<label for="ianno" class="form-label">Anno dell'insegnamento</label>
								<input type="text" name="ianno" class="form-control">
							</div>
							<div class="mb-3 mx-auto">
								<label for="idesc" class="form-label">Descrizione dell'insegnamento</label>
								<input type="text" name="idesc" class="form-control">
							</div>
							<div class="mb-3 mx-auto">
								<label for="iresp" class="form-label">Docente responsabile dell'insegnamento</label>
								<input type="text" name="iresp" class="form-control">
							</div>
							<div class="mb-3 mx-auto">
								<button type="submit" class="btn btn-primary" name="ibutton">Aggiungi nuovo insegnamento</button>
							</div>
						</form>
					</div>
				</div>
			</div>
		<?php } ?>
		<div class="container mt-5">
			<?php if($added) { ?>
				<?php if(empty($error_msg)) {?>
					<div class="alert alert-success text-center border" role="alert">
						<p><?php echo("Informazioni sul corso di laurea registrate correttamente");?></p>
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