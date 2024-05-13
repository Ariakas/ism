<?php
    require "vendor/autoload.php";
    Ism\DB::init();
    $users = Ism\DB::get_users_with_transactions();
    Ism\DB::destroy();
?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>International Service Management</title>
    <link rel="stylesheet" href="assets/css/style.css">
    <script defer src="assets/js/Fetch.js"></script>
    <script defer src="assets/js/Functions.js"></script>
    <script defer src="assets/js/script.js"></script>
</head>
<body>
    <label for="users">Select user:</label>
    <select id="users">
        <option></option>
    <?php foreach ($users as $user): ?>
        <option value="<?= $user["id"] ?>"><?= $user["name"] ?></option>
    <?php endforeach; ?>
    </select>
    <table>
        <thead>
            <tr>
                <th>Month</th>
                <th>Balance</th>
            </tr>
        </thead>
        <tbody id="balance_table">

        </tbody>
    </table>
</body>
</html>