<?php
require_once("../db-conn.php");



if($_SERVER["REQUEST_METHOD"] == "GET"){

    if(isset($_GET["id"]) and $_GET["id"] != ''){
        $postId = $_GET["id"];

        $query = "SELECT * FROM posts WHERE id = $postId";
        $result = mysqli_query($conn , $query);
        if(mysqli_num_rows($result) != 0){
            
            $post = mysqli_fetch_assoc($result);
            $postJson = json_encode($post);
        
            echo $postJson;
        }else{
            renderError("Post Not Found" , 404);
        }

    }else{
        renderError("Post Not Found" , 404);
    }


}else{
    renderError("Method not allowed" , 405);
}