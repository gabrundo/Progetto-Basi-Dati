<?php
    ini_set("display_errors", "On");
	ini_set("error_reporting", E_ALL);
	include_once('lib/dc_functions.php');

    session_start();

    $added = false;
    $error_msg = '';
    $logged = null;
    $logout_link = $_SERVER['PHP_SELF']."?log=del";
    $teacher = array();
    $teachings = array();

    if(isset($_SESSION['email'])) {
		$logged = $_SESSION['email'];
		$teacher = get_teacher_information($logged);
        $teachings = get_teacher_teachings($logged);
	}

    if(isset($_GET) && isset($_GET['log']) && $_GET['log'] == 'del') {
		unset($_SESSION['email']);
		$logged = null;
		sleep(1);
		http_response_code(301);
		header("Location: index.php");
		exit();
	}

    if(isset($_POST) && isset($_POST['corso']) && isset($_POST['codice']) && isset($_POST['data'])) {
        $error_msg = add_date_appeal($logged, $_POST['corso'], $_POST['codice'], $_POST['data']);
        $added = true;
        //aggiornamento del calcolo dell'appello inserito
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
				<a class="nav-link" href="esiti.php">Esiti Esami</a>
			</div>
			</div>
		</div>
	</nav>
	<div class="container-xl mt-5 border-bottom">
		<h1 class="pb-2 border-bottom text-center">Gestione calendario appelli d'esame</h1>
		<?php if(isset($logged)) { ?>
			<div class="container mt-5">
				<div class="alert alert-success text-center border" role="alert">
				<p>
					<?php echo("Benvenuto docente $teacher[0] $teacher[1], se vuoi effetuare il logout clicca ");?>
					<a href="<?php echo $logout_link;?>">qui</a>
				</p>
			</div>
            <?php if(!count($teachings) == 0) { ?>
                <div class="container mt-5">
                    <h3 class="text-center">Insegnamenti del docente <?php echo($teacher[0]." ".$teacher[1]);?></h3>
                    <table class="table table-striped">
                        <thead>
                            <th class="col">Corso di laurea</th>
                            <th class="col">Codice dell'insegnamento</th>
                            <th class="col">Nome dell'insegnamento</th>
                            <th class="col">Anno di erogazione</th>
                            <th class="col">Descrizione</th>
                        </thead>
                        <tbody>
                            <?php foreach($teachings as $values) {?>
                                <tr>
                                    <td><?php echo $values[0]; ?></td>
                                    <td><?php echo $values[1]; ?></td>
                                    <td><?php echo $values[2]; ?></td>
                                    <td><?php echo $values[3]; ?></td>
                                    <td><?php echo $values[4]; ?></td>
                                </tr>
                            <?php } ?>
                        </tbody>
                    </table>
                </div>
                <div class="container mt-5">
                    <h3 class="text-center">Appelli registrati per ogni insegnamento</h3>
                    <div class="row">
                        <?php foreach($teachings as $values) { ?>
                            <?php $appels = get_teaching_appeals($values[0], $values[1]); ?>
                            <div class="col">
                                <h4 class="text-center"><?php echo $values[2]; ?></h3>
                                <table class="table table-striped">
                                    <thead>
                                        <th class="col">Date</th>
                                    </thead>
                                    <tbody>
                                        <?php foreach($appels as $a) { ?>
                                            <tr>
                                                <td><?php echo $a; ?></td>
                                            </tr>
                                        <?php } ?>
                                    </tbody>
                                </table>
                            </div>
                        <?php } ?>
                    </div>
                </div>
                <div class="container border mt-5 mb-5">
                    <h3 class="text-center">Aggiunta data appello</h3>
                    <p class="text-center">Il formato della data deve essere yyyy-mm-dd</p>
                    <form action="" method="post">
                        <div class="row mb-3">
                            <div class="col-4">
                                <label for="corso" class="form-label">Corso di laurea</label>
                                <select name="corso" class="form-select" aria-label="Selezione corso di laurea dell'insegnamento">
                                    <?php foreach($teachings as $values) { ?>
                                        <option value="<?php echo $values[0];?>"><?php echo $values[0];?></option>
                                    <?php } ?>
                                </select>
                            </div>
                            <div class="col-4">
                                <label for="codice" class="form-label">Codice insegnamento</label>
                                <select name="codice" class="form-select" aria-label="Selezione corso di laurea dell'insegnamento">
                                    <?php foreach($teachings as $values) { ?>
                                        <option value="<?php echo $values[1];?>"><?php echo $values[1];?></option>
                                    <?php } ?>
                                </select>
                            </div>
                            <div class="col-4">
                                <label for="data" class="form-label">Data appello</label>
                                <input type="text" name="data" class="form-control">
                            </div>
                            <div class="col-12 mt-3 mx-auto text-center">
                                <button type="submit" class="btn btn-primary">
                                    Aggiungi nuovo appello
                                </button>
                            </div>
                        </div>                            
                    </form>
                </div>
            <?php } else { ?>
                <div class="alert alert-warning mt-5 text-center" role="alert">
                        <p>Il docente non è responsabile di alcun insegnamento</p>
                </div>
            <?php } ?>
            <div class="container mt-5">
                <?php if($added) { ?>
                    <?php if(empty($error_msg)) { ?>
                        <div class="alert alert-success text-center border" role="alert">
                            <p><?php echo("Appello d'esame aggiunto con successo");?></p>
                        </div>
                    <?php } else { ?>
                        <div class="alert alert-warning mt-5 text-center" role="alert">
                            <p><?php echo $error_msg; ?></p>
                        </div>
                    <?php } ?>
                <?php } ?>
            </div>
        <?php } ?>
	</div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js" integrity="sha384-geWF76RCwLtnZ8qwWowPQNguL3RmwHVBC9FhGdlKrxdiJJigb/j/68SIy3Te4Bkz" crossorigin="anonymous"></script>
</body>
</html>