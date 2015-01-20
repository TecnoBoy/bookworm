<?php

include("header.php");

$per_page = 25;


echo '
    <div id="input-area">
    <form method="post" action="authors.php">
        <label>First Name:</label>
        <input type="text" name="first_name">
        <label>Last Name:</label>
        <input type="text" name="last_name">
        <input type="submit" name="submit" value="Search" class="submit-button">
    </form>
    </div>
    ';

if ($_POST['first_name'])
{
    $title = $_POST['first_name'];
    $sql = "SELECT * FROM authors WHERE first_name LIKE '%$first_name%'";
}
else
{
    $sql = "SELECT * FROM authors ORDER BY first_name ASC";
}
if (!($res = $mysqli->query($sql)))
    die("Error: " . $mysqli->error);

$pages = ceil($res->num_rows / $per_page);

if (isset($_GET['page']) && is_numeric($_GET['page']))
{
    $page = $_GET['page'];
    if ($page > 0 && $page <= $res->num_rows)
    {
        $start = ($page - 1) * $per_page;
        $end = $start + $per_page;
    }
    else
    {
        $start = 0;
        $end = $per_page;
    }
}
else
{
    $start = 0;
    $end = $per_page;
}

echo "<p>";
for ($i = 1; $i <= $pages; $i++)
{
    if (isset($_GET['page']) && $_GET['page'] == $i)
    {
        echo $i . " ";
    }
    else
    {
        echo "<a href='authors.php?page=$i'>$i</a> ";
    }
}
echo "</p><br>";

echo "<table style='width:100%;'>";
echo "<tr><th>Name</th><th>Books</td></tr>";

for ($i = $start; $i < $end; $i++)
{
    if ($i == $res->num_rows) { break; }
     
    $res->data_seek($i);
    $row = $res->fetch_array();

    // Table row
    echo "<tr>";

    // Author name
    $author_id = $row['id'];
    $name = $row['first_name'] . " " . $row['last_name'];

    echo "<td>";
    echo "<a href=list.php?aut=$author_id>" . $name . "</a>";
    echo "</td>";

    $sql = "SELECT * FROM books_authors WHERE books_authors.author_id=$author_id";
    if (!($bres = $mysqli->query($sql)))
        die("Error: " . $mysqli->error);

    echo "<td>" . $bres->num_rows . "</td>";

    echo "</tr>";
}

echo "</table>";

include("footer.php");

?>

