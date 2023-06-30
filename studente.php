<?php
    ini_set("display_errors", "On");
	ini_set("error_reporting", E_ALL);
	include_once('lib/db_functions.php');

	session_start();

    $logged = null;
	$student = array();
    $logout_link = $_SERVER['PHP_SELF']."?log=del";
	$path = "SET search_path=uni;";
	$db = open_pg_connection();

    if(isset($_SESSION['email'])) {
		$logged = $_SESSION['email'];
	}

    if(isset($_GET) && isset($_GET['log']) && $_GET['log'] == 'del') {
		unset($_SESSION['email']);
		$logged = null;
	}

	if(isset($logged)) {
		pg_exec($db, $path);
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
    <div class="container-xl mt-5">
		<h1 class="pb-2 border-bottom text-center">Pagina principale dello studente</h1>
		<?php if(isset($logged)) { ?>
			<div class="container mt-5">
				<div class="alert alert-success text-center border" role="alert">
				<p>
					<?php echo("Benvenuto studente $logged, se vuoi effetuare il logout clicca ");?>
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
	<?php } ?>
	</div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js" integrity="sha384-geWF76RCwLtnZ8qwWowPQNguL3RmwHVBC9FhGdlKrxdiJJigb/j/68SIy3Te4Bkz" crossorigin="anonymous"></script>
</body>
</html>