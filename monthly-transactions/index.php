<?php

require "./class/autoload.php";

const DATAS_PATH = "./datas/";

$datas = new LoadDatas(DATAS_PATH);
$file_datas = $datas->renderFiles();
if(isset($_POST["csv_data"]) && $_POST["csv_data"] != "") {
    $file = (string) $_POST["csv_data"];
    $finaldata = $datas->render($file, true);
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Monthly Expenses</title>

    <style>
        * {
            font-size: 18px;
        }
        main {
            margin: 50px auto;
            width: 100%;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            row-gap: 50px;
        }
        form {
            display: flex;
            align-items: center;
            column-gap: 10px;
        }
        select {
            width: 200px;
            padding: 10px;
        }
        input {
            padding: 5px 15px;
        }

        table {
            width: 600px;
        }
        table td {
            border: 1px solid grey;
            padding: 5px 10px;
        }

        h2 {
            font-size: 26px;
            text-align: center;
        }
    </style>
</head>
<body>

    <main>

        <form action="" method="POST">
            <div class="form-input">
                <select name="csv_data" id="csv_data" required>
                    <option value="">Select File</option>
                    <?php
                    if(count($file_datas) > 0):
                        foreach($file_datas as $data):
                    ?>
                    <option value="<?= $data; ?>"><?= $data; ?></option>
                    <?php
                        endforeach;
                    endif;
                    ?>
                </select>
            </div>
            <div class="form-input">
                <input type="submit" value="Submit">
            </div>
        </form>

        <section>
            <h2><?= $file ?? ""; ?></h2>
            
            <?php
            if(isset($finaldata) && $finaldata != "") {
                echo $finaldata;
            }
            ?>
        </section>
        
    </main>
    
</body>
</html>