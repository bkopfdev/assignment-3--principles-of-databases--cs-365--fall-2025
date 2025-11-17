<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8">
    <title>Ben's Password Database</title>
    <link rel="stylesheet" href="css/style.css">
  </head>
  <body>
    <header>
      <h1>Benjamin Kopf's Password Database</h1>
    </header>

    <form id="reset-form" method="post"
            action="<?php echo $_SERVER['PHP_SELF']; ?>">
        <input id="reset-form" type="submit" value="Reset Fields / Return Home">
    </form>

    <form id="insert-account" action = "<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
    <?php
        require_once 'includes/config.php';
        require_once 'includes/helpers.php';

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $option = $_POST['submitted'] ?? null;

            if ($option !== null) {
                switch ($option) {
                    case '1':
                        insert_user($_POST['user'] ?? '', $_POST['fname'] ?? '', $_POST['lname'] ?? '');
                        header('Location: ' . $_SERVER['PHP_SELF']);
                        break;
                    case '2':
                        insert_website($_POST['websiteName'] ?? '', $_POST['websiteUrl'] ?? '');
                        header('Location: ' . $_SERVER['PHP_SELF']);
                        break;
                    case '3':
                        register_account_at($_POST['userID'] ?? '', $_POST['webID'] ?? '', $_POST['password'] ?? '', $_POST['email'] ?? '', $_POST['comment'] ?? ''
                        );
                        header('Location: ' . $_SERVER['PHP_SELF']);
                        break;
                    case '4':
                        delete($_POST['table-delete'] ?? '', $_POST['delete-attribute'] ?? '', $_POST['match-attribute'] ?? '');
                        header('Location: ' . $_SERVER['PHP_SELF']);
                        break;
                    case '5':
                        update($_POST['table-update'] ?? '', $_POST['update-attribute'] ?? '', $_POST['new-value'] ?? '', $_POST['pattern-attribute'] ?? '', $_POST['pattern-value'] ?? '');
                        header('Location: ' . $_SERVER['PHP_SELF']);
                        break;
                    case '6':
                        search($_POST['table-search'] ?? '', $_POST['search-key'] ?? '');
                        break;
                }
                exit;
            }
        }
    ?>

    <!-- The SEARCH operation ====================================== -->
    <form id = "search" action = "<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
        <fieldset>
            <legend>SEARCH</legend>
            <p>Choose a table, and input the keyword you'd like to search.</p>
            <p>
                <label for="table-search">Which Table:</label>
                <select name = "table-search" id = "table-search" required>
                    <option value="users">Users</option>
                    <option value="websites">Websites</option>
                    <option value="accounts_at">Accounts at</option>
                </select>
            </p>
             <p>
                <label for="search-key">Keyword:</label>
                <input required type="text" id="search-key" name="search-key">
            </p>
            <p><input type="hidden" name="submitted" value="6"></p>
            <p><input id="search" type="submit" value="Search" /></p>
        </fieldset>
    </form>

     <!-- The INSERT USER operation ====================================== -->
    <form id = "insert-user" action = "<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
        <fieldset>
            <legend>INSERT NEW USER</legend>
            <p>Insert a new user into the database!</p>
            <p>
                <label for="user">Username:</label>
                <input required type="text" id="user" name="user">
            </p>
            <p>
                <label for="fname">First Name:</label>
                <input required type="text" id="fname" name="fname">
            </p>
            <p>
                <label for="lname">Last Name:</label>
                <input required type="text" id="lname" name="lname">
            </p>

            <p><input type="hidden" name="submitted" value="1"></p>
            <p><input id="insert-user" type="submit" value="Add User" /></p>
        </fieldset>
    </form>

     <!-- The INSERT WEBSITE operation ====================================== -->
    <form id = "insert-website" action = "<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
        <fieldset>
            <legend>INSERT NEW WEBSITE</legend>
            <p>Insert a new website into the database!</p>
            <p>
                <label for="websiteName">Website Name:</label>
                <input required type="text" id="websiteName" name="websiteName">
            </p>
            <p>
                <label for="websiteUrl">URL:</label>
                <input required type="text" id="websiteUrl" name="websiteUrl">
            </p>

            <p><input type="hidden" name="submitted" value="2"></p>
            <p><input id="insert-website" type="submit" value="Add Website" /></p>
        </fieldset>
    </form>

     <!-- The REGISTER ACCOUNT operation ====================================== -->
    <form id = "register-account" action = "<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
        <fieldset>
            <legend>REGISTER ACCOUNT</legend>
            <p>Relate a website to an account with a password!</p>
            <p>
                <label for="userID">User ID:</label>
                <input required type="text" id="userID" name="userID">
            </p>
            <p>
                <label for="webID">Website ID:</label>
                <input required type="text" id="webID" name="webID">
            </p>
            <p>
                <label for="password">Password:</label>
                <input required type="text" id="password" name="password">
            </p>
            <p>
                <label for="email">Email:</label>
                <input required type="email" id="email" name="email">
            </p>
            <p>
                <label for="comment">Comment:</label>
                <textarea id="message" name="comment"></textarea>
            </p>
            <p><input type="hidden" name="submitted" value="3"></p>
            <p><input id="register-account" type="submit" value="Register" /></p>
        </fieldset>
    </form>

     <!-- The DELETE operation ====================================== -->
    <form id = "delete" action = "<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
        <fieldset>
            <legend>DELETE a User, Website, or Account</legend>
            <p>Give the data you want to delete, and it will be removed!</p>
            <p>
                <label for="table-delete">Which Table:</label>
                <select name = "table-delete" id = "table-delete" required>
                    <option value="users">Users</option>
                    <option value="websites">Websites</option>
                    <option value="accounts_at">Accounts at</option>
                </select>
            </p>
            <p>
                <label for = "delete-attribute">Attribute to match off of:</label>
                <select name = "delete-attribute" id = "delete-attribute" required>
                    <option value="userId">User ID</option>
                    <option value="webId">Web ID</option>
                    <option value="fname">First Name</option>
                    <option value="lname">Last Name</option>
                    <option value="username">Username</option>
                    <option value="email">Email</option>
                    <option value="webName">Website Name</option>
                    <option value="webUrl">URL</option>
                    <option value="password">Password</option>
                    <option value="comment">Comment</option>
                </select>
                <input required type="text" id="match-attribute" name="match-attribute">
            </p>

            <p><input type="hidden" name="submitted" value="4"></p>
            <p><input id="delete-info" type="submit" value="Delete" /></p>
        </fieldset>
    </form>

     <!-- The UPDATE operation ====================================== -->
    <form id = "update" action = "<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
        <fieldset>
            <legend>UPDATE a User, Website, or Account</legend>
            <p>Give the table you want to update. Then, input the attribute you want to change, and what you'll change it to! Then, give it the value to pattern match what you want to change!</p>
            <p>
                <label for="table-update">Which Table:</label>
                <select name = "table-update" id = "table-update" required>
                    <option value="users">Users</option>
                    <option value="websites">Websites</option>
                    <option value="accounts_at">Accounts at</option>
                </select>
            </p>
            <p>
                <label for = "update-attribute">Attribute to change:</label>
                <select name = "update-attribute" id = "update-attribute" required>
                    <option value="userId">User ID</option>
                    <option value="webId">Web ID</option>
                    <option value="fname">First Name</option>
                    <option value="lname">Last Name</option>
                    <option value="username">Username</option>
                    <option value="email">Email</option>
                    <option value="webName">Website Name</option>
                    <option value="webUrl">URL</option>
                    <option value="password">Password</option>
                    <option value="comment">Comment</option>
                </select>
                <label for="new-value">New value:</label>
                <input required type="text" id="new-value" name="new-value">
            </p>
            <p>
                <label for = "pattern-attribute">Attribute to Search for:</label>
                <select name = "pattern-attribute" id = "pattern-attribute" required>
                    <option value="userId">User ID</option>
                    <option value="webId">Web ID</option>
                    <option value="fname">First Name</option>
                    <option value="lname">Last Name</option>
                    <option value="username">Username</option>
                    <option value="email">Email</option>
                    <option value="webName">Website Name</option>
                    <option value="webUrl">URL</option>
                    <option value="password">Password</option>
                    <option value="comment">Comment</option>
                </select>
                <label for="pattern-value">Pattern search value:</label>
                <input required type="text" id="pattern-value" name="pattern-value">
            </p>
            <p><input type="hidden" name="submitted" value="5"></p>
            <p><input id="update-attribute" type="submit" value="Update" /></p>
        </fieldset>
    </form>

  </body>
</html>
