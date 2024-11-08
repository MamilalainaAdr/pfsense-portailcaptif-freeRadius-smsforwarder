<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Acheter</title>
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
        input[type="text"], input[type="password"], input[type="number"], select {
            width: 100%;
            padding: 12px;
            margin: 5px 0;
            border: 1px solid #ccc;
            border-radius: 4px;
            box-sizing: border-box;
        }
        label {
            display: block;
            text-align: left;
            margin-top: 5px;
            color: #888888;
            font-size: 11px;
        }
        .input-container {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-top: 10px;
        }
        select {
            color: #666666;
        }
        .note {
            font-size: 13px;
            font-weight: bold;
            color: #000080;
            margin-top: 20px;
            margin-left: 10px;
        }
        .divider {
            width: 20%;
            height: 1px;
            background-color: #a9a9a9;
            margin: 30px auto 5px auto;
        }
        .payment-method {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 10px;
        }
        .buttons {
            display: flex;
            justify-content: space-between;
            gap: 10px;
            margin-top: 20px;
        }
        .buttons button, .buttons input[type="submit"] {
            width: 48%;
            padding: 12px;
            border: none;
            border-radius: 4px;
            background-color: #4CAF50;
            color: white;
            cursor: pointer;
            height: 45px;
        }
        .buttons button {
            background-color: #ccc;
        }
        .buttons button:hover {
            background-color: #b0b0b0;
        }
        .buttons input[type="submit"]:hover {
            background-color: #45a049;
        }
    </style>
</head>
<body>
<div class="container">
    <img src="captiveportal-logo.png" alt="logo" style="width: 50px;">
    <h2 style="font-weight: bolder; margin-top: 0px; color: #2F4F4F;">Découvrir nos offres</h2>
    <form method="post" action="https://pfsense.localdomain.com/process.php" >
        <div class="divider"></div>

        <label style="font-weight: bolder; color: #4d4d4d; font-size: 13px; margin-top: 20px; margin-bottom: 0px;">Option de connexion :</label>
        <div class="input-container">
            <div>
                <label for="input_delay">Tarif :</label>
                <select id="input_delay" name="input_delay" onchange="updateCost()" required title="Veuillez choisir une option">
                    <option value="" disabled selected>...</option>
                    <option value="30">30 minutes</option>
                    <option value="60">1 heure</option>
                    <option value="120">2 heures</option>
                    <option value="180">3 heures</option>
                    <option value="240">4 heures</option>
                    <option value="480">8 heures</option>
                </select>
            </div>
            <div class="note" id="result">0 Ariary</div>
        </div>

        <label style="font-weight: bolder; color: #4d4d4d; font-size: 13px; margin-top: 20px; margin-bottom: 20px;">Paiement (via MVola au <b><strong style="font-size: 14px;">038 30 613 56</strong></b>)</label>

        <label id="ref" for="ref">Référence de paiement : <span style="color: red;">*</span></label>
        <input type="text" name="ref" placeholder="xxxxxxxxxx" required minlength="6" title="Veuillez renseigner ce champ">

        <!-- Hidden fields to store delay and cost -->
        <input type="hidden" name="hidden_delay" id="hidden_delay" value="">
        <input type="hidden" name="hidden_cost" id="hidden_cost" value="">

        <div class="buttons">
            <button type="button" onclick="goBack()">Retour</button>
            <input type="submit" value="Confirmer">
        </div>
        
    </form>
</div>

<script>
    function updateCost() {
        const delaySelect = document.getElementById('input_delay');
        const resultDiv = document.getElementById('result');
        const hiddenDelay = document.getElementById('hidden_delay');
        const hiddenCost = document.getElementById('hidden_cost');
        const costPerMinute = 50;
        
        // Si aucune option valide n'est sélectionnée, on masque le coût
        if (delaySelect.value === "" || delaySelect.value === "...") {
            resultDiv.textContent = "0 Ariary"; // Effacer le texte de coût
            hiddenDelay.value = "";
            hiddenCost.value = "";
        } else {
            const delay = parseInt(delaySelect.value);
            const cost = delay * costPerMinute;
            
            hiddenDelay.value = delay;
            hiddenCost.value = cost;

            const formattedCost = cost.toLocaleString('fr-FR');
            
            resultDiv.textContent = `${formattedCost} Ariary`;
        }
}

    function goBack() {
        window.history.back();
    }

    // Initialize cost display based on the first option selected
    document.addEventListener("DOMContentLoaded", updateCost);
</script>

</body>
</html>
