<?php
session_start();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="Cache-Control" content="no-cache">
    <title>Проверка клиентов</title>
    <link rel='stylesheet' href='css/blacklist.css' type='text/css' media='all' />
    <link rel='stylesheet' href='css/blacklistIndexHtml.css' type='text/css' media='all' />

    <link rel="shortcut icon" type="image/x-icon" href="images/1548252846.ico">
</head>
<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once 'config/config.php';
require_once 'php/BlackListController.php';
$controller = new BlackListController($config);
//echo $_POST['vid_id'];
/**
 * Created by PhpStorm.
 * User: Katanugina
 * Date: 24.01.2019
 * Time: 17:46


$dest_path = './files/';
if ($_FILES['file']['error'] == 0){
    $fname = $_POST['fname'];
    if (move_uploaded_file($_FILES['file']['tmp_name'], $dest_path)){
        md5($fname);
       $sql = "INSERT INTO 'files' ('id', 'fname') VALUES (NULL, '$fname')";
        echo $sql;
    }
}
switch ($command){
    case 'getFile':echo 'getFile' . $command; break;
}*/


if(count($_FILES)){


    $upload_name = $_SERVER["DOCUMENT_ROOT"]."/mod-tmp/".
        encodestring(basename($_FILES['userfile']['name']));
    echo '$upload_name==='.$upload_name;

    handleUploadedFile($upload_name,$_POST['vid_id'],$_SESSION['user_id'],$controller);

    if(move_uploaded_file($_FILES['userfile']['tmp_name'], ($upload_name)))
        echo "Файл успешно загружен в папку ".dirname($upload_name);
    else echo "Ошибка загрузки файла";
}

$controller->addFromFile();


// функция превода текста с кириллицы в траскрипт
    function encodestring($st)
    {
        // Сначала заменяем "односимвольные" фонемы.
        $st=strtr($st,"абвгдеёзийклмнопрстуфхъыэ_",
            "abvgdeeziyklmnoprstufh'iei");
        $st=strtr($st,"АБВГДЕЁЗИЙКЛМНОПРСТУФХЪЫЭ_",
            "ABVGDEEZIYKLMNOPRSTUFH'IEI");
        // Затем - "многосимвольные".
        $st=strtr($st,
            array(
                "ж"=>"zh", "ц"=>"ts", "ч"=>"ch", "ш"=>"sh",
                "щ"=>"shch","ь"=>"", "ю"=>"yu", "я"=>"ya",
                "Ж"=>"ZH", "Ц"=>"TS", "Ч"=>"CH", "Ш"=>"SH",
                "Щ"=>"SHCH","Ь"=>"", "Ю"=>"YU", "Я"=>"YA",
                "ї"=>"i", "Ї"=>"Yi", "є"=>"ie", "Є"=>"Ye"
            )
        );
        // Возвращаем результат.
        return $st;
    }
function handleUploadedFile($file,$vid_id, $user_id,$controller){
    $model = $controller->getModel();
    $model->prepareUploadClient();

    $handle = fopen($file, "r");

    $cnt = 10;
    $count = 0;

    while (($s = fgets($handle)) !== FALSE) {
        if($count++==0)
            continue;
        if (strlen(trim($s)) == 0)
            continue;
        $s = str_replace(array("\r", "\n"), '', $s);
        $data = explode(',', $s);

        $num = count($data);

       echo '$num= '. $num ;
        print_r($data)  ;

        $result = $model->uploadClient($data[0], $data[1],$data[2] , $data[3], $data[5], $vid_id);
        if(!$result){
            break;
        }
        $count++;
    }
}
   /*


//    $_POST['vid_id']
}*/

?>
