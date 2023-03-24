<?php require "../config/database.php"; require "../config/env.php";

if (isset($_POST["insert"]) && $_POST["insert"] == "newsletter" && isset($_POST["name"]) && $_POST["email"])
    Newsletter($_POST["name"], $_POST["email"]);

else if (isset($_GET["where"]) && isset($_GET["remove_id"]))
    Remove($_GET["where"], $_GET["remove_id"], isset($_GET["col"]) ? $_GET["col"] : 'id');

else if (isset($_GET["insert"]))
{
    if ($_GET["insert"] == "newsletter" && $_GET["name"] && $_GET["email"])
        Add($_GET["insert"],
            [
                'name'  => $_GET["name"],
                'email' => $_GET["email"]
            ]
        );
        
    else if ($_GET["insert"] == "medicine" && $_GET["name"] && $_GET["amount"] && $_GET["price"])
    {
        $last_id = Add($_GET["insert"],
            [
                'name'   => $_GET["name"],
                'amount' => $_GET["amount"],
                'price'  => $_GET["price"]
            ]
        );

        if(isset($_GET["surgery_id"]))
        {
            echo $last_id;
            Add("surgery_medicine",
                [
                    'surgery_id'   => $_GET["surgery_id"],
                    'medicine_id' => $last_id
                ]
            );
        }
    }

    else if ($_GET["insert"] == "breed" && $_GET["breed_name"] && $_GET["code"] && $_GET["species_id"])
        Add($_GET["insert"],
            [
                'name'       => $_GET["breed_name"],
                'code'       => $_GET["code"],
                'species_id' => $_GET["species_id"]
            ]
        );
    
    else if ($_GET["insert"] == "species" && $_GET["name"])
        Add($_GET["insert"],
            [
                'name' => $_GET["name"]
            ]
        );

    else if ($_GET["insert"] == "owner" && $_GET["first_name"] && $_GET["last_name"] && $_GET["date_of_birth"])
        Add($_GET["insert"],
            [
                'first_name'    => explode(" ", $_GET["first_name"])[0],
                'second_name'   => explode(" ", $_GET["first_name"])[1] ? explode(" ", $_GET["first_name"])[1] : "",
                'last_name'     => $_GET["last_name"],
                'date_of_birth' => $_GET["date_of_birth"]
            ]
        );
    
    else if ($_GET["insert"] == "accounts" && $_GET["permission_id"] && $_GET["specialisation_id"] & $_GET["first_name"] && $_GET["last_name"] && $_GET["date_of_birth"])
        Add($_GET["insert"],
            [
                'permission_id'     => $_GET["permission_id"],
                'specialisation_id' => $_GET["specialisation_id"],
                'first_name'        => explode(" ", $_GET["first_name"])[0],
                'second_name'       => explode(" ", $_GET["first_name"])[1] ? explode(" ", $_GET["first_name"])[1] : "",
                'last_name'         => $_GET["last_name"],
                'date_of_birth'     => $_GET["date_of_birth"]
            ]
        );
    
    else if ($_GET["insert"] == "customer" && $_GET["breed_id"] && $_GET["species_id"] && $_GET["owner_id"] && $_GET["name"] && $_GET["date_of_birth"] && $_GET["weight"])
        Add($_GET["insert"],
            [
                'breed_id'      => $_GET["breed_id"], 
                'species_id'    => $_GET["species_id"], 
                'owner_id'      => $_GET["owner_id"],
                'name'          => $_GET["name"], 
                'date_of_birth' => $_GET["date_of_birth"] ? $_GET["date_of_birth"] : 0, 
                'weight'        => explode("/", $_GET["weight"])[0] ? explode("/", $_GET["weight"])[0] : 0,
                'height'        => explode("/", $_GET["weight"])[1] ? explode("/", $_GET["weight"])[1] : 0
            ]
        );

    else if ($_GET["insert"] == "appointment" && $_GET["appointment_type_id"] && $_GET["account_id"] && $_GET["customer_id"] && $_GET["description"] && $_GET["date"] && $_GET["time"])
        Add($_GET["insert"],
            [
                'appointment_type_id' => $_GET["appointment_type_id"],
                'account_id'          => $_GET["account_id"],
                'customer_id'         => $_GET["customer_id"],
                'description'         => $_GET["description"],
                'date'                => $_GET["date"],
                'time'                => $_GET["time"]
            ]
        );
    
    else if ($_GET["insert"] == "appointment_types" && $_GET["name"])
        Add($_GET["insert"],
            [
                'name' => $_GET["name"]
            ]
        );
    
    else if ($_GET["insert"] == "specialisation" && $_GET["name"])
        Add($_GET["insert"],
            [
                'name' => $_GET["name"]
            ]
        );
    
    else if ($_GET["insert"] == "perrmission" && $_GET["name"])
        Add($_GET["insert"],
            [
                'name' => $_GET["name"]
            ]
        );
    
    else if ($_GET["insert"] == "treatment" && $_GET["customer_id"] && $_GET["date_from"] && $_GET["date_to"])
        Add($_GET["insert"],
            [
                'customer_id' => $_GET["customer_id"],
                'date_from'   => $_GET["date_from"],
                'date_to'     => $_GET["date_to"]
            ]
        );
    
    else if ($_GET["insert"] == "surgery" && $_GET["customer_id"] && $_GET["price"] && $_GET["date"] && $_GET["time"])
        Add($_GET["insert"],
            [
                'customer_id' => $_GET["customer_id"],
                'price'       => $_GET["price"],
                'date'        => $_GET["date"],
                'time'        => $_GET["time"],
            ]
        );

    else header("Location: $URL/klinika/account/index.php?error=true#$_GET[insert]");
}
else if (isset($_GET["update"]))
{
    if (isset($_GET["update_id"]))
    {
        $data = [];
        
        (isset($_GET["owner_id"]) && $_GET["owner_id"] != "") ? $data += ['owner_id' => $_GET["owner_id"]] : null;
        (isset($_GET["appointment_type_id"]) && $_GET["appointment_type_id"] != "") ? $data += ['appointment_type_id' => $_GET["appointment_type_id"]] : null;
        (isset($_GET["name"]) && $_GET["name"] != "") ? $data += ['name' => $_GET["name"]] : null;
        (isset($_GET["email"]) && $_GET["email"] != "") ? $data += ['email' => $_GET["email"]] : null;
        (isset($_GET["amount"]) && $_GET["amount"] != "") ? $data += ['amount' => $_GET["amount"]] : null;
        (isset($_GET["price"]) && $_GET["price"] != "") ? $data += ['price' => $_GET["price"]] : null;
        (isset($_GET["date"]) && $_GET["date"] != "") ? $data += ['date' => $_GET["date"]] : null;
        (isset($_GET["date_from"]) && $_GET["date_from"] != "") ? $data += ['date_from' => $_GET["date_from"]] : null;
        (isset($_GET["date_to"]) && $_GET["date_to"] != "") ? $data += ['date_to' => $_GET["date_to"]] : null;
        (isset($_GET["time"]) && $_GET["time"] != "") ? $data += ['time' => $_GET["time"]] : null;
        (isset($_GET["code"]) && $_GET["code"] != "") ? $data += ['code' => $_GET["code"]] : null;
        (isset($_GET["permission_id"]) && $_GET["permission_id"] != "") ? $data += ['permission_id' => $_GET["permission_id"]] : null;
        (isset($_GET["specialisation_id"]) && $_GET["specialisation_id"] != "") ? $data += ['specialisation_id' => $_GET["specialisation_id"]] : null;
        (isset($_GET["first_name"]) && $_GET["first_name"] != "") ? $data += ['first_name' => explode(" ", $_GET["first_name"])[0]] : null;
        (isset($_GET["second_name"]) && $_GET["second_name"] != "") ? $data += ['second_name' => explode(" ", $_GET["first_name"])[1] ? explode(" ", $_GET["first_name"])[1] : ""] : null;
        (isset($_GET["last_name"]) && $_GET["last_name"] != "") ? $data += ['last_name' => $_GET["last_name"]] : null;
        (isset($_GET["date_of_birth"]) && $_GET["date_of_birth"] != "") ? $data += ['date_of_birth' => $_GET["date_of_birth"]] : null;
        (isset($_GET["breed_id"]) && $_GET["breed_id"] != "") ? $data += ['breed_id' => $_GET["breed_id"]] : null;
        (isset($_GET["species_id"]) && $_GET["species_id"] != "") ? $data += ['species_id' => $_GET["species_id"]] : null;
        (isset($_GET["customer_id"]) && $_GET["customer_id"] != "") ? $data += ['customer_id' => $_GET["customer_id"]] : null;
        (isset($_GET["weight"]) && $_GET["weight"] != "") ? $data += ['weight' => explode("/", $_GET["weight"])[0] ? explode("/", $_GET["weight"])[0] : 0] : null;
        (isset($_GET["height"]) && $_GET["height"] != "") ? $data += ['height' => explode("/", $_GET["weight"])[1] ? explode("/", $_GET["weight"])[1] : 0] : null;

        if(count($data) != 0)
            Update($_GET["update"],
                $_GET["update_id"],
                $data
            );
        else header("Location: $URL/klinika/account/index.php?error=true#$_GET[update]");
    }
}
else header("Location: $URL/klinika/account/index.php?error=true");

?>