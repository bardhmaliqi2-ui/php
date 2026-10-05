<?php
// $my_file=fopen("file1.txt","w");
// fclose($my_file);

//f-read
$filename="file1.txt";
$file=fopen($filename,"r");

$filesize=filesize($filename);
$my_filedata=fread($file,$filesize);
echo $my_filedata . "<br>";
fclose($file);

$file1=fopen("myfile.txt","r");
while(!feof($file1)){
    echo fgets($file1);
    echo "<br>";
}

?>