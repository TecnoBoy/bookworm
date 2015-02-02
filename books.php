<?php

include("header.php");

$per_page = 20;

$NR_selected = ($_POST['book_status'] == 'not_read' ? 'selected' : '');
$R_selected = ($_POST['book_status'] == 'read' ? 'selected' : '');
echo '
    <div id="input-area">
    <form method="post" action="books.php">
        <label>Title:</label>
        <input type="text" name="title" value="'.htmlspecialchars($_POST['title']).'">
        
        <label>Status:</label>
        <select name="book_status">
			<option> </option>
            <option value="not_read" '.$NR_selected.'>Not Read</option>
            <option value="read" '.$R_selected.'>Read</option>
        </select>
        <input type="submit" name="submit" value="Search" class="submit-button">
    </form>
    </div>
    ';

if ($_POST['title'] or $_POST['book_status'])
{
    $title = $_POST['title'];
    $sql = "SELECT * FROM books WHERE title LIKE '%".mysql_real_escape_string($title)."%'";
	if ($_POST['book_status']){
		$book_status = $_POST['book_status'];
		$sql = $sql . "AND status='".mysql_real_escape_string($book_status)."'";
	}
}
else if ($_GET['cat'])
{
    $category_id = $_GET['cat'];
    $sql = "SELECT * FROM books INNER JOIN 
                (SELECT books_categories.book_id FROM books_categories 
                INNER JOIN categories ON books_categories.category_id=categories.id 
                WHERE category_id=".mysql_real_escape_string($category_id).") res 
            ON books.id=res.book_id";
}
else if ($_GET['aut'])
{
    $author_id = $_GET['aut'];
    $sql = "SELECT * FROM books INNER JOIN 
                (SELECT books_authors.book_id FROM books_authors 
                INNER JOIN authors ON books_authors.author_id=authors.id
                WHERE author_id=".mysql_real_escape_string($author_id).") res 
            ON books.id=res.book_id";
}
else
{
    $sql = "SELECT * FROM books ORDER BY title ASC";
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
    if ($_GET['page'] == $i)
    {
        echo $i . " ";
    }
    else
    {
        $link = "<a href=books.php?page=$i";
        if($category_id)
            $link .= "&cat=".htmlspecialchars($category_id);
        else if($author_id)
            $link .= "&aut=".htmlspecialchars($author_id);
        $link .= ">$i </a>";
        echo $link;
    }
}
echo "</p><br>";

echo "<table style='width:100%;'>";
echo "<tr> <th>Title</th><th>Edition</th><th>Authors</th><th>Categories</th> </tr>";

for ($i = $start; $i < $end; $i++)
{
    if ($i == $res->num_rows) { break; }
     
    $res->data_seek($i);
    $row = $res->fetch_array();

    // Table row
    echo "<tr>";

    // Book title
    
    $book_id = $row['id'];
    $book_title = $row['title'];
    $book_edition = $row['edition'];

    echo "<td>";
    echo "<a href=view.php?id=".htmlspecialchars($book_id).">" . htmlspecialchars($book_title) . "</a>";
    echo "</td>";

    echo "<td>" . htmlspecialchars($book_edition) . "</td>";

    // Book authors
    
    $sql = "SELECT authors.id, authors.first_name, authors.last_name
        FROM authors INNER JOIN books_authors
        ON authors.id=books_authors.author_id
        WHERE books_authors.book_id=".mysql_real_escape_string($book_id);
    if (!($ares = $mysqli->query($sql)))
        die("Error: " . $mysqli->error);

    echo "<td>";
    for ($j = 0; $j < $ares->num_rows; $j++)
    {
        $arow = $ares->fetch_array();
        $first = $arow['first_name'];
        $last = $arow['last_name'];
        $id = $arow['id'];        
        echo "<a href=books.php?page=1&aut=".htmlspecialchars($id).">";
        if (strcmp($first,$last) != 0)
            echo $first . " " . $last;
        else
            echo $last;
        echo "</a>";
        if ($j < $ares->num_rows-1)
            echo ", ";
    }
    echo "</td>";

    // Book categories
    $sql = "SELECT categories.id, categories.name
        FROM categories INNER JOIN books_categories
        ON categories.id=books_categories.category_id
        WHERE books_categories.book_id=".mysql_real_escape_string($book_id);
    if (!($cres = $mysqli->query($sql)))
        die("Error: " . $mysqli->error);

    echo "<td>";
    for ($j = 0; $j < $cres->num_rows; $j++)
    {
        $crow = $cres->fetch_array();
        $name = $crow['name'];
        $id = $crow['id']; 
        echo "<a href=books.php?page=1&cat=".htmlspecialchars($id).">";
        echo $name;
        echo "</a>";
        if ($j < $cres->num_rows-1)
            echo ", ";
    }
    echo "</td>";

    echo "</tr>";
}

echo "</table>";

include("footer.php");

?>

