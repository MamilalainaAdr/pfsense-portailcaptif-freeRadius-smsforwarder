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
        input[type="text"], input[type="password"],  input[type="number"] {
            width: 100%;
            padding: 12px;
            margin: 5px 0;
            border: 1px solid #ccc;
            border-radius: 4px;
            box-sizing: border-box;
        }
        /* input[type="submit"] {
            background-color: #4CAF50;
            color: white;
            border: none;
            cursor: pointer;
        }
        input[type="submit"]:hover {
            background-color: #45a049;
        } */
        label {
            display: block;
            text-align: left;
            margin-top: 5px;
            margin-bottom: 0px;
            color: #888888;
            font-size: 11px;
        }
        .input-container {
            display: flex;
            justify-content: space-between;
            margin-top: 10px;
        }
        .note {
            font-size: 10px;
            color: #999999;
            margin-top: 10px;
            margin-bottom: 10px;
            text-align: right;
        }
        .input-container input {
            width: 130px;
            margin-right: 10px;
        }
        .divider {
            width: 20%;
            height: 1px;
            background-color: #a9a9a9;
            margin: 30px auto 5px auto;
        }
        select, input[type="text"] {
            width: 100%;
            padding: 10px;
            margin-top: 10px;
            border: 1px solid #ccc;
            border-radius: 4px;
        }
        .payment-method {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 10px;
        }
        .payment-method select {
            width: 40%;
        }
        .payment-method input {
            width: 55%;
        }
        .buttons {
            display: flex;
            justify-content: space-between;
            gap: 10px;
            margin-top: 20px;
            margin-bottom: 30px;
        }
        .buttons button, .buttons input[type="submit"] {
            width: 48%; /* Ensures both buttons have the same width */
            padding: 12px;
            border: none;
            border-radius: 4px;
            background-color: #4CAF50;
            color: white;
            cursor: pointer;
            height: 45px; /* Ensures both buttons have the same height */
            display: inline-block;
            text-align: center;
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
    <form method="post" action="https://pfsense.localdomain.com/process.php" onsubmit="return validateForm()">
        <div class="divider"></div>

        <label style="font-weight: bolder; color: #4d4d4d; font-size: 13px; margin-top: 20px; margin-bottom: 0px;">Option de connexion :</label>
        <div class="input-container">
            <div>
                <label for="input_delay">Délai (en minutes) :</label>
                <input id="input_delay" name="input_delay" type="number" placeholder="..." oninput="handleInputChange('delay')">
            </div>
            <div>
                <label for="input_cost">Coût (en Ariary) :</label>
                <input id="input_cost" name="input_cost" type="number" placeholder="..." oninput="handleInputChange('cost')">
            </div>
        </div>

        <div class="note" id="result"></div>

        <label style="font-weight: bolder; color: #4d4d4d; font-size: 13px; margin-top: 20px; margin-bottom: 20px;">Paiement :</label>
        <label id="ref" for="ref">Veuillez effectuer votre paiement : </label>
        <div class="payment-method">
            <select id="payment-method" name="payment-method">
                <option value="Mvola">Mvola</option>
            </select>
            <input type="text" name="phone-number" placeholder="+261 38 30 613 56" disabled>
        </div>

        <label id="ref" for="ref">Référence de paiement : <span style="color: red;">*</span></label>
        <input type="text" name="ref" placeholder="xxxxxxxxxx" required>

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
    function handleInputChange(type) {
        const delayInput = document.getElementById('input_delay');
        const costInput = document.getElementById('input_cost');
        const hiddenDelay = document.getElementById('hidden_delay');
        const hiddenCost = document.getElementById('hidden_cost');

        // Reset other input when one is selected
        if (type === 'delay') {
            costInput.value = '';
            hiddenDelay.value = delayInput.value;
            hiddenCost.value = delayInput.value * 80; // Update cost based on delay
        } else {
            delayInput.value = '';
            hiddenCost.value = costInput.value;
            hiddenDelay.value = (costInput.value / 80).toFixed(2); // Update delay based on cost
        }

        calculateResult();
    }

    function calculateResult() {
        const delay = parseFloat(document.getElementById('input_delay').value) || 0;
        const cost = parseFloat(document.getElementById('input_cost').value) || 0;
        const resultDiv = document.getElementById('result');

        let result = "";

        if (delay >= 5) {
            result = `Coût: ${delay * 80} Ariary`;
        } else if (cost >= 400) {
            result = `Délai: ${(cost / 80).toFixed(2)} minutes`;
        } else {
            result = "";
        }

        resultDiv.textContent = result;
    }

    function validateForm() {
        const delay = parseFloat(document.getElementById('input_delay').value) || 0;
        const cost = parseFloat(document.getElementById('input_cost').value) || 0;

        if (delay < 5 && cost < 400) {
            alert("Le délai minimum est de 5 minutes ou le coût minimum est de 400 Ariary.");
            return false;
        }

        return true;
    }

    function goBack() {
        window.history.back();
    }
</script>

</body>
</html>
