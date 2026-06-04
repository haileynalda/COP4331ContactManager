<?php
    /*
     * SignUp
     */
	$inData = decodeInput();
    
    // fields
	$firstName = $inData["firstName"];
    $lastName = $inData["lastName"];
    $login = $inData["login"];
    $password = $inData["password"];

    // connections
	$conn = new mysqli("127.0.0.1", "TheBeast", "WeLoveCOP4331", "COP4331");
	if ($conn->connect_error)
	{
        errorReturn( $conn->connect_error );
	}
	else
	{
        // sql
		$sql = "SELECT * FROM Users WHERE Login=?";
		$stmt = $conn->prepare($sql);
		$stmt->bind_param("s", $login);
		$stmt->execute();
		$result = $stmt->get_result();
		$rows = mysqli_num_rows($result);
        // ensure user does not exist
		if ($rows == 0)
		{
			$stmt = $conn->prepare("INSERT into Users (FirstName, LastName, Login, Password) VALUES(?,?,?,?)");
			$stmt->bind_param("ssss", $firstName, $lastName, $login, $password);
			$stmt->execute();
			$id = $conn->insert_id;
			$stmt->close();
			$conn->close();
			http_response_code(200);
			$searchResults .= '{'.'"id": "'.$id.''.'"}';

            returnUserID($searchResults);
		} else {
			http_response_code(409);
            errorReturn("Username taken");
		}
	}
    
    // decode
	function decodeInput()
	{
		return json_decode(file_get_contents('php://input'), true);
	}

    // response
	function response( $obj )
	{
		header('Content-type: application/json');
		echo $obj;
	}

    // error
	function errorReturn( $err )
	{
		$retValue = '{"error":"' . $err . '"}';
        response( $retValue );
	}

    // pass userID
	function returnUserID( $searchResults )
	{
		$retValue = '{"results":[' . $searchResults . '],"error":""}';
        response( $retValue );
	}

?>
