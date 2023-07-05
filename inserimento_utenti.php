<?php
    ini_set("display_errors", "On");
	ini_set("error_reporting", E_ALL);
	include_once('lib/sg_functions.php');

	session_start();

    $added = false;
    $error_msg = "";
    $logged = null;
    $logout_link = $_SERVER['PHP_SELF']."?log=del";
	$segretary = array();

    if(isset($_SESSION['email'])) {
		$logged = $_SESSION['email'];
		$segretary = get_segretary_information($logged);

        if(isset($_POST)) {
            if(isset($_POST['sbutton'])) {
                $error_msg = insert_new_student($_POST['smatricola'], $_POST['semail'], $_POST['spsw'], $_POST['snome'], $_POST['scognome'], $_POST['scorso']);
                $added = true;
            } else if(isset($_POST['dbutton'])) {
                $error_msg = insert_new_teacher($_POST['demail'], $_POST['dpsw'], $_POST['dnome'], $_POST['dcognome']);
                $added = true;
            } else {
                $error_msg = "Inserimento dell'utente non riuscito";
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
				<a class="nav-link" href="#">Inserimento nuovi utenti</a>
			</div>
			</div>
		</div>
	</nav>
	<div class="container-xl mt-5 border-bottom">
		<h1 class="pb-2 border-bottom text-center">Pagina principale del segretario</h1>
		<?php if(isset($logged)) { ?>
			<div class="container mt-5">
				<div class="alert alert-success text-center border" role="alert">
				<p>
					<?php echo("Benvenuto segretario $segretary[0] $segretary[1], se vuoi effetuare il logout clicca ");?>
					<a href="<?php echo $logout_link;?>">qui</a>
				</p>
			</div>
			<div class="container mt-5">
				<div class="row">
                    <div class="col">
                        <h4 class="text-center">Inserimento nuovo studente</h4>
                        <form method="post">
                            <div class="mb-3 w-75 mx-auto">
                                <label for="" class="form-label">Matricola studente</label>
                                <input type="text" name="smatricola" class="form-control">
                            </div>
                            <div class="mb-3 w-75 mx-auto">
                                <label for="semail" class="form-label">Email studente</label>
                                <input type="text" name="semail" class="form-control">
                            </div>
                            <div class="mb-3 w-75 mx-auto">
                                <label for="" class="form-label">Password studente</label>
                                <input type="password" name="spsw" class="form-control">
                            </div>
                            <div class="mb-3 w-75 mx-auto">
                                <label for="snome" class="form-label">Nome studente</label>
                                <input type="text" name="snome" class="form-control">
                            </div>
                            <div class="mb-3 w-75 mx-auto">
                                <label for="scognome" class="form-label">Cognome studente</label>
                                <input type="text" name="scognome" class="form-control">
                            </div>
                            <div class="mb-3 w-75 mx-auto">
                                <label for="scorso" class="form-label">Corso di laurea studente</label>
                                <input type="text" name="scorso" class="form-control">
                            </div>
                            <div class="mb-3 w-75 mx-auto text-center">
                                <button type="submit" class="btn btn-primary" name="sbutton">Registra nuovo studente</button>
                            </div>
                        </form>
                    </div>
                    <div class="col">
                        <h4 class="text-center">Inserimento nuovo docente</h4>
                        <form method="post">
                            <div class="mb-3 w-75 mx-auto">
                                <label for="demail" class="form-label">Email docente</label>
                                <input type="text" name="demail" class="form-control">
                            </div>
                            <div class="mb-3 w-75 mx-auto">
                                <label for="dpsw" class="form-label">Password docente</label>
                                <input type="password" name="dpsw" class="form-control">
                            </div>
                            <div class="mb-3 w-75 mx-auto">
                                <label for="dnome" class="form-label">Nome docente</label>
                                <input type="text" name="dnome" class="form-control">
                            </div>
                            <div class="mb-3 w-75 mx-auto">
                                <label for="dcognome" class="form-label">Cognome docente</label>
                                <input type="text" name="dcognome" class="form-control">
                            </div>
                            <div class="mb-3 w-75 mx-auto text-center">
                                <button type="submit" class="btn btn-primary" name="dbutton">Registra nuovo docente</button>
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
						<p><?php echo("Utente registrato con successo");?></p>
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