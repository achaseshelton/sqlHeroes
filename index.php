<?php

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "heroes_db";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// create
function createHero()
{
    if (!isset($_POST['name'])) {
        echo "error 422, no name set.";
        return;
    };
    if (!isset($_POST['about_me'])) {
        echo "errror 422, no about_me set.";
        return;
    };
    if (!isset($_POST['biography'])) {
        echo "errror 422, no biography set.";
        return;
    };
    $name = $_POST['name'];
    $about_me = $_POST['about_me'];
    $biography = $_POST['biography'];

    $sql = "INSERT INTO heroes (name, about_me, biography) VALUES ('$name', '$about_me', '$biography')";

    global $conn;
    $hero_id = -1;
    if ($conn->query($sql) === TRUE) {
        $hero_id = $conn->insert_id;
        echo "New hero created successfully\n";
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
        return;
    }

    if (isset($_POST['ability_type'])) {
        $ability_id = $_POST['ability_type'];
        $sql = "INSERT INTO abilities (hero_id, ability_id) VALUES ('$hero_id', '$ability_id')";
    } else {
        echo "no ability assigned";
        return;
    };

    if ($conn->query($sql) === TRUE) {
        echo "New hero assigned ability $ability_id";
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }
}

function createAbility()
{
    if (!isset($_POST['ability_type'])) {
        echo "Error 422: no ability type set";
        return;
    }
    $ability_type = $_POST['ability_type'];

    $sql = "INSERT INTO ability_type (ability) VALUES ('$ability_type')";

    global $conn;
    if ($conn->query($sql) === TRUE) {
        echo "New ability created successfully";
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }
}

// read
function readAllHeroes()
{

    $sql = "SELECT
    heroes.name,
    heroes.about_me,
    heroes.biography,
    GROUP_CONCAT(ability_type.ability) AS abilities
    FROM ((heroes
    INNER JOIN abilities ON heroes.id = abilities.hero_id)
    INNER JOIN ability_type ON abilities.ability_id = ability_type.id)
    GROUP BY heroes.id";

    global $conn;
    $result = $conn->query($sql);
    if ($result->num_rows > 0) {
        $newArr = array();
        while ($db_field = mysqli_fetch_assoc($result)) {
            $newArr[] = $db_field;
        }
        echo json_encode($newArr);
    } else {
        echo "no results found: " . $conn->error;
    }
}

function readHero()
{
    if (!isset($_GET["id"])) {
        echo "Error 422: no id specified";
        return;
    };

    $id = $_GET["id"];

    $sql = "SELECT
    heroes.name,
    heroes.about_me,
    heroes.biography,
    GROUP_CONCAT(ability_type.ability) AS abilities
    FROM ((heroes
    INNER JOIN abilities ON heroes.id = abilities.hero_id)
    INNER JOIN ability_type ON abilities.ability_id = ability_type.id)
    WHERE heroes.id = $id
    GROUP BY heroes.id";

    global $conn;
    $result = $conn->query($sql);
    if ($result->num_rows > 0) {
        $newArr = array();
        while ($db_field = mysqli_fetch_assoc($result)) {
            $newArr[] = $db_field;
        }
        echo json_encode($newArr);
    } else {
        echo "Error finding hero: " . $conn->error;
    }
}

// update
function updateHero()
{
    if (!isset($_POST["id"])) {
        echo "errror 422, no id set.";
        return;
    };
    $sql = "UPDATE heroes SET ";
    if (isset($_POST["name"])) {
        $name = $_POST["name"];
        $sql .= "name = '$name', ";
    };
    if (isset($_POST["about_me"])) {
        $about_me = $_POST["about_me"];
        $sql .= "about_me = '$about_me', ";
    };
    if (isset($_POST["biography"])) {
        $biography = $_POST["biography"];
        $sql .= "biography = '$biography', ";
    };
    
    $id = $_POST["id"];
    
    $tempsql = rtrim($sql, ", ");
    $sql = $tempsql . " WHERE id='$id'";
    
    global $conn;
    if ($conn->query($sql) === TRUE) {
        echo "Hero updated successfully";
    } else {
        echo "Error updating record: " . $conn->error;
    }
    $sql2 = "UPDATE ablities SET ";
    if (isset($_POST["ability_id"])) {
        $ability_id = $_POST["ability_id"];
        $sql2 .= "ability_id = '$ability_id'";
    } else {
        return;
    }
    $sql2 .= " WHERE hero_id = '$id'";
    if ($conn->query($sql2) === TRUE) {
        echo "Hero updated successfully";
    } else {
        echo "Error updating record: " . $conn->error;
    }
}

function deleteHero()
{
    if (!isset($_GET["id"])) {
        echo "Error 422: no id specified";
        return;
    };

    $id = $_GET["id"];

    $sql = "DELETE FROM heroes WHERE id=$id";
    $sql2 = "DELETE FROM abilities WHERE hero_id=$id";

    global $conn;
    if ($conn->query($sql) === TRUE) {
        echo "Record deleted successfully";
    } else {
        echo "Error deleting record: " . $conn->error;
        return;
    }
    if ($conn->query($sql2) === TRUE) {
        echo "Record deleted successfully";
    } else {
        echo "Error deleting record: " . $conn->error;
    }
}
if (isset($_GET["route"])) {
    switch ($_GET["route"]) {
        case "createhero":
            createHero();
            break;
        case "read":
            readHero();
            break;
        case "update":
            updateHero();
            break;
        case "delete":
            deleteHero();
            break;
        case "readAll":
            readAllHeroes();
            break;
        case "createability":
            createAbility();
            break;
        default:
    }
}
$conn->close();
