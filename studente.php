<?php
	ini_set("display_errors", "On");
	ini_set("error_reporting", E_ALL);
	include_once('lib/function.php');

	$logged = null;

	session_start();

	$error_msg = '';
	if(isset($_POST) && isset($_POST['email']) && isset($_POST['password'])) {
		$logged = login_studente($_POST['email'], $_POST['password']);

		if(is_null($logged)) {
			$error_msg = "Credenziali errate riprovare il login!";
		}
	}

	if(isset($_SESSION['user'])) {
		$logged = $_SESSION['user'];
	}

	if(isset($logged)) {
		$_SESSION['user'] = $logged;
	}

	if(isset($_GET) && isset($_GET['log']) && $_GET['log'] == 'del') {
		unset($_SESSION['user']);
		$logged = null;
	}
?>

<!doctype html>
<html lang="en">
	<head>
		<meta charset="utf-8">
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<title>Bootstrap demo</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-9ndCyUaIbzAi2FUVXJi0CjmCapSmO7SnpJef0486qhLnuZ2cdeRhO02iuK6FUUVM" crossorigin="anonymous">
	</head>
	<body>
		<div class="container-xl">
			<?php
				//potrei spostare questo nella parte iniziale
				if(isset($logged)) {
					$logout_link = $_SERVER['PHP_SELF']."?log=del";
			?>
			<div class="container mt-5">
				<div class="alert alert-success text-center" role="alert">
					<p>
						<?php echo("Benvenuto $logged, se vuoi effetuare il logout clicca ");?>
						<a href="<?php echo $logout_link?>">qui</a>
					</p>
				</div>
			</div>

			<?php
				}
			?>

			<?php
				if(!isset($logged)) { ?>
					<div class="container-lg mt-5 border-bottom">
						<div class="text-center mb-2">
							<h2>Effettuare il login</h2>
							<p>Per accedere alla piattaforma tramite email e password</p>
						</div> 
						<div class="container border mb-5">
							<form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
								<div class="mt-3 mb-3 w-75 mx-auto">
									<label for="useremail" class="form-label">Email</label>
									<input type="email" name="email" id="useremail" class="form-control">
								</div>
								<div class="mb-3 w-75 mx-auto">
									<label for="userpassword" class="form-label">Password</label>
									<input type="password" name="password" id="userpassword" class="form-control">
								</div>
								<div class="mb-3 w-75 mx-auto text-center">
									<button type="submit" class="btn btn-primary">Login</button>
								</div>
							</form>
						</div>       
					</div>
			<?php
				}
			?>
		
			<?php
				if(!empty($error_msg)) { ?>
					<div class="alert alert-primary text-center" role="alert">
						<p>
							<?php echo $error_msg; ?>
						</p>
					</div>
			<?php 
				}
			?>
		</div>

    	<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js" integrity="sha384-geWF76RCwLtnZ8qwWowPQNguL3RmwHVBC9FhGdlKrxdiJJigb/j/68SIy3Te4Bkz" crossorigin="anonymous"></script>
  </body>
</html>