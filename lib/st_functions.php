<?php
    include_once('lib/db_functions.php');
    
    function get_student_information($email) {
    	$path = "SET search_path=uni;";
        $student = array();

        $db = open_pg_connection();
        pg_query($db, $path);

        if(isset($email)) {
            $sql = "SELECT matricola, nome, cognome, corso_laurea 
                FROM studente
                WHERE email = $1";
            $params = array($email);
    
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
        return $student;
    }

    function change_student_password($email, $oldpsw, $newpsw) {
        $path = "SET search_path=uni;";
        $result = array();

        $result['con'] = false;
        $result['msg'] = "";

        $db = open_pg_connection();
        pg_query($db, $path);

        if(isset($email) && isset($oldpsw) && isset($newpsw)) {
            $sql_confirm = "SELECT *
                FROM studente
                WHERE password = $1 and email = $2;";
            $sql_insert = "UPDATE studente SET password = $1 WHERE email = $2;";
    
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

    function get_student_valid_carrear($matricola) {
        $exams = array();
        $path = "SET search_path=uni;";
        $params = array($matricola);
        $sql = "SELECT * FROM carriera_valida WHERE studente = $1;";

        $db = open_pg_connection();
        pg_query($db, $path);

        $result = pg_prepare($db, "valid_carrear", $sql);
        $result = pg_execute($db, "valid_carrear", $params);

        if($result) {
            while($row = pg_fetch_assoc($result)) {
                $name = $row['nome'];
                $course = $row['corso_laurea'];
                $year = $row['anno'];
                $date = $row['data_recente'];
                $grade = $row['voto'];

                array_push($exams, array($name, $course, $year, $date, $grade));
            }
        }

        close_pg_connection($db);
        return $exams;
    }
?>