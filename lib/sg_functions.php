<?php
    include_once('lib/db_functions.php');
    
    function get_segretary_information($email) {
    	$path = "SET search_path=uni;";
        $segretary = array();

        $db = open_pg_connection();
        pg_query($db, $path);

        if(isset($email)) {
            $sql = "SELECT nome, cognome, segreteria 
                FROM segretario
                WHERE email = $1";
            $params = array($email);
    
            $result = pg_prepare($db, "segretary_description", $sql);
            $result = pg_execute($db, "segretary_description", $params);
    
            if($result) {
                while($row = pg_fetch_assoc($result)) {
                    $segretary = array(
                        $row['nome'],
                        $row['cognome'],
                        $row['segreteria']
                    );
                }
            }
        }

        close_pg_connection($db);
        return $segretary;
    }

    function change_segretary_password($email, $oldpsw, $newpsw) {
        $path = "SET search_path=uni;";
        $result = array();

        $result['con'] = false;
        $result['msg'] = "";

        $db = open_pg_connection();
        pg_query($db, $path);

        if(isset($email) && isset($oldpsw) && isset($newpsw)) {
            $sql_confirm = "SELECT *
                FROM segretario
                WHERE password = $1 and email = $2;";
            $sql_insert = "UPDATE segretario SET password = $1 WHERE email = $2;";
    
            $params_confim = array(
                md5($oldpsw), $email
            );
    
            $result_confirm = pg_prepare($db, "confirm_identity", $sql_confirm);
            $result_confirm = pg_execute($db, "confirm_identity", $params_confim);
    
            if($result_confirm) {
                $row = pg_fetch_assoc($result_confirm);
                if(!$row) 
                    $result = array(false, "Cambiamento della password non riuscito");
                else {
                    $params_insert = array(
                        md5($newpsw), $email
                    );
        
                    $result_insert = pg_prepare($db, "insert_psw", $sql_insert);
                    $result_insert = pg_execute($db, "insert_psw", $params_insert);
        
                    if($result_insert) {
                        $result = array(true, "");
                    } else {
                        $result = array(false, "Cambiamento della password non riuscito");
                    }
                }
            }
        }
        close_pg_connection($db);
        return $result;
    }

    function insert_new_student($matricola, $email, $password, $nome, $cognome, $corso_laurea) {
        $error_msg = '';
        $path = "SET search_path=uni;";
        $params = array(
            $matricola, $email, md5($password), $nome, $cognome, $corso_laurea
        );
        $sql = "INSERT INTO studente (matricola, email, password, nome, cognome, corso_laurea) values ($1, $2, $3, $4, $5, $6);";

        $db = open_pg_connection();
        pg_query($db, $path);

        $result = pg_prepare($db, "insert_student", $sql);
        $result = pg_execute($db, "insert_student", $params);

        if(!$result) {
            $error_msg = "Inserimento della nuovo studente non valido";
        }

        close_pg_connection($db);
        return $error_msg;
    }

    function insert_new_teacher($email, $password, $nome, $cognome) {
        $error_msg = '';
        $path = "SET search_path=uni;";
        $params = array(
            $email, md5($password), $nome, $cognome
        );
        $sql = "INSERT INTO docente (email, password, nome, cognome) values ($1, $2, $3, $4);";

        $db = open_pg_connection();
        pg_query($db, $path);

        $result = pg_prepare($db, "insert_teacher", $sql);
        $result = pg_execute($db, "insert_teacher", $params);

        if(!$result) {
            $error_msg = "Inserimento della nuovo docente non valido";
        }

        close_pg_connection($db);
        return $error_msg;
    }

    function delete_student($matricola) {
        $error_msg = '';
        $path = "SET search_path=uni;";
        $params = array(
            $matricola
        );
        $sql = "DELETE FROM studente WHERE matricola = $1;";

        $db = open_pg_connection();
        pg_query($db, $path);

        $result = pg_prepare($db, "delete_student", $sql);
        $result = pg_execute($db, "delete_student", $params);

        if(!$result) {
            $error_msg = "Cancellazione dello studente non effettuata";
        }

        close_pg_connection($db);
        return $error_msg;
    }

    function insert_new_course($nome, $tipologia, $facoltà) {
        $error_msg = '';
        $path = "SET search_path=uni;";
        $params = array(
            $nome, $tipologia, $facoltà
        );
        $sql = "INSERT INTO corso_laurea (nome, tipologia, segreteria) VALUES ($1, $2, $3);";

        $db = open_pg_connection();
        pg_query($db, $path);

        $result = pg_prepare($db, "insert_course", $sql);
        $result = pg_execute($db, "insert_course", $params);

        if(!$result) {
            $error_msg = "Inserimento del corso non effettuata";
        }

        close_pg_connection($db);
        return $error_msg;
    }

    function insert_new_teaching($corso, $codice, $nome, $anno, $descrizione, $responsabile) {
        $error_msg = '';
        $path = "SET search_path=uni;";
        $params = array(
            $corso, $codice, $nome, $anno, $descrizione, $responsabile
        );
        $sql = "INSERT INTO insegnamento (corso_laurea, codice, nome, anno, descrizione, responsabile) 
            VALUES ($1, $2, $3, $4, $5, $6);";

        $db = open_pg_connection();
        pg_query($db, $path);

        $result = pg_prepare($db, "insert_teaching", $sql);
        $result = pg_execute($db, "insert_teaching", $params);

        if(!$result) {
            $error_msg = pg_last_error($db);
        }

        close_pg_connection($db);
        return $error_msg;
    }
?>