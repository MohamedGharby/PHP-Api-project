<?php
require_once("../db-conn.php");

if($_SERVER["REQUEST_METHOD"] == "POST"){
    $answerId = isset($_POST['id'])? $_POST['id']: "";
    $token = isset($_POST['api_token'])? $_POST['api_token']: "";



    $errors = [];

    if(empty($answerId)){
        $errors[] = "answer id is required";
    }elseif(! is_numeric($answerId)){
        $errors[] = "answer id must be number";
    }elseif($answerId <= 0){
        $errors[] = "answer id must not zero or less";
    }

    if(empty($token)){
        $errors[] = "User Token is required";
    }


    $selectLoginUserIdQuery = "SELECT * FROM users WHERE api_token = '$token'";
    $resultLoginUserIdQuery = mysqli_query($conn , $selectLoginUserIdQuery);
    if ($resultLoginUserIdQuery) {
        if(mysqli_num_rows($resultLoginUserIdQuery) != 0){
            $checkIdQuery = "SELECT id FROM answers WHERE id = $answerId";
            $checkIdResult = mysqli_query($conn , $checkIdQuery);
        
            if($checkIdResult){
                if(mysqli_num_rows($checkIdResult)!=0){
        
                    if(empty($errors)){
                        
                        $voteQuery = "SELECT vote FROM answers WHERE id = $answerId";
                        $voteResult = mysqli_query($conn , $voteQuery);
                        $vote = mysqli_fetch_assoc($voteResult);
                        $newVote = $vote['vote'];
                        if($newVote > 0){
                            $newVote--;
                            
                            /*
                            $objectResult = mysqli_fetch_object($voteResult);
                            $ubdatedVote = $objectResult->vote;
                            $ubdatedVote++;
                            */
                    
                            $query =  "UPDATE answers SET vote = '$newVote' WHERE id = $answerId ";
                            $result = mysqli_query($conn , $query);
                    
                            if($result){
                                $successMessage = json_encode("vote decremented successfully");
                                echo $successMessage;
                            }else{
                                renderError("failed decrement vote",500);
                            }
                        }else{
                            renderError("Can not decrement vote",500);
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
            renderError("id not found" , 404);
        }

    }else{
        renderError("id not found" , 404);
    }
    




}else{
    renderError("Method not allowed" , 405);
}