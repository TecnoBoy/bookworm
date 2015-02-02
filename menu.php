<ul id="menu">
    <li><a href="index.php">Home</a></li>
    <li><a href="books.php">Books</a></li>
    <li><a href="authors.php">Authors</a></li>
    <li><a href="categories.php">Categories</a></li>
    <?php 
    if($_SESSION['login']== 1)
        echo "<li><a href='insert.php'>Insert</a></li>";
    ?>
    <li><a href="login.php">
    <?php if(isset($_SESSION['login']) && $_SESSION['login']==1) {echo "Logout";}else{echo "Login";} ?>
    </a></li>
</ul>
</br></br></br>
