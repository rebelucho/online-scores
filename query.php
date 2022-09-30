<?php
if (isset($_POST["val"]))
{
    $this_var = $_POST["val"];
    echo json_encode(array('value' => $this_var));
}

if (isset($_POST["hours"])){
	$this_var = $_POST["hours"];
    echo json_encode(array('value' => $this_var));	
}

?>