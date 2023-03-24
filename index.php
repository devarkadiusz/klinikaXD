<a href="./account/login"><button>Login</button></a>
<form action="./assets/worker.php" method="POST">
    <input type="hidden" name="insert" value="newsletter" />
    <input type="name" name="name" placeholder="Name" />
    <input type="email" name="email" placeholder="Email" <?php echo (isset($_GET['newsletter']) && $_GET['newsletter'] == 'email') ? 'class="red"' : ''; ?> />
    <input type="submit" value="Submit" />
</form>