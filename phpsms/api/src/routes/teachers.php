<?php
use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;

$app = new \Slim\App;
date_default_timezone_set('Africa/Tunis');

$app->options('/{routes:.+}', function ($request, $response, $args) {
    return $response;
});

$app->add(function ($req, $res, $next) {
    $response = $next($req, $res);
    return $response
            ->withHeader('Access-Control-Allow-Origin', '*')
            ->withHeader('Access-Control-Allow-Headers', 'X-Requested-With, Content-Type, Accept, Origin, Authorization')
            ->withHeader('Access-Control-Allow-Methods', 'GET, POST, PUT, DELETE, OPTIONS');
});

// Get All teachers
$app->get('/api/teachers', function(Request $request, Response $response){
    $sql = "SELECT * FROM teacher_tbl";

    try{
        // Get DB Object
        $db = new db();
        // Connect
        $db = $db->connect();

        $stmt = $db->query($sql);
        $teachers = $stmt->fetchAll(PDO::FETCH_OBJ);
        $db = null;
        echo json_encode($teachers);
    } catch(PDOException $e){
        echo '{"error": {"text": '.$e->getMessage().'}';
    }
});

// Get Single teacher
$app->get('/api/teacher/{id}', function(Request $request, Response $response){
    $id = $request->getAttribute('id');

    $sql = "SELECT * FROM teacher_tbl WHERE teacher_id = $id";

    try{
        // Get DB Object
        $db = new db();
        // Connect
        $db = $db->connect();

        $stmt = $db->query($sql);
        $teacher = $stmt->fetch(PDO::FETCH_OBJ);
        $db = null;
        echo json_encode($teacher);
    } catch(PDOException $e){
        echo '{"error": {"text": '.$e->getMessage().'}';
    }
});

// Add teacher
$app->post('/api/customer/add', function(Request $request, Response $response){
    $first_name = $request->getParam('first_name');
    $last_name = $request->getParam('last_name');
    $phone = $request->getParam('phone');
    $email = $request->getParam('email');
    $address = $request->getParam('address');
    $city = $request->getParam('city');
    $state = $request->getParam('state');

    $sql = "INSERT INTO customers (first_name,last_name,phone,email,address,city,state) VALUES
    (:first_name,:last_name,:phone,:email,:address,:city,:state)";

    try{
        // Get DB Object
        $db = new db();
        // Connect
        $db = $db->connect();

        $stmt = $db->prepare($sql);

        $stmt->bindParam(':first_name', $first_name);
        $stmt->bindParam(':last_name',  $last_name);
        $stmt->bindParam(':phone',      $phone);
        $stmt->bindParam(':email',      $email);
        $stmt->bindParam(':address',    $address);
        $stmt->bindParam(':city',       $city);
        $stmt->bindParam(':state',      $state);

        $stmt->execute();

        echo '{"notice": {"text": "teacher Added"}';

    } catch(PDOException $e){
        echo '{"error": {"text": '.$e->getMessage().'}';
    }
});



// // Add log
// {
// 	"teacher_id":"3",
// 	"status":"in",
// 	"time_in":"2018-05-13 10:25:02.000000",
// 	"time_out":"2018-05-13 10:25:02.000000",
// 	"minutes":"355",
// 	"date":"2018-05-13"
// }
$app->post('/api/log/add', function(Request $request, Response $response){
    $teacher_id = $request->getParam('teacher_id');
    $status = $request->getParam('status');
    $time_in = $request->getParam('time_in');
    $time_out = $request->getParam('time_out');
    $minutes = $request->getParam('minutes');
    $date = $request->getParam('date');



    $sql = "INSERT INTO log_tbl (teacher_id,status,time_in,time_out,minutes,date) VALUES
    (:teacher_id,:status,:time_in,:time_out,:minutes,:date)";

    try{
        // Get DB Object
        $db = new db();
        // Connect
        $db = $db->connect();

        $stmt = $db->prepare($sql);

        $stmt->bindParam(':teacher_id', $teacher_id);
        $stmt->bindParam(':status',  $status);
        $stmt->bindParam(':time_in',      $time_in);
        $stmt->bindParam(':time_out',      $time_out);
        $stmt->bindParam(':minutes',  $minutes);
        $stmt->bindParam(':date',      $date);


        $stmt->execute();

        echo '{"notice": {"text": "teacher Added"}';

    } catch(PDOException $e){
        echo '{"error": {"text": '.$e->getMessage().'}';
    }
});


// // Update teacher
// $app->put('/api/teacher/update/{id}', function(Request $request, Response $response){
//     $id = $request->getAttribute('id');
//     $first_name = $request->getParam('first_name');
//     $last_name = $request->getParam('last_name');
//     $phone = $request->getParam('phone');
//     $email = $request->getParam('email');
//     $address = $request->getParam('address');
//     $city = $request->getParam('city');
//     $state = $request->getParam('state');

//     $sql = "UPDATE teachers SET
// 				first_name 	= :first_name,
// 				last_name 	= :last_name,
//                 phone		= :phone,
//                 email		= :email,
//                 address 	= :address,
//                 city 		= :city,
//                 state		= :state
// 			WHERE id = $id";

//     try{
//         // Get DB Object
//         $db = new db();
//         // Connect
//         $db = $db->connect();

//         $stmt = $db->prepare($sql);

//         $stmt->bindParam(':first_name', $first_name);
//         $stmt->bindParam(':last_name',  $last_name);
//         $stmt->bindParam(':phone',      $phone);
//         $stmt->bindParam(':email',      $email);
//         $stmt->bindParam(':address',    $address);
//         $stmt->bindParam(':city',       $city);
//         $stmt->bindParam(':state',      $state);

//         $stmt->execute();

//         echo '{"notice": {"text": "teacher Updated"}';

//     } catch(PDOException $e){
//         echo '{"error": {"text": '.$e->getMessage().'}';
//     }
// });

// // Delete teacher
// $app->delete('/api/teacher/delete/{id}', function(Request $request, Response $response){
//     $id = $request->getAttribute('id');

//     $sql = "DELETE FROM teachers WHERE id = $id";

//     try{
//         // Get DB Object
//         $db = new db();
//         // Connect
//         $db = $db->connect();

//         $stmt = $db->prepare($sql);
//         $stmt->execute();
//         $db = null;
//         echo '{"notice": {"text": "teacher Deleted"}';
//     } catch(PDOException $e){
//         echo '{"error": {"text": '.$e->getMessage().'}';
//     }
// });





// Get All teachers working today
$app->get('/api/working', function(Request $request, Response $response){
    $date_today = date("Y-m-d");
    $sql = "SELECT * FROM log_tbl WHERE date = '$date_today' AND status ='in'";

    try{
        // Get DB Object
        $db = new db();
        // Connect
        $db = $db->connect();

        $stmt = $db->query($sql);
        $teachers = $stmt->fetchAll(PDO::FETCH_OBJ);
        $db = null;
        echo json_encode($teachers);
    } catch(PDOException $e){
        echo '{"error": {"text": '.$e->getMessage().'}';
    }
});



// Get All messages
$app->get('/api/messages', function(Request $request, Response $response){
    $date_today = date("Y-m-d");
    $sql = "SELECT * FROM contact_tbl WHERE status = 'unseen'";

    try{
        // Get DB Object
        $db = new db();
        // Connect
        $db = $db->connect();

        $stmt = $db->query($sql);
        $teachers = $stmt->fetchAll(PDO::FETCH_OBJ);
        $db = null;
        echo json_encode($teachers);
    } catch(PDOException $e){
        echo '{"error": {"text": '.$e->getMessage().'}';
    }
});