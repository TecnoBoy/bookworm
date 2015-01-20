<?php

include("header.php");

if ($_SESSION['login']==0)
    header("Location: index.php");




if ($_POST['book_title'] && $_POST['authors'] && $_POST['categories'])
{
    
    $book_title = trim($mysqli->real_escape_string($_POST['book_title']));
    $book_status = trim($mysqli->real_escape_string($_POST['book_status']));
    $book_file = trim($mysqli->real_escape_string($_POST['book_file']));
    $book_type = trim($mysqli->real_escape_string($_POST['book_type']));
    $book_language= trim($mysqli->real_escape_string($_POST['book_language']));

    if ($_POST['book_edition'])
    {
        $book_edition = trim($mysqli->real_escape_string($_POST['book_edition']));
        if (!is_numeric($book_edition)) 
            $book_edition = "1";
    }
    else
    {
        $book_edition = "1";
    }

    if ($_POST['book_image'])
    {
        $book_image = trim($mysqli->real_escape_string($_POST['book_image']));
    }
    else
    {
        $book_image = "0000/default.jpg";
    }

    $sql = "INSERT INTO books (title, edition, status, type, language, file, image) 
        VALUES ('$book_title', $book_edition, '$book_status', '$book_type', 
                '$book_language', '$book_file', '$book_image')";

    if (!$mysqli->query($sql))
        die("Error: " . $mysqli->error);

    $book_id = $mysqli->insert_id;

    // Process authors

    $authors = $mysqli->real_escape_string($_POST['authors']);
    $authors_ar = explode(',', $authors);
    
    foreach ($authors_ar as $author)
    {
        $author = trim($author);    // remove start and end white spaces
        $author_ar = explode(' ', $author, 2);
        
        $author_first_name = ucfirst(trim($author_ar[0]));
        $author_last_name = ucfirst(trim($author_ar[1]));
        if ($author_last_name == NULL)
            $author_last_name = $author_first_name;

        // Find out if the author already exists 
        $sql = "SELECT id FROM authors 
            WHERE first_name='$author_first_name' 
            AND last_name='$author_last_name'";

        if (!($res = $mysqli->query($sql)))
            die("Error" . $mysqli->error);

        if ($res->num_rows == 0)
        {
            $sql = "INSERT INTO authors (first_name, last_name)
                VALUES ('$author_first_name','$author_last_name')";
            if (!$mysqli->query($sql))
                die("Error: " . $mysqli->error);
 
            $author_id = $mysqli->insert_id;
        }
        else
        {
            $row = $res->fetch_row();
            $author_id = $row[0];
        }

        $sql = "INSERT INTO books_authors (book_id, author_id)
            VALUES ('$book_id', '$author_id')";
        if (!$mysqli->query($sql))
            die("Error: " . $mysqli->error);
    }
    
    // Process Categories

    $categories = $mysqli->real_escape_string($_POST['categories']);
    $categories_ar = explode(',', $categories);
    
    foreach ($categories_ar as $category)
    {
        $hier_ar = explode(':', $category); 

        $root_name = $hier_ar[0];
        $root_name = ucfirst(trim($root_name));
        $sql = "SELECT id, parent_id FROM categories 
            WHERE name='$root_name'";
        if (!($res = $mysqli->query($sql)))
            die("Error" . $mysqli->error);

        if ($res->num_rows == 0)
        {
            $parent_id = 0;
        }
        else if ($res->num_rows == 1)
        {
            $row = $res->fetch_assoc();
            $parent_id = $row['parent_id'];
        }
        else
            die("Error: Ambiguity in category name\n");

        foreach ($hier_ar as $hier_elem)
        {
            $name = ucfirst(trim($hier_elem));    // remove start and end white spaces

            // Find out if the category already exists 
            $sql = "SELECT id FROM categories 
                WHERE name='$name' AND parent_id=$parent_id";

            if (!($res = $mysqli->query($sql)))
                die("Error" . $mysqli->error);

            if ($res->num_rows == 0)
            {
                $sql = "INSERT INTO categories (name, parent_id) 
                    VALUES ('$name', $parent_id)";
                if (!$mysqli->query($sql))
                    die("Error: " . $mysqli->error);
     
                $category_id = $mysqli->insert_id;
            
                printf("[NEW] ");
            }
            else
            {
                $row = $res->fetch_assoc();
                $category_id = $row['id'];
                printf("[OLD] ");
            }

            printf("name: %s, id: %d, parent: %d<br>", $name, $category_id, $parent_id);

            $parent_id = $category_id;
        }

        $sql = "INSERT INTO books_categories (book_id, category_id)
                VALUES ('$book_id', '$category_id')";
        if (!$mysqli->query($sql))
            die("Error: " . $mysqli->error);
    }

    header("Refresh: 5, view.php?id=$book_id");
}
else
{
    echo '
    <div id="input-area">
    <form action="insert.php" method="post">
        <label>Title (*):</label>
        <input type="text" name="book_title">
        
        <label>Edition:</label>
        <input type="text" name="book_edition">
        
        <label>Authors (*):</label>
        <input type="text" name="authors">
       
        <label>Categories (*):</label>
        <input type="text" name="categories"> 

        <label>Status:</label>
        <select name="book_status">
            <option value="not_read">Not Read</option>
            <option value="read">Read</option>    
            <option value="reading">Reading</option>
            <option value="almost">Almost</option>    
            <option value="partially">Parially</option>    
        </select>

        <label>Type</label>
        <select name="book_type">
            <option value="book">Book</option>
            <option value="article">Article</option>    
            <option value="notes">Notes</option>
            <option value="manual">Manual</option>
            <option value="normative">Normative</option>    
        </select>

        <label>Language</label>
        <select name="book_language">
            <option value="en">English</option>
            <option value="it">Italian</option>
        </select>  

        <label>File:</label>
        <input type="text" name="book_file">

        <label>Image:</label>
        <input type="text" name="book_image">
     

        <input type="submit" name="submit" value="Submit" class="submit-button"> 
    </form>
    <div style="clear: both;"></div>
    </div>
    <br><br>
    * Title, Authors and Categories are mandatory.<br>
    ** Different Authors and Categories are comma separated.
    ';
}

include("footer.php");
?>

