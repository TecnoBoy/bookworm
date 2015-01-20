<?php

include("header.php");


$book_id = $_GET['id'];

$sql = "SELECT * FROM books WHERE id=$book_id";
if (!($res = $mysqli->query($sql)))
    die("Error: " . $mysqli->error);

$brow = $res->fetch_array();

echo "<h1>" . $brow['title'] . "</h1>";

$sql = "SELECT authors.first_name, authors.last_name
    FROM authors INNER JOIN books_authors 
    ON authors.id=books_authors.author_id
    WHERE books_authors.book_id=$book_id"; 
if (!($ares = $mysqli->query($sql)))
    die("Error: " . $mysqli->error);

echo "</br><h3>";
for ($i = 0; $i < $ares->num_rows; $i++)
{
    $arow = $ares->fetch_array();
    $first = $arow['first_name'];
    $last = $arow['last_name'];
    
    if (strcmp($first, $last) != 0)
        echo $first . " " . $last;
    else
        echo $last;
    
    if ($i < $ares->num_rows-1)
        echo ", ";
}
echo "</h3>";

echo "<br><br>";

$image = htmlspecialchars($brow['image'], ENT_QUOTES);

echo "<img src='$config->books_path/$image' width=200 height=280/><br><br>";

// Categories

echo "Categories: ";

$sql = "SELECT categories.name
    FROM categories INNER JOIN books_categories 
    ON categories.id=books_categories.category_id
    WHERE books_categories.book_id=$book_id"; 
if (!($cres = $mysqli->query($sql)))
    die("Error: " . $mysqli->error);

for ($i = 0; $i < $cres->num_rows; $i++)
{
    $crow = $cres->fetch_array();
    echo $crow['name'];
    
    if ($i < $cres->num_rows-1)
        echo ", ";
}
echo "<br>";

// Edition
echo "Edition: " . $brow['edition'] . "</br>";

// Type
echo "Type: ";
switch ($brow['type'])
{
case "book": 
    echo "Book"; break;
case "article":
    echo "Article"; break;
case "notes":
    echo "Notes"; break;
case "manual":
    echo "Manual"; break;
case "normative":
    echo "Normative"; break;
}
echo "</br>";

if ($_SESSION['login'] == 1)
{
    // Status
    echo "Status : ";
    switch ($brow['status'])
    {
    case "read": 
        echo "Read"; break;
    case "not_read": 
        echo "Not Read"; break;
    case "reading": 
        echo "Reading"; break;
    case "almost":
        echo "Almost Read"; break;
    case "partially":
        echo "Partially Read"; break;
    }
    echo "</br>";
}

// Language
echo "Language: ";
switch ($brow['language'])
{
case "en":
    echo "English"; break;
case "it":
    echo "Italian"; break;
}
echo "</br>";

// Insert date
echo "Insert Date: " . $brow['inserted'] . "</br>";

echo "</br>";

if ($_SESSION['login']==1 && $brow['file'] != NULL)
{
    $filename = $brow['file'];
    $filepath = $config->books_path . "/" . $filename;
    $size = filesize($filepath);
    $filepath = htmlspecialchars($filepath, ENT_QUOTES);

    if ($size < 1024)
        $size = $size . " b";
    else if ($size < (1024*1024))
        $size = round($size/1024, 2) . " Kb";
    else if ($size < (1024*1024*1024))
        $size = round($size/(1024*1024), 2) . " Mb";
    else
        $size = 0;
    if ($size != 0)
    {
        printf("name: %s<br>", $filepath);
        $downlink = preg_replace("/ /", "+", $filepath);
        $downlink = "download.php?filepath=" . $downlink;
        echo "<a href=$downlink>Download</a>";
        echo " (" . $size . ")" . "<br>";
    }
    else
    {
        echo "File too Big: " . $size . "bytes</br>";
    }
}


include("footer.php");

?>

