<?php
//password 
include_once '../login/db_connect.php';
include_once '../login/functions.php';
sec_session_start();

//  \/\/swap for paaword protected api\/\/
//if (login_check($mysqli) == true){
if(true == true){
	
//end password

require 'Slim/Slim.php';
\Slim\Slim::registerAutoloader();

$app = new \Slim\Slim(array(
    "MODE" => "development",
    "TEMPLATES.PATH" => "./templates"
));

$app->add(new \Slim\Middleware\ContentTypes());

$configurations = [
    'routes.case_sensitive' => false
];

$app->config($configurations);

//get Aircraft List
$app->get('/airport', function () use($app) {
	

	
	if(!($database = mysqli_connect('localhost', 'apiGET', 'apiget', 'joust' ))){
        die("Could not reconnect to the database. Server error.");
    }
	

	$query = "SELECT * FROM Airports";


    if(!($result = mysqli_query($database, $query))){
    ?>
        <h1 class = "err"><strong>Major Error:</strong></h1>
        <p>A SQL Exception occurred while interacting with the database.</p>
        <br />
        <p>The error message was:</p>

        <p><strong><?php echo mysqli_error($database);?></strong></p>

        <p>Please try again later.</p>
        <?php

        die("");
    }

    // create result array
    $arr = array();

	$app->contentType('application/json');

	if ($result->num_rows > 0) {
        while($row = $result->fetch_assoc()) {
            $arr[] = $row;
		}
        $json_response = json_encode($arr);
        echo $json_response;
	} else {
        echo "0 results";
	}
    mysqli_close($database);
});


//get Aircraft List
$app->get('/Aircraft', function () use($app) {
	
	$detail = $app->request->get('detail');
	$detail = filter_var($detail, FILTER_VALIDATE_BOOLEAN);
	
	if(!($database = mysqli_connect('localhost', 'apiGET', 'apiget', 'joust' ))){
        die("Could not reconnect to the database. Server error.");
    }
	
	if($detail == true){
		$query = "SELECT * FROM Aircraft";
	}else{
		$query = "SELECT tail_num FROM Aircraft";
	}
	

    if(!($result = mysqli_query($database, $query))){
    ?>
        <h1 class = "err"><strong>Major Error:</strong></h1>
        <p>A SQL Exception occurred while interacting with the database.</p>
        <br />
        <p>The error message was:</p>

        <p><strong><?php echo mysqli_error($database);?></strong></p>

        <p>Please try again later.</p>
        <?php

        die("");
    }

    // create result array
    $arr = array();

	$app->contentType('application/json');

	if ($result->num_rows > 0) {
        while($row = $result->fetch_assoc()) {
            $arr[] = $row;
		}
        $json_response = json_encode($arr);
        echo $json_response;
	} else {
        echo "0 results";
	}
    mysqli_close($database);
});

$app->get("/Aircraft(/:tail_num)", function ($tail_num) use($app) {
	if(!($database = mysqli_connect('localhost', 'apiGET', 'apiget', 'joust' ))){
        die("Could not reconnect to the database. Server error.");
    }

    $query = sprintf("select * from Aircraft where tail_num ='%s'", $tail_num);
    if(!($result = mysqli_query($database, $query))){
    ?>
        <h1 class = "err"><strong>Major Error:</strong></h1>
        <p>A SQL Exception occurred while interacting with the database.</p>
        <br />
        <p>The error message was:</p>

        <p><strong><?php echo mysqli_error($database);?></strong></p>

        <p>Please try again later.</p>
        <?php

        die("");
    }

    // create result array
    $arr = array();

	$app->contentType('application/json');

	if ($result->num_rows > 0) {
        while($row = $result->fetch_assoc()) {
            $arr[] = $row;
		}
        $json_response = json_encode($arr);
        echo $json_response;
	} else {
        echo "0 results";
	}
    mysqli_close($database);
});

$app->get("/Aircraft/Flights(/:tail_num)", function ($tail_num) use($app) {
	
	$detail = $app->request->get('detail');
	$detail = filter_var($detail, FILTER_VALIDATE_BOOLEAN);
	
	$start = $app->request->get('start');
	$end = $app->request->get('end');
	
	if(!($database = mysqli_connect('localhost', 'apiGET', 'apiget', 'joust' ))){
        die("Could not reconnect to the database. Server error.");
    }
	if($detail == true){
		if($start == null or $end == null){
			$query = sprintf("select * from Flight where Aircraft_tail_num ='%s'", $tail_num);
		}else{
			$query = sprintf("select * from Flight where Aircraft_tail_num ='%s' and (start_dt >= '%s' and start_dt <= '%s') or (end_dt >= '%s' and end_dt <= '%s')" , $tail_num, $start, $end, $start, $end);
		}
	}else{
		if($start == null or $end == null){
			$query = sprintf("select flight_id from Flight where Aircraft_tail_num ='%s'", $tail_num);
		}else{
			$query = sprintf("select flight_id from Flight where Aircraft_tail_num ='%s' and (start_dt >= '%s' and start_dt <= '%s') or (end_dt >= '%s' and end_dt <= '%s')" , $tail_num, $start, $end, $start, $end);
		}
	}
    if(!($result = mysqli_query($database, $query))){
    ?>
        <h1 class = "err"><strong>Major Error:</strong></h1>
        <p>A SQL Exception occurred while interacting with the database.</p>
        <br />
        <p>The error message was:</p>

        <p><strong><?php echo mysqli_error($database);?></strong></p>

        <p>Please try again later.</p>
        <?php

        die("");
    }

    // create result array
    $arr = array();

	$app->contentType('application/json');

	if ($result->num_rows > 0) {
        while($row = $result->fetch_assoc()) {
            $arr[] = $row;
		}
        $json_response = json_encode($arr);
        echo $json_response;
	} else {
        echo "0 results";
	}
    mysqli_close($database);
});

$app->get("/Aircraft/Engines(/:tail_num)", function ($tail_num) use($app) {
	
	$detail = $app->request->get('detail');
	$detail = filter_var($detail, FILTER_VALIDATE_BOOLEAN);
	
	if(!($database = mysqli_connect('localhost', 'apiGET', 'apiget', 'joust' ))){
        die("Could not reconnect to the database. Server error.");
    }
	
	if($detail == true){
		$query = sprintf("select * from Engine where Aircraft_tail_num ='%s'", $tail_num);
	}else{
		$query = sprintf("select serial_num from Engine where Aircraft_tail_num ='%s'", $tail_num);
	}
    if(!($result = mysqli_query($database, $query))){
    ?>
        <h1 class = "err"><strong>Major Error:</strong></h1>
        <p>A SQL Exception occurred while interacting with the database.</p>
        <br />
        <p>The error message was:</p>

        <p><strong><?php echo mysqli_error($database);?></strong></p>

        <p>Please try again later.</p>
        <?php

        die("");
    }

    // create result array
    $arr = array();

	$app->contentType('application/json');

	if ($result->num_rows > 0) {
        while($row = $result->fetch_assoc()) {
            $arr[] = $row;
		}
        $json_response = json_encode($arr);
        echo $json_response;
	} else {
        echo "0 results";
	}
    mysqli_close($database);
});





//get Aircraft List
$app->get('/Engine', function () use($app) {

	$detail = $app->request->get('detail');
	$detail = filter_var($detail, FILTER_VALIDATE_BOOLEAN);

	if(!($database = mysqli_connect('localhost', 'apiGET', 'apiget', 'joust' ))){
        die("Could not reconnect to the database. Server error.");
    }
	
	if($detail == true){
		$query = "SELECT * FROM Engine";
	}else{
		$query = "SELECT serial_num FROM Engine";
	}
	

    if(!($result = mysqli_query($database, $query))){
    ?>
        <h1 class = "err"><strong>Major Error:</strong></h1>
        <p>A SQL Exception occurred while interacting with the database.</p>
        <br />
        <p>The error message was:</p>

        <p><strong><?php echo mysqli_error($database);?></strong></p>

        <p>Please try again later.</p>
        <?php

        die("");
    }

    // create result array
    $arr = array();

	$app->contentType('application/json');

	if ($result->num_rows > 0) {
        while($row = $result->fetch_assoc()) {
            $arr[] = $row;
		}
        $json_response = json_encode($arr);
        echo $json_response;
	} else {
        echo "0 results";
	}
    mysqli_close($database);
});

$app->get("/Engine(/:serial_num)", function ($serial_num) use($app) {
	if(!($database = mysqli_connect('localhost', 'apiGET', 'apiget', 'joust' ))){
        die("Could not reconnect to the database. Server error.");
    }

    $query = sprintf("select * from Engine where serial_num ='%s'", $serial_num);
    if(!($result = mysqli_query($database, $query))){
    ?>
        <h1 class = "err"><strong>Major Error:</strong></h1>
        <p>A SQL Exception occurred while interacting with the database.</p>
        <br />
        <p>The error message was:</p>

        <p><strong><?php echo mysqli_error($database);?></strong></p>

        <p>Please try again later.</p>
        <?php

        die("");
    }

    // create result array
    $arr = array();

	$app->contentType('application/json');

	if ($result->num_rows > 0) {
        while($row = $result->fetch_assoc()) {
            $arr[] = $row;
		}
        $json_response = json_encode($arr);
        echo $json_response;
	} else {
        echo "0 results";
	}
    mysqli_close($database);
});

$app->get("/Engine/Flights(/:serial_num)", function ($serial_num) use($app) {
	
	$detail = $app->request->get('detail');
	$detail = filter_var($detail, FILTER_VALIDATE_BOOLEAN);
	
	$start = $app->request->get('start');
	$end = $app->request->get('end');
	
	if(!($database = mysqli_connect('localhost', 'apiGET', 'apiget', 'joust' ))){
        die("Could not reconnect to the database. Server error.");
    }
	if($detail == true){
		if($start == null or $end == null){
			$query = sprintf("select flight_id, Aircraft_tail_num, start_dt, end_dt, start_lat, end_lat, start_long, end_long, start_airport, end_airport, Engine_serial_num1, Engine_serial_num2, Engine_serial_num3, Engine_serial_num4 from Flight where (Engine_serial_num1 ='%s' or Engine_serial_num2 ='%s' or Engine_serial_num3 ='%s' or Engine_serial_num4 ='%s')", $serial_num, $serial_num, $serial_num, $serial_num);
		}else{
			//$endTimestamp = strtotime($end);
			//$startTimestamp = strtotime($start);
			$query = sprintf("select flight_id, Aircraft_tail_num, start_dt, end_dt, start_lat, end_lat, start_long, end_long, start_airport, end_airport, Engine_serial_num1, Engine_serial_num2, Engine_serial_num3, Engine_serial_num4 from Flight where (Engine_serial_num1 ='%s' or Engine_serial_num2 ='%s' or Engine_serial_num3 ='%s' or Engine_serial_num4 ='%s') and (start_dt >= '%s' and start_dt <= '%s') or (end_dt >= '%s' and end_dt <= '%s')", $serial_num, $serial_num, $serial_num, $serial_num, $start, $end, $start, $end);
		}
	}else{
		if($start == null or $end == null){
			$query = sprintf("select flight_id from Flight where (Engine_serial_num1 ='%s' or Engine_serial_num2 ='%s' or Engine_serial_num3 ='%s' or Engine_serial_num4 ='%s')", $serial_num, $serial_num, $serial_num, $serial_num);
		}else{
			//$endTimestamp = strtotime($end);
			//$startTimestamp = strtotime($start);
			$query = sprintf("select flight_id from Flight where (Engine_serial_num1 ='%s' or Engine_serial_num2 ='%s' or Engine_serial_num3 ='%s' or Engine_serial_num4 ='%s') and (start_dt >= '%s' and start_dt <= '%s') or (end_dt >= '%s' and end_dt <= '%s')", $serial_num, $serial_num, $serial_num, $serial_num, $start, $end, $start, $end);
		}
	}
    if(!($result = mysqli_query($database, $query))){
    ?>
        <h1 class = "err"><strong>Major Error:</strong></h1>
        <p>A SQL Exception occurred while interacting with the database.</p>
        <br />
        <p>The error message was:</p>

        <p><strong><?php echo mysqli_error($database);?></strong></p>

        <p>Please try again later.</p>
        <?php

        die("");
    }

    // create result array
    $arr = array();

	$app->contentType('application/json');

	if ($result->num_rows > 0) {
        while($row = $result->fetch_assoc()) {
            $arr[] = $row;
		}
        $json_response = json_encode($arr);
        echo $json_response;
	} else {
        echo "0 results";
	}
    mysqli_close($database);
});

$app->get("/Flight", function () use($app) {
	
	$detail = $app->request->get('detail');
	$detail = filter_var($detail, FILTER_VALIDATE_BOOLEAN);
	
	$start = $app->request->get('start');
	$end = $app->request->get('end');
	
	if(!($database = mysqli_connect('localhost', 'apiGET', 'apiget', 'joust' ))){
        die("Could not reconnect to the database. Server error.");
    }

	if($detail == true){
		if($start == null or $end == null){
			$query = sprintf("select flight_id, Aircraft_tail_num, start_dt, end_dt, start_lat, end_lat, start_long, end_long, start_airport, end_airport, Engine_serial_num1, Engine_serial_num2, Engine_serial_num3, Engine_serial_num4 from Flight");
		}else{
			$query = sprintf("select * from Flight where (start_dt >= '%s' and start_dt <= '%s') or (end_dt >= '%s' and end_dt <= '%s')", $serial_num, $serial_num, $serial_num, $serial_num, $start, $end, $start, $end);
		}
	}else{
		if($start == null or $end == null){
			$query = sprintf("select flight_id from Flight");
		}else{
			$query = sprintf("select flight_id from Flight where (start_dt >= '%s' and start_dt <= '%s') or (end_dt >= '%s' and end_dt <= '%s')", $serial_num, $serial_num, $serial_num, $serial_num, $start, $end, $start, $end);
		}
	}
    if(!($result = mysqli_query($database, $query))){
    ?>
        <h1 class = "err"><strong>Major Error:</strong></h1>
        <p>A SQL Exception occurred while interacting with the database.</p>
        <br />
        <p>The error message was:</p>

        <p><strong><?php echo mysqli_error($database);?></strong></p>

        <p>Please try again later.</p>
        <?php

        die("");
    }

    // create result array
    $arr = array();

	$app->contentType('application/json');

	if ($result->num_rows > 0) {
        while($row = $result->fetch_assoc()) {
            $arr[] = $row;
		}
        $json_response = json_encode($arr);
        echo $json_response;
	} else {
        echo "0 results";
	}
    mysqli_close($database);
});




$app->get("/Flight/detail(/:flight_id)", function ($flight_id) use($app) {
	if(!($database = mysqli_connect('localhost', 'apiGET', 'apiget', 'joust' ))){
        die("Could not reconnect to the database. Server error.");
    }

    $query = sprintf("select * from Flight where flight_id ='%s'", $flight_id);
    if(!($result = mysqli_query($database, $query))){
    ?>
        <h1 class = "err"><strong>Major Error:</strong></h1>
        <p>A SQL Exception occurred while interacting with the database.</p>
        <br />
        <p>The error message was:</p>

        <p><strong><?php echo mysqli_error($database);?></strong></p>

        <p>Please try again later.</p>
        <?php

        die("");
    }

    // create result array
    $arr = array();

	$app->contentType('application/json');

	if ($result->num_rows > 0) {
        while($row = $result->fetch_assoc()) {
            $arr[] = $row;
		}
        $json_response = json_encode($arr);
        echo $json_response;
	} else {
        echo "0 results";
	}
    mysqli_close($database);
});


$app->get("/Flight/graph(/:flight_id)", function ($flight_id) use($app) {

    echo json_encode(json_decode(file_get_contents( "./".$flight_id.".json", true),true));

});

$app->post("/user/update", function ($flight_id) use($app) {
		
	$fuel = $app->request->get('fuel');
	$percent = $app->request->get('percent');
	
	$id = $_SESSION['user_id'];
	$query = sprintf("UPDATE User_Settings SET fuel = '%s', percent = '%s' WHERE id ='%s'",$fuel,$percent, $id);
	
    if(!($result = mysqli_query($database, $query))){
    ?>
        <h1 class = "err"><strong>Major Error:</strong></h1>
        <p>A SQL Exception occurred while interacting with the database.</p>
        <br />
        <p>The error message was:</p>

        <p><strong><?php echo mysqli_error($database);?></strong></p>

        <p>Please try again later.</p>
        <?php

        die("");
    }
    echo ("Updated");

});

$app->get("/user/fuelish/flight/spec", function () use($app) {
	
	$detail = $app->request->get('detail');
	$detail = filter_var($detail, FILTER_VALIDATE_BOOLEAN);
	
	$id = $_SESSION['user_id'];


	if(!($database = mysqli_connect('localhost', 'apiGET', 'apiget', 'joust' ))){
        die("Could not reconnect to the database. Server error.");
    }

	
	
	$query = sprintf("select * from User_Settings where id ='%s'", $id);
	if(!($result = mysqli_query($database, $query))){
    ?>
        <h1 class = "err"><strong>Major Error:</strong></h1>
        <p>A SQL Exception occurred while interacting with the database.</p>
        <br />
        <p>The error message was:</p>

        <p><strong><?php echo mysqli_error($database);?></strong></p>

        <p>Please try again later.</p>
        <?php

        die("");
    }
		
	if ($result->num_rows > 0) {
        while($row = $result->fetch_assoc()) {
            $arr[] = $row;
		}

	}else{
		echo("NO SETTINGS or user $id");	
	}
	$percent_p1 = $arr[0]["percent_p1"];
	$percent_p2 = $arr[0]["percent_p2"];
	$percent_p3 = $arr[0]["percent_p3"];
	$percent_p4 = $arr[0]["percent_p4"];
	$percent_p5 = $arr[0]["percent_p5"];
	$percent_p6 = $arr[0]["percent_p6"];
	$percent_p7 = $arr[0]["percent_p7"];
	
	$fuel = $arr[0]["fuel"];
	
	
	
	if($detail == true){
		$query = sprintf("select flight_id, Aircraft_tail_num, start_dt, end_dt, start_lat, end_lat, start_long, end_long, start_airport, end_airport, Engine_serial_num1, Engine_serial_num2, Engine_serial_num3, Engine_serial_num4 from Flight WHERE 
		percent_best_e1_p1 > '%s' 
		or percent_best_e1_p2 > '%s'
		or percent_best_e1_p3 > '%s'
		or percent_best_e1_p4 > '%s'
		or percent_best_e1_p5 > '%s'
		or percent_best_e1_p6 > '%s'
		or percent_best_e1_p7 > '%s'
		or percent_best_e2_p1 > '%s' 
		or percent_best_e2_p2 > '%s'
		or percent_best_e2_p3 > '%s'
		or percent_best_e2_p4 > '%s'
		or percent_best_e2_p5 > '%s'
		or percent_best_e2_p6 > '%s'
		or percent_best_e2_p7 > '%s'
		or percent_best_e3_p1 > '%s' 
		or percent_best_e3_p2 > '%s'
		or percent_best_e3_p3 > '%s'
		or percent_best_e3_p4 > '%s'
		or percent_best_e3_p5 > '%s'
		or percent_best_e3_p6 > '%s'
		or percent_best_e3_p7 > '%s'
		or percent_best_e4_p1 > '%s' 
		or percent_best_e4_p2 > '%s'
		or percent_best_e4_p3 > '%s'
		or percent_best_e4_p4 > '%s'
		or percent_best_e4_p5 > '%s'
		or percent_best_e4_p6 > '%s'
		or percent_best_e4_p7 > '%s'"
		, $percent_p1, $percent_p2, $percent_p3, $percent_p4, $percent_p5, $percent_p6, $percent_p7
		, $percent_p1, $percent_p2, $percent_p3, $percent_p4, $percent_p5, $percent_p6, $percent_p7
		, $percent_p1, $percent_p2, $percent_p3, $percent_p4, $percent_p5, $percent_p6, $percent_p7
		, $percent_p1, $percent_p2, $percent_p3, $percent_p4, $percent_p5, $percent_p6, $percent_p7);
	}else{
		$query = sprintf("select flight_id from Flight WHERE 
		percent_best_e1_p1 > '%s' 
		or percent_best_e1_p2 > '%s'
		or percent_best_e1_p3 > '%s'
		or percent_best_e1_p4 > '%s'
		or percent_best_e1_p5 > '%s'
		or percent_best_e1_p6 > '%s'
		or percent_best_e1_p7 > '%s'
		or percent_best_e2_p1 > '%s' 
		or percent_best_e2_p2 > '%s'
		or percent_best_e2_p3 > '%s'
		or percent_best_e2_p4 > '%s'
		or percent_best_e2_p5 > '%s'
		or percent_best_e2_p6 > '%s'
		or percent_best_e2_p7 > '%s'
		or percent_best_e3_p1 > '%s' 
		or percent_best_e3_p2 > '%s'
		or percent_best_e3_p3 > '%s'
		or percent_best_e3_p4 > '%s'
		or percent_best_e3_p5 > '%s'
		or percent_best_e3_p6 > '%s'
		or percent_best_e3_p7 > '%s'
		or percent_best_e4_p1 > '%s' 
		or percent_best_e4_p2 > '%s'
		or percent_best_e4_p3 > '%s'
		or percent_best_e4_p4 > '%s'
		or percent_best_e4_p5 > '%s'
		or percent_best_e4_p6 > '%s'
		or percent_best_e4_p7 > '%s'"
		, $percent_p1, $percent_p2, $percent_p3, $percent_p4, $percent_p5, $percent_p6, $percent_p7
		, $percent_p1, $percent_p2, $percent_p3, $percent_p4, $percent_p5, $percent_p6, $percent_p7
		, $percent_p1, $percent_p2, $percent_p3, $percent_p4, $percent_p5, $percent_p6, $percent_p7
		, $percent_p1, $percent_p2, $percent_p3, $percent_p4, $percent_p5, $percent_p6, $percent_p7);
	}
    if(!($result = mysqli_query($database, $query))){
    ?>
        <h1 class = "err"><strong>Major Error:</strong></h1>
        <p>A SQL Exception occurred while interacting with the database.</p>
        <br />
        <p>The error message was:</p>

        <p><strong><?php echo mysqli_error($database);?></strong></p>

        <p>Please try again later.</p>
        <?php

        die("");
    }

    // create result array
    $arr = array();

	$app->contentType('application/json');

	if ($result->num_rows > 0) {
        while($row = $result->fetch_assoc()) {
            $arr[] = $row;
		}
        $json_response = json_encode($arr);
        echo $json_response;
	} else {
        echo "0 results";
	}
    mysqli_close($database);
});


$app->get("/user/fuelish/engine/best", function () use($app) {
	
	$detail = $app->request->get('detail');
	$detail = filter_var($detail, FILTER_VALIDATE_BOOLEAN);
	
	$id = $_SESSION['user_id'];


	if(!($database = mysqli_connect('localhost', 'apiGET', 'apiget', 'joust' ))){
        die("Could not reconnect to the database. Server error.");
    }

	
	
	$query = sprintf("select * from User_Settings where id ='%s'", $id);
	if(!($result = mysqli_query($database, $query))){
    ?>
        <h1 class = "err"><strong>Major Error:</strong></h1>
        <p>A SQL Exception occurred while interacting with the database.</p>
        <br />
        <p>The error message was:</p>

        <p><strong><?php echo mysqli_error($database);?></strong></p>

        <p>Please try again later.</p>
        <?php

        die("");
    }
		
	if ($result->num_rows > 0) {
        while($row = $result->fetch_assoc()) {
            $arr[] = $row;
		}

	}else{
		echo("NO SETTINGS");	
	}
	$percent_p1 = $arr[0]["percent_p1"];
	$percent_p2 = $arr[0]["percent_p2"];
	$percent_p3 = $arr[0]["percent_p3"];
	$percent_p4 = $arr[0]["percent_p4"];
	$percent_p5 = $arr[0]["percent_p5"];
	$percent_p6 = $arr[0]["percent_p6"];
	$percent_p7 = $arr[0]["percent_p7"];
	
	$fuel = $arr[0]["fuel"];
	
	
	
	if($detail == true){
		$query = sprintf("SELECT * FROM Engine WHERE serial_num IN
        (SELECT Engine_serial_num1 as serial_num from Flight WHERE 
		percent_best_e1_p1 > '%s'
		or percent_best_e1_p2 > '%s'
		or percent_best_e1_p3 > '%s'
		or percent_best_e1_p4 > '%s'
		or percent_best_e1_p5 > '%s'
		or percent_best_e1_p6 > '%s'
		or percent_best_e1_p7 > '%s') or serial_num IN
		(SELECT Engine_serial_num2 as serial_num from Flight WHERE 
		percent_best_e2_p1 > '%s'
		or percent_best_e2_p2 > '%s'
		or percent_best_e2_p3 > '%s'
		or percent_best_e2_p4 > '%s'
		or percent_best_e2_p5 > '%s'
		or percent_best_e2_p6 > '%s'
		or percent_best_e2_p7 > '%s')
        or serial_num IN
		(SELECT Engine_serial_num2 as serial_num from Flight WHERE 
		percent_best_e2_p1 > '%s'
		or percent_best_e2_p2 > '%s'
		or percent_best_e2_p3 > '%s'
		or percent_best_e2_p4 > '%s'
		or percent_best_e2_p5 > '%s'
		or percent_best_e2_p6 > '%s'
		or percent_best_e2_p7 > '%s')
        or serial_num IN
		(SELECT Engine_serial_num2 as serial_num from Flight WHERE 
		percent_best_e2_p1 > '%s'
		or percent_best_e2_p2 > '%s'
		or percent_best_e2_p3 > '%s'
		or percent_best_e2_p4 > '%s'
		or percent_best_e2_p5 > '%s'
		or percent_best_e2_p6 > '%s'
		or percent_best_e2_p7 > '%s');"
		, $percent_p1, $percent_p2, $percent_p3, $percent_p4, $percent_p5, $percent_p6, $percent_p7
		, $percent_p1, $percent_p2, $percent_p3, $percent_p4, $percent_p5, $percent_p6, $percent_p7
		, $percent_p1, $percent_p2, $percent_p3, $percent_p4, $percent_p5, $percent_p6, $percent_p7
		, $percent_p1, $percent_p2, $percent_p3, $percent_p4, $percent_p5, $percent_p6, $percent_p7);
	}else{
		$query = sprintf("SELECT serial_num FROM Engine WHERE serial_num IN
        (SELECT Engine_serial_num1 as serial_num from Flight WHERE 
		percent_best_e1_p1 > '%s'
		or percent_best_e1_p2 > '%s'
		or percent_best_e1_p3 > '%s'
		or percent_best_e1_p4 > '%s'
		or percent_best_e1_p5 > '%s'
		or percent_best_e1_p6 > '%s'
		or percent_best_e1_p7 > '%s') or serial_num IN
		(SELECT Engine_serial_num2 as serial_num from Flight WHERE 
		percent_best_e2_p1 > '%s'
		or percent_best_e2_p2 > '%s'
		or percent_best_e2_p3 > '%s'
		or percent_best_e2_p4 > '%s'
		or percent_best_e2_p5 > '%s'
		or percent_best_e2_p6 > '%s'
		or percent_best_e2_p7 > '%s')
        or serial_num IN
		(SELECT Engine_serial_num2 as serial_num from Flight WHERE 
		percent_best_e2_p1 > '%s'
		or percent_best_e2_p2 > '%s'
		or percent_best_e2_p3 > '%s'
		or percent_best_e2_p4 > '%s'
		or percent_best_e2_p5 > '%s'
		or percent_best_e2_p6 > '%s'
		or percent_best_e2_p7 > '%s')
        or serial_num IN
		(SELECT Engine_serial_num2 as serial_num from Flight WHERE 
		percent_best_e2_p1 > '%s'
		or percent_best_e2_p2 > '%s'
		or percent_best_e2_p3 > '%s'
		or percent_best_e2_p4 > '%s'
		or percent_best_e2_p5 > '%s'
		or percent_best_e2_p6 > '%s'
		or percent_best_e2_p7 > '%s');"
		, $percent_p1, $percent_p2, $percent_p3, $percent_p4, $percent_p5, $percent_p6, $percent_p7
		, $percent_p1, $percent_p2, $percent_p3, $percent_p4, $percent_p5, $percent_p6, $percent_p7
		, $percent_p1, $percent_p2, $percent_p3, $percent_p4, $percent_p5, $percent_p6, $percent_p7
		, $percent_p1, $percent_p2, $percent_p3, $percent_p4, $percent_p5, $percent_p6, $percent_p7);
	}
    if(!($result = mysqli_query($database, $query))){
    ?>
        <h1 class = "err"><strong>Major Error:</strong></h1>
        <p>A SQL Exception occurred while interacting with the database.</p>
        <br />
        <p>The error message was:</p>

        <p><strong><?php echo mysqli_error($database);?></strong></p>

        <p>Please try again later.</p>
        <?php

        die("");
    }

    // create result array
    $arr = array();

	$app->contentType('application/json');

	if ($result->num_rows > 0) {
        while($row = $result->fetch_assoc()) {
            $arr[] = $row;
		}
        $json_response = json_encode($arr);
        echo $json_response;
	} else {
        echo "0 results";
	}
    mysqli_close($database);
});

$app->get("/user/fuelish/engine/spec", function () use($app) {
	
	$detail = $app->request->get('detail');
	$detail = filter_var($detail, FILTER_VALIDATE_BOOLEAN);
	
	$id = $_SESSION['user_id'];


	if(!($database = mysqli_connect('localhost', 'apiGET', 'apiget', 'joust' ))){
        die("Could not reconnect to the database. Server error.");
    }

	
	
	$query = sprintf("select * from User_Settings where id ='%s'", $id);
	if(!($result = mysqli_query($database, $query))){
    ?>
        <h1 class = "err"><strong>Major Error:</strong></h1>
        <p>A SQL Exception occurred while interacting with the database.</p>
        <br />
        <p>The error message was:</p>

        <p><strong><?php echo mysqli_error($database);?></strong></p>

        <p>Please try again later.</p>
        <?php

        die("");
    }
		
	if ($result->num_rows > 0) {
        while($row = $result->fetch_assoc()) {
            $arr[] = $row;
		}

	}else{
		echo("NO SETTINGS");	
	}
	$percent_p1 = $arr[0]["percent_p1"];
	$percent_p2 = $arr[0]["percent_p2"];
	$percent_p3 = $arr[0]["percent_p3"];
	$percent_p4 = $arr[0]["percent_p4"];
	$percent_p5 = $arr[0]["percent_p5"];
	$percent_p6 = $arr[0]["percent_p6"];
	$percent_p7 = $arr[0]["percent_p7"];
	
	$fuel = $arr[0]["fuel"];
	
	
	
	if($detail == true){
		$query = sprintf("SELECT * FROM Engine WHERE serial_num IN
        (SELECT Engine_serial_num1 as serial_num from Flight WHERE 
		percent_best_e1_p1 > '%s'
		or percent_best_e1_p2 > '%s'
		or percent_best_e1_p3 > '%s'
		or percent_best_e1_p4 > '%s'
		or percent_best_e1_p5 > '%s'
		or percent_best_e1_p6 > '%s'
		or percent_best_e1_p7 > '%s') or serial_num IN
		(SELECT Engine_serial_num2 as serial_num from Flight WHERE 
		percent_best_e2_p1 > '%s'
		or percent_best_e2_p2 > '%s'
		or percent_best_e2_p3 > '%s'
		or percent_best_e2_p4 > '%s'
		or percent_best_e2_p5 > '%s'
		or percent_best_e2_p6 > '%s'
		or percent_best_e2_p7 > '%s')
        or serial_num IN
		(SELECT Engine_serial_num2 as serial_num from Flight WHERE 
		percent_best_e2_p1 > '%s'
		or percent_best_e2_p2 > '%s'
		or percent_best_e2_p3 > '%s'
		or percent_best_e2_p4 > '%s'
		or percent_best_e2_p5 > '%s'
		or percent_best_e2_p6 > '%s'
		or percent_best_e2_p7 > '%s')
        or serial_num IN
		(SELECT Engine_serial_num2 as serial_num from Flight WHERE 
		percent_best_e2_p1 > '%s'
		or percent_best_e2_p2 > '%s'
		or percent_best_e2_p3 > '%s'
		or percent_best_e2_p4 > '%s'
		or percent_best_e2_p5 > '%s'
		or percent_best_e2_p6 > '%s'
		or percent_best_e2_p7 > '%s');"
		, $percent_p1, $percent_p2, $percent_p3, $percent_p4, $percent_p5, $percent_p6, $percent_p7
		, $percent_p1, $percent_p2, $percent_p3, $percent_p4, $percent_p5, $percent_p6, $percent_p7
		, $percent_p1, $percent_p2, $percent_p3, $percent_p4, $percent_p5, $percent_p6, $percent_p7
		, $percent_p1, $percent_p2, $percent_p3, $percent_p4, $percent_p5, $percent_p6, $percent_p7);
	}else{
		$query = sprintf("SELECT serial_num FROM Engine WHERE serial_num IN
        (SELECT Engine_serial_num1 as serial_num from Flight WHERE 
		percent_best_e1_p1 > '%s'
		or percent_best_e1_p2 > '%s'
		or percent_best_e1_p3 > '%s'
		or percent_best_e1_p4 > '%s'
		or percent_best_e1_p5 > '%s'
		or percent_best_e1_p6 > '%s'
		or percent_best_e1_p7 > '%s') or serial_num IN
		(SELECT Engine_serial_num2 as serial_num from Flight WHERE 
		percent_best_e2_p1 > '%s'
		or percent_best_e2_p2 > '%s'
		or percent_best_e2_p3 > '%s'
		or percent_best_e2_p4 > '%s'
		or percent_best_e2_p5 > '%s'
		or percent_best_e2_p6 > '%s'
		or percent_best_e2_p7 > '%s')
        or serial_num IN
		(SELECT Engine_serial_num2 as serial_num from Flight WHERE 
		percent_best_e2_p1 > '%s'
		or percent_best_e2_p2 > '%s'
		or percent_best_e2_p3 > '%s'
		or percent_best_e2_p4 > '%s'
		or percent_best_e2_p5 > '%s'
		or percent_best_e2_p6 > '%s'
		or percent_best_e2_p7 > '%s')
        or serial_num IN
		(SELECT Engine_serial_num2 as serial_num from Flight WHERE 
		percent_best_e2_p1 > '%s'
		or percent_best_e2_p2 > '%s'
		or percent_best_e2_p3 > '%s'
		or percent_best_e2_p4 > '%s'
		or percent_best_e2_p5 > '%s'
		or percent_best_e2_p6 > '%s'
		or percent_best_e2_p7 > '%s');"
		, $percent_p1, $percent_p2, $percent_p3, $percent_p4, $percent_p5, $percent_p6, $percent_p7
		, $percent_p1, $percent_p2, $percent_p3, $percent_p4, $percent_p5, $percent_p6, $percent_p7
		, $percent_p1, $percent_p2, $percent_p3, $percent_p4, $percent_p5, $percent_p6, $percent_p7
		, $percent_p1, $percent_p2, $percent_p3, $percent_p4, $percent_p5, $percent_p6, $percent_p7);
	}
    if(!($result = mysqli_query($database, $query))){
    ?>
        <h1 class = "err"><strong>Major Error:</strong></h1>
        <p>A SQL Exception occurred while interacting with the database.</p>
        <br />
        <p>The error message was:</p>

        <p><strong><?php echo mysqli_error($database);?></strong></p>

        <p>Please try again later.</p>
        <?php

        die("");
    }

    // create result array
    $arr = array();

	$app->contentType('application/json');

	if ($result->num_rows > 0) {
        while($row = $result->fetch_assoc()) {
            $arr[] = $row;
		}
        $json_response = json_encode($arr);
        echo $json_response;
	} else {
        echo "0 results";
	}
    mysqli_close($database);
});

$app->get("/user/loss", function () use($app) {
	
	$detail = $app->request->get('detail');
	$detail = filter_var($detail, FILTER_VALIDATE_BOOLEAN);
	$formated = array();
	$id = $_SESSION['user_id'];
	

	if(!($database = mysqli_connect('localhost', 'apiGET', 'apiget', 'joust' ))){
        die("Could not reconnect to the database. Server error.");
    }

	
	
	$query = sprintf("select * from User_Settings where id ='%s'", $id);
	if(!($result = mysqli_query($database, $query))){
    ?>
        <h1 class = "err"><strong>Major Error:</strong></h1>
        <p>A SQL Exception occurred while interacting with the database.</p>
        <br />
        <p>The error message was:</p>

        <p><strong><?php echo mysqli_error($database);?></strong></p>

        <p>Please try again later.</p>
        <?php

        die("");
    }
		
	if ($result->num_rows > 0) {
        while($row = $result->fetch_assoc()) {
            $arr[] = $row;
		}

	}else{
		echo("NO SETTINGS");	
	}
	$percent_p1 = $arr[0]["percent_p1"];
	$percent_p2 = $arr[0]["percent_p2"];
	$percent_p3 = $arr[0]["percent_p3"];
	$percent_p4 = $arr[0]["percent_p4"];
	$percent_p5 = $arr[0]["percent_p5"];
	$percent_p6 = $arr[0]["percent_p6"];
	$percent_p7 = $arr[0]["percent_p7"];
	
	$fuel = $arr[0]["fuel"];
	


	$query = sprintf("SELECT sum(Loss) FROM 
	((SELECT dfc_best_e1_p1 AS Loss FROM Flight WHERE percent_best_e1_p1 > '%s') UNION 
	(SELECT dfc_best_e1_p2 AS Loss FROM Flight WHERE percent_best_e1_p1 > '%s') UNION
	(SELECT dfc_best_e1_p3 AS Loss FROM Flight WHERE percent_best_e1_p1 > '%s') UNION
	(SELECT dfc_best_e1_p4 AS Loss FROM Flight WHERE percent_best_e1_p1 > '%s') UNION
	(SELECT dfc_best_e1_p5 AS Loss FROM Flight WHERE percent_best_e1_p1 > '%s') UNION
	(SELECT dfc_best_e1_p6 AS Loss FROM Flight WHERE percent_best_e1_p1 > '%s') UNION
	(SELECT dfc_best_e1_p7 AS Loss FROM Flight WHERE percent_best_e1_p1 > '%s') UNION
	(SELECT dfc_best_e2_p1 AS Loss FROM Flight WHERE percent_best_e2_p1 > '%s') UNION 
	(SELECT dfc_best_e2_p2 AS Loss FROM Flight WHERE percent_best_e2_p1 > '%s') UNION
	(SELECT dfc_best_e2_p3 AS Loss FROM Flight WHERE percent_best_e2_p1 > '%s') UNION
	(SELECT dfc_best_e2_p4 AS Loss FROM Flight WHERE percent_best_e2_p1 > '%s') UNION
	(SELECT dfc_best_e2_p5 AS Loss FROM Flight WHERE percent_best_e2_p1 > '%s') UNION
	(SELECT dfc_best_e2_p6 AS Loss FROM Flight WHERE percent_best_e2_p1 > '%s') UNION
	(SELECT dfc_best_e2_p7 AS Loss FROM Flight WHERE percent_best_e2_p1 > '%s') UNION
	(SELECT dfc_best_e3_p1 AS Loss FROM Flight WHERE percent_best_e3_p1 > '%s') UNION 
	(SELECT dfc_best_e3_p2 AS Loss FROM Flight WHERE percent_best_e3_p1 > '%s') UNION
	(SELECT dfc_best_e3_p3 AS Loss FROM Flight WHERE percent_best_e3_p1 > '%s') UNION
	(SELECT dfc_best_e3_p4 AS Loss FROM Flight WHERE percent_best_e3_p1 > '%s') UNION
	(SELECT dfc_best_e3_p5 AS Loss FROM Flight WHERE percent_best_e3_p1 > '%s') UNION
	(SELECT dfc_best_e3_p6 AS Loss FROM Flight WHERE percent_best_e3_p1 > '%s') UNION
	(SELECT dfc_best_e3_p7 AS Loss FROM Flight WHERE percent_best_e3_p1 > '%s') UNION
	(SELECT dfc_best_e4_p1 AS Loss FROM Flight WHERE percent_best_e4_p1 > '%s') UNION 
	(SELECT dfc_best_e4_p2 AS Loss FROM Flight WHERE percent_best_e4_p1 > '%s') UNION
	(SELECT dfc_best_e4_p3 AS Loss FROM Flight WHERE percent_best_e4_p1 > '%s') UNION
	(SELECT dfc_best_e4_p4 AS Loss FROM Flight WHERE percent_best_e4_p1 > '%s') UNION
	(SELECT dfc_best_e4_p5 AS Loss FROM Flight WHERE percent_best_e4_p1 > '%s') UNION
	(SELECT dfc_best_e4_p6 AS Loss FROM Flight WHERE percent_best_e4_p1 > '%s') UNION
	(SELECT dfc_best_e4_p7 AS Loss FROM Flight WHERE percent_best_e4_p1 > '%s')
	) as a;"
	, $percent_p1, $percent_p2, $percent_p3, $percent_p4, $percent_p5, $percent_p6, $percent_p7
	, $percent_p1, $percent_p2, $percent_p3, $percent_p4, $percent_p5, $percent_p6, $percent_p7
	, $percent_p1, $percent_p2, $percent_p3, $percent_p4, $percent_p5, $percent_p6, $percent_p7
	, $percent_p1, $percent_p2, $percent_p3, $percent_p4, $percent_p5, $percent_p6, $percent_p7);

    if(!($result = mysqli_query($database, $query))){
    ?>
        <h1 class = "err"><strong>Major Error:</strong></h1>
        <p>A SQL Exception occurred while interacting with the database.</p>
        <br />
        <p>The error message was:</p>

        <p><strong><?php echo mysqli_error($database);?></strong></p>

        <p>Please try again later.</p>
        <?php

        die("");
    }

    // create result array
    $arr = array();
	
	$app->contentType('application/json');
	
    while($row = $result->fetch_assoc()) {
        $arr[] = $row;
	}
	$formated["best_lbs"] = $arr[0]["sum(Loss)"];
	unset($arr);
	//------------------------------------------------------------------------------------------------------------------------------------------------------------------------
	$query = sprintf("SELECT sum(Loss) FROM 
	((SELECT dfc_spec_e1_p1 AS Loss FROM Flight WHERE percent_spec_e1_p1 > '%s') UNION 
	(SELECT dfc_spec_e1_p2 AS Loss FROM Flight WHERE percent_spec_e1_p1 > '%s') UNION
	(SELECT dfc_spec_e1_p3 AS Loss FROM Flight WHERE percent_spec_e1_p1 > '%s') UNION
	(SELECT dfc_spec_e1_p4 AS Loss FROM Flight WHERE percent_spec_e1_p1 > '%s') UNION
	(SELECT dfc_spec_e1_p5 AS Loss FROM Flight WHERE percent_spec_e1_p1 > '%s') UNION
	(SELECT dfc_spec_e1_p6 AS Loss FROM Flight WHERE percent_spec_e1_p1 > '%s') UNION
	(SELECT dfc_spec_e1_p7 AS Loss FROM Flight WHERE percent_spec_e1_p1 > '%s') UNION
	(SELECT dfc_spec_e2_p1 AS Loss FROM Flight WHERE percent_spec_e2_p1 > '%s') UNION 
	(SELECT dfc_spec_e2_p2 AS Loss FROM Flight WHERE percent_spec_e2_p1 > '%s') UNION
	(SELECT dfc_spec_e2_p3 AS Loss FROM Flight WHERE percent_spec_e2_p1 > '%s') UNION
	(SELECT dfc_spec_e2_p4 AS Loss FROM Flight WHERE percent_spec_e2_p1 > '%s') UNION
	(SELECT dfc_spec_e2_p5 AS Loss FROM Flight WHERE percent_spec_e2_p1 > '%s') UNION
	(SELECT dfc_spec_e2_p6 AS Loss FROM Flight WHERE percent_spec_e2_p1 > '%s') UNION
	(SELECT dfc_spec_e2_p7 AS Loss FROM Flight WHERE percent_spec_e2_p1 > '%s') UNION
	(SELECT dfc_spec_e3_p1 AS Loss FROM Flight WHERE percent_spec_e3_p1 > '%s') UNION 
	(SELECT dfc_spec_e3_p2 AS Loss FROM Flight WHERE percent_spec_e3_p1 > '%s') UNION
	(SELECT dfc_spec_e3_p3 AS Loss FROM Flight WHERE percent_spec_e3_p1 > '%s') UNION
	(SELECT dfc_spec_e3_p4 AS Loss FROM Flight WHERE percent_spec_e3_p1 > '%s') UNION
	(SELECT dfc_spec_e3_p5 AS Loss FROM Flight WHERE percent_spec_e3_p1 > '%s') UNION
	(SELECT dfc_spec_e3_p6 AS Loss FROM Flight WHERE percent_spec_e3_p1 > '%s') UNION
	(SELECT dfc_spec_e3_p7 AS Loss FROM Flight WHERE percent_spec_e3_p1 > '%s') UNION
	(SELECT dfc_spec_e4_p1 AS Loss FROM Flight WHERE percent_spec_e4_p1 > '%s') UNION 
	(SELECT dfc_spec_e4_p2 AS Loss FROM Flight WHERE percent_spec_e4_p1 > '%s') UNION
	(SELECT dfc_spec_e4_p3 AS Loss FROM Flight WHERE percent_spec_e4_p1 > '%s') UNION
	(SELECT dfc_spec_e4_p4 AS Loss FROM Flight WHERE percent_spec_e4_p1 > '%s') UNION
	(SELECT dfc_spec_e4_p5 AS Loss FROM Flight WHERE percent_spec_e4_p1 > '%s') UNION
	(SELECT dfc_spec_e4_p6 AS Loss FROM Flight WHERE percent_spec_e4_p1 > '%s') UNION
	(SELECT dfc_spec_e4_p7 AS Loss FROM Flight WHERE percent_spec_e4_p1 > '%s')
	) as a;"
	, $percent_p1, $percent_p2, $percent_p3, $percent_p4, $percent_p5, $percent_p6, $percent_p7
	, $percent_p1, $percent_p2, $percent_p3, $percent_p4, $percent_p5, $percent_p6, $percent_p7
	, $percent_p1, $percent_p2, $percent_p3, $percent_p4, $percent_p5, $percent_p6, $percent_p7
	, $percent_p1, $percent_p2, $percent_p3, $percent_p4, $percent_p5, $percent_p6, $percent_p7);

    if(!($result = mysqli_query($database, $query))){
    ?>
        <h1 class = "err"><strong>Major Error:</strong></h1>
        <p>A SQL Exception occurred while interacting with the database.</p>
        <br />
        <p>The error message was:</p>

        <p><strong><?php echo mysqli_error($database);?></strong></p>

        <p>Please try again later.</p>
        <?php

        die("");
    }

    // create result array
    $arr = array();

	$app->contentType('application/json');
	
    while($row = $result->fetch_assoc()) {
        $arr[] = $row;
	}
	$formated["spec_lbs"] = $arr[0]["sum(Loss)"];
	
	
	
	$json_response = json_encode($formated);
    echo $json_response;
	
    mysqli_close($database);
});





$app->get("/user/fuelish/flight/best", function () use($app) {
	
	$detail = $app->request->get('detail');
	$detail = filter_var($detail, FILTER_VALIDATE_BOOLEAN);
	
	$id = $_SESSION['user_id'];


	if(!($database = mysqli_connect('localhost', 'apiGET', 'apiget', 'joust' ))){
        die("Could not reconnect to the database. Server error.");
    }

	
	
	$query = sprintf("select * from User_Settings where id ='%s'", $id);
	if(!($result = mysqli_query($database, $query))){
    ?>
        <h1 class = "err"><strong>Major Error:</strong></h1>
        <p>A SQL Exception occurred while interacting with the database.</p>
        <br />
        <p>The error message was:</p>

        <p><strong><?php echo mysqli_error($database);?></strong></p>

        <p>Please try again later.</p>
        <?php

        die("");
    }
		
	if ($result->num_rows > 0) {
        while($row = $result->fetch_assoc()) {
            $arr[] = $row;
		}

	}else{
		echo("NO SETTINGS");	
	}
	$percent_p1 = $arr[0]["percent_p1"];
	$percent_p2 = $arr[0]["percent_p2"];
	$percent_p3 = $arr[0]["percent_p3"];
	$percent_p4 = $arr[0]["percent_p4"];
	$percent_p5 = $arr[0]["percent_p5"];
	$percent_p6 = $arr[0]["percent_p6"];
	$percent_p7 = $arr[0]["percent_p7"];
	
	$fuel = $arr[0]["fuel"];
	
	
	
	if($detail == true){
		$query = sprintf("select flight_id, Aircraft_tail_num, start_dt, end_dt, start_lat, end_lat, start_long, end_long, start_airport, end_airport, Engine_serial_num1, Engine_serial_num2, Engine_serial_num3, Engine_serial_num4 from Flight WHERE 
		percent_best_e1_p1 > '%s' 
		or percent_best_e1_p2 > '%s'
		or percent_best_e1_p3 > '%s'
		or percent_best_e1_p4 > '%s'
		or percent_best_e1_p5 > '%s'
		or percent_best_e1_p6 > '%s'
		or percent_best_e1_p7 > '%s'
		or percent_best_e2_p1 > '%s' 
		or percent_best_e2_p2 > '%s'
		or percent_best_e2_p3 > '%s'
		or percent_best_e2_p4 > '%s'
		or percent_best_e2_p5 > '%s'
		or percent_best_e2_p6 > '%s'
		or percent_best_e2_p7 > '%s'
		or percent_best_e3_p1 > '%s' 
		or percent_best_e3_p2 > '%s'
		or percent_best_e3_p3 > '%s'
		or percent_best_e3_p4 > '%s'
		or percent_best_e3_p5 > '%s'
		or percent_best_e3_p6 > '%s'
		or percent_best_e3_p7 > '%s'
		or percent_best_e4_p1 > '%s' 
		or percent_best_e4_p2 > '%s'
		or percent_best_e4_p3 > '%s'
		or percent_best_e4_p4 > '%s'
		or percent_best_e4_p5 > '%s'
		or percent_best_e4_p6 > '%s'
		or percent_best_e4_p7 > '%s'"
		, $percent_p1, $percent_p2, $percent_p3, $percent_p4, $percent_p5, $percent_p6, $percent_p7
		, $percent_p1, $percent_p2, $percent_p3, $percent_p4, $percent_p5, $percent_p6, $percent_p7
		, $percent_p1, $percent_p2, $percent_p3, $percent_p4, $percent_p5, $percent_p6, $percent_p7
		, $percent_p1, $percent_p2, $percent_p3, $percent_p4, $percent_p5, $percent_p6, $percent_p7);
	}else{
		$query = sprintf("select flight_id from Flight WHERE 
		percent_best_e1_p1 > '%s' 
		or percent_best_e1_p2 > '%s'
		or percent_best_e1_p3 > '%s'
		or percent_best_e1_p4 > '%s'
		or percent_best_e1_p5 > '%s'
		or percent_best_e1_p6 > '%s'
		or percent_best_e1_p7 > '%s'
		or percent_best_e2_p1 > '%s' 
		or percent_best_e2_p2 > '%s'
		or percent_best_e2_p3 > '%s'
		or percent_best_e2_p4 > '%s'
		or percent_best_e2_p5 > '%s'
		or percent_best_e2_p6 > '%s'
		or percent_best_e2_p7 > '%s'
		or percent_best_e3_p1 > '%s' 
		or percent_best_e3_p2 > '%s'
		or percent_best_e3_p3 > '%s'
		or percent_best_e3_p4 > '%s'
		or percent_best_e3_p5 > '%s'
		or percent_best_e3_p6 > '%s'
		or percent_best_e3_p7 > '%s'
		or percent_best_e4_p1 > '%s' 
		or percent_best_e4_p2 > '%s'
		or percent_best_e4_p3 > '%s'
		or percent_best_e4_p4 > '%s'
		or percent_best_e4_p5 > '%s'
		or percent_best_e4_p6 > '%s'
		or percent_best_e4_p7 > '%s'"
		, $percent_p1, $percent_p2, $percent_p3, $percent_p4, $percent_p5, $percent_p6, $percent_p7
		, $percent_p1, $percent_p2, $percent_p3, $percent_p4, $percent_p5, $percent_p6, $percent_p7
		, $percent_p1, $percent_p2, $percent_p3, $percent_p4, $percent_p5, $percent_p6, $percent_p7
		, $percent_p1, $percent_p2, $percent_p3, $percent_p4, $percent_p5, $percent_p6, $percent_p7);
	}
    if(!($result = mysqli_query($database, $query))){
    ?>
        <h1 class = "err"><strong>Major Error:</strong></h1>
        <p>A SQL Exception occurred while interacting with the database.</p>
        <br />
        <p>The error message was:</p>

        <p><strong><?php echo mysqli_error($database);?></strong></p>

        <p>Please try again later.</p>
        <?php

        die("");
    }

    // create result array
    $arr = array();

	$app->contentType('application/json');

	if ($result->num_rows > 0) {
        while($row = $result->fetch_assoc()) {
            $arr[] = $row;
		}
        $json_response = json_encode($arr);
        echo $json_response;
	} else {
        echo "0 results";
	}
    mysqli_close($database);
});



$app->get("/user/settings", function () use($app) {


	$id = $_SESSION['user_id'];

    if(!($database = mysqli_connect('localhost', 'apiGET', 'apiget', 'joust' ))){
        die("Could not reconnect to the database. Server error.");
    }

    $query = sprintf("select * from User_Settings where id ='%s'", $id);
    if(!($result = mysqli_query($database, $query))){
    ?>
        <h1 class = "err"><strong>Major Error:</strong></h1>
        <p>A SQL Exception occurred while interacting with the database.</p>
        <br />
        <p>The error message was:</p>

        <p><strong><?php echo mysqli_error($database);?></strong></p>

        <p>Please try again later.</p>
        <?php

        die("");
    }
	/*
	if ($result->num_rows == 0) {
		$query = sprintf("INSERT (id) INTO User_Settings VALUES '".$id."');";
		if(!($result = mysqli_query($database, $query))){
		?>
			<h1 class = "err"><strong>Major Error:</strong></h1>
			<p>A SQL Exception occurred while interacting with the database.</p>
			<br />
			<p>The error message was:</p>

			<p><strong><?php echo mysqli_error($database);?></strong></p>

			<p>Please try again later.</p>
			<?php

			die("");
		}
		$query = sprintf("select * from User_Settings where id ='%s'", $id);
		if(!($result = mysqli_query($database, $query))){
		?>
			<h1 class = "err"><strong>Major Error:</strong></h1>
			<p>A SQL Exception occurred while interacting with the database.</p>
			<br />
			<p>The error message was:</p>

			<p><strong><?php echo mysqli_error($database);?></strong></p>

			<p>Please try again later.</p>
			<?php

			die("");
		}
	}
	*/


    // create result array
    $arr = array();

	$app->contentType('application/json');
	
	if ($result->num_rows > 0) {
        while($row = $result->fetch_assoc()) {
            $arr[] = $row;
		}
        $json_response = json_encode($arr);
        echo $json_response;
	} else {
        echo "0 results";
	}
    mysqli_close($database);

});


$app->post('/test', function () use($app) {
	$body = $app->request()->getBody();
	$value = $body["test"];
	echo ($value);

});

$app->get('/test', function () use($app) {
	if(!($database = mysqli_connect('localhost', 'apiGET', 'apiget', 'joust' ))){
        die("Could not reconnect to the database. Server error.");
    }
	$query = sprintf("select flight_id from Flight WHERE percent_best_e1_p1 > '%s' or percent_best_e1_p1 > '%s'", 0, 0);
	    if(!($result = mysqli_query($database, $query))){
    ?>
        <h1 class = "err"><strong>Major Error:</strong></h1>
        <p>A SQL Exception occurred while interacting with the database.</p>
        <br />
        <p>The error message was:</p>

        <p><strong><?php echo mysqli_error($database);?></strong></p>

        <p>Please try again later.</p>
        <?php

        die("");
    }

    // create result array
    $arr = array();

	$app->contentType('application/json');

	if ($result->num_rows > 0) {
        while($row = $result->fetch_assoc()) {
            $arr[] = $row;
		}
        $json_response = json_encode($arr);
        echo $json_response;
	} else {
        echo "0 results";
	}
    mysqli_close($database);
});





$app->post('/flight', function () use($app) {
	$body = $app->request()->getBody();

	date_default_timezone_set('America/New_York');
	
	//echo var_dump($body);
	//$engine1 = $body["Flight_Details"]["esn1"];
	//$engine2 = $body["Flight_Details"]["esn2"];
	//$engine3 = $body["Flight_Details"]["esn3"];
	//$engine4 = $body["Flight_Details"]["esn4"];
	
	$tailNum = $body[0]["Flight_Details"]["Aircraft_Tail_Num"];
	$startLat = $body[0]["Flight_Details"]["start_lat"];
	$startLong = $body[0]["Flight_Details"]["start_long"];
	$endLat = $body[0]["Flight_Details"]["end_lat"];
	$endLong = $body[0]["Flight_Details"]["end_long"];
	
	$tempStartDT = $body[0]["Flight_Details"]["start_dt"];
	$tempEndDT = $body[0]["Flight_Details"]["end_dt"];
	
	$startDT = date('Y-m-d H:i:s', strtotime($tempStartDT));
	$endDT = date('Y-m-d H:i:s', strtotime($tempEndDT));
	
	$dfc_best_total = $body[0]["Flight_Details"]["dfc_best_total"];
	
	$dfc_best_e1 = $body[0]["Flight_Details"]["dfc_best_e1"];
	$dfc_best_e2 = $body[0]["Flight_Details"]["dfc_best_e2"];
	$dfc_best_e3 = $body[0]["Flight_Details"]["dfc_best_e3"];
	$dfc_best_e4 = $body[0]["Flight_Details"]["dfc_best_e4"];
	
	$dfc_best_p1 = $body[0]["Flight_Details"]["dfc_best_p1"];
	$dfc_best_p2 = $body[0]["Flight_Details"]["dfc_best_p2"];
	$dfc_best_p3 = $body[0]["Flight_Details"]["dfc_best_p3"];
	$dfc_best_p4 = $body[0]["Flight_Details"]["dfc_best_p4"];
	$dfc_best_p5 = $body[0]["Flight_Details"]["dfc_best_p5"];
	$dfc_best_p6 = $body[0]["Flight_Details"]["dfc_best_p6"];
	$dfc_best_p7 = $body[0]["Flight_Details"]["dfc_best_p7"];
	
	$dfc_best_e1_p1 = $body[0]["Flight_Details"]["dfc_best_e1_p1"];
	$dfc_best_e1_p2 = $body[0]["Flight_Details"]["dfc_best_e1_p2"];
	$dfc_best_e1_p3 = $body[0]["Flight_Details"]["dfc_best_e1_p3"];
	$dfc_best_e1_p4 = $body[0]["Flight_Details"]["dfc_best_e1_p4"];
	$dfc_best_e1_p5 = $body[0]["Flight_Details"]["dfc_best_e1_p5"];
	$dfc_best_e1_p6 = $body[0]["Flight_Details"]["dfc_best_e1_p6"];
	$dfc_best_e1_p7 = $body[0]["Flight_Details"]["dfc_best_e1_p7"];
	
	$dfc_best_e2_p1 = $body[0]["Flight_Details"]["dfc_best_e2_p1"];
	$dfc_best_e2_p2 = $body[0]["Flight_Details"]["dfc_best_e2_p2"];
	$dfc_best_e2_p3 = $body[0]["Flight_Details"]["dfc_best_e2_p3"];
	$dfc_best_e2_p4 = $body[0]["Flight_Details"]["dfc_best_e2_p4"];
	$dfc_best_e2_p5 = $body[0]["Flight_Details"]["dfc_best_e2_p5"];
	$dfc_best_e2_p6 = $body[0]["Flight_Details"]["dfc_best_e2_p6"];
	$dfc_best_e2_p7 = $body[0]["Flight_Details"]["dfc_best_e2_p7"];
	
	$dfc_best_e3_p1 = $body[0]["Flight_Details"]["dfc_best_e3_p1"];
	$dfc_best_e3_p2 = $body[0]["Flight_Details"]["dfc_best_e3_p2"];
	$dfc_best_e3_p3 = $body[0]["Flight_Details"]["dfc_best_e3_p3"];
	$dfc_best_e3_p4 = $body[0]["Flight_Details"]["dfc_best_e3_p4"];
	$dfc_best_e3_p5 = $body[0]["Flight_Details"]["dfc_best_e3_p5"];
	$dfc_best_e3_p6 = $body[0]["Flight_Details"]["dfc_best_e3_p6"];
	$dfc_best_e3_p7 = $body[0]["Flight_Details"]["dfc_best_e3_p7"];

	$dfc_best_e4_p1 = $body[0]["Flight_Details"]["dfc_best_e4_p1"];
	$dfc_best_e4_p2 = $body[0]["Flight_Details"]["dfc_best_e4_p2"];
	$dfc_best_e4_p3 = $body[0]["Flight_Details"]["dfc_best_e4_p3"];
	$dfc_best_e4_p4 = $body[0]["Flight_Details"]["dfc_best_e4_p4"];
	$dfc_best_e4_p5 = $body[0]["Flight_Details"]["dfc_best_e4_p5"];
	$dfc_best_e4_p6 = $body[0]["Flight_Details"]["dfc_best_e4_p6"];
	$dfc_best_e4_p7 = $body[0]["Flight_Details"]["dfc_best_e4_p7"];

	
	$dfc_spec_total = $body[0]["Flight_Details"]["dfc_spec_total"];
	
	$dfc_spec_e1 = $body[0]["Flight_Details"]["dfc_spec_e1"];
	$dfc_spec_e2 = $body[0]["Flight_Details"]["dfc_spec_e2"];
	$dfc_spec_e3 = $body[0]["Flight_Details"]["dfc_spec_e3"];
	$dfc_spec_e4 = $body[0]["Flight_Details"]["dfc_spec_e4"];
	
	$dfc_spec_p1 = $body[0]["Flight_Details"]["dfc_spec_p1"];
	$dfc_spec_p2 = $body[0]["Flight_Details"]["dfc_spec_p2"];
	$dfc_spec_p3 = $body[0]["Flight_Details"]["dfc_spec_p3"];
	$dfc_spec_p4 = $body[0]["Flight_Details"]["dfc_spec_p4"];
	$dfc_spec_p5 = $body[0]["Flight_Details"]["dfc_spec_p5"];
	$dfc_spec_p6 = $body[0]["Flight_Details"]["dfc_spec_p6"];
	$dfc_spec_p7 = $body[0]["Flight_Details"]["dfc_spec_p7"];
	
	$dfc_spec_e1_p1 = $body[0]["Flight_Details"]["dfc_spec_e1_p1"];
	$dfc_spec_e1_p2 = $body[0]["Flight_Details"]["dfc_spec_e1_p2"];
	$dfc_spec_e1_p3 = $body[0]["Flight_Details"]["dfc_spec_e1_p3"];
	$dfc_spec_e1_p4 = $body[0]["Flight_Details"]["dfc_spec_e1_p4"];
	$dfc_spec_e1_p5 = $body[0]["Flight_Details"]["dfc_spec_e1_p5"];
	$dfc_spec_e1_p6 = $body[0]["Flight_Details"]["dfc_spec_e1_p6"];
	$dfc_spec_e1_p7 = $body[0]["Flight_Details"]["dfc_spec_e1_p7"];
	
	$dfc_spec_e2_p1 = $body[0]["Flight_Details"]["dfc_spec_e2_p1"];
	$dfc_spec_e2_p2 = $body[0]["Flight_Details"]["dfc_spec_e2_p2"];
	$dfc_spec_e2_p3 = $body[0]["Flight_Details"]["dfc_spec_e2_p3"];
	$dfc_spec_e2_p4 = $body[0]["Flight_Details"]["dfc_spec_e2_p4"];
	$dfc_spec_e2_p5 = $body[0]["Flight_Details"]["dfc_spec_e2_p5"];
	$dfc_spec_e2_p6 = $body[0]["Flight_Details"]["dfc_spec_e2_p6"];
	$dfc_spec_e2_p7 = $body[0]["Flight_Details"]["dfc_spec_e2_p7"];
	
	$dfc_spec_e3_p1 = $body[0]["Flight_Details"]["dfc_spec_e3_p1"];
	$dfc_spec_e3_p2 = $body[0]["Flight_Details"]["dfc_spec_e3_p2"];
	$dfc_spec_e3_p3 = $body[0]["Flight_Details"]["dfc_spec_e3_p3"];
	$dfc_spec_e3_p4 = $body[0]["Flight_Details"]["dfc_spec_e3_p4"];
	$dfc_spec_e3_p5 = $body[0]["Flight_Details"]["dfc_spec_e3_p5"];
	$dfc_spec_e3_p6 = $body[0]["Flight_Details"]["dfc_spec_e3_p6"];
	$dfc_spec_e3_p7 = $body[0]["Flight_Details"]["dfc_spec_e3_p7"];

	$dfc_spec_e4_p1 = $body[0]["Flight_Details"]["dfc_spec_e4_p1"];
	$dfc_spec_e4_p2 = $body[0]["Flight_Details"]["dfc_spec_e4_p2"];
	$dfc_spec_e4_p3 = $body[0]["Flight_Details"]["dfc_spec_e4_p3"];
	$dfc_spec_e4_p4 = $body[0]["Flight_Details"]["dfc_spec_e4_p4"];
	$dfc_spec_e4_p5 = $body[0]["Flight_Details"]["dfc_spec_e4_p5"];
	$dfc_spec_e4_p6 = $body[0]["Flight_Details"]["dfc_spec_e4_p6"];
	$dfc_spec_e4_p7 = $body[0]["Flight_Details"]["dfc_spec_e4_p7"];
	/*
	$fc_total = $body[0]["Flight_Details"]["fc_total"];
	
	$fc_e1 = $body[0]["Flight_Details"]["fc_e1"];
	$fc_e2 = $body[0]["Flight_Details"]["fc_e2"];
	$fc_e3 = $body[0]["Flight_Details"]["fc_e3"];
	$fc_e4 = $body[0]["Flight_Details"]["fc_e4"];
	
	$fc_p1 = $body[0]["Flight_Details"]["fc_p1"];
	$fc_p2 = $body[0]["Flight_Details"]["fc_p2"];
	$fc_p3 = $body[0]["Flight_Details"]["fc_p3"];
	$fc_p4 = $body[0]["Flight_Details"]["fc_p4"];
	$fc_p5 = $body[0]["Flight_Details"]["fc_p5"];
	$fc_p6 = $body[0]["Flight_Details"]["fc_p6"];
	$fc_p7 = $body[0]["Flight_Details"]["fc_p7"];
	
	$fc_e1_p1 = $body[0]["Flight_Details"]["fc_e1_p1"];
	$fc_e1_p2 = $body[0]["Flight_Details"]["fc_e1_p2"];
	$fc_e1_p3 = $body[0]["Flight_Details"]["fc_e1_p3"];
	$fc_e1_p4 = $body[0]["Flight_Details"]["fc_e1_p4"];
	$fc_e1_p5 = $body[0]["Flight_Details"]["fc_e1_p5"];
	$fc_e1_p6 = $body[0]["Flight_Details"]["fc_e1_p6"];
	$fc_e1_p7 = $body[0]["Flight_Details"]["fc_e1_p7"];
	
	$fc_e2_p1 = $body[0]["Flight_Details"]["fc_e2_p1"];
	$fc_e2_p2 = $body[0]["Flight_Details"]["fc_e2_p2"];
	$fc_e2_p3 = $body[0]["Flight_Details"]["fc_e2_p3"];
	$fc_e2_p4 = $body[0]["Flight_Details"]["fc_e2_p4"];
	$fc_e2_p5 = $body[0]["Flight_Details"]["fc_e2_p5"];
	$fc_e2_p6 = $body[0]["Flight_Details"]["fc_e2_p6"];
	$fc_e2_p7 = $body[0]["Flight_Details"]["fc_e2_p7"];
	
	$fc_e3_p1 = $body[0]["Flight_Details"]["fc_e3_p1"];
	$fc_e3_p2 = $body[0]["Flight_Details"]["fc_e3_p2"];
	$fc_e3_p3 = $body[0]["Flight_Details"]["fc_e3_p3"];
	$fc_e3_p4 = $body[0]["Flight_Details"]["fc_e3_p4"];
	$fc_e3_p5 = $body[0]["Flight_Details"]["fc_e3_p5"];
	$fc_e3_p6 = $body[0]["Flight_Details"]["fc_e3_p6"];
	$fc_e3_p7 = $body[0]["Flight_Details"]["fc_e3_p7"];

	$fc_e4_p1 = $body[0]["Flight_Details"]["fc_e4_p1"];
	$fc_e4_p2 = $body[0]["Flight_Details"]["fc_e4_p2"];
	$fc_e4_p3 = $body[0]["Flight_Details"]["fc_e4_p3"];
	$fc_e4_p4 = $body[0]["Flight_Details"]["fc_e4_p4"];
	$fc_e4_p5 = $body[0]["Flight_Details"]["fc_e4_p5"];
	$fc_e4_p6 = $body[0]["Flight_Details"]["fc_e4_p6"];
	$fc_e4_p7 = $body[0]["Flight_Details"]["fc_e4_p7"];
	
	*/
	$percent_best_total = $body[0]["Flight_Details"]["percent_best_total"];
	
	$percent_best_e1 = $body[0]["Flight_Details"]["percent_best_e1"];
	$percent_best_e2 = $body[0]["Flight_Details"]["percent_best_e2"];
	$percent_best_e3 = $body[0]["Flight_Details"]["percent_best_e3"];
	$percent_best_e4 = $body[0]["Flight_Details"]["percent_best_e4"];
	
	$percent_best_p1 = $body[0]["Flight_Details"]["percent_best_p1"];
	$percent_best_p2 = $body[0]["Flight_Details"]["percent_best_p2"];
	$percent_best_p3 = $body[0]["Flight_Details"]["percent_best_p3"];
	$percent_best_p4 = $body[0]["Flight_Details"]["percent_best_p4"];
	$percent_best_p5 = $body[0]["Flight_Details"]["percent_best_p5"];
	$percent_best_p6 = $body[0]["Flight_Details"]["percent_best_p6"];
	$percent_best_p7 = $body[0]["Flight_Details"]["percent_best_p7"];
	
	$percent_best_e1_p1 = $body[0]["Flight_Details"]["percent_best_e1_p1"];
	$percent_best_e1_p2 = $body[0]["Flight_Details"]["percent_best_e1_p2"];
	$percent_best_e1_p3 = $body[0]["Flight_Details"]["percent_best_e1_p3"];
	$percent_best_e1_p4 = $body[0]["Flight_Details"]["percent_best_e1_p4"];
	$percent_best_e1_p5 = $body[0]["Flight_Details"]["percent_best_e1_p5"];
	$percent_best_e1_p6 = $body[0]["Flight_Details"]["percent_best_e1_p6"];
	$percent_best_e1_p7 = $body[0]["Flight_Details"]["percent_best_e1_p7"];
	
	$percent_best_e2_p1 = $body[0]["Flight_Details"]["percent_best_e2_p1"];
	$percent_best_e2_p2 = $body[0]["Flight_Details"]["percent_best_e2_p2"];
	$percent_best_e2_p3 = $body[0]["Flight_Details"]["percent_best_e2_p3"];
	$percent_best_e2_p4 = $body[0]["Flight_Details"]["percent_best_e2_p4"];
	$percent_best_e2_p5 = $body[0]["Flight_Details"]["percent_best_e2_p5"];
	$percent_best_e2_p6 = $body[0]["Flight_Details"]["percent_best_e2_p6"];
	$percent_best_e2_p7 = $body[0]["Flight_Details"]["percent_best_e2_p7"];
	
	$percent_best_e3_p1 = $body[0]["Flight_Details"]["percent_best_e3_p1"];
	$percent_best_e3_p2 = $body[0]["Flight_Details"]["percent_best_e3_p2"];
	$percent_best_e3_p3 = $body[0]["Flight_Details"]["percent_best_e3_p3"];
	$percent_best_e3_p4 = $body[0]["Flight_Details"]["percent_best_e3_p4"];
	$percent_best_e3_p5 = $body[0]["Flight_Details"]["percent_best_e3_p5"];
	$percent_best_e3_p6 = $body[0]["Flight_Details"]["percent_best_e3_p6"];
	$percent_best_e3_p7 = $body[0]["Flight_Details"]["percent_best_e3_p7"];

	$percent_best_e4_p1 = $body[0]["Flight_Details"]["percent_best_e4_p1"];
	$percent_best_e4_p2 = $body[0]["Flight_Details"]["percent_best_e4_p2"];
	$percent_best_e4_p3 = $body[0]["Flight_Details"]["percent_best_e4_p3"];
	$percent_best_e4_p4 = $body[0]["Flight_Details"]["percent_best_e4_p4"];
	$percent_best_e4_p5 = $body[0]["Flight_Details"]["percent_best_e4_p5"];
	$percent_best_e4_p6 = $body[0]["Flight_Details"]["percent_best_e4_p6"];
	$percent_best_e4_p7 = $body[0]["Flight_Details"]["percent_best_e4_p7"];
	
	
	$percent_spec_total = $body[0]["Flight_Details"]["percent_spec_total"];
	
	$percent_spec_e1 = $body[0]["Flight_Details"]["percent_spec_e1"];
	$percent_spec_e2 = $body[0]["Flight_Details"]["percent_spec_e2"];
	$percent_spec_e3 = $body[0]["Flight_Details"]["percent_spec_e3"];
	$percent_spec_e4 = $body[0]["Flight_Details"]["percent_spec_e4"];
	
	$percent_spec_p1 = $body[0]["Flight_Details"]["percent_spec_p1"];
	$percent_spec_p2 = $body[0]["Flight_Details"]["percent_spec_p2"];
	$percent_spec_p3 = $body[0]["Flight_Details"]["percent_spec_p3"];
	$percent_spec_p4 = $body[0]["Flight_Details"]["percent_spec_p4"];
	$percent_spec_p5 = $body[0]["Flight_Details"]["percent_spec_p5"];
	$percent_spec_p6 = $body[0]["Flight_Details"]["percent_spec_p6"];
	$percent_spec_p7 = $body[0]["Flight_Details"]["percent_spec_p7"];
	
	$percent_spec_e1_p1 = $body[0]["Flight_Details"]["percent_spec_e1_p1"];
	$percent_spec_e1_p2 = $body[0]["Flight_Details"]["percent_spec_e1_p2"];
	$percent_spec_e1_p3 = $body[0]["Flight_Details"]["percent_spec_e1_p3"];
	$percent_spec_e1_p4 = $body[0]["Flight_Details"]["percent_spec_e1_p4"];
	$percent_spec_e1_p5 = $body[0]["Flight_Details"]["percent_spec_e1_p5"];
	$percent_spec_e1_p6 = $body[0]["Flight_Details"]["percent_spec_e1_p6"];
	$percent_spec_e1_p7 = $body[0]["Flight_Details"]["percent_spec_e1_p7"];
	
	$percent_spec_e2_p1 = $body[0]["Flight_Details"]["percent_spec_e2_p1"];
	$percent_spec_e2_p2 = $body[0]["Flight_Details"]["percent_spec_e2_p2"];
	$percent_spec_e2_p3 = $body[0]["Flight_Details"]["percent_spec_e2_p3"];
	$percent_spec_e2_p4 = $body[0]["Flight_Details"]["percent_spec_e2_p4"];
	$percent_spec_e2_p5 = $body[0]["Flight_Details"]["percent_spec_e2_p5"];
	$percent_spec_e2_p6 = $body[0]["Flight_Details"]["percent_spec_e2_p6"];
	$percent_spec_e2_p7 = $body[0]["Flight_Details"]["percent_spec_e2_p7"];
	
	$percent_spec_e3_p1 = $body[0]["Flight_Details"]["percent_spec_e3_p1"];
	$percent_spec_e3_p2 = $body[0]["Flight_Details"]["percent_spec_e3_p2"];
	$percent_spec_e3_p3 = $body[0]["Flight_Details"]["percent_spec_e3_p3"];
	$percent_spec_e3_p4 = $body[0]["Flight_Details"]["percent_spec_e3_p4"];
	$percent_spec_e3_p5 = $body[0]["Flight_Details"]["percent_spec_e3_p5"];
	$percent_spec_e3_p6 = $body[0]["Flight_Details"]["percent_spec_e3_p6"];
	$percent_spec_e3_p7 = $body[0]["Flight_Details"]["percent_spec_e3_p7"];

	$percent_spec_e4_p1 = $body[0]["Flight_Details"]["percent_spec_e4_p1"];
	$percent_spec_e4_p2 = $body[0]["Flight_Details"]["percent_spec_e4_p2"];
	$percent_spec_e4_p3 = $body[0]["Flight_Details"]["percent_spec_e4_p3"];
	$percent_spec_e4_p4 = $body[0]["Flight_Details"]["percent_spec_e4_p4"];
	$percent_spec_e4_p5 = $body[0]["Flight_Details"]["percent_spec_e4_p5"];
	$percent_spec_e4_p6 = $body[0]["Flight_Details"]["percent_spec_e4_p6"];
	$percent_spec_e4_p7 = $body[0]["Flight_Details"]["percent_spec_e4_p7"];
	
	
		
	$tsfc_total = $body[0]["Flight_Details"]["tsfc_total"];
	
	$tsfc_e1 = $body[0]["Flight_Details"]["tsfc_e1"];
	$tsfc_e2 = $body[0]["Flight_Details"]["tsfc_e2"];
	$tsfc_e3 = $body[0]["Flight_Details"]["tsfc_e3"];
	$tsfc_e4 = $body[0]["Flight_Details"]["tsfc_e4"];
	
	$tsfc_p1 = $body[0]["Flight_Details"]["tsfc_p1"];
	$tsfc_p2 = $body[0]["Flight_Details"]["tsfc_p2"];
	$tsfc_p3 = $body[0]["Flight_Details"]["tsfc_p3"];
	$tsfc_p4 = $body[0]["Flight_Details"]["tsfc_p4"];
	$tsfc_p5 = $body[0]["Flight_Details"]["tsfc_p5"];
	$tsfc_p6 = $body[0]["Flight_Details"]["tsfc_p6"];
	$tsfc_p7 = $body[0]["Flight_Details"]["tsfc_p7"];
	
	$tsfc_e1_p1 = $body[0]["Flight_Details"]["tsfc_e1_p1"];
	$tsfc_e1_p2 = $body[0]["Flight_Details"]["tsfc_e1_p2"];
	$tsfc_e1_p3 = $body[0]["Flight_Details"]["tsfc_e1_p3"];
	$tsfc_e1_p4 = $body[0]["Flight_Details"]["tsfc_e1_p4"];
	$tsfc_e1_p5 = $body[0]["Flight_Details"]["tsfc_e1_p5"];
	$tsfc_e1_p6 = $body[0]["Flight_Details"]["tsfc_e1_p6"];
	$tsfc_e1_p7 = $body[0]["Flight_Details"]["tsfc_e1_p7"];
	
	$tsfc_e2_p1 = $body[0]["Flight_Details"]["tsfc_e2_p1"];
	$tsfc_e2_p2 = $body[0]["Flight_Details"]["tsfc_e2_p2"];
	$tsfc_e2_p3 = $body[0]["Flight_Details"]["tsfc_e2_p3"];
	$tsfc_e2_p4 = $body[0]["Flight_Details"]["tsfc_e2_p4"];
	$tsfc_e2_p5 = $body[0]["Flight_Details"]["tsfc_e2_p5"];
	$tsfc_e2_p6 = $body[0]["Flight_Details"]["tsfc_e2_p6"];
	$tsfc_e2_p7 = $body[0]["Flight_Details"]["tsfc_e2_p7"];
	
	$tsfc_e3_p1 = $body[0]["Flight_Details"]["tsfc_e3_p1"];
	$tsfc_e3_p2 = $body[0]["Flight_Details"]["tsfc_e3_p2"];
	$tsfc_e3_p3 = $body[0]["Flight_Details"]["tsfc_e3_p3"];
	$tsfc_e3_p4 = $body[0]["Flight_Details"]["tsfc_e3_p4"];
	$tsfc_e3_p5 = $body[0]["Flight_Details"]["tsfc_e3_p5"];
	$tsfc_e3_p6 = $body[0]["Flight_Details"]["tsfc_e3_p6"];
	$tsfc_e3_p7 = $body[0]["Flight_Details"]["tsfc_e3_p7"];

	$tsfc_e4_p1 = $body[0]["Flight_Details"]["tsfc_e4_p1"];
	$tsfc_e4_p2 = $body[0]["Flight_Details"]["tsfc_e4_p2"];
	$tsfc_e4_p3 = $body[0]["Flight_Details"]["tsfc_e4_p3"];
	$tsfc_e4_p4 = $body[0]["Flight_Details"]["tsfc_e4_p4"];
	$tsfc_e4_p5 = $body[0]["Flight_Details"]["tsfc_e4_p5"];
	$tsfc_e4_p6 = $body[0]["Flight_Details"]["tsfc_e4_p6"];
	$tsfc_e4_p7 = $body[0]["Flight_Details"]["tsfc_e4_p7"];
	
	
	if(!($database = mysqli_connect('localhost', 'apiGET', 'apiget', 'joust' ))){
        die("Could not reconnect to the database. Server error.");
    }
	

	$query = sprintf("select serial_num from Engine where Aircraft_tail_num ='%s'", $tailNum);
	if(!($result = mysqli_query($database, $query))){
    ?>
        <h1 class = "err"><strong>Major Error:</strong></h1>
        <p>A SQL Exception occurred while interacting with the database.</p>
        <br />
        <p>The error message was:</p>

        <p><strong><?php echo mysqli_error($database);?></strong></p>

        <p>Please try again later.</p>
        <?php

        die("");
    }
		
	if ($result->num_rows > 0) {
        while($row = $result->fetch_assoc()) {
            $arr[] = $row;
		}

	}else{
		echo("NO ENGINES");	
	}
	$engine1 = $arr[0]["serial_num"];
	$engine2 = $arr[1]["serial_num"];
	$engine3 = $arr[2]["serial_num"];
	$engine4 = $arr[3]["serial_num"];
	//echo ("$engine1 $engine2  $engine3  $engine4 ");

	unset($arr);

	
	$query = sprintf("select * from Airports");
	if(!($result = mysqli_query($database, $query))){
    ?>
        <h1 class = "err"><strong>Major Error:</strong></h1>
        <p>A SQL Exception occurred while interacting with the database.</p>
        <br />
        <p>The error message was:</p>

        <p><strong><?php echo mysqli_error($database);?></strong></p>

        <p>Please try again later.</p>
        <?php

        die("");
    }
	
	$size = 0;
	
	if ($result->num_rows > 0) {
        while($row = $result->fetch_assoc()) {
            $arr[] = $row;
			$size ++;
		}
	} else {
        echo "NO AIRPORTS";
	}
	$test = $arr[0]["code"];
	
	$shortStartDist = 999999;
	$shortEndDist = 999999;
	$shortStartCode = "";
	$shortEndCode = "";
	
	foreach ($arr as &$value){
		$lat1 = $value["lat"];
		$long1 = $value["long"];
		$lat2 = $startLat;
		$long2 = $startLong;
		
		$theta = $long1 - $long2;
		$distance = (sin(deg2rad($lat1)) * sin(deg2rad($lat2))) + (cos(deg2rad($lat1)) * cos(deg2rad($lat2)) * cos(deg2rad($theta)));
		$distance = acos($distance);
		$distance = rad2deg($distance);
		$distance = $distance * 60 * 1.1515;
		
		if($distance < $shortStartDist){
			$shortStartDist = $distance;
			$shortStartCode = $value["code"];
		}
		
		$lat2 = $endLat;
		$long2 = $endLong;
		
		$theta = $long1 - $long2;
		$distance = (sin(deg2rad($lat1)) * sin(deg2rad($lat2))) + (cos(deg2rad($lat1)) * cos(deg2rad($lat2)) * cos(deg2rad($theta)));
		$distance = acos($distance);
		$distance = rad2deg($distance);
		$distance = $distance * 60 * 1.1515;
		
		if($distance < $shortEndDist){
			$shortEndDist = $distance;
			$shortEndCode = $value["code"];
		}
	}
	//echo ("  $shortStartCode $shortEndCode");

	date_default_timezone_set('America/Los_Angeles');
	
	$startPHPDT = new DateTime($startDT);
	$endPHPDT = new DateTime($endDT);
	
	//$diff = $startPHPDT->diff( $endPHPDT );
	//echo $diff->format( '%Y-%M-%D %H:%I:%S' );
	
	$durationSeconds = $endPHPDT->getTimestamp() - $startPHPDT->getTimestamp();
	//echo ($durationSeconds);
	
	$query = "INSERT INTO Flight 
		(duration, start_dt, end_dt, start_lat, end_lat, start_long, end_long, start_airport, end_airport,  Engine_serial_num1, Engine_serial_num2, Engine_serial_num3, Engine_serial_num4, Aircraft_tail_num, 
		
		dfc_best_total, dfc_best_e1, dfc_best_e2, dfc_best_e3, dfc_best_e4, 
		dfc_best_p1, dfc_best_p2, dfc_best_p3, dfc_best_p4, dfc_best_p5, dfc_best_p6, dfc_best_p7,
		dfc_best_e1_p1, dfc_best_e1_p2, dfc_best_e1_p3, dfc_best_e1_p4, dfc_best_e1_p5, dfc_best_e1_p6, dfc_best_e1_p7,
		dfc_best_e2_p1, dfc_best_e2_p2, dfc_best_e2_p3, dfc_best_e2_p4, dfc_best_e2_p5, dfc_best_e2_p6, dfc_best_e2_p7,
		dfc_best_e3_p1, dfc_best_e3_p2, dfc_best_e3_p3, dfc_best_e3_p4, dfc_best_e3_p5, dfc_best_e3_p6, dfc_best_e3_p7,
		dfc_best_e4_p1, dfc_best_e4_p2, dfc_best_e4_p3, dfc_best_e4_p4, dfc_best_e4_p5, dfc_best_e4_p6, dfc_best_e4_p7,
		
		dfc_spec_total, dfc_spec_e1, dfc_spec_e2, dfc_spec_e3, dfc_spec_e4, 
		dfc_spec_p1, dfc_spec_p2, dfc_spec_p3, dfc_spec_p4, dfc_spec_p5, dfc_spec_p6, dfc_spec_p7,
		dfc_spec_e1_p1, dfc_spec_e1_p2, dfc_spec_e1_p3, dfc_spec_e1_p4, dfc_spec_e1_p5, dfc_spec_e1_p6, dfc_spec_e1_p7,
		dfc_spec_e2_p1, dfc_spec_e2_p2, dfc_spec_e2_p3, dfc_spec_e2_p4, dfc_spec_e2_p5, dfc_spec_e2_p6, dfc_spec_e2_p7,
		dfc_spec_e3_p1, dfc_spec_e3_p2, dfc_spec_e3_p3, dfc_spec_e3_p4, dfc_spec_e3_p5, dfc_spec_e3_p6, dfc_spec_e3_p7,
		dfc_spec_e4_p1, dfc_spec_e4_p2, dfc_spec_e4_p3, dfc_spec_e4_p4, dfc_spec_e4_p5, dfc_spec_e4_p6, dfc_spec_e4_p7,
		
		percent_best_total, percent_best_e1, percent_best_e2, percent_best_e3, percent_best_e4, 
		percent_best_p1, percent_best_p2, percent_best_p3, percent_best_p4, percent_best_p5, percent_best_p6, percent_best_p7,
		percent_best_e1_p1, percent_best_e1_p2, percent_best_e1_p3, percent_best_e1_p4, percent_best_e1_p5, percent_best_e1_p6, percent_best_e1_p7,
		percent_best_e2_p1, percent_best_e2_p2, percent_best_e2_p3, percent_best_e2_p4, percent_best_e2_p5, percent_best_e2_p6, percent_best_e2_p7,
		percent_best_e3_p1, percent_best_e3_p2, percent_best_e3_p3, percent_best_e3_p4, percent_best_e3_p5, percent_best_e3_p6, percent_best_e3_p7,
		percent_best_e4_p1, percent_best_e4_p2, percent_best_e4_p3, percent_best_e4_p4, percent_best_e4_p5, percent_best_e4_p6, percent_best_e4_p7,
		
		percent_spec_total, percent_spec_e1, percent_spec_e2, percent_spec_e3, percent_spec_e4, 
		percent_spec_p1, percent_spec_p2, percent_spec_p3, percent_spec_p4, percent_spec_p5, percent_spec_p6, percent_spec_p7,
		percent_spec_e1_p1, percent_spec_e1_p2, percent_spec_e1_p3, percent_spec_e1_p4, percent_spec_e1_p5, percent_spec_e1_p6, percent_spec_e1_p7,
		percent_spec_e2_p1, percent_spec_e2_p2, percent_spec_e2_p3, percent_spec_e2_p4, percent_spec_e2_p5, percent_spec_e2_p6, percent_spec_e2_p7,
		percent_spec_e3_p1, percent_spec_e3_p2, percent_spec_e3_p3, percent_spec_e3_p4, percent_spec_e3_p5, percent_spec_e3_p6, percent_spec_e3_p7,
		percent_spec_e4_p1, percent_spec_e4_p2, percent_spec_e4_p3, percent_spec_e4_p4, percent_spec_e4_p5, percent_spec_e4_p6, percent_spec_e4_p7,

		
		tsfc_total, tsfc_e1, tsfc_e2, tsfc_e3, tsfc_e4, 
		tsfc_p1, tsfc_p2, tsfc_p3, tsfc_p4, tsfc_p5, tsfc_p6, tsfc_p7,
		tsfc_e1_p1, tsfc_e1_p2, tsfc_e1_p3, tsfc_e1_p4, tsfc_e1_p5, tsfc_e1_p6, tsfc_e1_p7,
		tsfc_e2_p1, tsfc_e2_p2, tsfc_e2_p3, tsfc_e2_p4, tsfc_e2_p5, tsfc_e2_p6, tsfc_e2_p7,
		tsfc_e3_p1, tsfc_e3_p2, tsfc_e3_p3, tsfc_e3_p4, tsfc_e3_p5, tsfc_e3_p6, tsfc_e3_p7,
		tsfc_e4_p1, tsfc_e4_p2, tsfc_e4_p3, tsfc_e4_p4, tsfc_e4_p5, tsfc_e4_p6, tsfc_e4_p7
		
		) VALUES 
		('".$durationSeconds."','".$endDT."','".$startDT."','".$startLat."','".$endLat."','".$startLong."','".$endLong."','".$shortStartCode."','".$shortEndCode."','".$engine1."','".$engine2."','".$engine3."','".$engine4."','".$tailNum."',
		
		'".$dfc_best_total."','".$dfc_best_e1."','".$dfc_best_e2."','".$dfc_best_e3."','".$dfc_best_e4."',
		'".$dfc_best_p1."','".$dfc_best_p2."','".$dfc_best_p3."','".$dfc_best_p4."','".$dfc_best_p5."','".$dfc_best_p6."','".$dfc_best_p7."',
		'".$dfc_best_e1_p1."','".$dfc_best_e1_p2."','".$dfc_best_e1_p3."','".$dfc_best_e1_p4."','".$dfc_best_e1_p5."','".$dfc_best_e1_p6."','".$dfc_best_e1_p7."',
		'".$dfc_best_e2_p1."','".$dfc_best_e2_p2."','".$dfc_best_e2_p3."','".$dfc_best_e2_p4."','".$dfc_best_e2_p5."','".$dfc_best_e2_p6."','".$dfc_best_e2_p7."',
		'".$dfc_best_e3_p1."','".$dfc_best_e3_p2."','".$dfc_best_e3_p3."','".$dfc_best_e3_p4."','".$dfc_best_e3_p5."','".$dfc_best_e3_p6."','".$dfc_best_e3_p7."',
		'".$dfc_best_e4_p1."','".$dfc_best_e4_p2."','".$dfc_best_e4_p3."','".$dfc_best_e4_p4."','".$dfc_best_e4_p5."','".$dfc_best_e4_p6."','".$dfc_best_e4_p7."',
		
		'".$dfc_spec_total."','".$dfc_spec_e1."','".$dfc_spec_e2."','".$dfc_spec_e3."','".$dfc_spec_e4."',
		'".$dfc_spec_p1."','".$dfc_spec_p2."','".$dfc_spec_p3."','".$dfc_spec_p4."','".$dfc_spec_p5."','".$dfc_spec_p6."','".$dfc_spec_p7."',
		'".$dfc_spec_e1_p1."','".$dfc_spec_e1_p2."','".$dfc_spec_e1_p3."','".$dfc_spec_e1_p4."','".$dfc_spec_e1_p5."','".$dfc_spec_e1_p6."','".$dfc_spec_e1_p7."',
		'".$dfc_spec_e2_p1."','".$dfc_spec_e2_p2."','".$dfc_spec_e2_p3."','".$dfc_spec_e2_p4."','".$dfc_spec_e2_p5."','".$dfc_spec_e2_p6."','".$dfc_spec_e2_p7."',
		'".$dfc_spec_e3_p1."','".$dfc_spec_e3_p2."','".$dfc_spec_e3_p3."','".$dfc_spec_e3_p4."','".$dfc_spec_e3_p5."','".$dfc_spec_e3_p6."','".$dfc_spec_e3_p7."',
		'".$dfc_spec_e4_p1."','".$dfc_spec_e4_p2."','".$dfc_spec_e4_p3."','".$dfc_spec_e4_p4."','".$dfc_spec_e4_p5."','".$dfc_spec_e4_p6."','".$dfc_spec_e4_p7."',
		
		'".$percent_best_total."','".$percent_best_e1."','".$percent_best_e2."','".$percent_best_e3."','".$percent_best_e4."',
		'".$percent_best_p1."','".$percent_best_p2."','".$percent_best_p3."','".$percent_best_p4."','".$percent_best_p5."','".$percent_best_p6."','".$percent_best_p7."',
		'".$percent_best_e1_p1."','".$percent_best_e1_p2."','".$percent_best_e1_p3."','".$percent_best_e1_p4."','".$percent_best_e1_p5."','".$percent_best_e1_p6."','".$percent_best_e1_p7."',
		'".$percent_best_e2_p1."','".$percent_best_e2_p2."','".$percent_best_e2_p3."','".$percent_best_e2_p4."','".$percent_best_e2_p5."','".$percent_best_e2_p6."','".$percent_best_e2_p7."',
		'".$percent_best_e3_p1."','".$percent_best_e3_p2."','".$percent_best_e3_p3."','".$percent_best_e3_p4."','".$percent_best_e3_p5."','".$percent_best_e3_p6."','".$percent_best_e3_p7."',
		'".$percent_best_e4_p1."','".$percent_best_e4_p2."','".$percent_best_e4_p3."','".$percent_best_e4_p4."','".$percent_best_e4_p5."','".$percent_best_e4_p6."','".$percent_best_e4_p7."',
		
		'".$percent_spec_total."','".$percent_spec_e1."','".$percent_spec_e2."','".$percent_spec_e3."','".$percent_spec_e4."',
		'".$percent_spec_p1."','".$percent_spec_p2."','".$percent_spec_p3."','".$percent_spec_p4."','".$percent_spec_p5."','".$percent_spec_p6."','".$percent_spec_p7."',
		'".$percent_spec_e1_p1."','".$percent_spec_e1_p2."','".$percent_spec_e1_p3."','".$percent_spec_e1_p4."','".$percent_spec_e1_p5."','".$percent_spec_e1_p6."','".$percent_spec_e1_p7."',
		'".$percent_spec_e2_p1."','".$percent_spec_e2_p2."','".$percent_spec_e2_p3."','".$percent_spec_e2_p4."','".$percent_spec_e2_p5."','".$percent_spec_e2_p6."','".$percent_spec_e2_p7."',
		'".$percent_spec_e3_p1."','".$percent_spec_e3_p2."','".$percent_spec_e3_p3."','".$percent_spec_e3_p4."','".$percent_spec_e3_p5."','".$percent_spec_e3_p6."','".$percent_spec_e3_p7."',
		'".$percent_spec_e4_p1."','".$percent_spec_e4_p2."','".$percent_spec_e4_p3."','".$percent_spec_e4_p4."','".$percent_spec_e4_p5."','".$percent_spec_e4_p6."','".$percent_spec_e4_p7."',
				

		'".$tsfc_total."','".$tsfc_e1."','".$tsfc_e2."','".$tsfc_e3."','".$tsfc_e4."',
		'".$tsfc_p1."','".$tsfc_p2."','".$tsfc_p3."','".$tsfc_p4."','".$tsfc_p5."','".$tsfc_p6."','".$tsfc_p7."',
		'".$tsfc_e1_p1."','".$tsfc_e1_p2."','".$tsfc_e1_p3."','".$tsfc_e1_p4."','".$tsfc_e1_p5."','".$tsfc_e1_p6."','".$tsfc_e1_p7."',
		'".$tsfc_e2_p1."','".$tsfc_e2_p2."','".$tsfc_e2_p3."','".$tsfc_e2_p4."','".$tsfc_e2_p5."','".$tsfc_e2_p6."','".$tsfc_e2_p7."',
		'".$tsfc_e3_p1."','".$tsfc_e3_p2."','".$tsfc_e3_p3."','".$tsfc_e3_p4."','".$tsfc_e3_p5."','".$tsfc_e3_p6."','".$tsfc_e3_p7."',
		'".$tsfc_e4_p1."','".$tsfc_e4_p2."','".$tsfc_e4_p3."','".$tsfc_e4_p4."','".$tsfc_e4_p5."','".$tsfc_e4_p6."','".$tsfc_e4_p7."'
		
		);";

		
		/*
				
		fc_total, fc_e1, fc_e2, fc_e3, fc_e4, 
		fc_p1, fc_p2, fc_p3, fc_p4, fc_p5, fc_p6, fc_p7,
		fc_e1_p1, fc_e1_p2, fc_e1_p3, fc_e1_p4, fc_e1_p5, fc_e1_p6, fc_e1_p7,
		fc_e2_p1, fc_e2_p2, fc_e2_p3, fc_e2_p4, fc_e2_p5, fc_e2_p6, fc_e2_p7,
		fc_e3_p1, fc_e3_p2, fc_e3_p3, fc_e3_p4, fc_e3_p5, fc_e3_p6, fc_e3_p7,
		fc_e4_p1, fc_e4_p2, fc_e4_p3, fc_e4_p4, fc_e4_p5, fc_e4_p6, fc_e4_p7,
		
		
		
				'".$fc_total."','".$fc_e1."','".$fc_e2."','".$fc_e3."','".$fc_e4."',
		'".$fc_p1."','".$fc_p2."','".$fc_p3."','".$fc_p4."','".$fc_p5."','".$fc_p6."','".$fc_p7."',
		'".$fc_e1_p1."','".$fc_e1_p2."','".$fc_e1_p3."','".$fc_e1_p4."','".$fc_e1_p5."','".$fc_e1_p6."','".$fc_e1_p7."',
		'".$fc_e2_p1."','".$fc_e2_p2."','".$fc_e2_p3."','".$fc_e2_p4."','".$fc_e2_p5."','".$fc_e2_p6."','".$fc_e2_p7."',
		'".$fc_e3_p1."','".$fc_e3_p2."','".$fc_e3_p3."','".$fc_e3_p4."','".$fc_e3_p5."','".$fc_e3_p6."','".$fc_e3_p7."',
		'".$fc_e4_p1."','".$fc_e4_p2."','".$fc_e4_p3."','".$fc_e4_p4."','".$fc_e4_p5."','".$fc_e4_p6."','".$fc_e4_p7."',
		
		*/

	if(!($result = mysqli_query($database, $query))){
        echo mysqli_error($database);
    } else {
        //return newly created data
		$lastID = mysqli_insert_id($database);
		$query = sprintf("select flight_id from Flight where flight_id='%s'", $lastID);
		if(!($result = mysqli_query($database, $query))){
			echo mysqli_error($database);
		} else {
			$row = $result->fetch_assoc();
			$flight_id = $row["flight_id"];
			
			file_put_contents($flight_id . ".json" , json_encode($body[0]["Flight_Graph_Data"]));
			
			$query = "UPDATE Engine SET Flight_id_last_flight = '".$flight_id."' WHERE Aircraft_tail_num = '".$tailNum."'";
			if(!($result = mysqli_query($database, $query))){
				echo mysqli_error($database);
			}
			$query = "UPDATE Aircraft SET Flight_id_last_flight = '".$flight_id."' WHERE tail_num = '".$tailNum."'";
			if(!($result = mysqli_query($database, $query))){
				echo mysqli_error($database);
			}
			$query = "UPDATE Aircraft SET location = '".$shortEndCode."' WHERE tail_num = '".$tailNum."'";
			if(!($result = mysqli_query($database, $query))){
				echo mysqli_error($database);
			}
		}
		
	}
});



$app->run();
}else{
	echo "login error";
}
?>

