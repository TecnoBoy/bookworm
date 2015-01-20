<?php 

include("header.php"); 

echo "<br><br>";

//echo '<img src="images/bg1.jpg" width="400" height="400" style="float:right">';


echo "<h1>Summary</h1><br>";

$sql = "SELECT COUNT(*) FROM books";
if (!($res = $mysqli->query($sql)))
    die("Error: " . $mysqli->error);
$row = $res->fetch_row();
echo "Books: " . $row[0] . "</br>";

$sql = "SELECT COUNT(*) FROM authors";
if (!($res = $mysqli->query($sql)))
    die("Error: " . $mysqli->error);
$row = $res->fetch_row();
echo "Authors: " . $row[0] . "</br>";

$sql = "SELECT COUNT(*) FROM categories";
if (!($res = $mysqli->query($sql)))
    die("Error: " . $mysqli->error);
$row = $res->fetch_row();
echo "Categories: " . $row[0] . "</br>";

echo "<br>";
echo "<h1>Last Inserted</h1>";

$sql = "SELECT * FROM books ORDER BY inserted DESC LIMIT 10";
if (!($res = $mysqli->query($sql)))
    die("Error: " . $mysqli->error);
echo "<ul>";
for ($i = 0; $i < $res->num_rows; $i++)
{
    $row = $res->fetch_assoc();
    $title = $row['title'];
    $id = $row['id'];
    echo "<li>";
    echo "<a href=view.php?id=$id>$title</br>";
    echo "</li>";
}
echo "</ul>";

include("footer.php");

?>
