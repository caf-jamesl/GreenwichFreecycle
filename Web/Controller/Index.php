<?php

main();

function main()
{
    outputPage();
}

function outputPage()
{
    $html = file_get_contents("../View/index.html");
    echo $html;
}
