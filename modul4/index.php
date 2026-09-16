<?php
    //Built-in functions
    //phpinfo();

    $x="Hello";
    print_r($x);
    echo "<br>";

    $a=5;
    echo gettype($a);
    echo "<br>";
    
    $b=12.56;
    echo gettype($b);
    echo "<br>";

    $c="Hi";
    echo gettype($c);
    echo "<br>";

function display(){
    echo "this is php version ".phpversion();
    echo "<br>";
}
display();

function hello(){
    echo "Hello World";
    echo "<br>";
}
hello();

function sum(){
    $value=120+20;
    echo $value;
    echo "<br>";
}
sum();

function qungallunga($x,$y){
    $value=$x+$y;
    echo $value;
    echo "<br>";
}
qungallunga(371.2,222.1);

function maks($a,$b){
    if($a>$b){
        return $a;
    }else{
        return $b;
    }
}
$a=27.43;
$b=73.98;

$test= maks($a,$b);
echo "the max of $a and $b is $test";
echo "<br>";

function localVariable() {
    $h = 10; // local variable
    //echo $z;
    echo $h;
}
localVariable();
echo "<br>";

function callCounter(){
    static $count = 0;
    $count++; //1
    echo "The value of count is: $count";
    echo "<br>";
}
callCounter(); //count=1
callCounter();
?>