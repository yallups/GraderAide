//Edit and OK to Save GUI
function doSave() {
	//
	$draft = "";
	$className = "";
	$funName = "";
	$theHidden = "";

	//
	$draft = $_REQUEST["code"];

	//Check to see if they are submitting a test
	if ($_REQUEST["submit"]=="Test") {
		if ($_REQUEST["code"]!=="") {
			//
			if(!empty($draft)) {
				//
				echo "<hr /><br /><h3>Code:</h3><br />";
				//
				if(empty($_REQUEST["existingClass"]) || true){
                    if(eval($draft) === FALSE) {
						//
                        echo "Could not evaluate your GUI code.  Please check your syntax and try again.";
					}
				}

				//
				echo "<hr /><br />";
			}
		}
	}
	else if ($_REQUEST["submit"]=="Save!") {
		$selecTion = $_REQUEST["classSel"];
		if(empty($selecTion)) $selecTion = " , ";
		$pieces = explode(",", $selecTion);
		$className = $pieces[0];
		$funName = $pieces[1];

		//
		$draft = $_REQUEST["code"];
		$draft = stripslashes($draft);

		//
		$className = $_REQUEST["className"];
		$className = stripslashes($className);

		//
		$funName = $_REQUEST["funName"];
		$funName = stripslashes($funName);

		if(!empty($draft) && !empty($className) && !empty($funName) ) {

//Try to look up the class in the DB
$query = "SELECT COUNT(ID) FROM `Template` WHERE ClassName = '" . trim($className) . "' AND FunctionName = '" . trim($funName) . "'";
			$result = mysql_query($query);
			$row = mysql_fetch_array($result);

			//Check to see if we got anything
			if($row!=null && mysql_num_rows($result) > 0)
			{
				//Check to see if it exists
				if($row[0] > 0)
				{
//Update class
$query = "UPDATE `Template` SET Body = '" . mysql_real_escape_string($draft) . "' WHERE ClassName = '" . trim($className) . "' AND FunctionName = '$funName' ";
//echo($qq."<br>");
				}
				else
				{
//Insert new class
$query = "INSERT INTO `Template` ( ClassName, FunctionName, Body, Active, LastUpdated) VALUES ( '" . trim($className) . "' , '" . trim($funName) . "', '" . mysql_real_escape_string($draft) . "', 1, NOW() )";
				}
			}
			else
			{
//Insert new class
$query = "INSERT INTO `Template` ( ClassName, FunctionName, Body, Active, LastUpdated) VALUES ( '" . trim($className) . "' , '" . trim($funName) . "', '" . mysql_real_escape_string($draft) . "', 1, NOW() )";
			}

			//Execute
mysql_query( $query ) or die(mysql_error());
echo "Saved<br>";
		}
		else
		{
			echo "Could not save GUI class, invalid data specified";
		}
	}
	else if ($_REQUEST["submit"]=="Go!") {
		//
		$selecTion = $_REQUEST["classSel"];
		if(empty($selecTion)) $selecTion = " , ";
		$pieces = explode(",", $selecTion);
		$className = $pieces[0];
		$funName = $pieces[1];
		//
$result = mysql_query("SELECT * FROM `Template` WHERE ClassName = '" . trim($className) . "' AND FunctionName = '" . trim($funName) . "' ORDER BY ID ASC LIMIT 0,1");
		$row = mysql_fetch_array($result);
		$draft = $row['Body'];
$theHidden = "<input type='hidden' value='" . trim($className) . "' name='existingClass' /><input type='hidden' value='" . trim($funName) . "' name='existingFun' />";
	}

	echo "<FORM METHOD=post>";
	echo "<H3>Select GUI To Edit:</H3>";
	echo "<SELECT NAME='classSel'>";

	$result = mysql_query("SELECT * FROM `Template` ORDER BY ClassName, FunctionName ASC");

	while($row = mysql_fetch_array($result))
	{
		//
        echo "<option value='" . $row['ClassName'] . "," . $row['FunctionName'] . "' ";
        if(!empty($className) && !empty($funName) && $row['ClassName'] == $className && $row['FunctionName'] == $funName) echo " selected ";
        echo " >" . $row['ClassName'] . " - " . $row['FunctionName'] . "</option>";
	}

	echo "</SELECT>";
	echo "&nbsp;<INPUT TYPE='Submit' VALUE='Go!' NAME='submit' />";

	echo "<br><br>";

	echo "<B>Edit GUI Object</B>";
	echo "<BR>";
	echo "<B>Type PHP Code:</B><br>";
	echo "<pre>";
	echo "<TEXTAREA rows='15' cols='80' NAME='code'>";
	echo htmlentities($draft);
	echo "</TEXTAREA></pre><BR>";
	echo "<INPUT TYPE='Submit' VALUE='Test' NAME='submit'/>";
	echo "<br><br>";
	echo "<H3>Save GUI Object:</H3>";
	echo "Class Name: &nbsp;";
	echo "<INPUT TYPE='text' NAME='className' VALUE='$className'/>";
	echo "&nbsp;&nbsp;&nbsp;";
	echo "Function Name:&nbsp;";
	echo "<INPUT TYPE='text' NAME='funName' VALUE='$funName'/>";
	echo "&nbsp;&nbsp;&nbsp;";
	echo "<INPUT TYPE='Submit' VALUE='Save!' NAME='submit' />";
	echo "</FORM>";
	echo "<BR>";
}

//
doSave();

function edit() {
    $result = mysql_query("SELECT UserName,Role,Active FROM `Users`");

    echo '<script>function changeUser(val){ document.alert('you changed me '+val); }</script>

    echo '<form><select onchange="changeUser(this.val)"><option value="" selected="selected"></option>';
    while ($row = mysql_fetch_array($result, MYSQL_NUM)) {
        $role = $row[1] == 0 ? 'Admin' : 'User';
        $active = $row[2] == 1 ? '' : ' - inactive';

        echo('<option value="'.$row[0].'">'.$row[0].' - '.$role.$active.'</option>');
    }
    echo '</select>';

    echo '<br/><br/><br/>';

    //echo '<form>';
    echo 'User Name: <input type="text" name="uname">&nbsp;&nbsp;';
    echo 'Password: <input type="text" name="password">&nbsp;&nbsp;';
    echo '<input type="submit" value="submit">';
    echo '</form>';

}

edit();
