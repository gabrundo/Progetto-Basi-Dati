<?php   
    define("myhost", "localhost");
    define("myuser", "bitnami");
    define("mypsw", "bitnami");
    define("mydb", "universita");
?>

<?php
    function open_pg_connection() {
        $connection = "host=".myhost." dbname=".mydb." user=".myuser." password=".mypsw;
        return pg_connect($connection);
    }

    function close_pg_connection($db) {
        return pg_close($db);
    }

    function login_utente($email, $psw, $usertype) {
        $logged = null;
        $user = null;
        $db = open_pg_connection();
        $path = "SET search_path=uni;";

        switch ($usertype) {
            case "st": 
                $user = "studente";
                break;
            case "dc": 
                $user = "docente";
                break;
            case "sg": 
                $user = "segretario";
                break;
            default:
                return false;
        }

        $sql = "SELECT email FROM ".$user." WHERE email = $1 and password = $2";
        $params = array(
            $email,
            md5($psw)
        );

        pg_exec($db, $path);

        $result = pg_prepare($db, "check", $sql);
        $result = pg_execute($db, "check", $params);

        if($row = pg_fetch_assoc($result)) {
            $logged = $row['email'];
        }

        close_pg_connection($db);

        return $logged;
    }
?>