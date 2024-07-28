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

//  store exams -------------------------------------------------------------------------------------------------------

function storeExam($ExamInput)
{

    global $conn;

    $subject_id = mysqli_real_escape_string($conn, $ExamInput['subject_id']);
    $date = mysqli_real_escape_string($conn, $ExamInput['date']);
    $max_score = mysqli_real_escape_string($conn, $ExamInput['max_score']);

    if (empty(trim($subject_id))) {
        return erorr422('Enter subject id');
    } elseif (empty(trim($date))) {
        return erorr422('Enter Exam date');
    } elseif (empty(trim($max_score))) {
        return erorr422('Enter max score');
    }else {
        $query = "INSERT INTO exams (subject_id,date,max_score) VALUES ('$subject_id',' $date','$max_score')";
        $result = mysqli_query($conn, $query);

        if ($result) {
            $data = [
                'status' => 201,
                'mesage' => 'exam created Successfuly ',
            ];
            header('HTTP/1.0 201 Created');
            return json_encode($data);
        } else {
            $data = [
                'status' => 405,
                'mesage' => 'Internal Server Error ',
            ];
            header('HTTP/1.0 500 Internal Server Error');
            return json_encode($data);
        }
    }
}


//  show all exams --------------------------------------------------------------------------------------------------------
function getExamList(){
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

    // تنفيذ الاستعلام
    $query = "SELECT * FROM exams";
    $query_run = mysqli_query($conn, $query);

    if ($query_run) {
        if (mysqli_num_rows($query_run) > 0) {
            $res = mysqli_fetch_all($query_run, MYSQLI_ASSOC);

            $data = [
                'status' => 200,
                'message' => 'exams List Fetched Successfully',
                'data' => $res  // إضافة قائمة المستخدمين هنا
            ];
            header("HTTP/1.0 200 OK");
            echo json_encode($data);
        } else {
            $data = [
                'status' => 404,
                'message' => 'No exams Found',
            ];
            header("HTTP/1.0 404 No exams Found");
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


//  show exam by id ---------------------------------------------------------------------------------------------------------

function  get_exam_by_id($ExamsParams){
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



    $exam_id = mysqli_real_escape_string($conn, $ExamsParams['exam_id']);

    $query = "SELECT * FROM exams WHERE exam_id = '$exam_id' Limit 1 ";

    $reult = mysqli_query($conn, $query);

    if ($reult) {
        if (mysqli_num_rows($reult) > 0) {
            $res = mysqli_fetch_all($reult, MYSQLI_ASSOC);

            $data = [
                'status' => 200,
                'message' => 'exam  Fetched Successfully',
                'data' => $res  // إضافة قائمة المستخدمين هنا
            ];
            header("HTTP/1.0 200 OK");
            echo json_encode($data);
        } else {
            $data = [
                'status' => 404,
                'message' => 'No exam Found',
            ];
            header("HTTP/1.0 404 No exam Found");
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

//  update Exam ---------------------------------------------------------------------------------------------------------

function  updateExam($ExamInput,$ExamParams){
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

    if(!isset($ExamParams["exam_id"]) ){

        return erorr422('exam id not found in url'); 
       }elseif($ExamParams["exam_id"] == null){
        return erorr422('Enter exam id');
       }


    $exam_id = mysqli_real_escape_string($conn, $ExamParams['exam_id']);



    $subject_id = mysqli_real_escape_string($conn, $ExamInput['subject_id']);
    $date = mysqli_real_escape_string($conn, $ExamInput['date']);
    $max_score = mysqli_real_escape_string($conn, $ExamInput['max_score']);
   
    if (empty(trim($subject_id))) {
        return erorr422('Enter subject id');
    } elseif (empty(trim($date))) {
        return erorr422('Enter exam date');
    } elseif (empty(trim($max_score))) {
        return erorr422('Enter max score');
    } else {
        $query = "UPDATE  exams SET subject_id='$subject_id', date='$date', max_score='$max_score'     WHERE exam_id = '$exam_id' LIMIT 1 ";
        $result = mysqli_query($conn, $query);

        if ($result) {
            $data = [
                'status' => 200,
                'mesage' => 'exam updated Successfuly ',
            ];
            header('HTTP/1.0 200 Success');
            return json_encode($data);
        } else {
            $data = [
                'status' => 405,
                'mesage' => 'Internal Server Error ',
            ];
            header('HTTP/1.0 500 Internal Server Error');
            return json_encode($data);
        }
    }
}

//  delete exam ---------------------------------------------------------------------------------------------------------

function  delete_exam($ExamParams){
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

    if(!isset($ExamParams["exam_id"]) ){

        return erorr422('exam id not found in url'); 
       }elseif($ExamParams["exam_id"] == null){
        return erorr422('Enter Your id');
       }

    $exam_id = mysqli_real_escape_string($conn, $ExamParams['exam_id']);

    $query = "DELETE FROM exams WHERE exam_id = '$exam_id' Limit 1 ";

    $reult = mysqli_query($conn, $query);

    if ($reult) {
       
            $data = [
                'status' => 200,
                'message' => 'exam deleted Successfully',
            ];
            header("HTTP/1.0 200 OK");
            return json_encode($data);
        } else {
            $data = [
                'status' => 404,
                'message' => 'No exam Found',
            ];
            header("HTTP/1.0 404 No exam Found");
            echo json_encode($data);
        }
    } 
    // إغلاق اتصال قاعدة البيانات

?>