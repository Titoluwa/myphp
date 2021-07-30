<?php
session_start();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="todo.css">
    <title>To do List </title>
</head>
<body align="center" >
    <h1>
    Welcome, <?php echo ucwords($_SESSION['user']);?>.
    </h1>
    <form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF'])?>" method="POST">
        <label for="todo"><b>Add item to your To-do-list</b></label>
        &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;
        <label for="tick">Write the id of complete your task</label>
        
        <br>
        <input type="text" name="todo" >
        <input type="submit" id='add' value="ADD">
        &nbsp;
        
        <input type="number" name="tick" id="tick">
        <input type="submit" value="UPDATE" id="submit">
    </form>
    <br>
    <?php
        $servername = "localhost";
        $username = "root";
        $password = "";
        $dbname = "todo";
        //FUNCTION TO DISPLAY THE TABLE.
        function displaytodo(){
            try {
                $servername = "localhost";
                $username = "root";
                $password = "";
                $dbname = "todo";
                $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
                    // set the PDO error mode to exception
                $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                // To display the items on the table
                $lo = strtolower($_SESSION['tabb']);
                $table = ('user'.$lo);
                $sqls = $conn->prepare("SELECT id, item, `status` FROM `$table`"); 
                $sqls->execute();
                $result = $sqls->fetchAll(PDO::FETCH_ASSOC);
                echo "<b>Your TO DO</b><br>";
                echo '<form action="<?php echo htmlspecialchars($_SERVER[\'PHP_SELF\'])?>" method="POST">';
                echo "<form><table align=\"center\"><tr><th>Task (id)</th><th>Status</th></tr>" ;
                
                foreach($result as $row){
                    $active = $row['status'];
                    echo "<td>".$row['item']." (".$row['id'].")</td><td>";
                    if ($active==0){
                        echo 'Active';
                    }else {
                        echo 'Complete';
                    }
                    echo "</tr>";
                }      
                echo "</table></form>";
        
            } catch(PDOException $e) {
                null;
            }
        }
        displaytodo();
        // TO CREATE THE DB
        try {
            $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
                // set the PDO error mode to exception
            $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                // To create table for a specfic user to do
            $lo = strtolower($_SESSION['tabb']);
            $table = ('user'.$lo);
            $tabb = "CREATE TABLE `todo`.`$table` ( `id` INT(6) NOT NULL AUTO_INCREMENT , `item` VARCHAR(255) NOT NULL , `timeofentry` DATETIME NOT NULL , `status` TINYINT(1) NOT NULL DEFAULT '0', PRIMARY KEY (`id`)) ENGINE = InnoDB;";
                //if the table does not exist, EXECUTE, else do nothing
            try{
                $conn->exec($tabb);
                echo "just created table";
                
            }catch(PDOException $e){
                null;
            }
        }catch(PDOException $e) {
            echo $sql . "<br>" . $e->getMessage();
            // pass;
        }
        //TO COLLECT TASK AND DISPLAY THE TABLE
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            // collect value of input field (the to do item)
            $task = $_POST['todo'];
            if (empty($task)) {
                null;
            }else {
                try {
                    $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
                    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                        
                        // To add items to the 'to do list' table
                    $sqli = "INSERT INTO $table (item, timeofentry) VALUES (\"$task\", NOW())";
                    $conn->exec($sqli);
                    echo "New task added successfully!!<br>";
                }
                catch(PDOException $e) {
                    null;
                    // pass;
                }
                
                
            }
            
        }
        //TO UPDATE TASK AND DIAPLAY THE TABLE
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            // collect value of input field (the to do item)
            $done = (int)$_POST['tick'];
            if(empty($done)){
                null;
            }else{
                try {
                    $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
                    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                    // To update the status of the complete
                    $sqlu = "UPDATE $table SET `status`='1' WHERE id=$done";
                    $conn->exec($sqlu);
                    echo "Records UPDATED successfully <br>";
                }
                catch(PDOException $e){
                    null;
                }
            }
            
        }
    ?>
</body>
</html>

<!-- <a href="read.php?id='. $row['id'] .'"> -->
<!-- <a href="update.php?id='. $row['id'] .'"> -->
<a href="delete.php?id='. $row['id'] .'">