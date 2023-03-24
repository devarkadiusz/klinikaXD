<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300&display=swap" rel="stylesheet">
<style>
    body {
        margin: 0 auto;
        max-width: 1700px;
        padding: 20px 20px 0 20px;
        font-family: 'Poppins', sans-serif;
        background-color: white;
    }
    footer {
        margin: 50px 0 15px 0;
        font-size: 15px;
    }
    table, td, th {
        padding: 10px 20px;
        font-size: 15px;
    }
    a {
        color: #2d2d2d;
    }
    table {
        border: 1px solid #e7e7e7;
    }
    tr {
        border-bottom: 1px solid #e7e7e7;
        background-color: white;
    }
    tr:hover {
        background-color: rgba(242, 242, 242, .7);
    }
    tr:not(:first-child) {
        color: #6c6c6c;
    }
    tr:first-child {
        top: 70px;
        position: sticky;
        box-shadow: 0px 1px 0 0px #f1f1f1;
        text-transform : capitalize;
        background-color: #f4f4f4;
        z-index: 2;
    }
    tr:last-child {
        bottom: 0;
        position: sticky;
        box-shadow: 0px -1px 0 0px #f1f1f1;
    }

    tr.edit {
        background-color: #d8ffe8;
    }
    
    table {
        width: 100%;
        border-collapse: collapse;
    }
    .logo {
        max-width: 170px;
    }
    .popup {
        left: 0;
        top: 0;
        width: 100%;
        padding: 10px 20px;
        position: fixed;     
        box-sizing: border-box;
        text-align: center;
        animation-name: hide;
        animation-delay: 2s;
        animation-duration: 1s;
        animation-fill-mode: forwards;
        opacity: 1;
        z-index: 999;
    }
    .popup.done {
        background: #ccf8e2;
    }
    .popup.error {
        background: #f8cccc;
    }
    .popup span {
        padding: 10px 20px;
    }
    @keyframes hide {
        0% {
            top: 0;
            opacity: 1;
        }
        50% {
            opacity: 0;
        }
        100% {
            top: -100px;
            opacity: 0;
        }
    }
    .header {
        top: 0;
        width: 100%;
        min-height: 70px;
        max-height: 70px;
        display: flex;
        position: sticky;
        align-items: center;
        justify-content: space-between;
        background-color: white;
        z-index: 3;
        
    }
    .input {
        width: 100%;
        padding: 5px 10px;
    }
    button, input[type="submit"] {
        cursor: pointer;
        padding: 5px 10px;
    }
    .appointment_design .content {
        display: flex;
    }
    .appointment_design .content form {
        max-width: 350px;
        width: 100%;
    }
    .appointment_design .data {
        width: 100%;
        max-width: 350px;
        margin: 0 0 0 10px;
    }
    .appointment_design div span {
        width: 100%;
        margin: 0 0 10px 0;
        display: flex;
        font-size: 18px;
        justify-content: space-between;
    }
    .appointment_design div p {
        margin: 0;
    }
    .appointment_design input, .appointment_design select, .appointment_design textarea {
        width: 100%;
        margin: 0 0 15px 0;
        padding: 10px 20px;
    }
    .database_options {
        width: 170px;
        max-width: 170px;
        min-width: 170px;
        display: flex;
    }
    .database_options button, .database_options a {
        width: 100%;
        font-size: 12px;
    }
    ul {
        margin: 0;
        padding: 0;
        display: flex;
        flex-wrap: wrap;
        align-items: center;
        justify-content: center;
        list-style-type: none;
    }
    ul li {
        margin: 0 5px;
    }
</style>

<?php require "../config/database.php";

echo '<div>';
echo "<a href='?'><img class='logo' src='../assets/img/logo.svg' /></a>";

if(isset($_GET["dboptions"])) {
    echo '<span class="database_options"><a href="?LoadSQL=true" style="margin-right: 5px"><button>Load SQL</button></a>';
    echo '<a href="?DropTableSQL=true"><button>Drop SQL</button></a></span>';
} else {
    echo '<span class="database_options">';
    echo '<a href="?dboptions=true"><button>Database options</button></a></span>';
    echo '</span>';
}
echo '</div>';

if(isset($_GET["LoadSQL"]) && $_GET["LoadSQL"] == "true") {
    LoadSQL();
}
else if(isset($_GET["DropTableSQL"]) && $_GET["DropTableSQL"] == "true") {
    DropTableSQL();
};

if(isset($_GET["done"]) && $_GET["done"] == true ) {
    echo "<div class='popup done'><span>done</span></div>";
}
else if(isset($_GET["error"]) && $_GET["error"] == true ) {
    echo "<div class='popup error'><span>error</span></div>";
};

if(isset($_GET["appointment_design"]))
{
    echo "<section id='design' class='appointment_design'>";
    echo "<div id='addappointment' class='header'><h3>Add appointment</div>";
    echo DesignAppointment($_GET["appointment_design"]);
    echo "</section>";
}
else if(isset($_GET["treatment_design"]))
{
    echo "<section id='design' class='appointment_design'>";
    echo "<div id='addappointment' class='header'><h3>Add treatment</div>";
    echo DesignTreatment($_GET["treatment_design"]);
    echo "</section>";
}
else if(isset($_GET["surgery_design"]))
{
    echo "<section id='design' class='appointment_design'>";
    echo "<div id='addappointment' class='header'><h3>Add surgery</div>";
    echo DesignSurgery($_GET["surgery_design"]);
    echo "</section>";
}
else if(isset($_GET["medicine_design"]))
{
    echo "<section id='design' class='appointment_design'>";
    echo "<div id='addappointment' class='header'><h3>Add medicine</div>";
    echo DesignMedicine($_GET["medicine_design"]);
    echo "</section>";
};

echo "<section class=''>";
echo "<div id='surgery' class='header'><h3>Surgery list</h3></div>";
echo GetSurgery();
echo "</section>";

echo "<section class=''>";
echo "<div id='appointment' class='header'><h3>Appointment list</div>";
echo GetAppointment();
echo "</section>";

echo "<section class=''>";
echo "<div id='treatment' class='header'><h3>Treatment list</h3></div>";
echo GetTreatment();
echo "</section>";

echo "<section class=''>";
echo "<div id='customer' class='header'><h3>Customer list</h3></div>";
echo GetCustomer();
echo "</section>";

echo "<section class=''>";
echo "<div id='owner' class='header'><h3>Owner list</h3></div>";
echo GetOwner();
echo "</section>";

echo "<section class=''>";
echo "<div id='newsletter' class='header'><h3>Newsletter list</h3></div>";
echo GetNewsletter();
echo "</section>";

echo "<section class=''>";
echo "<div id='accounts' class='header'><h3>Account list</h3></div>";
echo GetAccount();
echo "</section>";

echo "<section class=''>";
echo "<div id='medicine' class='header'><h3>Medicine list</h3></div>";
echo GetMedicine();
echo "</section>";

echo "<section class=''>";
echo "<div id='appointment_types' class='header'><h3>Appointment types list</h3></div>";
echo GetAppointmentTypes();
echo "</section>";

echo "<section class=''>";
echo "<div id='specialisation' class='header'><h3>Specialisation list</h3></div>";
echo GetSpecialisation();
echo "</section>";

echo "<section class=''>";
echo "<div id='perrmission' class='header'><h3>Perrmission list</h3></div>";
echo GetPerrmission();
echo "</section>";

echo "<section class=''>";
echo "<div id='breed' class='header'><h3>Breed list</h3></div>";
echo GetBreed();
echo "</section>";

echo "<section class=''>";
echo "<div id='species' class='header'><h3>Species list</h3></div>";
echo GetSpecies();
echo "</section>";
?>

<footer>
    <span>Created and designed by devarkadiusz - 2022.</span>
</footer>
