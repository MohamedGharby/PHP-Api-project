<?php
require_once("../db-conn.php");

if($_SERVER["REQUEST_METHOD"] == "POST"){

    $postId = isset($_POST['id'])? $_POST['id']: "";
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

    if(empty($postId)){
        $errors[] = "post id is required";
    }elseif(! is_numeric($postId)){
        $errors[] = "post id must be number";
    }elseif($postId <= 0){
        $errors[] = "post id must not zero or less";
    }

    
    
        
        $selectLoginUserIdQuery = "SELECT * FROM users WHERE api_token = '$token'";
        $resultLoginUserIdQuery = mysqli_query($conn , $selectLoginUserIdQuery);
        if($selectLoginUserIdQuery){
            if(mysqli_num_rows($resultLoginUserIdQuery) != 0){

                $id = mysqli_fetch_assoc($resultLoginUserIdQuery);
                $loginUserId = $id['id'];
                
                $checkValuesQuery = "SELECT * FROM posts WHERE id = $postId";
                $checkValuesQueryResult = mysqli_query($conn , $checkValuesQuery);

                if($checkValuesQueryResult){
                    if(mysqli_num_rows($checkValuesQueryResult)!=0){

                        $checkValues = mysqli_fetch_assoc($checkValuesQueryResult);
                        $oldTitle = $checkValues['title'];
                        $oldBody = $checkValues['body'];


                                if(empty($errors)){
                                    //update in db
                                    
                                    $query =  "UPDATE posts SET title = '$title' , body = '$body' WHERE  user_id = '$loginUserId' AND id = $postId ";
                                    $result = mysqli_query($conn , $query);
                                    $checkResult = mysqli_affected_rows( $conn) ;

                                    
                                    if($result){
                                        if($oldTitle != $title or $oldBody != $body){
                                            
                                            if($checkResult > 0){
                                                    
                                                    $successMessage = json_encode("post updated successfully");
                                                    echo $successMessage;
                                               
                                            }else{
                                                renderError("Login user only who can update",500);
                                            }
                                        }else{
                                            renderError("you can not update same values",500);

                                        }

                                    }else{
                                            renderError("failed update post",500);
                                    }
                                
                                }else{
                                        $errorMessage =json_encode($errors);
                                        echo $errorMessage ;
                                }


                    }else{
                        renderError("Post not found" , 404);
                    }
                    
                }else{
                    renderError("Post not found" , 404);
                }


            }else{
                renderError("id not found" , 404);
                }            


        }else{
             renderError("id not found" , 404);
             }



}else{
    renderError("Method not allowed" , 405);
};
