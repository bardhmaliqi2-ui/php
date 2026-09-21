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
echo "<br>";

// $basketball_players=array("LeBron James,Steph Curry,Michael Jordan,Kobe Bryant,Anthony Edwards"); //I
$basketball_players=["LeBron James","Steph Curry","Michael Jordan","Kobe Bryant","Anthony Edwards"];         //II

echo $basketball_players[0];
echo "<br>";
echo end($basketball_players);
echo "<br>";
echo count($basketball_players);
echo "<br>";
echo "<br>";

array_push($basketball_players,"Jalen Brunson");
array_unshift($basketball_players, "Victor Wembanyama");

echo "<br>";
echo count($basketball_players);
for($i=0;$i<7;$i++){
    echo $basketball_players[$i];
    echo "<br>";
}


array_shift($basketball_players); // - removes the first item
array_pop($basketball_players);  //- removes the last item

echo "<br>";
echo "<br>";

for($i=0;$i<5;$i++){
    echo $basketball_players[$i];
    echo "<br>";
}
echo "<br>";
echo "<br>";
var_dump($basketball_players);

$output1=array_slice($basketball_players,4);
$output2=array_slice($basketball_players,0,3);
echo "<br>";
var_dump($output1);
echo "<br>";
var_dump($output2);
?>