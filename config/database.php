<?php

function LoadSQL() {
    include "connect.php";
    require "../config/env.php";

    $sql = file_get_contents('../config/projekt_kllinika.sql');
    $pdo->exec($sql);

    header("Location: $URL/klinika/account/index.php?done=true");
    exit();
}

function DropTableSQL() {
    include "connect.php";
    require "../config/env.php";

    $table_list = ['accounts','appointment','appointment_types','breed','customer','medicine','newsletter','owner','perrmission','specialisation','species','surgery','surgery_medicine','treatment'];
    $sql = "";

    for ($index=0; $index < count($table_list); $index++) { 
        $sql .= "TRUNCATE TABLE $table_list[$index];";
    }

    $pdo->exec($sql);
    header("Location: $URL/klinika/account/index.php?done=true");

    exit();
}

function exist($from, $name, $has) {
    include "connect.php";
    $db = $pdo->query("SELECT COUNT(*) FROM $from WHERE $name = '$has'");
    return (bool) $db->fetchColumn();
};

function Login($first_name, $last_name)
{
    include "connect.php";

    $db = $pdo->query("SELECT COUNT(*) FROM accounts WHERE first_name='$first_name' AND last_name='$last_name'");

    if(!$db->fetchColumn()) {
        header("Location: $URL/klinika/account/login/index.php?try_again");
        exit();
    };

    header("Location: $URL/klinika/account/index.php");
    exit();    
};

function Newsletter($name, $email) {
    if(exist('newsletter', 'email', $email))
    {
        header("Location: $URL/klinika?newsletter=email");
        exit();
    };

    include "connect.php";

    $sql = 'INSERT INTO newsletter (name, email) VALUES (:name, :email);';
    $db = $pdo->prepare($sql);
    $db->execute([
        'name' => $name,
        'email' => $email
    ]);

    header("Location: $URL/klinika?newsletter=done");
};

function Remove($where, $remove_id, $col = 'id') {
    if(!exist($where, $col, $remove_id))
    {
        exit(404);
    };

    include "connect.php";

    $sql = "DELETE FROM $where WHERE $where.$col = $remove_id LIMIT 1;";
    $db = $pdo->query($sql);

    header("Location: $URL/klinika/account/index.php?done=true#$where");
};

function Add($insert, $data_values) {
    include "connect.php";

    $sql = "INSERT INTO $insert (".implode(", ", array_keys($data_values)).") VALUES (".implode(", ", substr_replace(array_keys($data_values), ':', 0, 0)).");";
    $db = $pdo->prepare($sql);
    $db->execute($data_values);

    header("Location: $URL/klinika/account/index.php?done=true#$insert");

    return $pdo->lastInsertId();
};

function mapped_implode($glue, $array, $symbol = '=', $apostrophe = '`', $apostrophe2 = "'") {
    return implode($glue, array_map(
            function($k, $v) use($symbol, $apostrophe, $apostrophe2) {
                return $apostrophe . $k . $apostrophe . $symbol . $apostrophe2 . $v . $apostrophe2;
            },
                array_keys($array),
                array_values($array)
            )
        );
};

function Update($update, $id, $data_values) {
    include "connect.php";

    $sql = "UPDATE $update SET ".mapped_implode(', ', $data_values, ' = ')." WHERE $update.`id` = $id";
    $db = $pdo->prepare($sql);
    $db->execute();

    header("Location: $URL/klinika/account/index.php?done=true#$update");
};

function CreateInput($type, $name, $placeholder, $value = "") {
    return "<input class='input' type='$type' name='$name' placeholder='$placeholder' value='$value'/>";
};

function DesignAppointment($customer_id) {
    include "connect.php";

    $sql = "SELECT `customer`.`id`, `customer`.`name`, `customer`.`date_of_birth`, `customer`.`weight`, `customer`.`height`, `owner`.`first_name`, `owner`.`second_name`, `owner`.`last_name`, `breed`.`name` AS `breed_name`, `species`.`name` AS `species_name` FROM `customer`, `breed`, `species`, `owner` WHERE `customer`.`breed_id` = `breed`.`id` AND `customer`.`species_id` = `species`.`id` AND `customer`.`owner_id` = `owner`.`id` AND `customer`.`id` = $customer_id LIMIT 1";
    $db = $pdo->query($sql);

    while ($row = $db->fetch()) {

        echo "<div class='content'>";
        echo "<form action='../assets/worker.php' method='GET'>";
        echo GetAppointmentTypeList();
        echo GetAccountList();
        echo "<textarea name='description' placeholder='description'></textarea>";
        echo CreateInput("time", "time", "time");
        echo CreateInput("date", "date", "date");
        echo CreateInput("hidden", "customer_id", "", $customer_id);
        echo CreateInput("hidden", "insert", "", "appointment");
        echo CreateInput("submit", "", "", "Submit");

        echo "</form>";
        echo "<div class='data''>";
    
        echo "<span><p>Name:</p><p>$row[name]</p></span>";
        echo "<span><p>Species:</p><p>$row[species_name]</p></span>";
        echo "<span><p>Breed:</p><p>$row[breed_name]</p></span>";
        echo "<span><p>Weight/height:</p><p>$row[weight]/$row[height]</p></span>";
        echo "<span><p>Owner:</p><p>$row[first_name] $row[second_name] $row[last_name]</p></span>";

        echo "</div>";
        echo "</div>";
    }
}

function DesignTreatment($customer_id) {
    include "connect.php";

    $sql = "SELECT `customer`.`id`, `customer`.`name`, `customer`.`date_of_birth`, `customer`.`weight`, `customer`.`height`, `owner`.`first_name`, `owner`.`second_name`, `owner`.`last_name`, `breed`.`name` AS `breed_name`, `species`.`name` AS `species_name` FROM `customer`, `breed`, `species`, `owner` WHERE `customer`.`breed_id` = `breed`.`id` AND `customer`.`species_id` = `species`.`id` AND `customer`.`owner_id` = `owner`.`id` AND `customer`.`id` = $customer_id LIMIT 1";
    $db = $pdo->query($sql);

    while ($row = $db->fetch()) {
        echo "<div class='content'>";
        echo "<form action='../assets/worker.php' method='GET'>";
        echo CreateInput("date", "date_from", "date");
        echo CreateInput("date", "date_to", "date");
        echo CreateInput("hidden", "customer_id", "", $customer_id);
        echo CreateInput("hidden", "insert", "", "treatment");
        echo CreateInput("submit", "", "", "Submit");

        echo "</form>";
        echo "<div class='data''>";
    
        echo "<span><p>Name:</p><p>$row[name]</p></span>";
        echo "<span><p>Species:</p><p>$row[species_name]</p></span>";
        echo "<span><p>Breed:</p><p>$row[breed_name]</p></span>";
        echo "<span><p>Weight/height:</p><p>$row[weight]/$row[height]</p></span>";
        echo "<span><p>Owner:</p><p>$row[first_name] $row[second_name] $row[last_name]</p></span>";

        echo "</div>";
        echo "</div>";
    }
}

function DesignSurgery($customer_id) {
    include "connect.php";

    $sql = "SELECT `customer`.`id`, `customer`.`name`, `customer`.`date_of_birth`, `customer`.`weight`, `customer`.`height`, `owner`.`first_name`, `owner`.`second_name`, `owner`.`last_name`, `breed`.`name` AS `breed_name`, `species`.`name` AS `species_name` FROM `customer`, `breed`, `species`, `owner` WHERE `customer`.`breed_id` = `breed`.`id` AND `customer`.`species_id` = `species`.`id` AND `customer`.`owner_id` = `owner`.`id` AND `customer`.`id` = $customer_id LIMIT 1";
    $db = $pdo->query($sql);

    while ($row = $db->fetch()) {
        echo "<div class='content'>";
        echo "<form action='../assets/worker.php' method='GET'>";
        echo CreateInput("text", "name", "Name");
        echo GetCustomerList();
        echo CreateInput("number", "price", "0");
        echo CreateInput("time", "time", "time");
        echo CreateInput("date", "date", "date");
        echo CreateInput("hidden", "customer_id", "", $customer_id);
        echo CreateInput("hidden", "insert", "", "surgery");
        echo CreateInput("submit", "", "", "Submit");

        echo "</form>";
        echo "<div class='data''>";
    
        echo "<span><p>Name:</p><p>$row[name]</p></span>";
        echo "<span><p>Species:</p><p>$row[species_name]</p></span>";
        echo "<span><p>Breed:</p><p>$row[breed_name]</p></span>";
        echo "<span><p>Weight/height:</p><p>$row[weight]/$row[height]</p></span>";
        echo "<span><p>Owner:</p><p>$row[first_name] $row[second_name] $row[last_name]</p></span>";

        echo "</div>";
        echo "</div>";
    }
}

function DesignMedicine($surgery_id) {
    include "connect.php";

    echo "<div class='content'>";
    echo "<form action='../assets/worker.php' method='GET'>";
    echo CreateInput("text", "name", "name");
    echo CreateInput("number", "price", "price");
    echo CreateInput("number", "amount", "amount");
    echo CreateInput("hidden", "surgery_id", "", $surgery_id);
    echo CreateInput("hidden", "insert", "", "medicine");
    echo CreateInput("submit", "", "", "Submit");
    echo "</form>";

}

function GetSpeciesList($classname = '') {
    include "connect.php";

    $sql = 'SELECT `id`,`name` FROM `species`';
    $db = $pdo->query($sql);

    $result = "";

    while ($row = $db->fetch()) {
        $result .= "<option value='".$row['id']."'>".$row['name']."</option>";
    };

    return "<select name='species_id' class='".$classname."'>" . $result . "</select>";
};

function GetOwnerList() {
    include "connect.php";

    $sql = 'SELECT `id`, `first_name`, `second_name`, `last_name` FROM `owner`';
    $db = $pdo->query($sql);

    $result = "";

    while ($row = $db->fetch()) {
        $result .= "<option class='owner_id' value='".$row['id']."'>".$row['first_name']." ".$row['second_name']." ".$row['last_name']."</option>";
    };

    return "<select name='owner_id'><option hidden></option>" . $result . "</select>";
};

function GetPermissionList() {
    include "connect.php";

    $sql = 'SELECT `id`, `name` FROM `perrmission`';
    $db = $pdo->query($sql);

    $result = "";

    while ($row = $db->fetch()) {
        $result .= "<option value='".$row['id']."'>".$row['name']."</option>";
    };

    return "<select name='permission_id'>" . $result . "</select>";
};

function GetSpecialisationList() {
    include "connect.php";

    $sql = 'SELECT `id`, `name` FROM `specialisation`';
    $db = $pdo->query($sql);

    $result = "";

    while ($row = $db->fetch()) {
        $result .= "<option value='".$row['id']."'>".$row['name']."</option>";
    };

    return "<select name='specialisation_id'>" . $result . "</select>";
};

function GetCustomerList($selected_id = -1) {
    include "connect.php";

    $sql = 'SELECT `id`, `owner_id`, `name` FROM `customer`';
    $db = $pdo->query($sql);

    $result = "";
    $selected = "";

    while ($row = $db->fetch()) {
        if($selected_id == $row['id'])
            $selected = "selected";
        else $selected = "";

        $result .= "<option $selected class='owner_id_".$row['owner_id']."' value='".$row['id']."'>".$row['name']."</option>";
    };

    return "<select name='customer_id'>" . $result . "</select>";
};

function GetAccountList() {
    include "connect.php";

    $sql = 'SELECT `id`, `first_name`, `second_name`, `last_name` FROM `accounts`';
    $db = $pdo->query($sql);

    $result = "";

    while ($row = $db->fetch()) {
        $result .= "<option value='".$row['id']."'>".$row['first_name']." ".$row['second_name']." ".$row['last_name']."</option>";
    };

    return "<select name='account_id'>" . $result . "</select>";
};

function GetBreedList() {
    include "connect.php";

    $sql = 'SELECT `id`, `species_id`, `name` FROM `breed`';
    $db = $pdo->query($sql);

    $result = "";

    while ($row = $db->fetch()) {
        $result .= "<option class='species_id_".$row['species_id']."' value='".$row['id']."'>".$row['name']."</option>";
    };

    return "<select name='breed_id' class='breed_id'><option hidden></option>" . $result . "</select>";
};

function GetAppointmentTypeList() {
    include "connect.php";

    $sql = 'SELECT `id`, `name` FROM `appointment_types`';
    $db = $pdo->query($sql);

    $result = "";

    while ($row = $db->fetch()) {
        $result .= "<option value='".$row['id']."'>".$row['name']."</option>";
    };

    return "<select name='appointment_type_id'><option hidden></option>" . $result . "</select>";
};

function GetNewsletter() {
    include "connect.php";

    $section_name = "newsletter";

    $desc = "";
    $order_by = "";

    $_rows = ['name', 'email'];
    $_edit_row = $_rows;

    if(isset($_GET['order_by']) && isset($_GET['order_by']))
    {
        for ($index=0; $index < count($_rows); $index++)
        {
            if($_GET['order_by'] == $_rows[$index])
            {
                $order_by = " ORDER BY ".$_GET['order_by'];
                if(isset($_GET['desc']) && $_GET['desc'] == 'true') $order_by .= " DESC";
                else $desc = "&desc=true";
                
                break;
            }
        }
    };

    $sql = "SELECT `id`, `name`, `email` FROM `$section_name`".$order_by;
    $db = $pdo->query($sql);      

    echo "<table><tbody>";
    echo "<tr>";

    for ($index=0; $index < count($_rows); $index++)
        echo "<th><a href='?order_by=$_rows[$index]".$desc."#$section_name'>$_rows[$index]</a></th>";

    echo "<th>Options</th>";
    echo "</tr>";

    while ($row = $db->fetch()) {

        if(isset($_GET["where"]) && $_GET["where"] == $section_name && isset($_GET["update_id"]) && $_GET["update_id"] == $row['id'])
        {
            for ($index=0; $index < count($_rows); $index++)
                $_edit_row[$index] = $row[$_rows[$index]];
            echo "<tr id='edit_".$row['id']."' class='edit'>";
        }
        else echo "<tr>";

        for ($index=0; $index < count($_rows); $index++)
            echo "<th>".$row[$_rows[$index]]."</th>";

        echo "<th><a href='?where=$section_name&update_id=".$row['id']."#$section_name'><button>Edit</button></a> ";
        echo "<a href='../assets/worker.php?where=$section_name&remove_id=".$row['id']."#$section_name'><button>Remove</button></a></th>";

        echo "</tr>";
    };

    echo "<tr><form action='../assets/worker.php' method='GET'>";

    echo "<th>" . CreateInput('text', $_rows[0], $_edit_row[0]) . "</th>";
    echo "<th>" . CreateInput('email', $_rows[1], $_edit_row[1]) . "</th>";

    if(isset($_GET["where"]) && $_GET["where"] == $section_name && isset($_GET["update_id"]))
        echo "<th>" . CreateInput('hidden', 'update', $_GET["where"], $_GET["where"]) . CreateInput('hidden', 'update_id', $_GET["update_id"], $_GET["update_id"]) . CreateInput('submit', '', 'Update', 'Update') . "</th>";
    else
        echo "<th>" . CreateInput('hidden', 'insert', $section_name, $section_name) . CreateInput('submit', '', 'Add', 'Add') . "</th>";
    
    echo "</form></tr>";
    echo "</tbody></table>";
};

function GetMedicine() {
    include "connect.php";

    $section_name = "medicine";

    $desc = "";
    $order_by = "";

    $_rows = ['name', 'amount', 'price'];
    $_edit_row = $_rows;

    if(isset($_GET['order_by']) && isset($_GET['order_by']))
    {
        for ($index=0; $index < count($_rows); $index++)
        {
            if($_GET['order_by'] == $_rows[$index])
            {
                $order_by = " ORDER BY ".$_GET['order_by'];
                if(isset($_GET['desc']) && $_GET['desc'] == 'true') $order_by .= " DESC";
                else $desc = "&desc=true";
                
                break;
            }
        }
    };

    $sql = "SELECT `id`, `name`, `amount`, `price` FROM `$section_name`".$order_by;
    $db = $pdo->query($sql);

    echo "<table><tbody>";
    echo "<tr>";

    for ($index=0; $index < count($_rows); $index++)
        echo "<th><a href='?order_by=$_rows[$index]".$desc."#$section_name'>$_rows[$index]</a></th>";

    echo "<th>Options</th>";
    echo "</tr>";

    while ($row = $db->fetch()) {

        if(isset($_GET["where"]) && $_GET["where"] == $section_name && isset($_GET["update_id"]) && $_GET["update_id"] == $row['id'])
        {
            for ($index=0; $index < count($_rows); $index++)
                $_edit_row[$index] = $row[$_rows[$index]];
            echo "<tr id='edit_".$row['id']."' class='edit'>";
        }
        else echo "<tr>";

        for ($index=0; $index < count($_rows); $index++)
            echo "<th>".$row[$_rows[$index]]."</th>";

        echo "<th><a href='?where=$section_name&update_id=".$row['id']."#$section_name'><button>Edit</button></a> ";
        echo "<a href='../assets/worker.php?where=$section_name&remove_id=".$row['id']."#$section_name'><button>Remove</button></a></th>";

        echo "</tr>";
    };

    echo "<tr><form action='../assets/worker.php' method='GET'>";

    echo "<th>" . CreateInput('text', $_rows[0], $_edit_row[0]) . "</th>";
    echo "<th>" . CreateInput('number', $_rows[1], $_edit_row[1], $_edit_row[1]) . "</th>";
    echo "<th>" . CreateInput('number', $_rows[2], $_edit_row[2], $_edit_row[2]) . "</th>";

    if(isset($_GET["where"]) && $_GET["where"] == $section_name && isset($_GET["update_id"]))
        echo "<th>" . CreateInput('hidden', 'update', $_GET["where"], $_GET["where"]) . CreateInput('hidden', 'update_id', $_GET["update_id"], $_GET["update_id"]) . CreateInput('submit', '', 'Update', 'Update') . "</th>";
    else
        echo "<th>" . CreateInput('hidden', 'insert', $section_name, $section_name) . CreateInput('submit', '', 'Add', 'Add') . "</th>";

    echo "</form></tr>";
    echo "</tbody></table>";
};

function GetBreed() {
    include "connect.php";

    $section_name = "breed";
    
    $desc = "";
    $order_by = "";

    $_rows = ['species_name', 'breed_name', 'code'];
    $_edit_row = $_rows;

    if(isset($_GET['order_by']) && isset($_GET['order_by']))
    {
        for ($index=0; $index < count($_rows); $index++)
        {
            if($_GET['order_by'] == $_rows[$index])
            {
                $order_by = " ORDER BY ".$_GET['order_by'];
                if(isset($_GET['desc']) && $_GET['desc'] == 'true') $order_by .= " DESC";
                else $desc = "&desc=true";
                
                break;
            }
        }
    };

    $sql = "SELECT `$section_name`.`id`, `$section_name`.`code`, `$section_name`.`name` AS `breed_name`, `species`.`name` AS `species_name` FROM `$section_name`,`species` WHERE `$section_name`.`species_id` = `species`.`id`".$order_by;
    $db = $pdo->query($sql);

    echo "<table><tbody>";
    echo "<tr>";

    for ($index=0; $index < count($_rows); $index++)
        echo "<th><a href='?order_by=$_rows[$index]".$desc."#$section_name'>$_rows[$index]</a></th>";

    echo "<th>Options</th>";
    echo "</tr>";

    while ($row = $db->fetch()) {

        if(isset($_GET["where"]) && $_GET["where"] == $section_name && isset($_GET["update_id"]) && $_GET["update_id"] == $row['id'])
        {
            for ($index=0; $index < count($_rows); $index++)
                $_edit_row[$index] = $row[$_rows[$index]];
            echo "<tr id='edit_".$row['id']."' class='edit'>";
        }
        else echo "<tr>";

        for ($index=0; $index < count($_rows); $index++)
            echo "<th>".$row[$_rows[$index]]."</th>";

        echo "<th><a href='?where=$section_name&update_id=".$row['id']."#$section_name'><button>Edit</button></a> ";
        echo "<a href='../assets/worker.php?where=$section_name&remove_id=".$row['id']."#$section_name'><button>Remove</button></a></th>";

        echo "</tr>";
    };

    echo "<tr><form action='../assets/worker.php' method='GET'>";
    echo "<th>" . GetSpeciesList() . "</th>";

    echo "<th>" . CreateInput('text', 'name', $_edit_row[1]) . "</th>";
    echo "<th>" . CreateInput('text', $_rows[2], $_edit_row[2]) . "</th>";

    if(isset($_GET["where"]) && $_GET["where"] == $section_name && isset($_GET["update_id"]))
        echo "<th>" . CreateInput('hidden', 'update', $_GET["where"], $_GET["where"]) . CreateInput('hidden', 'update_id', $_GET["update_id"], $_GET["update_id"]) . CreateInput('submit', '', 'Update', 'Update') . "</th>";
    else
        echo "<th>" . CreateInput('hidden', 'insert', $section_name, $section_name) . CreateInput('submit', '', 'Add', 'Add') . "</th>";

    echo "</form></tr>";
    echo "</tbody></table>";
};

function GetSpecies() {
    include "connect.php";

    $section_name = "species";
    
    $desc = "";
    $order_by = "";

    $_rows = ['name'];
    $_edit_row = $_rows;

    if(isset($_GET['order_by']) && isset($_GET['order_by']))
    {
        for ($index=0; $index < count($_rows); $index++)
        {
            if($_GET['order_by'] == $_rows[$index])
            {
                $order_by = " ORDER BY ".$_GET['order_by'];
                if(isset($_GET['desc']) && $_GET['desc'] == 'true') $order_by .= " DESC";
                else $desc = "&desc=true";
                
                break;
            }
        }
    };

    $sql = "SELECT `id`,`name` FROM `$section_name`".$order_by;
    $db = $pdo->query($sql);

    echo "<table><tbody>";
    echo "<tr>";

    for ($index=0; $index < count($_rows); $index++)
        echo "<th><a href='?order_by=$_rows[$index]".$desc."#$section_name'>$_rows[$index]</a></th>";

    echo "<th>Options</th>";
    echo "</tr>";

    while ($row = $db->fetch()) {

        if(isset($_GET["where"]) && $_GET["where"] == $section_name && isset($_GET["update_id"]) && $_GET["update_id"] == $row['id'])
        {
            for ($index=0; $index < count($_rows); $index++)
                $_edit_row[$index] = $row[$_rows[$index]];
            echo "<tr id='edit_".$row['id']."' class='edit'>";
        }
        else echo "<tr>";

        for ($index=0; $index < count($_rows); $index++)
            echo "<th>".$row[$_rows[$index]]."</th>";

        echo "<th><a href='?where=$section_name&update_id=".$row['id']."#$section_name'><button>Edit</button></a> ";
        echo "<a href='../assets/worker.php?where=$section_name&remove_id=".$row['id']."#$section_name'><button>Remove</button></a></th>";

        echo "</tr>";
    };

    echo "<tr><form action='../assets/worker.php' method='GET'>";
    echo "<th>" . CreateInput('text', 'name', $_edit_row[0]) . "</th>";

    if(isset($_GET["where"]) && $_GET["where"] == $section_name && isset($_GET["update_id"]))
        echo "<th>" . CreateInput('hidden', 'update', $_GET["where"], $_GET["where"]) . CreateInput('hidden', 'update_id', $_GET["update_id"], $_GET["update_id"]) . CreateInput('submit', '', 'Update', 'Update') . "</th>";
    else
        echo "<th>" . CreateInput('hidden', 'insert', $section_name, $section_name) . CreateInput('submit', '', 'Add', 'Add') . "</th>";

    echo "</form></tr>";
    echo "</tbody></table>";
};

function GetSurgery() {
    include "connect.php";

    $section_name = "surgery";

    $desc = "";
    $desc_medicine = "";
    $order_by = "";
    $order_by_medicine = "";

    $_rows = ['customer_name', 'name', 'price', 'date', 'time', 'medicine'];
    $_edit_row = $_rows;

    if(isset($_GET['order_by']) && isset($_GET['order_by'])){
        if ($_GET['order_by'] == 'medicine') {
            $order_by_medicine = " ORDER BY name";
            if(isset($_GET['desc']) && $_GET['desc'] == 'true') $order_by_medicine .= " DESC";
            else $desc_medicine = "&desc=true";
        } else {
            for ($index=0; $index < count($_rows); $index++)
            {
                if($_GET['order_by'] == $_rows[$index])
                {
                    $order_by = " ORDER BY ".$_GET['order_by'];
                    if(isset($_GET['desc']) && $_GET['desc'] == 'true') $order_by .= " DESC";
                    else $desc = "&desc=true";
                    
                    break;
                }
            }
        }
    };

    $sql = "SELECT `customer`.`id` AS `customer_id`, `customer`.`name` AS `customer_name`, `surgery`.`id`,`surgery`.`name`,`surgery`.`price`,`surgery`.`date`,`surgery`.`time` FROM `$section_name`,`customer` WHERE `$section_name`.`customer_id` = `customer`.`id`".$order_by;
    $db = $pdo->query($sql);

    echo "<table><tbody>";
    echo "<tr>";

    for ($index=0; $index < count($_rows) - 1; $index++)
        echo "<th><a href='?order_by=$_rows[$index]".$desc."#$section_name'>$_rows[$index]</a></th>";
    
    echo "<th><a href='?order_by=$_rows[5]".$desc_medicine."#$section_name'>$_rows[5]</a></th>";

    echo "<th>Options</th>";
    echo "</tr>";

    while ($row = $db->fetch()) {
        
        $sql2 = "SELECT `surgery_medicine`.`id`, `medicine`.`name` FROM `surgery`,`surgery_medicine`,`medicine` WHERE `surgery_medicine`.`surgery_id` = `surgery`.`id` AND `surgery_medicine`.`medicine_id` = `medicine`.`id` AND `surgery`.`id` = $row[id]".$order_by_medicine;
        $medicine = $pdo->query($sql2);
        $medicine2 = $pdo->query($sql2);

        if(isset($_GET["where"]) && $_GET["where"] == $section_name && isset($_GET["update_id"]) && $_GET["update_id"] == $row['id'])
        {
            $_edit_row[0] = $row['customer_id'];
            for ($index=1; $index < count($_rows) - 1; $index++)
                $_edit_row[$index] = $row[$_rows[$index]];

            $med_edit = "<ul>";

            while ($med_row = $medicine->fetch()) {
                $med_edit .= "<li><a href='../assets/worker.php?where=surgery_medicine&remove_id=$med_row[id]#$section_name'>$med_row[name]</a></li>";
            }

            $_edit_row[5] = $med_edit."</ul>";
            
            echo "<tr id='edit_".$row['id']."' class='edit'>";
        }
        else echo "<tr>";

        for ($index=0; $index < count($_rows) - 1; $index++)
            echo "<th>".$row[$_rows[$index]]."</th>";

        echo "<th><ul>";
        while ($med_row = $medicine2->fetch()) {
            echo "<li>$med_row[name]</li>";
        };
        echo "<li><a href='?medicine_design=$row[id]#design'><button>Add</button></a></li>"; 
        echo "</ul></th>";

        echo "<th><a href='?where=$section_name&update_id=".$row['id']."#$section_name'><button>Edit</button></a> ";
        echo "<a href='../assets/worker.php?where=$section_name&remove_id=".$row['id']."#$section_name'><button>Remove</button></a></th>";
        echo "</tr>";
    };

    if(isset($_GET["where"]) && $_GET["where"] == $section_name && isset($_GET["update_id"])) {

        echo "<tr><form action='../assets/worker.php' method='GET'>";
        echo "<th>" . GetCustomerList($_edit_row[0]) . "</th>";

        echo "<th>" . CreateInput('text', $_rows[1], $_edit_row[1]) . "</th>";
        echo "<th>" . CreateInput('text', $_rows[2], $_edit_row[2]) . "</th>";
        echo "<th>" . CreateInput('date', $_rows[3], $_edit_row[3]) . "</th>";
        echo "<th>" . CreateInput('time', $_rows[4], $_edit_row[4]) . "</th>";

        echo "<th>$_edit_row[5]</th>";

    
        echo "<th>" . CreateInput('hidden', 'update', $_GET["where"], $_GET["where"]) . CreateInput('hidden', 'update_id', $_GET["update_id"], $_GET["update_id"]) . CreateInput('submit', '', 'Update', 'Update') . "</th>";
        echo "</form></tr>";
    } 
    // else
    //     echo "<th>" . CreateInput('hidden', 'insert', $section_name, $section_name) . CreateInput('submit', '', 'Add', 'Add') . "</th>";

    echo "</tbody></table>";
};

function GetTreatment() {
    include "connect.php";

    $section_name = "treatment";

    $desc = "";
    $order_by = "";

    $_rows = ["customer_id", "customer_name", 'date_from', 'date_to'];
    $_edit_row = $_rows;

    if(isset($_GET['order_by']) && isset($_GET['order_by']))
    {
        for ($index=1; $index < count($_rows); $index++)
        {
            if($_GET['order_by'] == $_rows[$index])
            {
                $order_by = " ORDER BY ".$_GET['order_by'];
                if(isset($_GET['desc']) && $_GET['desc'] == 'true') $order_by .= " DESC";
                else $desc = "&desc=true";
                
                break;
            }
        }
    };

    $sql = "SELECT `customer`.`id` AS `customer_id`, `customer`.`name` AS `customer_name`,`$section_name`.`id`,`$section_name`.`date_from`,`$section_name`.`date_to` FROM `$section_name`,`customer` WHERE `$section_name`.`customer_id` = `customer`.`id`".$order_by;
    $db = $pdo->query($sql);

    echo "<table><tbody>";
    echo "<tr>";

    for ($index=1; $index < count($_rows); $index++)
        echo "<th><a href='?order_by=$_rows[$index]".$desc."#$section_name'>$_rows[$index]</a></th>";

    echo "<th>Options</th>";
    echo "</tr>";

    while ($row = $db->fetch()) {

        if(isset($_GET["where"]) && $_GET["where"] == $section_name && isset($_GET["update_id"]) && $_GET["update_id"] == $row['id'])
        {
            for ($index=0; $index < count($_rows); $index++)
                $_edit_row[$index] = $row[$_rows[$index]];
            
            echo "<tr id='edit_".$row['id']."' class='edit'>";
        }
        else echo "<tr>";

        for ($index=1; $index < count($_rows); $index++)
            echo "<th>".$row[$_rows[$index]]."</th>";

        echo "<th><a href='?where=$section_name&update_id=".$row['id']."#$section_name'><button>Edit</button></a> ";
        echo "<a href='../assets/worker.php?where=$section_name&remove_id=".$row['id']."'><button>Remove</button></a></th>";

        echo "</tr>";
    };

    if(isset($_GET["where"]) && $_GET["where"] == $section_name && isset($_GET["update_id"])) {
        echo "<tr><form action='../assets/worker.php' method='GET'>";

        echo "<th>" . $_edit_row[1] . CreateInput('hidden', $_rows[0], $_edit_row[0], $_edit_row[0], $_edit_row[0]) . "</th>";
        echo "<th>" . CreateInput('date', $_rows[2], $_edit_row[2]) . "</th>";
        echo "<th>" . CreateInput('date', $_rows[3], $_edit_row[3]) . "</th>";
    
        echo "<th>" . CreateInput('hidden', 'update', $_GET["where"], $_GET["where"]) . CreateInput('hidden', 'update_id', $_GET["update_id"], $_GET["update_id"]) . CreateInput('submit', '', 'Update', 'Update') . "</th>";

        echo "</form></tr>";
    }
    echo "</tbody></table>";
};

function GetPerrmission() {
    include "connect.php";

    $section_name = "perrmission";

    $desc = "";
    $order_by = "";

    $_rows = ['name'];
    $_edit_row = $_rows;

    if(isset($_GET['order_by']) && isset($_GET['order_by']))
    {
        for ($index=0; $index < count($_rows); $index++)
        {
            if($_GET['order_by'] == $_rows[$index])
            {
                $order_by = " ORDER BY ".$_GET['order_by'];
                if(isset($_GET['desc']) && $_GET['desc'] == 'true') $order_by .= " DESC";
                else $desc = "&desc=true";
                
                break;
            }
        }
    };

    $sql = "SELECT `id`,`name` FROM `$section_name`".$order_by;
    $db = $pdo->query($sql);

    echo "<table><tbody>";
    echo "<tr>";

    for ($index=0; $index < count($_rows); $index++)
        echo "<th><a href='?order_by=$_rows[$index]".$desc."#$section_name'>$_rows[$index]</a></th>";

    echo "<th>Options</th>";
    echo "</tr>";

    while ($row = $db->fetch()) {
        if(isset($_GET["where"]) && $_GET["where"] == $section_name && isset($_GET["update_id"]) && $_GET["update_id"] == $row['id'])
        {
            for ($index=0; $index < count($_rows); $index++)
                $_edit_row[$index] = $row[$_rows[$index]];
            
            echo "<tr id='edit_".$row['id']."' class='edit'>";
        }
        else echo "<tr>";

        for ($index=0; $index < count($_rows); $index++)
            echo "<th>".$row[$_rows[$index]]."</th>";

        echo "<th><a href='?where=$section_name&update_id=".$row['id']."#$section_name'><button>Edit</button></a> ";
        echo "<a href='../assets/worker.php?where=$section_name&remove_id=".$row['id']."'><button>Remove</button></a></th>";

        echo "</tr>";
    };

    echo "<tr><form action='../assets/worker.php' method='GET'>";

    for ($index=0; $index < count($_rows); $index++)
        echo "<th>" . CreateInput('text', $_rows[$index], $_edit_row[$index]) . "</th>";

    if(isset($_GET["where"]) && $_GET["where"] == $section_name && isset($_GET["update_id"]))
        echo "<th>" . CreateInput('hidden', 'update', $_GET["where"], $_GET["where"]) . CreateInput('hidden', 'update_id', $_GET["update_id"], $_GET["update_id"]) . CreateInput('submit', '', 'Update', 'Update') . "</th>";
    else
        echo "<th>" . CreateInput('hidden', 'insert', $section_name, $section_name) . CreateInput('submit', '', 'Add', 'Add') . "</th>";

    echo "</form></tr>";
    echo "</tbody></table>";
};

function GetAppointmentTypes() {
    include "connect.php";

    $section_name = "appointment_types";

    $desc = "";
    $order_by = "";

    $_rows = ['name'];
    $_edit_row = $_rows;

    if(isset($_GET['order_by']) && isset($_GET['order_by']))
    {
        for ($index=0; $index < count($_rows); $index++)
        {
            if($_GET['order_by'] == $_rows[$index])
            {
                $order_by = " ORDER BY ".$_GET['order_by'];
                if(isset($_GET['desc']) && $_GET['desc'] == 'true') $order_by .= " DESC";
                else $desc = "&desc=true";
                
                break;
            }
        }
    };

    $sql = "SELECT `id`,`name` FROM `$section_name`".$order_by;
    $db = $pdo->query($sql);

    echo "<table><tbody>";

    for ($index=0; $index < count($_rows); $index++)
        echo "<th><a href='?order_by=$_rows[$index]".$desc."#$section_name'>$_rows[$index]</a></th>";
    
    echo "<th>Options</th>";

    while ($row = $db->fetch()) {
        echo "<tr>";

        echo "<th>".$row['name']."</th>";
        echo "<th><a href='../assets/worker.php?where=$section_name&remove_id=".$row['id']."'><button>Remove</button></a></th>";

        echo "</tr>";
    };

    echo "<tr><form action='../assets/worker.php' method='GET'>";

    echo "<th>" . CreateInput('text', $_rows[0], $_edit_row[0]) . "</th>";

    if(isset($_GET["where"]) && $_GET["where"] == $section_name && isset($_GET["update_id"]))
        echo "<th>" . CreateInput('hidden', 'update', $_GET["where"], $_GET["where"]) . CreateInput('hidden', 'update_id', $_GET["update_id"], $_GET["update_id"]) . CreateInput('submit', '', 'Update', 'Update') . "</th>";
    else
        echo "<th>" . CreateInput('hidden', 'insert', $section_name, $section_name) . CreateInput('submit', '', 'Add', 'Add') . "</th>";

    echo "</form></tr>";
    echo "</tbody></table>";
};

function GetSpecialisation() {
    include "connect.php";

    $section_name = "specialisation";

    $desc = "";
    $order_by = "";

    $_rows = ['name'];
    $_edit_row = $_rows;

    if(isset($_GET['order_by']) && isset($_GET['order_by']))
    {
        for ($index=0; $index < count($_rows); $index++)
        {
            if($_GET['order_by'] == $_rows[$index])
            {
                $order_by = " ORDER BY ".$_GET['order_by'];
                if(isset($_GET['desc']) && $_GET['desc'] == 'true') $order_by .= " DESC";
                else $desc = "&desc=true";
                
                break;
            }
        }
    };

    $sql = "SELECT `id`,`name` FROM `$section_name`".$order_by;
    $db = $pdo->query($sql);

    echo "<table><tbody>";

    for ($index=0; $index < count($_rows); $index++)
        echo "<th><a href='?order_by=$_rows[$index]".$desc."#$section_name'>$_rows[$index]</a></th>";
    
    echo "<th>Options</th>";

    while ($row = $db->fetch()) {
        echo "<tr>";

        echo "<th>".$row['name']."</th>";

        echo "<th><a href='?where=$section_name&update_id=".$row['id']."#$section_name'><button>Edit</button></a> ";
        echo "<a href='../assets/worker.php?where=$section_name&remove_id=".$row['id']."'><button>Remove</button></a></th>";
        echo "</tr>";
    };

    echo "<tr><form action='../assets/worker.php' method='GET'>";

    echo "<th>" . CreateInput('text', $_rows[0], $_edit_row[0]) . "</th>";

    if(isset($_GET["where"]) && $_GET["where"] == $section_name && isset($_GET["update_id"]))
        echo "<th>" . CreateInput('hidden', 'update', $_GET["where"], $_GET["where"]) . CreateInput('hidden', 'update_id', $_GET["update_id"], $_GET["update_id"]) . CreateInput('submit', '', 'Update', 'Update') . "</th>";
    else
        echo "<th>" . CreateInput('hidden', 'insert', $section_name, $section_name) . CreateInput('submit', '', 'Add', 'Add') . "</th>";

    echo "</form></tr>";
    echo "</tbody></table>";
};

function GetOwner() {
    include "connect.php";

    $section_name = "owner";

    $desc = "";
    $order_by = "";

    $_rows = ['first_name', 'second_name', 'last_name', 'date_of_birth'];
    $_edit_row = $_rows;

    if(isset($_GET['order_by']) && isset($_GET['order_by']))
    {
        for ($index=0; $index < count($_rows); $index++)
        {
            if($_GET['order_by'] == $_rows[$index])
            {
                $order_by = " ORDER BY ".$_GET['order_by'];
                if(isset($_GET['desc']) && $_GET['desc'] == 'true') $order_by .= " DESC";
                else $desc = "&desc=true";
                
                break;
            }
        }
    };

    $sql = 'SELECT `id`,`first_name`,`second_name`,`last_name`,`date_of_birth` FROM `owner`'.$order_by;
    $db = $pdo->query($sql);

    echo "<table><tbody>";
    echo "<tr><th><a href='?order_by=first_name".$desc."#$section_name'>First name</a> <a href='?order_by=second_name".$desc."#$section_name'> Second name</a></th><th><a href='?order_by=last_name".$desc."#$section_name'>Last name</a></th><th><a href='?order_by=date_of_birth".$desc."#$section_name'>Birth</a></th><th>Options</th></tr>";

    while ($row = $db->fetch()) {
        echo "<tr>";

        echo "<th>".$row['first_name'].$row['second_name']."</th>";
        echo "<th>".$row['last_name']."</th>";
        echo "<th>".$row['date_of_birth']."</th>";
        echo "<th><a href='../assets/worker.php?where=$section_name&remove_id=".$row['id']."'><button>Remove</button></a></th>";

        echo "</tr>";
    };

    echo "<tr><form action='../assets/worker.php' method='GET'>";

    echo "<th>" . CreateInput('text', 'first_name', 'first_name') . "</th>";
    echo "<th>" . CreateInput('text', 'last_name', 'last_name') . "</th>";
    echo "<th>" . CreateInput('date', 'date_of_birth', '') . "</th>";
    echo "<th>" . CreateInput('hidden', 'insert', $section_name, $section_name) . CreateInput('submit', '', 'Add', 'Add') . "</th>";

    echo "</form></tr>";
    echo "</tbody></table>";
};

function GetCustomer() {
    include "connect.php";

    $section_name = "customer";

    $desc = "";
    $order_by = "";

    $_rows = ['breed_name', 'species_name', 'first_name', 'second_name', 'last_name', "customer_name", 'date_of_birth', 'weight', 'height'];
    $_edit_row = $_rows;

    if(isset($_GET['order_by']) && isset($_GET['order_by']))
    {
        for ($index=0; $index < count($_rows); $index++)
        {
            if($_GET['order_by'] == $_rows[$index])
            {
                $order_by = " ORDER BY ".$_GET['order_by'];
                if(isset($_GET['desc']) && $_GET['desc'] == 'true') $order_by .= " DESC";
                else $desc = "&desc=true";
                
                break;
            }
        }
    };

    $sql = 'SELECT `customer`.`id`, `customer`.`name` AS `customer_name`, `customer`.`date_of_birth`, `customer`.`weight`, `customer`.`height`, `owner`.`first_name`, `owner`.`second_name`, `owner`.`last_name`, `breed`.`name` AS `breed_name`, `species`.`name` AS `species_name` FROM `customer`, `breed`, `species`, `owner` WHERE `customer`.`breed_id` = `breed`.`id` AND `customer`.`species_id` = `species`.`id` AND `customer`.`owner_id` = `owner`.`id`'.$order_by;
    $db = $pdo->query($sql);

    $ap_sql = $pdo->query("SELECT `customer`.`id` FROM `customer`, `appointment` WHERE `appointment`.`customer_id` = `customer`.`id`");
    $ap_db = $ap_sql->fetchAll(PDO::FETCH_ASSOC);

    $tr_sql = $pdo->query("SELECT `customer`.`id` FROM `customer`, `treatment` WHERE `treatment`.`customer_id` = `customer`.`id`");
    $tr_db = $tr_sql->fetchAll(PDO::FETCH_ASSOC);

    $su_sql = $pdo->query("SELECT `customer`.`id` FROM `customer`, `surgery` WHERE `surgery`.`customer_id` = `customer`.`id`");
    $su_db = $su_sql->fetchAll(PDO::FETCH_ASSOC);
    
    $ap_ids = [];
    foreach ($ap_db as $data) array_push($ap_ids, $data['id']);

    $tr_ids = [];
    foreach ($tr_db as $data) array_push($tr_ids, $data['id']);

    $su_ids = [];
    foreach ($su_db as $data) array_push($su_ids, $data['id']);

    echo "<table><tbody>";

    echo "<tr><th><a href='?order_by=species_name".$desc."#$section_name'>Species</a></th>";
    echo "<th><a href='?order_by=breed_name".$desc."#$section_name'>Breed</a></th>";
    echo "<th style='min-width: 67px; font-size: 12px'>Owner<br> <a href='?order_by=first_name".$desc."#$section_name'>First name</a> / <a href='?order_by=second_name".$desc."#$section_name'>Second name</a> / <a href='?order_by=last_name".$desc."#$section_name'>Last name</a></th>";
    echo "<th><a href='?order_by=customer_name".$desc."#$section_name'>Name</a></th>";
    echo "<th><a href='?order_by=date_of_birth".$desc."#$section_name'>Birth</a></th>";
    echo "<th><a href='?order_by=weight".$desc."#$section_name'>Weight</a> / <a href='?order_by=height".$desc."#$section_name'>Height</a></th>";

    echo "<th>Appointment</th>";
    echo "<th>Surgery</th>";
    echo "<th>Treatment</th>";    

    echo "<th>Options</th></tr>";

    while ($row = $db->fetch()) {
        echo "<tr>";
        echo "<th>".$row['species_name']."</th>";
        echo "<th>".$row['breed_name']."</th>";
        echo "<th>".$row['first_name']." ".$row['second_name']." ".$row['last_name']."</th>";
        echo "<th>".$row['customer_name']."</th>";
        echo "<th>".$row['date_of_birth']."</th>";
        echo "<th>".$row['weight']."/".$row['height']."</th>";

        if($ap_ids && in_array($row['id'], $ap_ids))
            echo "<th><a href='../assets/worker.php?where=appointment&remove_id=$row[id]&col=customer_id'><button>Cancel</button></a></th>";
        else
            echo "<th><a href='?appointment_design=$row[id]#design'><button>Add</button></a></th>";

        if($su_ids && in_array($row['id'], $su_ids))
            echo "<th><a href='../assets/worker.php?where=surgery&remove_id=$row[id]&col=customer_id'><button>Cancel</button></a></th>";
        else
            echo "<th><a href='?surgery_design=$row[id]#design'><button>Add</button></a></th>";  

        if($tr_ids && in_array($row['id'], $tr_ids))
            echo "<th><a href='../assets/worker.php?where=treatment&remove_id=$row[id]&col=customer_id'><button>Cancel</button></a></th>";
        else
            echo "<th><a href='?treatment_design=$row[id]#design'><button>Add</button></a></th>";  
  
        echo "<th><a href='../assets/worker.php?where=$section_name&remove_id=".$row['id']."'><button>Remove</button></a></th>";
        echo "</tr>";
    };

    echo "<tr><form action='../assets/worker.php' method='GET'>";

    echo "<th>" . GetSpeciesList('species_id') . "</th>";
    echo "<th>" . GetBreedList() . "</th>";
    echo "<th>" . GetOwnerList() . "</th>";
    echo "<th>" . CreateInput('text', 'name', 'name') . "</th>";
    echo "<th>" . CreateInput('date', 'date_of_birth', '') . "</th>";
    echo "<th>" . CreateInput('text', 'weight', 'weigh/height') . "</th>";

    echo "<th></th>";
    echo "<th></th>";
    echo "<th></th>";

    echo "<th>" . CreateInput('hidden', 'insert', 'customer', 'customer') . CreateInput('submit', '', 'Add', 'Add') . "</th>";

    echo "</form></tr>";
    
    echo "</tbody></table>";

    echo '<script>
        var species_id = document.querySelector(".species_id");
        var breed_id = document.querySelector(".breed_id");
        
        function change_data() {
            let _count = 0 ;
            for(let index = 1; index < breed_id.options.length; index++ )
                if(breed_id.options[index].className != `species_id_${species_id.value}`)
                    breed_id.options[index].hidden = true
                else {
                    breed_id.options[index].hidden = ""; 
                    _count += 1;
                }
            
            breed_id.value = 0;
            breed_id.disabled = _count == 0 ? true : "";
            
        };

        document.onload = change_data();
        species_id.addEventListener("input", change_data);
        
    </script>';
};

function GetAccount() {
    include "connect.php";

    $section_name = "accounts";

    $desc = "";
    $order_by = "";

    $_rows = ["`perrmission`.`name`", 'specialisation_name', 'first_name', 'second_name', 'last_name', 'date_of_birth'];
    $_edit_row = $_rows;

    if(isset($_GET['order_by']) && isset($_GET['order_by']))
    {
        for ($index=0; $index < count($_rows); $index++)
        {
            if($_GET['order_by'] == $_rows[$index])
            {
                $order_by = " ORDER BY ".$_GET['order_by'];
                if(isset($_GET['desc']) && $_GET['desc'] == 'true') $order_by .= " DESC";
                else $desc = "&desc=true";
                
                break;
            }
        }
    };

    $sql = 'SELECT `accounts`.id, `perrmission`.`name` AS `perrmission_name` , `specialisation`.`name` AS `specialisation_name` , `accounts`.first_name , `accounts`.second_name , `accounts`.last_name , `accounts`.date_of_birth FROM `accounts`, `perrmission`, `specialisation` WHERE accounts.permission_id = `perrmission`.id AND `accounts`.`specialisation_id` = `specialisation`.`id` '.$order_by;
    $db = $pdo->query($sql);

    echo "<table><tbody>";
    echo "<tr><th><a href='?order_by=`perrmission`.`name`".$desc."#$section_name'>Permission</a></th><th><a href='?order_by=specialisation_name".$desc."#$section_name'>Specialisation</a></th><th><a href='?order_by=first_name".$desc."#$section_name'>First name</a> / <a href='?order_by=last_name".$desc."#$section_name'>last ame</a></th><th><a href='?order_by=last_name".$desc."#$section_name'>Last name</a.</th><th><a href='?order_by=date_of_birth".$desc."#$section_name'>date_of_birth</a></th><th>Options</th></tr>";
    
    while ($row = $db->fetch()) {
        echo "<tr>";

        echo "<th>".$row['perrmission_name']."</th>";
        echo "<th>".$row['specialisation_name']."</th>";
        echo "<th>".$row['first_name']." ".$row['second_name']."</th>";
        echo "<th>".$row['last_name']."</th>";
        echo "<th>".$row['date_of_birth']."</th>";
        echo "<th><a href='../assets/worker.php?where=$section_name&remove_id=".$row['id']."'><button>Remove</button></a></th>";

        echo "</tr>";
    };

    echo "<tr><form action='../assets/worker.php' method='GET'>";

    echo "<th>" . GetPermissionList() . "</th>";
    echo "<th>" . GetSpecialisationList() . "</th>";
    echo "<th>" . CreateInput('text', 'first_name', 'first name') . "</th>";
    echo "<th>" . CreateInput('text', 'last_name', 'last name') . "</th>";
    echo "<th>" . CreateInput('date', 'date_of_birth', '') . "</th>";
    echo "<th>" . CreateInput('hidden', 'insert', $section_name, $section_name) . CreateInput('submit', '', 'Add', 'Add') . "</th>";
    echo "</form></tr>";
    
    echo "</tbody></table>";
};

function GetAppointment() {
    include "connect.php";

    $section_name = "appointment";
    
    $desc = "";
    $order_by = ";";

    $_rows = ['name', "customer_name", "species_name", "breed_name", 'first_name', 'second_name', 'last_name', 'description', 'time', 'date'];
    $_edit_row = $_rows;

    if(isset($_GET['order_by']) && isset($_GET['order_by']))
    {
        for ($index=0; $index < count($_rows); $index++)
        {
            if($_GET['order_by'] == $_rows[$index])
            {
                $order_by = " ORDER BY ".$_GET['order_by'];
                if(isset($_GET['desc']) && $_GET['desc'] == 'true') $order_by .= " DESC;";
                else $desc = "&desc=true";
                
                break;
            }
        }
    };

    $sql = "SELECT `appointment`.`id`, `customer`.`name` AS `customer_name`, `breed`.`name` AS `breed_name`, `species`.`name` AS `species_name`, `appointment`.`description`, `appointment`.`date`, `appointment`.`time`, `appointment_types`.`name`, `accounts`.`first_name`, `accounts`.`second_name`, `accounts`.`last_name` FROM `appointment`,`appointment_types`, `accounts`, `customer`, `species`, `breed` WHERE `appointment`.`appointment_type_id` = `appointment_types`.`id` AND `appointment`.`customer_id` = `customer`.`id` AND `appointment`.`account_id` = `accounts`.`id` AND `customer`.`species_id` = `species`.`id` AND `customer`.`breed_id` = `breed`.`id`".$order_by;
    $db = $pdo->query($sql);

    echo "<table><tbody>";
    echo "<tr>";

    echo "<th><a href='?order_by=name".$desc."#$section_name'>Type</a></th>";
    echo "<th><a href='?order_by=customer_name".$desc."#$section_name'>Pet name</a></th>";
    echo "<th><a href='?order_by=species_name".$desc."#$section_name'>Species</a></th>";
    echo "<th><a href='?order_by=breed_name".$desc."#$section_name'>Breed</a></th>";
    echo "<th>Personel<br><span style='font-size: 12px'><a href='?order_by=first_name".$desc."#$section_name'>First name</a> / <a href='?order_by=second_name".$desc."#$section_name'>Second name</a> / <a href='?order_by=last_name".$desc."#$section_name'>Last name</a></span></th>";
    echo "<th><a href='?order_by=description".$desc."#$section_name'>Description</a></th>";
    echo "<th><a href='?order_by=time".$desc."#$section_name'>Time</a></th>";
    echo "<th><a href='?order_by=date".$desc."#$section_name'>Date</a></th>";

    echo "<th>Options</th>";
    echo "</tr>";

    while ($row = $db->fetch()) {

        if(isset($_GET["where"]) && $_GET["where"] == $section_name && isset($_GET["update_id"]) && $_GET["update_id"] == $row['id'])
        {
            for ($index=0; $index < count($_rows); $index++)
            {
                switch ($_edit_row[$index]) {
                    case "species_id":
                        $_edit_row[$index] = $row['species_name'];
                        break;

                    case "customer_id":
                        $_edit_row[$index] = $row['customer_name'];
                        break;

                    case "breed_id":
                        $_edit_row[$index] = $row['breed_name'];
                        break;
                    
                    default:
                        $_edit_row[$index] = $row[$_rows[$index]];
                        break;
                }
            }

            echo "<tr id='edit_".$row['id']."' class='edit'>";
        }

        else echo "<tr>";

        echo "<th>".$row['name']."</th>";
        echo "<th>".$row["customer_name"]."</th>";
        echo "<th>".$row["species_name"]."</th>";
        echo "<th>".$row["breed_name"]."</th>";
        echo "<th>".$row['first_name']." ".$row['second_name']." ".$row['last_name']."</th>";
        echo "<th>".$row['description']."</th>";
        echo "<th>".$row['time']."</th>";
        echo "<th>".$row['date']."</th>";

        echo "<th><a href='../assets/worker.php?where=$section_name&remove_id=".$row['id']."#$section_name'><button>Remove</button></a></th>";
        echo "</tr>";
    };

    echo "</tr>";
    echo "</tbody></table>";
};

?>