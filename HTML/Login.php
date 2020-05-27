<?php
//signin.php
include 'DatabaseConnection.php';

//first, check if the user is already signed in. If that is the case, there is no need to display this page
//      SORRY GUYS I DIDN'T CHECK FIRST LET ME JUST SEE IF THIS SHIT WOOOOOOORKSSSSSSSSS

    if($_SERVER['REQUEST_METHOD'] == 'POST')
    {
        /* so, the form has been posted, we'll process the data in three steps:
            1.  Check the data
            2.  Let the user refill the wrong fields (if necessary)
            3.  Varify if the data is correct and return the correct response
        */
        $errors = array(); /* declare the array for later use */

        if(!isset($_POST['username']))
        {
            $errors[] = 'The username field must not be empty.';
        }

        if(!isset($_POST['pass']))
        {
            $errors[] = 'The password field must not be empty.';
        }

        if(!empty($errors)) /*check for an empty array, if there are errors, they're in this array (note the ! operator)*/
        {
            echo 'Uh-oh.. a couple of fields are not filled in correctly..';
            echo '<ul>';
            foreach($errors as $key => $value) /* walk through the array so all the errors get displayed */
            {
                echo '<li>' . $value . '</li>'; /* this generates a nice error list */
            }
            echo '</ul>';
        }
        else
        {
            //the form has been posted without errors, so save it
            //notice the use of mysql_real_escape_string, keep everything safe!
            //also notice the sha1 function which hashes the password
            $username = $_POST['username'];
            $password = $_POST['pass'];

                $statementemnString = "select 
                                            user_id, 
                                            user_name, 
                                            user_level
                                       from users
                                       where user_name = :username and user_pass=:password;
                                            ";
                $statement=$pdoconnection->prepare($statementemnString);
                $statement->bindParam(":username",$username);
                $statement->bindParam(":password",$password);
                $statement->execute();
            }

            if(!$statement)
            {
                //something went wrong, display the error
                echo 'Something went wrong while signing in. Please try again later.';
                //echo mysql_error(); //debugging purposes, uncomment when needed
            }
            else
            {
                //the query was successfully executed, there are 2 possibilities
                //1. the query returned data, the user can be signed in
                //2. the query returned an empty result set, the credentials were wrong
                if($statement->rowCount() == 0)
                {
                    echo 'You have supplied a wrong user/password combination. Please try again.';
                }
                else
                {
                    //set the $_SESSION['signed_in'] variable to TRUE
                    $_SESSION['signed_in'] = true;

                    //we also put the user_id and user_name values in the $_SESSION, so we can use it at various pages
                    while($row = $statement->fetch(PDO::FETCH_ASSOC))
                    {
                        $_SESSION['user_id']    = $row['user_id'];
                        $_SESSION['user_name']  = $row['user_name'];
                        $_SESSION['user_level'] = $row['user_level'];
                    }

                    echo 'Welcome, ' . $_SESSION['user_name'] . '. <a href="Categories.php">Proceed to the forum overview</a>.';
                }
            }
        }

?>


<html>

<head>
  <link rel="stylesheet" href="../CSS/Signin.css">
  <link href="https://fonts.googleapis.com/css?family=Ubuntu" rel="stylesheet">
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Log in</title>
</head>

<body>
  <div class="main">
      <form action="processLogin.php" METHOD="POST">
            <p class="sign" align="center">Login</p>
              <input name="username" class="un " type="text" align="center" placeholder="Username">
              <input name="pass" class="pass" type="password" align="center" placeholder="Password">
          <a class="submit" align="center">Login</a>
      </form>
    </div>
     
</body>

</html>