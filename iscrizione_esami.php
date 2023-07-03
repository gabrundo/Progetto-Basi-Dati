<?php
    ini_set("display_errors", "On");
	ini_set("error_reporting", E_ALL);
	include_once('lib/st_functions.php');

    $course = null;
    $error_msg = '';
    $number = null;
    $logged = null;
    $logout_link = $_SERVER['PHP_SELF']."?log=del";
    $session = array();
    $student = array();
    $subscribed = false;
    $subscriptions = array();

    session_start();

    if(isset($_SESSION['email'])) {
        $logged = $_SESSION['email'];
        $student = get_student_information($logged);

        $number = $student[0];
        $course = $student[3];
        $session = get_session($course);
        $subscriptions = get_student_exam_subscriptions($number);


        if(isset($_POST) && isset($_POST['corso']) && isset($_POST['codice']) && isset($_POST['data'])) {
            $error_msg = exam_subscription($number, $_POST['corso'], $_POST['codice'], $_POST['data']);
            $subscribed = true;
            $subscriptions = get_student_exam_subscriptions($number);
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

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Carriera studente</title>
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
                <a class="nav-link active" aria-current="page" href="studente.php">Home</a>
                <a class="nav-link" href="carriera.php">Carriera</a>
            </div>
            </div>
        </div>
    </nav>
    <div class="container-xl border-bottom mt-5">
        <h1 class="pb-2 border-bottom text-center">
            Iscrizione agli esami per lo studente <?php echo($student[1]." ".$student[2]); ?>
        </h1>
        <?php if(isset($logged)) { ?>
			<div class="container mt-5">
				<div class="alert alert-success text-center border" role="alert">
				<p>
					<?php echo("Benvenuto studente $student[1] $student[2], se vuoi effetuare il logout clicca ");?>
					<a href="<?php echo $logout_link;?>">qui</a>
				</p>
			</div>
            <div class="container mt-5">
                <h3 class="text-center">Iscrizione ad appelli confermate</h3>
                <table class="table table-striped">
                    <thead>
                        <th class="col">Corso di laurea</th>
                        <th class="col">Nome dell'insegnamento</th>
                        <th class="col">Data appello d'esame</th>
                    </thead>
                    <tbody>
                        <?php foreach($subscriptions as $s) { ?>
                            <tr>
                                <td><?php echo $s[0]; ?></td>
                                <td><?php echo $s[1]; ?></td>
                                <td><?php echo $s[2]; ?></td>
                            </tr>
                        <?php } ?>
                    </tbody>
                </table>
            </div>
            <div class="container mt-5">
                <?php if(count($session) == 0) { ?>
                    <div class="alert alert-warning mt-5 text-center" role="alert">
                        <p>Nessun appello possibile a cui registrarsi</p>
                    </div>
                <?php } else {?>  
                    <h3 class="text-center">Appelli disponibili per l'iscrizione</h3>
                    <form action="" method="post">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th class="col">Corso di laurea</th>
                                    <th class="col">Nome dell'insegnamento</th>
                                    <th class="col">Data dell'appello</th>
                                    <th class="col">Selezione appello</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach($session as $values) {?>
                                    <tr>
                                        <td>
                                            <input type="hidden" name="corso" value="<?php echo $values[0];?>">
                                            <?php echo $values[0]; ?>
                                        </td>
                                        <td>
                                            <input type="hidden" name="codice" value="<?php echo $values[3];?>">
                                            <?php echo $values[1]; ?>
                                        </td>
                                        <td>
                                            <input type="hidden" name="data" value="<?php echo $values[2];?>">
                                            <?php echo $values[2]; ?>
                                        </td>
                                        <td>
                                            <button type="submit" class="btn btn-primary">Iscrizione</button>
                                        </td>
                                    </tr>
                                <?php } ?>
                            </tbody>
                        </table>
                    </form>
                <?php } ?>
                <div class="container mt-5">
                    <?php if($subscribed) { ?>
                        <?php if(empty($error_msg)) {?>
                            <div class="alert alert-success text-center border" role="alert">
                                <p><?php echo("Iscrizione all'esame effettuata con successo");?></p>
                            </div>
                        <?php } else { ?>
                            <div class="alert alert-warning mt-5 text-center" role="alert">
                                <p><?php echo $error_msg; ?></p>
                            </div>
                        <?php } ?>
                    <?php } ?>
                </div>
            </div>
        <?php } ?>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js" integrity="sha384-geWF76RCwLtnZ8qwWowPQNguL3RmwHVBC9FhGdlKrxdiJJigb/j/68SIy3Te4Bkz" crossorigin="anonymous"></script>
</body>
</html>
