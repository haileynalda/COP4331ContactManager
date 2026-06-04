<?php
    /*
     * insert description
     */
    $inData = decodeInput();
    
    // fields
    $userId = $inData["userId"];
    $firstName = $inData["firstName"];
    $lastName = $inData["lastName"];
    
    // connection
    $conn = new mysqli("127.0.0.1", "TheBeast", "WeLoveCOP4331", "COP4331");
    if($conn->connect_error)
    {
        errorReturn( $conn->connect_error );
    }
    else
    {
        // sql
        $stmt = $conn->prepare("DELETE FROM Contacts WHERE FirstName = ? AND LastName = ? AND UserID = ?");
        $stmt->bind_param("ssi", $firstName, $lastName, $userId);
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
