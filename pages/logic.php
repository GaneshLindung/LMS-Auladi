<?php 
if($target_page == "") {
    require("pages/page/home.php");
} else {
    if(file_exists("pages/page/".$target_page.".php")) {
        require("pages/page/".$target_page.".php");
    }
}
?>