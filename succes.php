<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Succès</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #d4edda;
        }
        .container {
            margin: 50px auto;
            width: 50%;
            max-width: 400px;
            text-align: center;
            padding: 20px;
            border: 2px solid #c3e6cb;
            background-color: #d4edda;
            border-radius: 8px;
        }
        h1 {
            color: #155724;
        }
        button {
            display: inline-block;
            padding: 10px 20px;
            font-size: 16px;
            color: white;
            background-color: #aaaaaa;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }
        button:hover {
            background-color: #383838;
        }
        .primary_hide {
            display: none;
            margin-top: 20px;
            color: black;
        }
        a {
            display: block;
            margin-top: 20px;
            text-decoration: underline;
        }
        a:hover {
            text-decoration: underline;
            color: rgb(1, 1, 201);
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Inscription réussie !</h1>
        <button id="toggleButton">Voir mes identifiants</button>
        <div id="credentials" class="primary_hide">
            <p>Nom: [Votre référence]</p>
            <p>Mot de passe: [Votre référence]</p>
        </div>
        <a href="https://pfsense.localdomain.com:8003/index.php?zone=lan">Retour à l'accueil</a>
    </div>

    <script>
        // Toggle visibility of the credentials div
        document.getElementById("toggleButton").addEventListener("click", function() {
            const credentials = document.getElementById("credentials");
            if (credentials.style.display === "none" || credentials.style.display === "") {
                credentials.style.display = "block";
            } else {
                credentials.style.display = "none";
            }
        });
    </script>
</body>
</html>
