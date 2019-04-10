<?
if ( isset( $_COOKIE["session"] ) ) {
	header("Location: /index.php");
	exit;
}

// if this is a login request
if ($_SERVER["REQUEST_METHOD"] == "POST") {

require_once "config.php";

if ( empty( trim( $_POST["username"] ) ) || empty( trim( $_POST["password"] ) ) ) {
	exit;
}

$request = "select `username`, `password` from `users` where `username` = :username";
$statement = $db->prepare($request);
$statement->execute([":username" => $_POST["username"]]);

if ( $statement->rowCount() == 0 ){
	exit;
}

$result = $statement->fetch(PDO::FETCH_ASSOC);

if ( $_POST["password"] == $result["password"] ) {
	$token = bin2hex( random_bytes(32) );

	setcookie("session", $token);
	header("Location: /index.php");
	exit();
}
}

?>

<form action="" method="POST">
<h3>login</h3>
<span>
	<label for="username">username</label>
	<input type="text" name="username">
</span>
<span>
	<label for="password">password</label>
	<input type="text" name="password">
</span>
<input type="submit" name="login">
</form>
