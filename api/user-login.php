<?php

require_once("../db-conn.php");

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $userEmail = isset($_POST["email"])? trim(htmlspecialchars($_POST["email"])): "";
    $userPassword = isset($_POST["password"])? $_POST["password"]: "";

    $errors = [];



    if (empty($userEmail)) {
        $errors[] = "emai is required";
    }elseif (!is_string($userEmail)) {
        $errors[] = "must be string";
    }elseif (strlen($userEmail) > 255) {
        $errors[] = "email must not be more than 255 character";
    }     elseif(!filter_var($userEmail , FILTER_VALIDATE_EMAIL)){
     $errors[] = "must be valid email";
     }



    if (empty($userPassword)){
       $errors[] = "password is required";
    }elseif (strlen($userPassword) > 255) {
       $errors[] = "password must not be more than 255 character";
    }



    if (empty($errors)){

        $query = "SELECT * FROM users WHERE email = '$userEmail'";
        $result = mysqli_query($conn , $query);

        if ( mysqli_num_rows($result)  == 1) {

           $user = mysqli_fetch_assoc($result);
           $isLogin = password_verify($userPassword , $user['password']);
           if ($isLogin) {
            $successMessage = "login sucessfully";
            $apiToken = uniqid();
            $tokenInsertQuery = "UPDATE users SET api_token = '$apiToken' WHERE email = '$userEmail'" ;
            $tokenInsertResult = mysqli_query($conn , $tokenInsertQuery);
            if ($tokenInsertResult) {
                $userToken = json_encode($apiToken);

                $succsesLogin = array('Message'=> "$successMessage" , 'Token'=> $apiToken);
                echo json_encode($succsesLogin);
            }else{
                renderError("failed to login1",500);
            }
           
           }else{
            renderError("password is not correct",500);
           }
            
        }else{
            renderError("failed to login",500);
           }

    }else{
        $errorMessage =json_encode($errors);
        echo $errorMessage ;
     }






}else{
   renderError("Method not allowed" , 405);
}