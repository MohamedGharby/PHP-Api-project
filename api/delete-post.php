<?php
require_once("../db-conn.php");

if($_SERVER["REQUEST_METHOD"] == "POST"){

    $postId = isset($_POST['id'])? $_POST['id']: "";
    $token = isset($_POST['api_token'])? $_POST['api_token']: "";


    //valedation
    $errors = [];

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


        $checkPostIdQuery = "SELECT id FROM posts WHERE id = $postId";
        $checkPostIdResult = mysqli_query($conn , $checkPostIdQuery);
        if($checkPostIdResult){
            if(mysqli_num_rows($checkPostIdResult)!=0){
                $selectLoginUserIdQuery = "SELECT * FROM users WHERE api_token = '$token'";
                $resultLoginUserIdQuery = mysqli_query($conn , $selectLoginUserIdQuery);
                if($selectLoginUserIdQuery){
                    if(mysqli_num_rows($resultLoginUserIdQuery) != 0){
        
                        $id = mysqli_fetch_assoc($resultLoginUserIdQuery);
                        $loginUserId = $id['id'];
        
        
                                        if(empty($errors)){
                                            //delete in db
                                            
                                            $query =  "DELETE FROM posts WHERE  user_id = '$loginUserId' AND id = $postId ";
                                            $result = mysqli_query($conn , $query);
                                            $checkResult = mysqli_affected_rows( $conn) ;
        
                                            
                                            if($result){
                                                    
                                                    if($checkResult > 0){
                                                            
                                                            $successMessage = json_encode("post deleted successfully");
                                                            echo $successMessage;
                                                       
                                                    }else{
                                                        renderError("Login user only who can delete",500);
                                                    }
                                                
                                            }else{
                                                    renderError("failed delete post",500);
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
                renderError("Post not found" , 404);
            }


        }else{
            renderError("Post not found" , 404);
        }
        
        

}else{
    renderError("Method not allowed" , 405);
};
