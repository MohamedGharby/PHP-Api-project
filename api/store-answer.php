<?php
require_once("../db-conn.php");

if($_SERVER["REQUEST_METHOD"] == "POST"){

    $body = isset($_POST['body']) ? trim(htmlspecialchars($_POST['body'])):"";
    $token = isset($_POST['api_token'])? $_POST['api_token']: "";
    $postId = isset($_POST['id'])? $_POST['id']: "";



    //valedation
    $errors = [];



    if(empty($body)){
        $errors[] = "body is required";
    }elseif(! is_string($body)){
        $errors[] = "body must be string";
    }

    if(empty($token)){
        $errors[] = "User Token is required";
    }

    if(empty($postId)){
        $errors[] = "post id is required";
    }elseif(! is_numeric($postId)){
        $errors[] = "post id must be number";
    }elseif($postId <= 0){
        $errors[] = "post id must not zero or less";
    }


    $selectUserIdQuery = "SELECT * FROM users WHERE api_token = '$token'";
    $resultUserIdQuery = mysqli_query($conn , $selectUserIdQuery);

    if($resultUserIdQuery){
        if(mysqli_num_rows($resultUserIdQuery) != 0){
            
        $userId = mysqli_fetch_assoc($resultUserIdQuery);
        $answerUserId = $userId['id'];


        $selectPostIdQuery = "SELECT * FROM posts WHERE id = '$postId'";
        $resultPostIdQuery = mysqli_query($conn , $selectPostIdQuery);

        if($resultPostIdQuery){
            if(mysqli_num_rows($resultPostIdQuery) != 0){
                $postId = mysqli_fetch_assoc($resultPostIdQuery);
                $answerPostId = $postId['id'];

                

                if(empty($errors)){
                    //insert in db


                    $query = "INSERT INTO answers( body , user_id , post_id) VALUES ('$body', '$answerUserId' , '$answerPostId') ";
                    $result = mysqli_query($conn , $query);

                    if($result){
                        $successMessage = json_encode("answer added successfully");
                        echo $successMessage;
                    }else{
                    renderError("failed add answer",500);
                    }

                }else{
                    $errorMessage =json_encode($errors);
                    echo $errorMessage ;
                }




            }else{
            renderError("post not found" , 404);
            }


        }else{
            renderError("post not found" , 404);
         }



        }else{
            renderError("user id not found" , 404);
         }


    }else{
        renderError("user id not found" , 404);
     }





}else{
    renderError("Method not allowed" , 405);
}