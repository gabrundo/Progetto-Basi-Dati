<?php
    ini_set("display_errors", "On");
	ini_set("error_reporting", E_ALL);
	include_once('lib/db_functions.php');

	session_start();

	$psw_change = false;
	$error_msg = "";
    $logged = null;
	$student = array();
    $logout_link = $_SERVER['PHP_SELF']."?log=del";
	$path = "SET search_path=uni;";

	$db = open_pg_connection();
	pg_exec($db, $path);

    if(isset($_SESSION['email'])) {
		$logged = $_SESSION['email'];
	}

    if(isset($_GET) && isset($_GET['log']) && $_GET['log'] == 'del') {
		unset($_SESSION['email']);
		$logged = null;
		sleep(1);
		http_response_code(301);
		header("Location: index.php");
		exit();
	}

	if(isset($logged)) {
		$sql = "SELECT matricola, nome, cognome, corso_laurea 
			FROM studente
			WHERE email = $1";
		$params = array($logged);

		$result = pg_prepare($db, "student_description", $sql);
		$result = pg_execute($db, "student_description", $params);

		if($result) {
			while($row = pg_fetch_assoc($result)) {
				$student = array(
					$row['matricola'],
					$row['nome'],
					$row['cognome'],
					$row['corso_laurea']
				);
			}
		}
	}

	if(isset($logged) && isset($_POST['oldpsw']) && isset($_POST['newpsw'])) {
		$sql_confirm = "SELECT *
			FROM studente
			WHERE password = $1 and email = $2;";
		$sql_insert = "UPDATE studente SET password = $1 WHERE email = $2;";

		$params_confim = array(
			md5($_POST['oldpsw']), $logged
		);

		$result_confirm = pg_prepare($db, "confirm_identity", $sql_confirm);
		$result_confirm = pg_execute($db, "confirm_identity", $params_confim);

		if($result_confirm) {
			$row = pg_fetch_assoc($result_confirm);
			if(!$row) 
				$error_msg = "Cambiamento della password non riuscito";
			else {
				$params_insert = array(
					md5($_POST['newpsw']), $logged
				);
	
				$result_insert = pg_prepare($db, "insert_psw", $sql_insert);
				$result_insert = pg_execute($db, "insert_psw", $params_insert);
	
				if($result_insert) {
					$psw_change = true;
				} else {
					$error_msg = "Cambiamento della password non riuscito";
				}
			}
		}

		echo $error_msg;
		$_POST['oldpws'] = null;
		$_POST['newpsw'] = null;
	}

	close_pg_connection($db);
?>

<!doctype html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>Studente</title>
	<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-9ndCyUaIbzAi2FUVXJi0CjmCapSmO7SnpJef0486qhLnuZ2cdeRhO02iuK6FUUVM" crossorigin="anonymous">
</head>
<body>
    <div class="container-xl mt-5 border-bottom">
		<h1 class="pb-2 border-bottom text-center">Pagina principale dello studente</h1>
		<?php if(isset($logged)) { ?>
			<div class="container mt-5">
				<div class="alert alert-success text-center border" role="alert">
				<p>
					<?php echo("Benvenuto studente $student[1] $student[2], se vuoi effetuare il logout clicca ");?>
					<a href="<?php echo $logout_link;?>">qui</a>
				</p>
			</div>
			<div class="container mt-5">
				<table class="table table-striped">
					<h3 class="text-center">Informazioni dello studente</h3>
					<thead>
						<tr>
							<th scope="col">Informazione</th>
							<th scope="col">Valore</th>
						</tr>
					</thead>
					<tbody>
						<tr>
							<th scope="row">Matricola</th>
							<td><?php echo $student[0]; ?></td>
						</tr>
						<tr>
							<th scope="row">Nome</th>
							<td><?php echo $student[1]; ?></td>
						</tr>
						<tr>
							<th scope="row">Cognome</th>
							<td><?php echo $student[2]; ?></td>
						</tr>
						<tr>
							<th scope="row">Email</th>
							<td><?php echo $logged; ?></td>
						</tr>
						<tr>
							<th scope="row">Corso di laurea</th>
							<td><?php echo $student[3]; ?></td>
						</tr>
					</tbody>
				</table>
			</div>
			<?php if(!$psw_change) { ?>
				<div class="container mt-5">
					<h3 class="text-center">Cambiare la password del utente</h3>
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
					<p>
						<?php echo("Password cambiata con successo");?>
					</p>
					</div>
				<?php }?>
		<?php } ?>
	</div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js" integrity="sha384-geWF76RCwLtnZ8qwWowPQNguL3RmwHVBC9FhGdlKrxdiJJigb/j/68SIy3Te4Bkz" crossorigin="anonymous"></script>
</body>
</html>