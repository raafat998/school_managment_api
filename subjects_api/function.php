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

//  store storeSubject -------------------------------------------------------------------------------------------------------

function storeSubject($SubjectInput)
{

    global $conn;

    $name = mysqli_real_escape_string($conn, $SubjectInput['name']);
    $description = mysqli_real_escape_string($conn, $SubjectInput['description']);
   
    if (empty(trim($name))) {
        return erorr422('Enter Your Name');
    } elseif (empty(trim($description))) {
        return erorr422('Enter Your description');
    } else {
        $query = "INSERT INTO subjects (name,description) VALUES ('$name',' $description')";
        $result = mysqli_query($conn, $query);

        if ($result) {
            $data = [
                'status' => 201,
                'mesage' => 'subject created Successfuly ',
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


//  show all Subjectss --------------------------------------------------------------------------------------------------------
function getSubjectsList(){
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
    $query = "SELECT * FROM subjects";
    $query_run = mysqli_query($conn, $query);

    if ($query_run) {
        if (mysqli_num_rows($query_run) > 0) {
            $res = mysqli_fetch_all($query_run, MYSQLI_ASSOC);

            $data = [
                'status' => 200,
                'message' => 'Subjects List Fetched Successfully',
                'data' => $res  // إضافة قائمة المستخدمين هنا
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


//  show supject by id ---------------------------------------------------------------------------------------------------------

function  get_Subjects_by_id($SubjectsParams){
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



    $subject_id = mysqli_real_escape_string($conn, $SubjectsParams['subject_id']);

    $query = "SELECT * FROM subjects WHERE subject_id = '$subject_id' Limit 1 ";

    $reult = mysqli_query($conn, $query);

    if ($reult) {
        if (mysqli_num_rows($reult) > 0) {
            $res = mysqli_fetch_all($reult, MYSQLI_ASSOC);

            $data = [
                'status' => 200,
                'message' => 'Subject List Fetched Successfully',
                'data' => $res  // إضافة قائمة المستخدمين هنا
            ];
            header("HTTP/1.0 200 OK");
            echo json_encode($data);
        } else {
            $data = [
                'status' => 404,
                'message' => 'No Subject Found',
            ];
            header("HTTP/1.0 404 No Subject Found");
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

//  update Subjects ---------------------------------------------------------------------------------------------------------

function  updateSubject($supjectInput,$supjectParams){
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

    if(!isset($supjectParams["subject_id"]) ){

        return erorr422('Subjects id not found in url'); 
       }elseif($supjectParams["subject_id"] == null){
        return erorr422('Enter Your id');
       }


    $subject_id = mysqli_real_escape_string($conn, $supjectParams['subject_id']);



    $name_update = mysqli_real_escape_string($conn, $supjectInput['name']);
    $description = mysqli_real_escape_string($conn, $supjectInput['description']);
   

    if (empty(trim($name_update))) {
        return erorr422('Enter Your  Name');
    } elseif (empty(trim($description))) {
        return erorr422('Enter Your description');
    } else {
        $query = "UPDATE  subjects SET name='$name_update', description='$description'    WHERE subject_id = '$subject_id' LIMIT 1 ";
        $result = mysqli_query($conn, $query);

        if ($result) {
            $data = [
                'status' => 200,
                'mesage' => 'Subject updated Successfuly ',
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

//  delete Subject ---------------------------------------------------------------------------------------------------------

function  delete_supject($SubjectsParams){
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

    if(!isset($SubjectsParams["subject_id"]) ){

        return erorr422('Subjects id not found in url'); 
       }elseif($SubjectsParams["subject_id"] == null){
        return erorr422('Enter Your id');
       }

    $subject_id = mysqli_real_escape_string($conn, $SubjectsParams['subject_id']);

    $query = "DELETE FROM subjects WHERE subject_id = '$subject_id' Limit 1 ";

    $reult = mysqli_query($conn, $query);

    if ($reult) {
       
            $data = [
                'status' => 200,
                'message' => 'Subjects deleted Successfully',
            ];
            header("HTTP/1.0 200 OK");
            return json_encode($data);
        } else {
            $data = [
                'status' => 404,
                'message' => 'No Subjects Found',
            ];
            header("HTTP/1.0 404 No Subjects Found");
            echo json_encode($data);
        }
    } 
    // إغلاق اتصال قاعدة البيانات

?>