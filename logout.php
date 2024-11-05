<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="content-type" content="text/html; charset=UTF-8" />
    <title>Se déconnecter</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f5f5f5;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
        }

        .container {
            background-color: white;
            padding: 40px;
            border-radius: 10px;
            box-shadow: 0px 10px 20px rgba(0, 0, 0, 0.1);
            width: 290px;
            max-height: 80vh;
            overflow-y: auto;
            text-align: center;
        }

        .container h2 {
            font-weight: bolder;
            letter-spacing: 2px;
            margin-top: 0px;
            color: #4CAF50;
        }

        input[type="submit"] {
            background: none;
            border: none;
            color: #333333;
            font-size: 13px;
            text-decoration: none;
            cursor: pointer;
            padding: 0;
            margin-bottom: 5px;
        }

        input[type="submit"]:hover {
            color: #000000;
            text-decoration: underline;
        }

        .divider {
            width: 20%;
            height: 1px;
            background-color: #a9a9a9;
            margin: 30px auto 15px auto;
        }

    </style>
</head>

<body>

    <div class="container">
        <img src="captiveportal-logo.png" alt="logo" style="width: 50px;">
        <h2>Session active</h2>

        <form method="post" action="<?=$logouturl;?>">
            <input type="hidden" name="redirurl" value="$PORTAL_REDIRURL$">
            <input type="hidden" name="zone" value="$PORTAL_ZONE$">
            <input name="logout_id" type="hidden" value="<?=$sessionid;?>" />
            <input name="zone" type="hidden" value="<?=$cpzone;?>" />
            <div class="divider"></div>
            <input name="logout" type="submit" value="Se déconnecter">
        </form>
    </div>

</body>
</html>