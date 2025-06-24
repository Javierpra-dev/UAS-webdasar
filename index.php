<html>
    <head>
        <title>::Login Page::</title>
        <link rel="stylesheet" href="style.css">
    </head>
    <body>
         <form action="process_login.php" method="POST">
         <table>
            <tr>
                <td colspan="2" class="title">LOGIN</td>
            </tr>
            <tr>
                <td>Username</td>
                <td><input type="text" name="username" required /></td>
            </tr>
            <tr>
                <td>Password</td>
                <td><input type="password" name="password" required /></td>
            </tr>
            <tr>
                <td colspan="2"><input type="checkbox" /> Ingatkan saya</td>
            </tr>
            <tr>
                <td colspan="2" class="full-row"><input type="submit" value="SUBMIT" /></td>
            </tr>
        </table>
         </form>
    </body>
</html>

