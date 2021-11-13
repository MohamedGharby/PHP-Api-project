<?php

require_once("../db-conn.php");

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $userName = isset($_POST["name"])? trim(htmlspecialchars($_POST["name"])): "";
    $userEmail = isset($_POST["email"])? trim(htmlspecialchars($_POST["email"])): "";
    $userPassword = isset($_POST["password"])? trim(htmlspecialchars($_POST["password"])): "";
     $errors = [];


     if (empty($userName)){
      $errors[] = "name is required";
   }elseif (!is_string($userName)) {
      $errors[] = "must be string";
   }elseif (strlen($userName) > 255) {
      $errors[] = "name must not be more than 255 character";
   }

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


     if (empty($errors)) {
          
       $hashedPassword = password_hash( $userPassword , PASSWORD_DEFAULT);
         
        $query = "INSERT INTO users(name , email , password) VALUES ('$userName' , '$userEmail' , '$hashedPassword')";
        $result = mysqli_query($conn , $query);

        if($result){
            $successMessage = json_encode("registered sucessfully");
            echo $successMessage;
        }else{
           renderError("failed to register",500);
        }
     }else{
        $errorMessage =json_encode($errors);
        echo $errorMessage ;
     }
}else{
   renderError("Method not allowed" , 405);
}