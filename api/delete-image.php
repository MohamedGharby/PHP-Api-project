<?php
require_once("../db-conn.php");
if($_SERVER["REQUEST_METHOD"] == "POST"){
    $token = isset($_POST['api_token'])? $_POST['api_token']: "";




    //valedation
    $errors = [];

    if(empty($token)){
        $errors[] = "User Token is required";
    }

    $selectLoginUserIdQuery = "SELECT * FROM users WHERE api_token = '$token'";
    $resultLoginUserIdQuery = mysqli_query($conn , $selectLoginUserIdQuery);
    if($selectLoginUserIdQuery){
        if(mysqli_num_rows($resultLoginUserIdQuery) != 0){


            if(empty($errors)){

              //delete from db
                                    
                $query =  "UPDATE users SET image = NULL WHERE  api_token = '$token' ";
                $result = mysqli_query($conn , $query);
                $checkResult = mysqli_affected_rows( $conn) ;  

                if($result){

                        
                        if($checkResult > 0){
                                
                                $successMessage = json_encode("image deleted successfully");
                                echo $successMessage;
                        
                        }else{
                            renderError("Login user only who can delete image",500);
                        }
                    

                }else{
                        renderError("failed delete image",500);
                }
            




            }else{
                    $errorMessage =json_encode($errors);
                    echo $errorMessage ;
                 }



        }else{
            renderError("user not found" , 404);
             }
    }else{
         renderError("user not found" , 404);
         }









}else{
    renderError("Method not allowed" , 405);
}