<?php

require "./class/bmi-calculator.php";


if($_SERVER["REQUEST_METHOD"] == "POST") {


    if(isset($_POST["height"], $_POST["weight"]) && strlen($_POST["height"]) > 0 && strlen($_POST["weight"]) > 0) {
        $height = (float) $_POST["height"];
        $weight = (float) $_POST["weight"];
        $unit = (string) $_POST["unit_system"];

        if(!$height || !$weight) {
            $error = "Input value won't accept 0";
        } else {
            $bmi = new bmiCalculator($height, $weight, unit: $unit);
            [$score, $category, $color] = (array) $bmi->calculate();
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>BMI Calculator</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
            background-color: #f5f5f5;
        }
        h1 {
            text-align: center;
            color: #333;
        }
        .container {
            background-color: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
        .form-group {
            margin-bottom: 15px;
        }
        label {
            display: block;
            margin-bottom: 5px;
            font-weight: bold;
        }
        input[type="number"], select {
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
            font-size: 16px;
            width: 100%;
        }
        button:hover {
            background-color: #45a049;
        }
        .result {
            margin-top: 20px;
            padding: 15px;
            border-radius: 4px;
            text-align: center;
        }
        .error {
            color: #e74c3c;
            text-align: center;
        }
        .bmi-info {
            margin-top: 20px;
            background-color: #f9f9f9;
            padding: 15px;
            border-radius: 4px;
        }
        .unit-toggle {
            display: flex;
            margin-bottom: 15px;
        }
        .unit-toggle label {
            flex: 1;
            text-align: center;
            padding: 10px;
            background-color: #eee;
            cursor: pointer;
        }
        .unit-toggle input[type="radio"] {
            display: none;
        }
        .unit-toggle input[type="radio"]:checked + label {
            background-color: #4CAF50;
            color: white;
        }
        .unit-toggle label:first-of-type {
            border-radius: 4px 0 0 4px;
        }
        .unit-toggle label:last-of-type {
            border-radius: 0 4px 4px 0;
        }
    </style>
</head>
<body>
    <h1>BMI Calculator</h1>
    <div class="container">
        <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
            <div class="unit-toggle">
                <input type="radio" id="metric_cm" name="unit_system" value="cm" checked>
                <label for="metric_cm">(kg/cm)</label>
                <input type="radio" id="metric_m" name="unit_system" value="m">
                <label for="metric_m">(kg/m)</label>
            </div>

            <div class="form-group">
                <label for="weight">Weight:</label>
                <input type="number" step="0.01" id="weight" name="weight" required>
            </div>

            <div class="form-group">
                <label for="height">Height:</label>
                <input type="number" step="0.01" id="height" name="height" required>
            </div>

            <button type="submit">Calculate BMI</button>
        </form>

        <?php if(isset($error) && !empty($error)): ?>
            <div class="error">
                <p><?php echo $error; ?></p>
            </div>
        <?php endif; ?>

        <?php if(isset($score)): ?>
            <div class="result" style="background-color: <?php echo $color; ?>; color: white;">
                <h2>Your BMI: <?php echo $score; ?></h2>
                <p>Category: <?php echo $category; ?></p>
            </div>
        <?php endif; ?>

        <div class="bmi-info">
            <h3>BMI Categories:</h3>
            <ul>
                <li>Underweight: BMI less than 18.5</li>
                <li>Normal weight: BMI 18.5 to 24.9</li>
                <li>Overweight: BMI 25 to 29.9</li>
                <li>Obesity: BMI 30 or greater</li>
            </ul>
            
            <h3>BMI Formula:</h3>
            <p style="margin: 5px 0px;"><strong>Mixed Unit:</strong> BMI = weight(kg) / height(m = (height(cm) / 100))²</p>
            <p style="margin: 5px 0px;"><strong>Metric System:</strong> BMI = weight(kg) / height(m)²</p>
            
            <p><small>Note: BMI is a screening tool but not diagnostic of body fatness or health.</small></p>
        </div>
    </div>
</body>
</html>