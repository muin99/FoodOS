<?php

include "../../controllers/agent/DeliveryAgentController.php";

?>

<!DOCTYPE html>

<html>

<head>

<title>

Agent Login

</title>

</head>

<body>

<center>

<h1>

Delivery Agent Login

</h1>

<form method="post">

<table>

<tr>

<td>Phone:</td>

<td>

<input type="text"
name="phone">

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

name="login"

value="Login">

</td>

</tr>

</table>

</form>

<?php

echo $error;

?>

</center>

</body>

</html>