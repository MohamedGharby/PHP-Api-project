<?php
require_once("../db-conn.php");
if($_SERVER["REQUEST_METHOD"] == "POST"){
    $token = isset($_POST['api_token'])? $_POST['api_token']: "";
    $image = isset($_FILES['image'])? $_FILES['image']: "";
    $imgName = $image['name'];
    $uploadingError = $image['error'];
    $allowed =  array('jpeg','jpg', "png", "gif", "bmp", "JPEG","JPG", "PNG", "GIF", "BMP");
    $imgExtension = pathinfo($imgName, PATHINFO_EXTENSION);
    $imgNameWithoutExtentsion = pathinfo($imgName , PATHINFO_FILENAME);
    $imgNewName = $imgNameWithoutExtentsion . uniqid() . ".$imgExtension";



    //valedation
    $errors = [];

    if(empty($token)){
        $errors[] = "User Token is required";
    }

    if(empty($image)){
        $errors[] = "image is required";
    }elseif(!in_array($imgExtension,$allowed)){
        $errors[] = "File upload must be have image extinion";
    }

    $selectLoginUserIdQuery = "SELECT * FROM users WHERE api_token = '$token'";
    $resultLoginUserIdQuery = mysqli_query($conn , $selectLoginUserIdQuery);
    if($selectLoginUserIdQuery){
        if(mysqli_num_rows($resultLoginUserIdQuery) != 0){


            if(empty($errors) and $uploadingError == 0){

              //update in db
                                    
                $query =  "UPDATE users SET image = '$imgNewName' WHERE  api_token = '$token' ";
                $result = mysqli_query($conn , $query);
                $checkResult = mysqli_affected_rows( $conn) ;  

                if($result){

                        
                        if($checkResult > 0){
                                
                                $successMessage = json_encode("image uploaded successfully");
                                echo $successMessage;
                        
                        }else{
                            renderError("Login user only who can upload image",500);
                        }
                    

                }else{
                        renderError("failed upload image",500);
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