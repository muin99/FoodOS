<?php

session_start();

if(!isset($_SESSION["agent"]))
{

header("location:login.php");

}

?>

<!DOCTYPE html>

<html>

<head>

<title>

Dashboard

</title>

</head>

<body>

<center>

<h1>

Welcome

<?php

echo $_SESSION["agent_name"];

?>

</h1>

<a href="logout.php">

Logout

</a>

</center>

</body>

</html>