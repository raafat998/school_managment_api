<?php

header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json');
header('Access-Control-Allow-Methods: GET, POST, PUT');
header('Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With');

include ("function.php");

$requestMethod = $_SERVER["REQUEST_METHOD"];

if ($requestMethod == "GET") {
    if (isset($_GET["teacher_id"])) {
        echo get_students_by_subject_id($_GET);
    } elseif (isset($_GET["student_id"])) {
        echo get_subjects_by_student_id($_GET);
    } elseif (isset($_GET["student_exams"])) {
        echo get_exams_by_student_id($_GET);
    } elseif (isset($_GET["subject_exams"])) {
        echo get_exams_by_subject_id($_GET);
    } else {
        $data = [
            'status' => 400,
            'message' => 'Bad Request: teacher_id, student_id, student_exams, or subject_exams is required',
        ];
        header("HTTP/1.0 400 Bad Request");
        echo json_encode($data);
    }
} elseif ($requestMethod == "POST") {
    $inputData = json_decode(file_get_contents("php://input"), true);
    if (isset($inputData["student_id"]) && isset($inputData["subject_id"])) {
        register_student_to_subject($inputData);
    } else {
        $data = [
            'status' => 400,
            'message' => 'Bad Request: student_id and subject_id are required',
        ];
        header("HTTP/1.0 400 Bad Request");
        echo json_encode($data);
    }
} elseif ($requestMethod == "PUT") {
    $inputData = json_decode(file_get_contents("php://input"), true);
    if (isset($inputData["exam_id"])) {
        update_exam_details($inputData);
    } else {
        $data = [
            'status' => 400,
            'message' => 'Bad Request: exam_id is required',
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
