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
?>