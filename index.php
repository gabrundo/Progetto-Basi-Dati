<?php
	ini_set("display_errors", "On");
	ini_set("error_reporting", E_ALL);
	include_once('lib/db_functions.php');

	$logged = null;

	session_start();

	$error_msg = '';
	if(isset($_POST) && isset($_POST['email']) && isset($_POST['password']) && isset($_POST['type'])) {
		$logged = login_utente($_POST['email'], $_POST['password'], $_POST['type']);

		if(is_null($logged)) {
			$error_msg = "Credenziali errate riprovare il login!";
		}
	}

	if(isset($_SESSION['email'])) {
		$logged = $_SESSION['email'];
	}

	if(isset($logged)) {
		$_SESSION['email'] = $logged;

        http_response_code(301);
        switch ($_POST['type']) {
            case "st":
                header('Location: studente.php');
                break;
            case "dc":
                header('Location: docente.php');
                break;
            case "sg":
                header('Location: segretario.php');
                break;
        }
        exit();
	}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Benvenuto</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-9ndCyUaIbzAi2FUVXJi0CjmCapSmO7SnpJef0486qhLnuZ2cdeRhO02iuK6FUUVM" crossorigin="anonymous">
</head>
<body>    
    <div class="container-xl mt-5">
        <h1 class="pb-2 border-bottom text-center">Piattaforma di gestione esami universitari</h1>

        <?php if(!isset($logged)) { ?>
            <div class="container-lg mt-5 border-bottom">
                <div class="text-center mb-2">
                    <h2>Effettuare il login</h2>
                    <p>Per accedere alla piattaforma selezionare tipologia utente e inserire email e password</p>
                </div> 
                <div class="container border mb-5">
                    <form method="post">
                        <div class="mt-3 mb-3 w-75 mx-auto">
                            <label for="type" class="form-label">Tipologia utente</label>
                            <select name="type" class="form-select" aria-label="Selezione tipologia utente">
                                <option value="st">Studente</option>
                                <option value="dc">Docente</option>
                                <option value="sg">Segretario</option>
                            </select>
                        </div>
                        <div class="mb-3 w-75 mx-auto">
                            <label for="typeemail" class="form-label">Email</label>
                            <input type="email" name="email" id="typeemail" class="form-control">
                        </div>
                        <div class="mb-3 w-75 mx-auto">
                            <label for="typepassword" class="form-label">Password</label>
                            <input type="password" name="password" id="typepassword" class="form-control">
                        </div>
                        <div class="mb-3 w-75 mx-auto text-center">
                            <button type="submit" class="btn btn-primary">Login</button>
                        </div>
                    </form>
                </div>       
            </div>
        <?php } ?>

        <?php if(!empty($error_msg)) { ?>
            <div class="alert alert-warning mt-5 text-center" role="alert">
                <p>
                    <?php echo $error_msg; ?>
                </p>
            </div>
        <?php } ?>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js" integrity="sha384-geWF76RCwLtnZ8qwWowPQNguL3RmwHVBC9FhGdlKrxdiJJigb/j/68SIy3Te4Bkz" crossorigin="anonymous"></script>
</body>
</html>