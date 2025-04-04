<?php
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
            max-width: 500px;
            margin: 0 auto;
            padding: 20px;
        }
        .calculator {
            background-color: #f5f5f5;
            border-radius: 5px;
            padding: 20px;
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
        }
        h1 {
            text-align: center;
            color: #333;
        }
        .form-group {
            margin-bottom: 15px;
        }
        label {
            display: block;
            margin-bottom: 5px;
            font-weight: bold;
        }
        input, select {
            width: 100%;
            padding: 8px;
            border: 1px solid #ddd;
            border-radius: 4px;
            box-sizing: border-box;
        }
        button {
            background-color: #4CAF50;
            color: white;
            padding: 10px 15px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            width: 100%;
            font-size: 16px;
        }
        button:hover {
            background-color: #45a049;
        }
        .result {
            margin-top: 20px;
            padding: 10px;
            background-color: #e9f7ef;
            border-radius: 4px;
            text-align: center;
            font-size: 18px;
        }
        .error {
            color: red;
            text-align: center;
            margin-top: 10px;
        }
    </style>
</head>
<body>
    
    <?php
    if(isset($_POST["num1"], $_POST["num2"], $_POST["operator"])) {
        $num1 = (float) $_POST["num1"];
        $num2 = (float) $_POST["num2"];
        $op = (string) $_POST["operator"];

        if($op == "divide" && $num2 == 0) {
            $error = "Cannot divide by zero";
        }
        else {
            $calc = new simpleCalculator(x:$num1, y:$num2, operator:$op);
            $result = $calc->calculate();
        }
    }
    ?>

    <div class="calculator">
        <h1>Simple Calculator</h1>
        
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
</body>
</html>