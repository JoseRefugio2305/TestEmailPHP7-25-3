<?php
    include "conn.php";
    $tokenver=$_GET["tokenver"];

    try{
        $stmt=$conn->prepare("CALL `verifyemail`(?)");
        $stmt->bind_param('s',$tokenver);
        $stmt->execute();
        $stmt->bind_result($isexists);
        
        if ($stmt -> affected_rows) 
        {
            $exists = $stmt -> fetch();

            if ($exists) 
            {
                if($isexists=='Existe')
                {
                    echo "correo verificado";
                }
                else
                {
                    echo "token incorrecto";
                }
            }
            else
            {
                echo "error";
            }
        }
        else
        {
            echo "error";
        }
    }catch(Exception $e){
        echo "error";
    }
?>