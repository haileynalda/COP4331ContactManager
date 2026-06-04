
<?php
    /*
     * Login entry
     */
	$inData = decodeInput();
    
    // return fields
	$id = 0;
	$firstName = "";
	$lastName = "";
    
    // fields
    $phoneNumber = $inData["phoneNumber"];
    $emailAddress = $inData["emailAddress"];
    $userId = $inData["userId"];
    
    // connection
	$conn = new mysqli("127.0.0.1", "TheBeast", "WeLoveCOP4331", "COP4331");
	if( $conn->connect_error )
	{
        errorReturn( $conn->connect_error );
	}
	else
	{
        // sql
		$stmt = $conn->prepare("SELECT ID,FirstName,LastName FROM Users WHERE (Login=? AND Password=?)");
		$stmt->bind_param("ss", $inData["login"], $inData["password"]);
		$stmt->execute();
		$result = $stmt->get_result();

		if( $row = $result->fetch_assoc()  )
		{
            returnFields( $row['FirstName'], $row['LastName'], $row['ID'] );
		}
		else
		{
            errorReturn("No Records Found");
		}

		$stmt->close();
		$conn->close();
	}
    
    // decode
	function decodeInput()
	{
		return json_decode(file_get_contents('php://input'), true);
	}

    // respond
	function respond( $obj )
	{
		header('Content-type: application/json');
		echo $obj;
	}
    
    // error
	function errorReturn( $err )
	{
		$retValue = '{"id":0,"firstName":"","lastName":"","error":"' . $err . '"}';
		respond( $retValue );
	}
    
    // collect return fields
	function returnFields( $firstName, $lastName, $id )
	{
		$retValue = '{"id":' . $id . ',"firstName":"' . $firstName . '","lastName":"' . $lastName . '","error":""}';
		respond( $retValue );
	}

?>
