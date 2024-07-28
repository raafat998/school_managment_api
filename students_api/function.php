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

//  store students -------------------------------------------------------------------------------------------------------

function storeStudent($userInput)
{

    global $conn;

    $name = mysqli_real_escape_string($conn, $userInput['name']);
    $birth_date = mysqli_real_escape_string($conn, $userInput['birth_date']);
    $address = mysqli_real_escape_string($conn, $userInput['address']);
    $contact_information = mysqli_real_escape_string($conn, $userInput['contact_information']);

   
    if (empty(trim($name))) {
        return erorr422('Enter Your Name');
    } elseif (empty(trim($birth_date))) {
        return erorr422('Enter Your birth date');
    } elseif (empty(trim($address))) {
        return erorr422('Enter Your address');
    }elseif (empty(trim($contact_information))) {
        return erorr422('Enter Your contact information');
    } else {
        $query = "INSERT INTO students (name,birth_date,address,contact_information) VALUES ('$name',' $birth_date','$address','$contact_information')";
        $result = mysqli_query($conn, $query);

        if ($result) {
            $data = [
                'status' => 201,
                'mesage' => 'student created Successfuly ',
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


//  show all students --------------------------------------------------------------------------------------------------------
function getStudentList(){
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

   
    $query = "SELECT * FROM students";

    $query_run = mysqli_query($conn, $query);

    if ($query_run) {

        if (mysqli_num_rows($query_run) > 0) {

            $res = mysqli_fetch_all($query_run, MYSQLI_ASSOC);

            $data = [
                'status' => 200,
                'message' => 'students List Fetched Successfully',
                'data' => $res  // إضافة قائمة المستخدمين هنا
            ];
            header("HTTP/1.0 200 OK");
            echo json_encode($data);
        } else {
            $data = [
                'status' => 404,
                'message' => 'No students Found',
            ];
            header("HTTP/1.0 404 No students Found");
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


//  show user by id ---------------------------------------------------------------------------------------------------------

function  get_student_by_id($StudentParams){
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

    $query = "SELECT * FROM students WHERE student_id = '$student_id' Limit 1 ";

    $reult = mysqli_query($conn, $query);

    if ($reult) {
        if (mysqli_num_rows($reult) > 0) 
        {
            $res = mysqli_fetch_all($reult, MYSQLI_ASSOC);

            $data = [
                'status' => 200,
                'message' => 'student List Fetched Successfully',
                'data' => $res  // إضافة قائمة المستخدمين هنا
            ];
            header("HTTP/1.0 200 OK");
            echo json_encode($data);
        } else {
            $data = [
                'status' => 404,
                'message' => 'No student Found',
            ];
            header("HTTP/1.0 404 No student Found");
            echo json_encode($data);
        }
    } else {
        $data = [
            'status' => 505,
            'message' => 'Internal Server Error',
        ];
        header("HTTP/1.0 500 Internal Server Error");
        echo json_encode($data);
    }

    // إغلاق اتصال قاعدة البيانات
    $conn->close();
}

//  update Student ---------------------------------------------------------------------------------------------------------

function  updateStudent($userInput,$userParams){
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

    if(!isset($userParams["student_id"]) ){

        return erorr422('student id not found in url'); 
       }elseif($userParams["student_id"] == null){
        return erorr422('Enter Your id');
       }


    $student_id = mysqli_real_escape_string($conn, $userParams['student_id']);



    $name_update = mysqli_real_escape_string($conn, $userInput['name']);
    $birth_update = mysqli_real_escape_string($conn, $userInput['birth_date']);
    $address_update = mysqli_real_escape_string($conn, $userInput['address']);
    $contact_update = mysqli_real_escape_string($conn, $userInput['contact_information']);


    if (empty(trim($name_update))) {
        return erorr422('Enter Your  Name');
    } elseif (empty(trim($birth_update))) {
        return erorr422('Enter Your birth date');
    } elseif (empty(trim($address_update))) {
        return erorr422('Enter Your address');
    }elseif (empty(trim($contact_update))) {
        return erorr422('Enter Your contact information');
    } else {
        
        $query = "UPDATE  students SET name='$name_update', birth_date='$birth_update', address='$address_update' ,contact_information='$contact_update'    WHERE student_id = '$student_id' LIMIT 1 ";
        $result = mysqli_query($conn, $query);

        if ($result) {
            $data = [
                'status' => 200,
                'mesage' => 'student updated Successfuly ',
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

function  delete_student($StudentParams){
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

    if(!isset($StudentParams["student_id"]) ){

        return erorr422('student id not found in url'); 
       }elseif($StudentParams["student_id"] == null){
        return erorr422('Enter Your id');
       }

    $student_id = mysqli_real_escape_string($conn, $StudentParams['student_id']);

    $query = "DELETE FROM students WHERE student_id = '$student_id' Limit 1 ";

    $reult = mysqli_query($conn, $query);

    if ($reult) {
       
            $data = [
                'status' => 200,
                'message' => 'student deleted Successfully',
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