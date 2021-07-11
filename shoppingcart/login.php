<?php
if($_SERVER["REQUEST_METHOD"] == "POST") {
    if(empty($_POST['email'])) {
        echo "Az email nem maradhat üresen!";
    }
}

?>