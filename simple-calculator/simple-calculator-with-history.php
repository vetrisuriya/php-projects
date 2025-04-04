<?php
session_start();

require "./class/simple-calculator.php";
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Simple Calculator</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            max-width: 800px;
            margin: 0 auto;
            padding: 20px;
            background-color: #f9f9f9;
        }
        .container {
            background-color: #fff;
            padding: 25px;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0,0,0,0.1);
        }
        h1 {
            color: #2c3e50;
            text-align: center;
            margin-bottom: 30px;
            border-bottom: 2px solid #eaeaea;
            padding-bottom: 10px;
        }
        .form-row {
            display: flex;
            flex-wrap: wrap;
            margin: 0 -10px;
        }
        .form-group {
            flex: 1;
            margin: 0 10px 20px;
            min-width: 200px;
        }
        label {
            display: block;
            margin-bottom: 8px;
            font-weight: bold;
            color: #444;
        }
        input, select {
            width: 100%;
            padding: 12px;
            border: 1px solid #ddd;
            border-radius: 6px;
            box-sizing: border-box;
            font-size: 16px;
            transition: border-color 0.3s;
        }
        input:focus, select:focus {
            border-color: #3498db;
            outline: none;
        }
        .checkbox-group {
            margin: 15px 0;
        }
        .checkbox-label {
            display: flex;
            align-items: center;
            cursor: pointer;
        }
        .checkbox-label input {
            width: auto;
            margin-right: 8px;
        }
        button {
            background-color: #3498db;
            color: white;
            padding: 12px 20px;
            border: none;
            border-radius: 6px;
            cursor: pointer;
            font-size: 16px;
            width: 100%;
            transition: background-color 0.3s;
        }
        button:hover {
            background-color: #2980b9;
        }
        .result {
            margin-top: 25px;
            padding: 20px;
            background-color: #e7f3fe;
            border-left: 6px solid #2196F3;
            border-radius: 4px;
        }
        .all-results {
            margin-top: 25px;
        }
        .all-results h3 {
            margin-bottom: 15px;
            color: #2c3e50;
        }
        .history {
            margin-top: 40px;
        }
        .history h3 {
            color: #2c3e50;
            border-bottom: 1px solid #eaeaea;
            padding-bottom: 10px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 15px;
        }
        table th, table td {
            padding: 12px;
            text-align: left;
            border-bottom: 1px solid #eaeaea;
        }
        table th {
            background-color: #f5f5f5;
            font-weight: bold;
            color: #555;
        }
        table tr:hover {
            background-color: #f9f9f9;
        }
        .unit-info {
            margin-top: 40px;
            background-color: #f9f9f9;
            padding: 20px;
            border-radius: 6px;
        }
        .unit-info h3 {
            color: #2c3e50;
            margin-bottom: 15px;
        }
        .unit-info p {
            margin-bottom: 10px;
            line-height: 1.5;
        }
        .formula-list {
            list-style-type: none;
            padding: 0;
        }
        .formula-list li {
            margin-bottom: 8px;
            padding-left: 20px;
            position: relative;
        }
        .formula-list li:before {
            content: "•";
            position: absolute;
            left: 0;
            color: #3498db;
        }
        .tabs {
            display: flex;
            margin-bottom: 20px;
        }
        .tab {
            padding: 10px 20px;
            background-color: #f1f1f1;
            border-radius: 5px 5px 0 0;
            cursor: pointer;
            margin-right: 5px;
        }
        .tab.active {
            background-color: #3498db;
            color: white;
        }
        .tab-content {
            display: none;
        }
        .tab-content.active {
            display: block;
        }
    </style>
</head>
<body>

    <?php
    if(isset($_GET["clear"]) && $_GET["clear"] == "history") {
        unset($_SESSION["history"]);
    }

    if(isset($_POST["num1"], $_POST["num2"], $_POST["operator"])) {
        $num1 = (float) $_POST["num1"];
        $num2 = (float) $_POST["num2"];
        $op = (string) $_POST["operator"];

        if($op == "divide" && $num2 == 0) {
            $error = "Cannot divide by zero";
        }
        else {
            $calc = new simpleCalculator(x:$num1, y:$num2, operator:$op);
            $calc->storeHistory();
            $result = $calc->calculate();
        }
    }

    if(isset($_SESSION["history"])) {
        $histories = array_reverse($_SESSION["history"]);
    }
    ?>

    <div class="container">
        <h1>Simple Calculator With History</h1>
        
        <div class="tabs">
            <div class="tab active" onclick="showTab('calculator')">Calculator</div>
            <div class="tab" onclick="showTab('history')">History</div>
        </div>
        
        <div id="calculator" class="tab-content active">
            <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
                <div class="form-group">
                    <label for="num1">First Number:</label>
                    <input type="text" id="num1" name="num1" value="<?php echo $result ?? ''; ?>" required>
                </div>
                
                <div class="form-group">
                    <label for="operator">Operation:</label>
                    <select id="operator" name="operator" required>
                        <option value="add">Addition (+)</option>
                        <option value="subtract">Subtraction (-)</option>
                        <option value="multiply">Multiplication (×)</option>
                        <option value="divide">Division (÷)</option>
                    </select>
                </div>
                
                <div class="form-group">
                    <label for="num2">Second Number:</label>
                    <input type="text" id="num2" name="num2" required>
                </div>
                
                <button type="submit">Calculate</button>
            </form>
            
            <?php if(isset($error)): ?>
                <div class="error"><?php echo $error; ?></div>
            <?php endif; ?>
            <div class="result">Result: <?php echo $result ?? 0; ?></div>
        </div>
        
        <div id="history" class="tab-content">
            <div class="unit-info">
                <h3><?php if(isset($histories)): ?><a href="?clear=history">Clear History</a><?php endif; ?></h3>
                
                <ul class="formula-list">
                    <?php
                    if(isset($histories)) {
                        foreach($histories as $history) {
                    ?>
                        <li><?= $history; ?></li>
                    <?php
                        }
                    }
                    ?>
                </ul>
            </div>
        </div>
    </div>

    <script>
        function showTab(tabId) {
            // Hide all tabs
            var tabContents = document.getElementsByClassName('tab-content');
            for (var i = 0; i < tabContents.length; i++) {
                tabContents[i].classList.remove('active');
            }
            
            // Deactivate all tab buttons
            var tabs = document.getElementsByClassName('tab');
            for (var i = 0; i < tabs.length; i++) {
                tabs[i].classList.remove('active');
            }
            
            // Show the selected tab
            document.getElementById(tabId).classList.add('active');
            
            // Activate the clicked tab button
            var clickedTab = event.currentTarget;
            clickedTab.classList.add('active');
        }
    </script>
</body>
</html>