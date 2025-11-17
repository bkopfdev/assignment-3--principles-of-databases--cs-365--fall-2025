<?php

/**
 * insert_user($username, $fname, $lname)
 *
 * Takes in a username, the first name, and last name.
 * Inserts a new user into the users database with that information
 *
 * @param string $username Username of the user that will be added
 * @param string $fname The first name of the user that will be added
 * @param string $lname The last name of the user that will be added
 *
*/
function insert_user($username, $fname, $lname) {
    include_once "config.php";

    try {
        $db = new PDO(
            "mysql:host=" . DBHOST . "; dbname=" . DBNAME . ";charset=utf8",
            DBUSER,
            DBPASS
        );

        $statement = $db -> prepare("INSERT INTO users (username, fname, lname) VALUES (\"{$username}\", \"{$fname}\", \"{$lname}\")");
        $statement -> execute();

        $statement = null;
    }
    catch(PDOException $error) {
        echo "<p class='highlight'>The function " .
            "<code>valueExistsInAttribute</code> has generated the " .
            "following error:</p>" .
            "<pre>$error</pre>" .
            "<p class='highlight'>Exiting…</p>";

        exit;
    }
}

/**
 *
 * insert_website($websiteName, $websiteURL)
 *
 * Takes a website name and URL. Inserts it into the websites table.
 *
 * @param string $websiteName The name of the website that will be added
 * @param string $websiteURL The URL of the website that will be added
 *
 */
function insert_website($websiteName, $websiteURL) {
    include_once "config.php";

    try {
        $db = new PDO(
            "mysql:host=" . DBHOST . "; dbname=" . DBNAME . ";charset=utf8",
            DBUSER,
            DBPASS
        );

        $statement = $db -> prepare("INSERT INTO websites (webName, webUrl) VALUES (\"{$websiteName}\", \"{$websiteURL}\")");
        $statement -> execute();

        $statement = null;
    }
    catch(PDOException $error) {
        echo "<p class='highlight'>The function " .
            "<code>valueExistsInAttribute</code> has generated the " .
            "following error:</p>" .
            "<pre>$error</pre>" .
            "<p class='highlight'>Exiting…</p>";

        exit;
    }
}

/**
 *
 * register_account_at($userId, $webId, $password, $email, $comment)
 *
 * Takes in a user id, web id, password, email, and comment.
 * Inserts information into the account_at table, which is the relation between users and websites.
 *
 * @param string $userId The user id of the account that will be linked
 * @param string $webId The web id of the account that will be linked
 * @param string $password The password of the account that will be linked
 * @param string $email The email of the account that will be linked
 * @param string $comment The comment of the account that will be linked
 *
 */
function register_account_at($userId, $webId, $password, $email, $comment) {
    include_once "config.php";

    try {
        $db = new PDO(
            "mysql:host=" . DBHOST . "; dbname=" . DBNAME . ";charset=utf8",
            DBUSER,
            DBPASS
        );

        $statement = $db -> prepare("INSERT INTO accounts_at (userId, webId, password, email, comment) VALUES (\"{$userId}\", \"{$webId}\", AES_ENCRYPT(\"{$password}\", '" . KEY_STR . "', '" . INIT_VECTOR . "'), \"{$email}\", \"{$comment}\")");        $statement -> execute();

        $statement = null;
    }
    catch(PDOException $error) {
        echo "<p class='highlight'>The function " .
            "<code>valueExistsInAttribute</code> has generated the " .
            "following error:</p>" .
            "<pre>$error</pre>" .
            "<p class='highlight'>Exiting…</p>";

        exit;
    }
}


/**
 *
 * delete($table, $attribute, $pattern_match)
 *
 * Deletes an entry from the given table, where the given attribute matches the pattern
 * If the user chooses to delete a user or website via their userId or webId, the program will
 * also delete the entries associated with that user or website in the accounts_at table.
 *
 * @param string $table The table to delete from
 * @param string $attribute The attribute to match on
 * @param string $pattern_match The value to match on
 *
 */
function delete($table, $attribute, $pattern_match) {
    include_once "config.php";

    try {
        $db = new PDO(
            "mysql:host=" . DBHOST . "; dbname=" . DBNAME . ";charset=utf8",
            DBUSER,
            DBPASS
        );

        # Ran into situation where if the attribute to match is the password, its encrypted. So this fixes that by encrypting the given value if it's a password
        if ($attribute === 'password') {
            $statement = $db -> prepare("DELETE FROM `{$table}` WHERE `{$attribute}` = AES_ENCRYPT(\"{$pattern_match}\", '" . KEY_STR . "', '" . INIT_VECTOR . "')");
        } else {
            $statement = $db -> prepare("DELETE FROM `{$table}` WHERE `{$attribute}` = \"{$pattern_match}\"");
        }
        $statement -> execute();

        # If you were to delete a user using their userId, the program will delete all the accounts_at entries associated to that user.
        if($table === 'users' && $attribute === 'userId') {
            $statement = $db -> prepare("DELETE FROM accounts_at WHERE userId = \"{$pattern_match}\"");
            $statement -> execute();
        }

        # Does the same thing as above, but instead with the websites
        if($table === 'websites' && $attribute === 'webId') {
            $statement = $db -> prepare("DELETE FROM accounts_at WHERE webId = \"{$pattern_match}\"");
            $statement -> execute();
        }

        $statement = null;
    }
    catch(PDOException $error) {
        echo "<p class='highlight'>The function " .
            "<code>valueExistsInAttribute</code> has generated the " .
            "following error:</p>" .
            "<pre>$error</pre>" .
            "<p class='highlight'>Exiting…</p>";

        exit;
    }
}

/**
 *
 * update($table, $attribute, $new_value, $pattern_attribute, $pattern_value)
 *
 * Updates an entry in the table by using a pattern attribute.
 * Replaces the attribute given with the new value, in an entry where the pattern attribute matches the pattern value.
 *
 * @param string $table The table to update
 * @param string $attribute The attribute to update
 * @param string $new_value The new value to set the attribute to
 * @param string $pattern_attribute The attribute to match the row on
 * @param string $pattern_value The value to match on
 *
 */
function update($table, $attribute, $new_value, $pattern_attribute, $pattern_value) {
    include_once "config.php";

    try {
        $db = new PDO(
            "mysql:host=" . DBHOST . "; dbname=" . DBNAME . ";charset=utf8",
            DBUSER,
            DBPASS
        );

        $statement = $db -> prepare("UPDATE `{$table}` SET `{$attribute}` = '{$new_value}' WHERE `{$pattern_attribute}` = '{$pattern_value}'");
        $statement -> execute();
        $statement = null;
    } catch(PDOException $error) {
        echo "<p class='highlight'>The function " .
            "<code>valueExistsInAttribute</code> has generated the " .
            "following error:</p>" .
            "<pre>$error</pre>" .
            "<p class='highlight'>Exiting…</p>";

        exit;
    }
}

/**
 *
 * search($table, $search_key)
 *
 * Takes in a table and key, and will generate an HTML table of the related entries where the key is somewhere in the row.
 *
 * @param string $table The table to search
 * @param string $search_key The key to search for
 *
 */
function search($table, $search_key) {
    include_once "config.php";

    try {
        $db = new PDO(
            "mysql:host=" . DBHOST . "; dbname=" . DBNAME . ";charset=utf8",
            DBUSER,
            DBPASS,
            [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]
        );

        if ("websites" === $table) {
            $query = "SELECT webName, webUrl, webId FROM websites WHERE webName LIKE '%{$search_key}%' OR webUrl LIKE '%{$search_key}%'";
            $statement = $db -> prepare($query);
            $statement -> execute();
            $statement = null;

            # I dont think the spaces are necessary, but they help keep me organized
            # There may also be a way to include this all in one echo command, but this was way easier for me to read and debug.
            echo "<table>";
            echo "    <tr>";
            echo "      <th>Web ID</th>";
            echo "      <th>Website</th>";
            echo "      <th>URL</th>";
            echo "    </tr>";
            echo "<tbody>";

            # Iterates through the results of the query written at the top, and prints the results out into the table.
            foreach ($db ->query($query) as $row) {
                echo "<tr>";
                echo "  <td>" . htmlspecialchars((string)($row[2] ?? '')) . "</td>";
                echo "  <td>" . htmlspecialchars($row[0]) . "</td>";
                echo "  <td>" . htmlspecialchars($row[1]) . "</td>";
                echo "</tr>";
            }
            # Closes the table
            echo "</tbody>";
            echo "</table>";
        }

        elseif ("users" === $table) {
            $query = "SELECT userId, username, fname, lname FROM users WHERE username LIKE '%{$search_key}%' OR fname LIKE '%{$search_key}%' OR lname LIKE '%{$search_key}%'";
            $statement = $db -> prepare($query);
            $statement -> execute();
            $statement = null;

             # I dont think the spaces are necessary, but they help keep me organized
            echo "<table>";
            echo "    <tr>";
            echo "      <th>User ID</th>";
            echo "      <th>Username</th>";
            echo "      <th>First Name</th>";
            echo "      <th>Last Name</th>";
            echo "    </tr>";
            echo "<tbody>";

            # Same as written above
            foreach ($db ->query($query) as $row) {
                echo "<tr>\n";
                echo "  <td>" . htmlspecialchars((string)($row[0] ?? '')) . "</td>";
                echo "  <td>" . htmlspecialchars($row[1]) . "</td>";
                echo "  <td>" . htmlspecialchars($row[2]) . "</td>";
                echo "  <td>" . htmlspecialchars($row[3]) . "</td>";
                echo "</tr>";
            }
            echo "</tbody>";
            echo "</table>";
        }

        # Full transparency: There is a slight issue with the query that gets the password to print to the user. I've rewritten the line about a hundred times, and this was the best way I could get it to kind of work. It wont print all the passwords like it should, but if you add any new users into the database, it will show you those passwords. I have no idea why it wont show all of them, but I unfortunately caught this issue too late for me to ask you for help.
        elseif ("accounts_at" === $table) {
            $query = "SELECT userId, webId, CAST(AES_DECRYPT(password,'" . KEY_STR . "','" . INIT_VECTOR . "') AS CHAR), email, comment FROM accounts_at WHERE userId LIKE '%{$search_key}%' OR webId LIKE '%{$search_key}%' OR email LIKE '%{$search_key}%' OR comment LIKE '%{$search_key}%'";
            $statement = $db -> prepare($query);
            $statement -> execute();
            $statement = null;

             # I dont think the spaces are necessary, but they help keep me organized
            echo "<table>";
            echo "    <tr>";
            echo "      <th>User ID</th>";
            echo "      <th>Web ID</th>";
            echo "      <th>Password</th>";
            echo "      <th>Email</th>";
            echo "      <th>Comment</th>";
            echo "    </tr>\n";
            echo "<tbody>\n";

            # Same as written above
            foreach ($db ->query($query) as $row) {
                echo "<tr>";
                echo "  <td>" . htmlspecialchars((string)($row[0] ?? '')) . "</td>";
                echo "  <td>" . htmlspecialchars((string)($row[1] ?? '')) . "</td>";
                echo "  <td>" . htmlspecialchars((string)($row[2] ?? '')) . "</td>";
                echo "  <td>" . htmlspecialchars((string)($row[3] ?? '')) . "</td>";
                echo "   <td>" . htmlspecialchars((string)($row[4] ?? '')) . "</td>";
                echo "</tr>\n";
            }
            echo "</tbody>\n";
            echo "</table>\n";
        }

    } catch(PDOException $error) {
        echo "<p class='highlight'>The function " .
            "<code>valueExistsInAttribute</code> has generated the " .
            "following error:</p>" .
            "<pre>$error</pre>" .
            "<p class='highlight'>Exiting…</p>";

        exit;
    }
}

?>
