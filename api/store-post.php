<?php
require_once("../db-conn.php");

if($_SERVER["REQUEST_METHOD"] == "POST"){

    $title = isset($_POST['title']) ? trim(htmlspecialchars($_POST['title'])): "";
    $body = isset($_POST['body']) ? trim(htmlspecialchars($_POST['body'])):"";
    $token = isset($_POST['api_token'])? $_POST['api_token']: "";


    //valedation
    $errors = [];

    if(empty($title)){
        $errors[] = "Title is required";
    }elseif(!is_string($title)){
        $errors[] = "Title must be string";
    }elseif(strlen($title) > 255){
        $errors[] = "Title must not be more than 255 character";
    }


    if(empty($body)){
        $errors[] = "body is required";
    }elseif(! is_string($body)){
        $errors[] = "body must be string";
    }

    if(empty($token)){
        $errors[] = "User Token is required";
    }

    
    
        
        $selectUserIdQuery = "SELECT * FROM users WHERE api_token = '$token'";
        $resultUserIdQuery = mysqli_query($conn , $selectUserIdQuery);
        if($resultUserIdQuery){
            if(mysqli_num_rows($resultUserIdQuery) != 0){

                $userId = mysqli_fetch_assoc($resultUserIdQuery);
                $postUserId = $userId['id'];

                if(empty($errors)){
                    //insert in db
                    $query = "INSERT INTO posts(title , body , user_id) VALUES ('$title' , '$body', '$postUserId') ";
                    $result = mysqli_query($conn , $query);
            
                    if($result){
                        $successMessage = json_encode("post added successfully");
                        echo $successMessage;
                    }else{
                       renderError("failed add post",500);
                    }
            
                }else{
                    $errorMessage =json_encode($errors);
                    echo $errorMessage ;
                }
            



            }else{
                    renderError("id not found" , 404);
                 }

                 
        }else{
             renderError("id not found" , 404);
             }
        


}else{
    renderError("Method not allowed" , 405);
}