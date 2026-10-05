<?php

$students = array(
    [
        "name" => "Orges",
        "surname" => "Murati",
        "age" => 14,
        "number_of_activities" => 31,
        "completed_activities" => 6,
    ],
    [
        "name" => "Eris",
        "surname" => "Ujkani",
        "age" => 15,
        "number_of_activities" => 31,
        "completed_activities" => 25
    ],
    [
        "name" => "Mars",
        "surname" => "Veliqi",
        "age" => 14,
        "number_of_activities" => 31,
        "completed_activities" => 19
    ]
);

foreach ($students as &$student) {
    $student["percentage"] =
        ($student["completed_activities"] / $student["number_of_activities"]) * 100;

    if ($student["percentage"] <= 20) {
        $student["result"] = "Bad";
    } elseif ($student["percentage"] <= 40) {
        $student["result"] = "Average";
    } elseif ($student["percentage"] <= 60) {
        $student["result"] = "Good";
    } elseif ($student["percentage"] <= 79) {
        $student["result"] = "Very Good";
    } else {
        $student["result"] = "Excellent";
    }
}

unset($student);

foreach ($students as $student) {
    echo "Emri: " . $student["name"] . "<br>";
    echo "Mbiemri: " . $student["surname"] . "<br>";
    echo "Mosha: " . $student["age"] . "<br>";
    echo "Numri i aktiviteteve: " . $student["number_of_activities"] . "<br>";
    echo "Aktivitetet e përfunduara: " . $student["completed_activities"] . "<br>";
    echo "Përqindja: " . round($student["percentage"], 2) . "%<br>";
    echo "<hr>";
}

?>