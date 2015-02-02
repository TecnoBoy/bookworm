<?php

include("header.php");

if (isset($_SESSION['login']) && $_SESSION['login']==1)
{
    $_SESSION['login']=0;
    header("Location: index.php");
}

if ($_POST['user'] && $_POST['pass'])
{
    $user = trim($mysqli->real_escape_string($_POST['user']));
    $pass = trim($mysqli->real_escape_string($_POST['pass']));
    
    if ($user=="user" && $pass=="pass")
    {
        $_SESSION['login']=1;
        echo "<h2>Authentication Success</h2>";
    }
    else
    {
        echo "<h2>Authentication Failure</h2>";
    }
    header("Refresh: 2; index.php");
}
else
{
    echo '
    <div id="input-area">
    <form action="login.php" method="post">
        <label>Username:</label>
        <input type="text" name="user">
        
        <label>Password:</label>
        <input type="password" name="pass">

        <input type="submit" name="bmit" value="Login" class="submit-button"> 
    </form>
    <div style="clear: both;"></div>
    </div>
    <br><br>
    ';
}

include("footer.php");

?>
