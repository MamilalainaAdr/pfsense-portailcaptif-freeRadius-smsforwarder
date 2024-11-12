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
            font-family: Arial, sans-serif;
            font-size: 13px;
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
            width: 260px;
            max-height: 80vh;
            overflow-y: auto;
            text-align: center;
        }
        button {
            display: inline-block;
            padding: 10px 20px;
            font-size: 14px;
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
            font-size: 12px;
        }
        .primary_hide p{
            margin: 0;
            padding: 0;
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
        <h2 style="font-weight: bolder; margin-top: 0px; color: #155724;">Inscription réussie !</h2>
        <button id="toggleButton">Voir mes identifiants</button>
        <div id="credentials" class="primary_hide">
            <p>Nom: [Votre référence]</p>
            <p>Mot de passe: [Votre référence]</p>
        </div>
        <a href="http://192.168.100.1:8002/index.php?zone=lan">Retour à l'accueil</a>
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
