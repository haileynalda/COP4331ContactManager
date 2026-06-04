<?php
    
    /*
     * This is to add contacts, I don't have much to say
     */
	$inData = decodeInput();
	
    // fields
	$firstName = $inData["firstName"];
    $lastName = $inData["lastName"];
	$phone = $inData["phone"];
    $email = $inData["email"];
    $userId = $inData["userId"];

    // connection
	$conn = new mysqli("127.0.0.1", "TheBeast", "WeLoveCOP4331", "COP4331");
	if ($conn->connect_error)
	{
        errorReturn( $conn->connect_error );
	}
	else
	{
        // sql
        $stmt = $conn->prepare("INSERT into Contacts (FirstName,LastName,Phone,Email, UserID) VALUES(?,?,?,?,?)");
        $stmt->bind_param("ssssi", $firstName, $lastName, $phone, $email, $userId);
        $stmt->execute();
        $stmt->close();
        $conn->close();
        errorReturn("");
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
		$retValue = '{"error":"' . $err . '"}';
        respond( $retValue );
	}

?>
