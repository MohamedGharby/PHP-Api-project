<?php
require_once("../db-conn.php");
/*
if($_SERVER["REQUEST_METHOD"] == "POST"){
    $id = isset($_POST['id'])? $_POST['id']: "";


 

    $checkIdQuery = "SELECT id FROM answers WHERE id = $id";
    $checkIdResult = mysqli_query($conn , $checkIdQuery);

    if($checkIdResult){
        if(mysqli_num_rows($checkIdResult)!=0){

           
                //delete from db
        
                $query = "DELETE FROM answers WHERE id = $id";
                $result = mysqli_query($conn , $query);
        
                if($result){
                    $successMessage = json_encode("answer deleted successfully");
                    echo $successMessage;
                }else{
                    renderError("failed delete answer",500);
                }
        
            
        }else{
            renderError("answer not found" , 404);
        }
        
    }else{
        renderError("answer not found" , 404);
    }




}else{
    renderError("Method not allowed" , 405);
}
*/


if($_SERVER["REQUEST_METHOD"] == "POST"){

    $answerId = isset($_POST['id'])? $_POST['id']: "";
    $postId = isset($_POST['post_id'])? $_POST['post_id']: "";
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

                        $post = mysqli_fetch_assoc($resultPostIdQuery);
                        $post_id = $post['post_id'];


                        $checkAnswerIdQuery = "SELECT * FROM answers WHERE id = $answerId";
                        $checkAnswerIdQueryResult = mysqli_query($conn , $checkAnswerIdQuery);
        
                        if($checkAnswerIdQueryResult ){
                            if(mysqli_num_rows($checkAnswerIdQueryResult)!=0 ){

                                        if(empty($errors)){
                                            //delete in db
                                            
                                            $query =  "DELETE FROM answers WHERE  user_id = '$loginUserId' AND id = '$answerId' AND post_id = '$post_id' ";
                                            $result = mysqli_query($conn , $query);
                                            $checkResult = mysqli_affected_rows( $conn) ;
        
                                            
                                            if($result){
                     
                                                    
                                                    if($checkResult > 0){
                                                            
                                                            $successMessage = json_encode("answer deleted successfully");
                                                            echo $successMessage;
                                                       
                                                    }else{
                                                        renderError("Login user only who can delete",500);
                                                    }
                                                
        
                                            }else{
                                                    renderError("failed delete answer",500);
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
