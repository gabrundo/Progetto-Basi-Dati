<?php
    ini_set("display_errors", "On");
	ini_set("error_reporting", E_ALL);
	include_once('lib/sg_functions.php');

	session_start();

    $error_msg = "";
    $logged = null;
    $logout_link = $_SERVER['PHP_SELF']."?log=del";
	$psw_change = false;
	$segretary = array();

    if(isset($_SESSION['email'])) {
		$logged = $_SESSION['email'];
		$segretary = get_segretary_information($logged);
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
		$s = change_segretary_password($logged, $_POST['oldpsw'], $_POST['newpsw']);

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
    <title>Segretario</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-9ndCyUaIbzAi2FUVXJi0CjmCapSmO7SnpJef0486qhLnuZ2cdeRhO02iuK6FUUVM" crossorigin="anonymous">
</head>
<body>
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
				<table class="table table-striped">
					<h3 class="text-center">Informazioni del segretario</h3>
					<thead>
						<tr>
							<th scope="col">Informazione</th>
							<th scope="col">Valore</th>
						</tr>
					</thead>
					<tbody>
						<tr>
							<th scope="row">Nome</th>
							<td><?php echo $segretary[0]; ?></td>
						</tr>
						<tr>
							<th scope="row">Cognome</th>
							<td><?php echo $segretary[1]; ?></td>
						</tr>
						<tr>
							<th scope="row">Email</th>
							<td><?php echo $logged; ?></td>
						</tr>
						<tr>
							<th scope="row">Facoltà</th>
							<td><?php echo $segretary[2]; ?></td>
						</tr>
					</tbody>
				</table>
			</div>
			<?php if(!$psw_change) { ?>
				<div class="container mt-5">
					<h3 class="text-center">Cambiare la password del segretario</h3>
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