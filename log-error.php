<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Se connecter</title>
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
            width: 300px;
            text-align: center;
        }
        input[type="text"], input[type="password"], input[type="submit"] {
            width: 100%;
            padding: 12px;
            margin: 5px 0;
            border: 1px solid #ccc;
            border-radius: 4px;
            box-sizing: border-box;
        }
        input[type="submit"] {
            background-color: #4CAF50;
            color: white;
            border: none;
            cursor: pointer;
            margin-bottom: 20px;
        }
        input[type="submit"]:hover {
            background-color: #45a049;
        }
        label {
            display: block;
            text-align: left;
            margin-top: 5px;
            margin-bottom: 0px;
            color: #888888;
            font-size: 11px;
        }
        .divider {
            width: 20%;
            height: 1px;
            background-color: #a9a9a9;
            margin: 30px auto 20px auto;
        }
        #loginForm {
            display: block; /* Initially hide the login form */
        }
        button {
            width: 100%;
            padding: 12px;
            margin: 5px 0;
            border: 1px solid #ccc;
            border-radius: 4px;
            box-sizing: border-box;
            background-color: #4caf50;
            color: white;
            cursor: pointer;
        }
        button:hover {
            background-color: #45a049;
        }
        .error-message {
            color: red;
            margin: 20px 10px;
            font-size: 12px;
            display: block; /* Initially hide the error message */
        }
    </style>
</head>
<body>
<div class="container">
    <img src="captiveportal-logo.png" alt="logo" style="width: 50px;">
    <h2 style="font-weight: bolder; letter-spacing: 8px; margin-top: 0px; color: #2F4F4F;">Bienvenue</h2>
    <div class="divider"></div>
    
    <!-- Buttons initially shown -->
    <button onclick="discoverOffers()">Découvrir nos offres</button>
    <button id="toggleLoginBtn">J'ai déjà un compte</button>

    <!-- Hidden login form -->
    <div id="loginForm">
        <div class="error-message" id="errorMessage">Veuillez vérifier vos identifiants.</div>
        <form method="post" action="$PORTAL_ACTION$">
            <label for="auth_user">Nom d'utilisateur <span style="color: red;">*</span></label>
            <input id="auth_user" name="auth_user" type="text" required>

            <label for="auth_pass">Mot de passe <span style="color: red;">*</span></label>
            <input id="auth_pass" name="auth_pass" type="password" required>

            <input name="redirurl" type="hidden" value="$PORTAL_REDIRURL$">
            <input name="zone" type="hidden" value="$PORTAL_ZONE$">
            <input name="accept" type="submit" value="Se connecter">
        </form>
    </div>
</div>

<script>
    // Placeholder function for "Découvrir nos offres"
    function discoverOffers() {
        window.location.href = 'https://pfsense.localdomain.com/purchase.php'; // Redirects to pay.php
    }
</script>

</body>
</html>
