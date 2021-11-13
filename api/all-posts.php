<?php
require_once("../db-conn.php");



if($_SERVER["REQUEST_METHOD"] == "GET"){

    $page = (isset($_GET['page'])) ? (int) $_GET['page'] : 1;
    $totalCountQuery = "SELECT COUNT(id) AS totalCount FROM posts";
    $totalCountResult = mysqli_query($conn,$totalCountQuery);
    $total_Count = mysqli_fetch_assoc($totalCountResult);
    $totalCount = (int) $total_Count['totalCount'];
    $pageLimit = 5;
    $pagesNum = ceil($totalCount / $pageLimit) ;
    $offset = ($page - 1) * $pageLimit;


    if( validatePage( $page , $pagesNum)){
        
        $query = "SELECT * FROM posts ORDER BY created_at DESC LIMIT $pageLimit OFFSET $offset";
        $result = mysqli_query($conn , $query);
        $posts = mysqli_fetch_all($result , MYSQLI_ASSOC);
        $postsJson = json_encode($posts);
    
        echo $postsJson;
    }else{
        renderError("page not found" , 404);
    }





}else{
    renderError("Method not allowed" , 405);
}