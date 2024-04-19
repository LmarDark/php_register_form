<?php
    if($_POST)
    {
        $servername = "XXXXXXXX";
        $user = "XXXXXXXX";
        $password = "XXXXXXXX";
        $db = "localhostdb";
        $port = "XXXXXXXX";

        if(isset($_POST['user_sg']) && ($_POST['psw_sg']) || ($_POST['user_lg']) && ($_POST['psw_lg']))
        {
            $conn = mysqli_connect($servername, $user, $password, $db, $port);
            
            @$user_sg = $conn->real_escape_string($_POST['user_sg']);
            @$psw_sg = $conn->real_escape_string($_POST['psw_sg']);

            if($conn)
            {
                $sqltable = "CREATE TABLE IF NOT EXISTS `userstable` (
                    `id` INT(10) UNSIGNED ZEROFILL NOT NULL AUTO_INCREMENT,
                    `username` VARCHAR(50) NULL DEFAULT NULL COLLATE 'utf8mb4_general_ci',
                    `password` VARCHAR(255) NULL DEFAULT NULL COLLATE 'utf8mb4_general_ci',
                    PRIMARY KEY (`id`) USING BTREE
                )
                COLLATE='utf8mb4_general_ci'
                ENGINE=InnoDB";

                $verify_table_exists = $conn->query($sqltable);
                
                if(isset($verify_table_exists) && ($user_sg) && ($psw_sg))
                {
                    $hash_psw = password_hash($psw_sg, PASSWORD_DEFAULT);

                    $sqlsign = sprintf("INSERT INTO userstable(username, password) VALUES('$user_sg', '$hash_psw');");
                    $conn->query($sqlsign);
                    header("Location: ./index.html");
                    session_destroy(); //
                    exit;
                }
                if(isset($verify_table_exists) && ($_POST['user_lg']) && ($_POST['psw_lg']))
                {
                    @$user_lg = $conn->real_escape_string($_POST['user_lg']);
                    @$psw_lg = $conn->real_escape_string($_POST['psw_lg']);

                    $sqllogin = sprintf("SELECT * FROM userstable WHERE username='$user_lg'");
                    $result = $conn->query($sqllogin);
                    $user = $result->fetch_assoc();
                    
                    $user['id'];
                    $user['username'];
                    $user['password'];
                    
                    if($user === NULL){
                        echo "<script>alert('Não foi encontrado nenhum usuário com este nome!')</script>";
                    }
                    else
                    {
                        if(password_verify($psw_lg, $user['password']))
                        {
                            session_start();
                            $_SESSION['user_lg'] = $user_lg;
                            header("Location: home.php");
                        }
                    }
                }
            }
        }
    }


?>
