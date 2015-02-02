<?php

include("header.php");


function build_tree($categories, $parent_id=0)
{
    $tree = array();
    foreach($categories as $category)
    {
        if ($category['parent_id'] == $parent_id) 
        {
            unset($categories[$category]);
            $tree[] = array(
                'category' => $category,
                'children' => build_tree($categories, $category['id'])
            );
        }
    }
    return empty($tree) ? null : $tree;
}

function print_tree($tree)
{
    if (count($tree) > 0)
    {
        echo "<ul>";
        foreach($tree as $node)
        {
            $name = $node['category']['name'];
            $id = $node['category']['id'];
            echo "<li>";
            echo "<a href=books.php?cat=$id>" . $name . "</a>";
            print_tree($node['children']);
            echo "</li>";
        }
        echo "</ul>";
    }
}


$sql = "SELECT * FROM categories ORDER BY name ASC";
if (!($res = $mysqli->query($sql)))
    die("Error: " . $mysqli->error);


$categories = array();

while ($category = $res->fetch_assoc()) 
{
    $categories[] = $category;
}

$tree = build_tree($categories);
print_tree($tree);

include("footer.php");

?>

