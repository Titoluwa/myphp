<?php
session_start();
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "login";

$nameErr = '';
$passwordErr = '';
if ($_SERVER["REQUEST_METHOD"] == "POST"){
    // Login Details
    $name = $_REQUEST['username'];
    $pass = $_REQUEST['pass'];

    if (empty($name)){
        $nameErr = 'You need to enter your username';
    }elseif (empty($pass)){
        $passwordErr = "No password entered";
    }else{
        try{
            $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
            // set the PDO error mode to exception
            $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            $sql = $conn->prepare("SELECT username, allname, pass FROM users");
            $sql->execute();
            $data = $sql->fetchAll(PDO::FETCH_ASSOC);
            $namArr = array();
            $passArr = array();
            for($i = 0; $i<(count($data)); $i++){
                array_push($namArr, $data[$i]['username']);
                array_push($passArr, $data[$i]['pass']);
            }
            if(in_array($name, $namArr)){
                $r = array_search($name, $namArr);
                if ($pass == $passArr[$r]){
                // opens the user's to do session
                    $names = $data[$r]['allname'];
                    $_SESSION['tabb'] = $name;
                    $_SESSION['user'] = $names;
                    header("location: todo.php");
                }else{
                    $passwordErr = 'Password not correct';
                }
            }else{
                $nameErr =  'Not Found';
            }
        }
        catch(PDOException $e){
            echo 'Error: '.$e->getMessage();
        }
    }
}
?>

<!doctype html>    
<html>    
<head>
    <link rel="stylesheet" href="login.css">    
    <title>Welcome| Login</title>    
</head>    
<body align='center'>   
    <h2>User Login</h2> 
    <form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF'])?>" method="POST">
        <label for="username">Username: </label>
        <input type="text" name="username" id="username">
        <br>
        <i style="color:red;"><?php echo $nameErr;?></i>
        <br>
        <label for="pass">Password: </label>
        <input type="password" name="pass" id="pass">
        <br>
        <i style="color:red;"><?php echo $passwordErr;?></i>
        <br><br>        
        <input type="submit" id="submit" name="submit" value="Login">
    </form>
</body>    
</html>    