<?php
    /*
     * Update Contacts
     */
	$inData = decodeInput();

    // fields
	$phone = $inData["phone"];
	$email = $inData["email"];
	$newFirst = $inData["newFirstName"];
	$newLast = $inData["newLastName"];
	$id = $inData["id"];

    // connection
	$conn = new mysqli("127.0.0.1", "TheBeast", "WeLoveCOP4331", "COP4331");
    if ($conn->connect_error)
    {
        errorReturn( $conn->connect_error );
    }
    else
    {
        // sql
        $stmt = $conn->prepare("UPDATE Contacts SET FirstName = ?, LastName=?, Phone= ?, Email= ? WHERE ID= ?");
        $stmt->bind_param("ssssi", $newFirst, $newLast, $phone, $email, $id);
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

    // response
	function response( $obj )
	{
		header('Content-type: application/json');
		echo $obj;
	}

    // error
	function errorReturn( $err )
	{
		$retValue = '{"id":0,"firstName":"","lastName":"","error":"' . $err . '"}';
        response( $retValue );
	}

?>
