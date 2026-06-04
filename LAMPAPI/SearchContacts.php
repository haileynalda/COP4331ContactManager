<?php
    /*
     * Search
     */
	$inData = decodeInput();
    
    // return fields
	$searchResults = "";
	$searchCount = 0;
    
    // connections
	$conn = new mysqli("127.0.0.1", "TheBeast", "WeLoveCOP4331", "COP4331");
	if ($conn->connect_error)
	{
        errorReturn( $conn->connect_error );
	}
	else
	{
        // sql
		$stmt = $conn->prepare("SELECT * FROM Contacts WHERE (FirstName like ? OR LastName like?) AND UserID=?");
		$colorName = "%" . $inData["search"] . "%";
		$stmt->bind_param("sss", $colorName, $colorName, $inData["userId"]);
		$stmt->execute();

		$result = $stmt->get_result();
        
        // compile results into an array
		while($row = $result->fetch_assoc())
		{
			if( $searchCount > 0 )
			{
				$searchResults .= ",";
			}
			$searchCount++;
			$searchResults .= '{"FirstName" : "' . $row["FirstName"]. '", "LastName" : "' . $row["LastName"]. '", "Phone" : "' . $row["Phone"]. '", "Email" : "' . $row["Email"]. '", "UserID" : "' . $row["UserID"].'", "ID" : "' . $row["ID"]. '"}';
		}
        
		if( $searchCount == 0 )
		{
            errorReturn( "No Records Found" );
		}
		else
		{
            returnFields( $searchResults );
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
	function returnFields( $searchResults )
	{
		$retValue = '{"results":[' . $searchResults . '],"error":""}';
        respond( $retValue );
	}

?>
