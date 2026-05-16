<?php

include "../../controllers/agent/DeliveryAgentController.php";

?>

<!DOCTYPE html>

<html>

<head>

<title>

Agent Registration

</title>

</head>

<body>

<center>

<h1>

Delivery Agent Registration

</h1>

<form method="post">

<table>

<tr>

<td>Name:</td>

<td>

<input type="text"
name="name">

</td>

</tr>


<tr>

<td>Phone:</td>

<td>

<input type="text"
name="phone">

</td>

</tr>


<tr>

<td>Vehicle:</td>

<td>

<select name="vehicle">

<option>Bike</option>

<option>Bicycle</option>

</select>

</td>

</tr>


<tr>

<td>Password:</td>

<td>

<input type="password"
name="password">

</td>

</tr>


<tr>

<td>

<input
type="submit"

name="register"

value="Register">

</td>

</tr>

</table>

</form>

<?php

echo $success;

echo $error;

?>

<a href="login.php">

Login Here

</a>

</center>

</body>

</html>