<?php
/**
 * Created by PhpStorm.
 * User: Katanugina
 * Date: 24.01.2019
 * Time: 17:46
 */

$dest_path = './files/';
if ($_FILES['file']['error'] == 0){
    $fname = $_POST['fname'];
    if (move_uploaded_file($_FILES['file']['tmp_name'], $dest_path)){
        md5($fname);
       $sql = "INSERT INTO 'files' ('id', 'fname') VALUES (NULL, '$fname')";
        echo $sql;
    }
}
?>
