<?php
$num=4;
if($num>0){
    echo "$num is greater than 0";
}

$age=14;
if(($age>12) && ($age<20)){
    echo "<br>";
    echo "You are a teenager";
}

$age=21;
if (($age<18)){
    echo "<br>";
    echo "You are under 18";
}else{
    echo "<br>";
    echo "You are an adult";
}

$numb=3;
if($numb<0){
    echo "<br>";
    echo "The value of $numb is a negative number";
}elseif($numb==0){
    echo "<br>";
    echo "The value of $numb is zero";
}else{
    echo "<br>";
    echo "The value of $numb is a positive number";
}

$number=-2;
if($number<0){
    echo "<br>";
    echo "The value of $number is a negative number";
}elseif($numb==0){
    echo "<br>";
    echo "The value of $number is zero";
}else{
    echo "<br>";
    echo "The value of $number is a positive number";
}

$x=32;
$y=33;
if($x==$y){
    echo "<br>";
    echo "The same";
}else{
    echo "<br>";
    echo "Not the same";
}
echo "<br>";

$age=41;
switch(($age)){
    case(($age<10)): 
    echo "<br>";
    echo "You are a child";
    break;
case($age<16):
    echo "<br>";
    echo "You are a teenager";
    break;
case($age>20):
    echo "<br>";
    echo "You are a adult";
    break;

default:echo "You are old";
}
?>