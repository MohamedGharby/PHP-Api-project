<?php
require_once("../db-conn.php");

if($_SERVER["REQUEST_METHOD"] == "POST"){

    $answerId = isset($_POST['id'])? $_POST['id']: "";
    $postId = isset($_POST['post_id'])? $_POST['post_id']: "";
    $body = isset($_POST['body']) ? trim(htmlspecialchars($_POST['body'])):"";
    $token = isset($_POST['api_token'])? $_POST['api_token']: "";


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

    if(empty($answerId)){
        $errors[] = "answer id is required";
    }elseif(! is_numeric($answerId)){
        $errors[] = "answer id must be number";
    }elseif($answerId <= 0){
        $errors[] = "answer id must not zero or less";
    }


    
    
        
        $selectLoginUserIdQuery = "SELECT * FROM users WHERE api_token = '$token'";
        $resultLoginUserIdQuery = mysqli_query($conn , $selectLoginUserIdQuery);
        if($selectLoginUserIdQuery){
            if(mysqli_num_rows($resultLoginUserIdQuery) != 0){

                $id = mysqli_fetch_assoc($resultLoginUserIdQuery);
                $loginUserId = $id['id'];

                $selectPostIdQuery = "SELECT * FROM answers WHERE post_id = '$postId'";
                $resultPostIdQuery = mysqli_query($conn , $selectPostIdQuery);

                if($resultPostIdQuery){
                    if(mysqli_num_rows($resultPostIdQuery)!=0){

                        $checkValuesQuery = "SELECT * FROM answers WHERE id = $answerId";
                        $checkValuesQueryResult = mysqli_query($conn , $checkValuesQuery);
        
                        if($checkValuesQueryResult ){
                            if(mysqli_num_rows($checkValuesQueryResult)!=0 ){
        
                                $checkValues = mysqli_fetch_assoc($checkValuesQueryResult);
                                $oldBody = $checkValues['body'];
        
                                $post = mysqli_fetch_assoc($resultPostIdQuery);
                                $post_id = $post['post_id'];
        
        
        
        
                                        if(empty($errors)){
                                            //update in db
                                            
                                            $query =  "UPDATE answers SET body = '$body' WHERE  user_id = '$loginUserId' AND id = '$answerId' AND post_id = '$post_id' ";
                                            $result = mysqli_query($conn , $query);
                                            $checkResult = mysqli_affected_rows( $conn) ;
        
                                            
                                            if($result){
                                                if($oldBody != $body){
                                                    
                                                    if($checkResult > 0){
                                                            
                                                            $successMessage = json_encode("answer updated successfully");
                                                            echo $successMessage;
                                                       
                                                    }else{
                                                        renderError("Login user only who can update",500);
                                                    }
                                                }else{
                                                    renderError("you can not update same values",500);
        
                                                }
        
                                            }else{
                                                    renderError("failed update answer",500);
                                            }
                                        
                                        }else{
                                                $errorMessage =json_encode($errors);
                                                echo $errorMessage ;
                                        }
        
        
                            }else{
                                renderError("answer not found" , 404);
                            }
                            
                        }else{
                            renderError("answer not found" , 404);
                        }

                    }else{
                        renderError("post not found" , 404);
                    }
                }else{
                    renderError("post  not found" , 404);
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
