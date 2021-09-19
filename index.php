<?php
if(!empty($_SESSION["role"] == 2)){
    header("location:admin/index.php");
} else {
    header("location:vues/index.php");
}
