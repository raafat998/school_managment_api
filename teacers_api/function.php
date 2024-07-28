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

//  store techers -------------------------------------------------------------------------------------------------------

function storeTeacher($userInput)
{

    global $conn;

    $name = mysqli_real_escape_string($conn, $userInput['name']);
    $subjects_taught = mysqli_real_escape_string($conn, $userInput['subjects_taught']);
    $contact_information = mysqli_real_escape_string($conn, $userInput['contact_information']);
    $subject_id = mysqli_real_escape_string($conn, $userInput['subject_id']);

    if (empty(trim($name))) {
        return erorr422('Enter Your Name');
    } elseif (empty(trim($subjects_taught))) {
        return erorr422('Enter Your subjects taught');
    } elseif (empty(trim($subject_id))) {
        return erorr422('Enter subject id');
    }elseif (empty(trim($contact_information))) {
        return erorr422('Enter Your contact information');
    } else {
        $query = "INSERT INTO teachers (name,subjects_taught,contact_information,subject_id) VALUES ('$name',' $subjects_taught','$contact_information','$subject_id')";
        $result = mysqli_query($conn, $query);

        if ($result) {
            $data = [
                'status' => 201,
                'mesage' => 'teacher created Successfuly ',
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


//  show all Teachers --------------------------------------------------------------------------------------------------------
function getTeachersList(){
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
    $query = "SELECT * FROM teachers";
    $query_run = mysqli_query($conn, $query);

    if ($query_run) {
        if (mysqli_num_rows($query_run) > 0) {
            $res = mysqli_fetch_all($query_run, MYSQLI_ASSOC);

            $data = [
                'status' => 200,
                'message' => 'teachers List Fetched Successfully',
                'data' => $res  // إضافة قائمة المستخدمين هنا
            ];
            header("HTTP/1.0 200 OK");
            echo json_encode($data);
        } else {
            $data = [
                'status' => 404,
                'message' => 'No teachers Found',
            ];
            header("HTTP/1.0 404 No teachers Found");
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


//  show teacher by id ---------------------------------------------------------------------------------------------------------

function  get_teacher_by_id($TeacherParams){
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



    $teacher_id = mysqli_real_escape_string($conn, $TeacherParams['teacher_id']);

    $query = "SELECT * FROM teachers WHERE teacher_id = '$teacher_id' Limit 1 ";

    $reult = mysqli_query($conn, $query);

    if ($reult) {
        if (mysqli_num_rows($reult) > 0) {
            $res = mysqli_fetch_all($reult, MYSQLI_ASSOC);

            $data = [
                'status' => 200,
                'message' => 'teacher  Fetched Successfully',
                'data' => $res  // إضافة قائمة المستخدمين هنا
            ];
            header("HTTP/1.0 200 OK");
            echo json_encode($data);
        } else {
            $data = [
                'status' => 404,
                'message' => 'No teacher Found',
            ];
            header("HTTP/1.0 404 No teacher Found");
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

//  update tecaher ---------------------------------------------------------------------------------------------------------

function  updateTeacher($teacherInput,$teacherParams){
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

    if(!isset($teacherParams["teacher_id"]) ){

        return erorr422('teacher id not found in url'); 
       }elseif($teacherParams["teacher_id"] == null){
        return erorr422('Enter  id');
       }


    $teacher_id = mysqli_real_escape_string($conn, $teacherParams['teacher_id']);



    $name_update = mysqli_real_escape_string($conn, $teacherInput['name']);
    $subjects_taught = mysqli_real_escape_string($conn, $teacherInput['subjects_taught']);
    $contact_information = mysqli_real_escape_string($conn, $teacherInput['contact_information']);
    $subject_id = mysqli_real_escape_string($conn, $teacherInput['subject_id']);


    if (empty(trim($name_update))) {
        return erorr422('Enter Your  Name');
    } elseif (empty(trim($subjects_taught))) {
        return erorr422('Enter Your subjects taught');
    } elseif (empty(trim($subject_id))) {
        return erorr422('Enter Yor subject id');
    }elseif (empty(trim($contact_information))) {
        return erorr422('Enter Your contact information');
    } else {
        $query = "UPDATE  teachers SET name='$name_update', subjects_taught='$subjects_taught', contact_information='$contact_information' ,subject_id='$subject_id'    WHERE teacher_id = '$teacher_id' LIMIT 1 ";
        $result = mysqli_query($conn, $query);

        if ($result) {
            $data = [
                'status' => 200,
                'mesage' => 'teacher updated Successfuly ',
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

//  delete student ---------------------------------------------------------------------------------------------------------

function  delete_teacher($TeacherParams){
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

    if(!isset($TeacherParams["teacher_id"]) ){

        return erorr422('teacher id not found in url'); 
       }elseif($TeacherParams["teacher_id"] == null){
        return erorr422('Enter Your id');
       }

    $teacher_id = mysqli_real_escape_string($conn, $TeacherParams['teacher_id']);

    $query = "DELETE FROM teachers WHERE teacher_id = '$teacher_id' Limit 1 ";

    $reult = mysqli_query($conn, $query);

    if ($reult) {
       
            $data = [
                'status' => 200,
                'message' => 'teacher deleted Successfully',
            ];
            header("HTTP/1.0 200 OK");
            return json_encode($data);
        } else {
            $data = [
                'status' => 404,
                'message' => 'No student Found',
            ];
            header("HTTP/1.0 404 No student Found");
            echo json_encode($data);
        }
    } 
    // إغلاق اتصال قاعدة البيانات

?>