<?php 
//  show error -------------------------------------------------------------------------------------------------------

require "../conn.php";
function erorr422($message)
{
    $data = [
        'status' => 422,
        'mesage' => $message

    ];
    header('HTTP/1.0 422 Unprocessable Entity');
    echo json_encode($data);
}



//  get_students_by_subject_id ---------------------------------------------------------------------------------------------------------

function get_students_by_subject_id($SubjectParams) {
    global $conn;

    // التحقق من اتصال قاعدة البيانات
    if ($conn->connect_error) {
        $data = [
            'status' => 500,
            'message' => 'Database Connection Failed',
        ];
        header("HTTP/1.0 500 Internal Server Error");
        echo json_encode($data);
        exit();
    }

    $subject_id = mysqli_real_escape_string($conn, $SubjectParams['subject_id']);

    $query = "SELECT students.* FROM students 
              JOIN student_subject ON students.student_id = student_subject.student_id 
              WHERE student_subject.subject_id = '$subject_id'";

    $result = mysqli_query($conn, $query);

    if ($result) {
        if (mysqli_num_rows($result) > 0) {
            $students = mysqli_fetch_all($result, MYSQLI_ASSOC);

            $data = [
                'status' => 200,
                'message' => 'Students Fetched Successfully',
                'data' => $students
            ];
            header("HTTP/1.0 200 OK");
            echo json_encode($data);
        } else {
            $data = [
                'status' => 404,
                'message' => 'No Students Found',
            ];
            header("HTTP/1.0 404 No Students Found");
            echo json_encode($data);
        }
    } else {
        $data = [
            'status' => 500,
            'message' => 'Internal Server Error',
        ];
        header("HTTP/1.0 500 Internal Server Error");
        echo json_encode($data);
    }

    // إغلاق اتصال قاعدة البيانات
    $conn->close();
}


//  get_subjects_by_student_id ---------------------------------------------------------------------------------------------------------


function get_subjects_by_student_id($StudentParams) {
    global $conn;

    // التحقق من اتصال قاعدة البيانات
    if ($conn->connect_error) {
        $data = [
            'status' => 500,
            'message' => 'Database Connection Failed',
        ];
        header("HTTP/1.0 500 Internal Server Error");
        echo json_encode($data);
        exit();
    }

    $student_id = mysqli_real_escape_string($conn, $StudentParams['student_id']);

    $query = "SELECT subjects.* FROM subjects 
              JOIN student_subject ON subjects.subject_id = student_subject.subject_id 
              WHERE student_subject.student_id = '$student_id'";

    $result = mysqli_query($conn, $query);

    if ($result) {
        if (mysqli_num_rows($result) > 0) {
            $subjects = mysqli_fetch_all($result, MYSQLI_ASSOC);

            $data = [
                'status' => 200,
                'message' => 'Subjects Fetched Successfully',
                'data' => $subjects
            ];
            header("HTTP/1.0 200 OK");
            echo json_encode($data);
        } else {
            $data = [
                'status' => 404,
                'message' => 'No Subjects Found',
            ];
            header("HTTP/1.0 404 No Subjects Found");
            echo json_encode($data);
        }
    } else {
        $data = [
            'status' => 500,
            'message' => 'Internal Server Error',
        ];
        header("HTTP/1.0 500 Internal Server Error");
        echo json_encode($data);
    }

    // إغلاق اتصال قاعدة البيانات
    $conn->close();
}

//  register_student_to_subject ---------------------------------------------------------------------------------------------------------

function register_student_to_subject($StudentSubjectParams) {
    global $conn;
    if ($conn->connect_error) {
        $data = [
            'status' => 500,
            'message' => 'Database Connection Failed',
        ];
        header("HTTP/1.0 500 Internal Server Error");
        echo json_encode($data);
        exit();
    }

    $student_id = mysqli_real_escape_string($conn, $StudentSubjectParams['student_id']);
    $subject_id = mysqli_real_escape_string($conn, $StudentSubjectParams['subject_id']);

    $query = "INSERT INTO student_subject (student_id, subject_id) VALUES ('$student_id', '$subject_id')";

    if (mysqli_query($conn, $query)) {
        $data = [
            'status' => 201,
            'message' => 'Student Registered to Subject Successfully',
        ];
        header("HTTP/1.0 201 Created");
        echo json_encode($data);
    } else {
        $data = [
            'status' => 500,
            'message' => 'Internal Server Error',
        ];
        header("HTTP/1.0 500 Internal Server Error");
        echo json_encode($data);
    }

    $conn->close();
}

// get_exams_by_student_id----------------------------------------------------------------------------------------------------------------

function get_exams_by_student_id($StudentParams) {
    global $conn;
    if ($conn->connect_error) {
        $data = [
            'status' => 500,
            'message' => 'Database Connection Failed',
        ];
        header("HTTP/1.0 500 Internal Server Error");
        echo json_encode($data);
        exit();
    }

    $student_id = mysqli_real_escape_string($conn, $StudentParams['student_id']);

    $query = "SELECT exams.* FROM exams 
              JOIN student_exam ON exams.exam_id = student_exam.exam_id 
              WHERE student_exam.student_id = '$student_id'";

    $result = mysqli_query($conn, $query);

    if ($result) {
        if (mysqli_num_rows($result) > 0) {
            $exams = mysqli_fetch_all($result, MYSQLI_ASSOC);

            $data = [
                'status' => 200,
                'message' => 'Exams Fetched Successfully',
                'data' => $exams
            ];
            header("HTTP/1.0 200 OK");
            echo json_encode($data);
        } else {
            $data = [
                'status' => 404,
                'message' => 'No Exams Found',
            ];
            header("HTTP/1.0 404 No Exams Found");
            echo json_encode($data);
        }
    } else {
        $data = [
            'status' => 500,
            'message' => 'Internal Server Error',
        ];
        header("HTTP/1.0 500 Internal Server Error");
        echo json_encode($data);
    }

    $conn->close();
}

// get_exams_by_subject_id ----------------------------------------------------------------------------------------------------------------

function get_exams_by_subject_id($SubjectParams) {
    global $conn;
    if ($conn->connect_error) {
        $data = [
            'status' => 500,
            'message' => 'Database Connection Failed',
        ];
        header("HTTP/1.0 500 Internal Server Error");
        echo json_encode($data);
        exit();
    }

    $subject_id = mysqli_real_escape_string($conn, $SubjectParams['subject_id']);

    $query = "SELECT * FROM exams WHERE subject_id = '$subject_id'";

    $result = mysqli_query($conn, $query);

    if ($result) {
        if (mysqli_num_rows($result) > 0) {
            $exams = mysqli_fetch_all($result, MYSQLI_ASSOC);

            $data = [
                'status' => 200,
                'message' => 'Exams Fetched Successfully',
                'data' => $exams
            ];
            header("HTTP/1.0 200 OK");
            echo json_encode($data);
        } else {
            $data = [
                'status' => 404,
                'message' => 'No Exams Found',
            ];
            header("HTTP/1.0 404 No Exams Found");
            echo json_encode($data);
        }
    } else {
        $data = [
            'status' => 500,
            'message' => 'Internal Server Error',
        ];
        header("HTTP/1.0 500 Internal Server Error");
        echo json_encode($data);
    }

    $conn->close();
}

// update_exam_details----------------------------------------------------------------------------------------------------------------

function update_exam_details($ExamParams) {
    global $conn;
    if ($conn->connect_error) {
        $data = [
            'status' => 500,
            'message' => 'Database Connection Failed',
        ];
        header("HTTP/1.0 500 Internal Server Error");
        echo json_encode($data);
        exit();
    }

    $exam_id = mysqli_real_escape_string($conn, $ExamParams['exam_id']);
    $subject_id = mysqli_real_escape_string($conn, $ExamParams['subject_id']);
    $date = mysqli_real_escape_string($conn, $ExamParams['date']);
    $max_score = mysqli_real_escape_string($conn, $ExamParams['max_score']);

    $query = "UPDATE exams SET subject_id = '$subject_id', date = '$date', max_score = '$max_score' WHERE exam_id = '$exam_id'";

    if (mysqli_query($conn, $query)) {
        $data = [
            'status' => 200,
            'message' => 'Exam Details Updated Successfully',
        ];
        header("HTTP/1.0 200 OK");
        echo json_encode($data);
    } else {
        $data = [
            'status' => 500,
            'message' => 'Internal Server Error',
        ];
        header("HTTP/1.0 500 Internal Server Error");
        echo json_encode($data);
    }

    $conn->close();
}
?>

?>