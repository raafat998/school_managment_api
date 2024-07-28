<?php

header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json');
header('Access-Control-Allow-Method: GET');
header('Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Request-With');

include ("function.php");

$requestMethod = $_SERVER["REQUEST_METHOD"];

if ($requestMethod == "GET") {
    if (isset($_GET["teacher_id"])) {

        $phone_list = get_students_by_subject_id($_GET);
        echo($phone_list);

    } elseif (isset($_GET["student_id"])) {

        get_subjects_by_student_id($_GET);
    } else {
        $data = [
            'status' => 400,
            'message' => 'Bad Request: student_id or teacher_id is required',
        ];
        header("HTTP/1.0 400 Bad Request");
        echo json_encode($data);
    }
} else {
    $data = [
        'status' => 405,
        'message' => $requestMethod . ' Method Not Allowed',
    ];
    header("HTTP/1.0 405 Method Not Allowed");
    echo json_encode($data);
}
?>
