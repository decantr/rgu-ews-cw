<? session_start();

if ( isset( $_SESSION["token"] ) ) {
	header("location: /index.php");
}

$error = "";

// if this is a login request
if ($_SERVER["REQUEST_METHOD"] == "POST") {

require_once "config.php";

if ( empty( trim( $_POST["username"] ) ) || empty( trim( $_POST["password"] ) ) ) {
	$error = "Enter a valid username and password";
	return;
}

$request = "select `username`, `password`, `seen` from `users` where `username` = :username";
$statement = $db->prepare($request);
$statement->execute([":username" => $_POST["username"]]);

if ( $statement->rowCount() == 0 ){
	$error = "No such user";
	return;
}

$result = $statement->fetch(PDO::FETCH_ASSOC);

if ( $_POST["password"] == $result["password"] ) {
	$update = $db->prepare("UPDATE users SET seen = NOW() WHERE `username` = :username");
	$update->execute([":username" => $_POST["username"]]);

	// store data in session variables
	$_SESSION["token"] = bin2hex( random_bytes(32) );
	$_SESSION["seen"] = $results["seen"];
	$_SESSION["username"] = $username;

	header("location: /");
}
}
?>

<link rel="stylesheet" type="text/css" href="style.css" />

<? include("header.php") ?>

<form action="" method="POST">
<h1>Login</h1>
<span class="error"><?php echo $error ?></span>
<span>
	<label for="username">Username</label>
	<input type="text" name="username">
</span>
<span>
	<label for="password">Password</label>
	<input type="password" name="password" required>
</span>
<input type="submit" name="login">
</form>
or <a href="register.php">register</a>

