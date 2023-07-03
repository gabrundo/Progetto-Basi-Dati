<?php
    ini_set("display_errors", "On");
	ini_set("error_reporting", E_ALL);
	include_once('lib/st_functions.php');

    $exams = array();
    $number = null;
    $logged = null;
    $logout_link = $_SERVER['PHP_SELF']."?log=del";
    $student = array(); 

    session_start();

    if(isset($_SESSION['email'])) {
        $logged = $_SESSION['email'];
        $student = get_student_information($logged);

        $number = $student[0];
        $exams = get_student_valid_carrear($number);
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
                <a class="nav-link" href="iscrizione_esami.php">Iscrizione esami</a>
            </div>
            </div>
        </div>
    </nav>
    <div class="container-xl border-bottom mt-5">
        <h1 class="pb-2 border-bottom text-center">
            Carriera valida dello studente <?php echo($student[1]." ".$student[2]); ?>
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
                <?php if(count($exams) == 0) { ?>
                    <div class="alert alert-warning mt-5 text-center" role="alert">
                        <p>Nessun esame valido registrato in carriera</p>
                    </div>
                <?php } else {?>
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th class="col">Nome insegnamento</th>
                                <th class="col">Corso di laurea</th>
                                <th class="col">Anno di erogazione</th>
                                <th class="col">Data di sostenimento</th>
                                <th class="col">Voto</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach($exams as $values) {?>
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
                <?php } ?>
            </div>
        <?php } ?>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js" integrity="sha384-geWF76RCwLtnZ8qwWowPQNguL3RmwHVBC9FhGdlKrxdiJJigb/j/68SIy3Te4Bkz" crossorigin="anonymous"></script>
</body>
</html>