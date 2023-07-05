<?php
    include_once('lib/db_functions.php');
    
    function get_teacher_information($email) {
    	$path = "SET search_path=uni;";
        $teacher = array();

        $db = open_pg_connection();
        pg_query($db, $path);

        if(isset($email)) {
            $sql = "SELECT nome, cognome 
                FROM docente
                WHERE email = $1";
            $params = array($email);
    
            $result = pg_prepare($db, "teacher_description", $sql);
            $result = pg_execute($db, "teacher_description", $params);
    
            if($result) {
                while($row = pg_fetch_assoc($result)) {
                    $teacher = array(
                        $row['nome'],
                        $row['cognome']
                    );
                }
            }
        }

        close_pg_connection($db);
        return $teacher;
    }

    function change_teacher_password($email, $oldpsw, $newpsw) {
        $path = "SET search_path=uni;";
        $result = array();

        $result['con'] = false;
        $result['msg'] = "";

        $db = open_pg_connection();
        pg_query($db, $path);

        if(isset($email) && isset($oldpsw) && isset($newpsw)) {
            $sql_confirm = "SELECT *
                FROM docente
                WHERE password = $1 and email = $2;";
            $sql_insert = "UPDATE docente SET password = $1 WHERE email = $2;";
    
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

    function get_teacher_teachings($email) {
        $path = "SET search_path=uni;";
        $params = array($email);
        $sql = "SELECT corso_laurea, codice, nome, anno, descrizione
            FROM insegnamento
            WHERE responsabile = $1;";
        $teachings = array();

        $db = open_pg_connection();
        pg_query($db, $path);

        $result = pg_prepare($db, "techings_desc", $sql);
        $result = pg_execute($db, "techings_desc", $params);

        if($result) {
            while($row = pg_fetch_assoc($result)) {
                $corso = $row['corso_laurea'];
                $codice = $row['codice'];
                $nome = $row['nome'];
                $anno = $row['anno'];
                $descrizione = $row['descrizione'];

                array_push($teachings, array($corso, $codice, $nome, $anno, $descrizione));
            }
        }

        close_pg_connection($db);
        return $teachings;
    }

    function add_date_appeal($email, $corso, $codice, $data) {
        $error_msg = '';
        $path = "SET search_path=uni;";
        $params_check = array($corso, $codice, $email);
        $sql_check = "SELECT * FROM insegnamento
            WHERE corso_laurea = $1 and codice = $2 and responsabile = $3;";
        $sql_insert = "INSERT INTO appello (corso_laurea, codice, data) VALUES ($1, $2, $3);";

        $db = open_pg_connection();
        pg_query($db, $path);

        $result_check = pg_prepare($db, "teaching_check", $sql_check);
        $result_check = pg_execute($db, "teaching_check", $params_check);

        if($result_check) {
            $row = pg_fetch_assoc($result_check);
            if(!$row) {
                $error_msg = 'Insegnamento non gestito dal professore';
            } else {
                $params_insert = array($corso, $codice, $data);
                
                $result_insert = pg_prepare($db, "appeal_insert", $sql_insert);
                $result_insert = pg_execute($db, "appeal_insert", $params_insert);

                if(!$result_insert) 
                    $error_msg = pg_last_error($db);
            }
        }

        close_pg_connection($db);
        return $error_msg;
    }

    function get_teaching_appeals($corso, $codice) {
        $dates = array();
        $path = "SET search_path=uni;";
        $params = array($corso, $codice);
        $sql = "SELECT data FROM appello WHERE corso_laurea = $1 and codice = $2;";

        $db = open_pg_connection();
        pg_query($db, $path);

        $result = pg_prepare($db, "date_appello", $sql);
        $result = pg_execute($db, "date_appello", $params);

        if($result) {
            $dates = pg_fetch_all_columns($result);
        }

        close_pg_connection($db);
        return $dates;
    }

    function get_teaching_to_evalueate($email) {
        $path = "SET search_path=uni;";
        $params = array($email);
        $sql = "SELECT s.studente, s.corso_laurea, s.codice, s.data
            FROM sostiene s INNER JOIN insegnamento i ON s.corso_laurea = i.corso_laurea AND s.codice = i.codice
            WHERE voto IS NULL AND i.responsabile = $1;";
        $to_evalueate = array();

        $db = open_pg_connection();
        pg_query($db, $path);

        $result = pg_prepare($db, "get_teachings_to_value", $sql);
        $result = pg_execute($db, "get_teachings_to_value", $params);

        if($result) {
            while($row = pg_fetch_assoc($result)) {
                $studente = $row['studente'];                    
                $corso = $row['corso_laurea'];
                $codice = $row['codice'];
                $data = $row['data'];

                array_push($to_evalueate, array($studente, $corso, $codice, $data));
            }
        }

        close_pg_connection($db);
        return $to_evalueate;
    }

    function update_teaching_value($studente, $corso, $codice, $data, $voto) {
        $error_msg = '';
        $path = "SET search_path=uni;";
        
        $params = array($voto, $studente, $corso, $codice, $data);
        $sql = "UPDATE sostiene SET voto = $1
            WHERE studente = $2 AND corso_laurea = $3 AND codice = $4 AND data = $5;";

        $db = open_pg_connection();
        pg_query($db, $path);

        $result = pg_prepare($db, "evaluate", $sql);
        $result = pg_execute($db, "evaluate", $params);

        if($result) {
            $error_msg = '';
        } else {
            $error_msg = 'Registrazione del voto del appello non riuscito';
        }

        close_pg_connection($db);
        
        return $error_msg;
    }
?>