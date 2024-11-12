<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Erreur</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f8d7da;
            display: flex;
            justify-content: center;
            font-size: 13px;
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
        p {
            color: black;
            margin: 20px 0;
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
        <h2 style="font-weight: bolder; margin-top: 0px; color: #721c24;">Oups!</h2>
        <p>Une erreur s'est produite. Veuillez vérifier vos informations et réessayer.</p>
        <a href="http://192.168.100.1:8002/index.php?zone=lan">Retour à l'accueil</a>
    </div>
</body>
</html>
