<?php
require_once("../db-conn.php");



if($_SERVER["REQUEST_METHOD"] == "GET"){

    if(isset($_GET["id"]) and $_GET["id"] != ''){
        $answerId = $_GET["id"];

        $query = "SELECT * FROM answers WHERE id = $answerId";
        $result = mysqli_query($conn , $query);
        if(mysqli_num_rows($result) != 0){
            
            $post = mysqli_fetch_assoc($result);
            $postJson = json_encode($post);
        
            echo $postJson;
        }else{
            renderError("answer Not Found" , 404);
        }

    }else{
        renderError("answer Not Found" , 404);
    }


}else{
    renderError("Method not allowed" , 405);
}