<?php

function renderError(string $message ,int $stutusCode):void
{

    echo json_encode($message) ;
    http_response_code($stutusCode);
};


function validatePage(int $page , int $pagesNum) : bool
{
    return ($page >= 1 and $page <= $pagesNum);
};