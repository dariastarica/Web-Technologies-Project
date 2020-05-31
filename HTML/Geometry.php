<?php
session_start();
?>
<!DOCTYPE html>
<html>
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<head>
    <title> MEq </title>
    <meta charset="UTF-8"/>
    <meta http-equiv="X-UA-Compatible" content="ie=edge"/>
    <link href="https://fonts.googleapis.com/css?family=Ubuntu" rel="stylesheet">
    <link href='https://fonts.googleapis.com/css?family=Roboto' rel='stylesheet'>
    <link rel="stylesheet" type="text/css" href="../CSS/Front+flex.css">
</head>
<body>
<div class="topnav">
    <a class="active" href="Front+flex.php">Home</a>
    <a href="News.php">News</a>
    <a href="Categories.php">Categories</a>
    <a href="Contact.php">Contact</a>
    <div class="login-container">
        <?php
        if ($_SESSION['logged'] == false) {
            echo '<button type="button" onclick="location.href =\'Login.php\'">Login</button>';
        } else {
            echo '<button type="button" onclick="location.href =\'AddEquation.php\'">Add Equation</button>';
        }
        ?>
    </div>
</div>
<div class="equation-view">
    <?php
    include 'DatabaseConnection.php';
    echo "<table style='border: solid 1px black;'>";
    echo "<tr><th>Equation</th><th>Description</th><th>User</th><th>Category</th></tr>";
    class TableRows extends RecursiveIteratorIterator {
        function __construct($it) {
            parent::__construct($it, self::LEAVES_ONLY);
        }

        function current() {
            return "<td style='width:150px;border:1px solid black;'>" . parent::current(). "</td>";
        }

        function beginChildren() {
            echo "<tr>";
        }

        function endChildren() {
            echo "</tr>" . "\n";
        }
    }
    $sql="SELECT post_content, post_name, user_name, category FROM posts join users u on posts.post_by = u.user_id where category='Geometry'";
    $statement = $pdoconnection->prepare($sql);
    $statement->execute();
    $result=$statement->setFetchMode(PDO::FETCH_ASSOC);
    if ($row = $statement->fetch() != null){
        foreach (new TableRows(new RecursiveArrayIterator($statement->fetchAll())) as $k => $v) {
            echo $v;
        }
        echo "</table>";
    }
    else{
        echo "</table>";
        echo 'No equations added yet!';
    }
    ?>
</div>
<div class="footer">
    <footer>
        <div class="footer-container">
            <button type="button" onclick="location.href = 'People/Maria.php' ">Maria Istrate</button>
            <button type="button" onclick="location.href = 'People/Daria.php' ">Daria Stărică</button>
            <button type="button" onclick="location.href = 'People/Tudor.php' ">Tudor Mihăiuc</button>
        </div>
    </footer>
</div>
</body>
</html>