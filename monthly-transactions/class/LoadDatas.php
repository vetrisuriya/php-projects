<?php

class LoadDatas {

    public string $path;
    private array $files;

    public function __construct(string $path) {

        $this->path = $path;

        // check if path exists
        if(is_dir($path)) {
            // scan the given path
            $all_files = scandir($path);

            foreach($all_files as $file_val) {
                if($file_val != "." && $file_val != "..") {
                    // css files only allowed
                    if(pathinfo($file_val)['extension'] == 'csv') {
                        $this->files[] = $file_val;
                    }
                }
            }
        }

    }

    public function renderFiles() {
        return array_reverse($this->files);
    }

    public function render(string $file, bool $html) {
        if($file != "") {

            $tables = "";
            $file_data = file($this->path."".$file);

            if($html === true) {

                $data_header = explode(",", array_shift($file_data));
                $tables .= "<table><thead><tr>";
                foreach($data_header as $head) {
                    $tables .= "<td>".$head."</td>";
                }
                $tables .= "</tr></thead><tbody>";

                $total_income = 0;
                $total_expense = 0;
                $net_total = 0;
                foreach($file_data as $total_data) {
                    $val = explode(",", $total_data)[3];

                    if((strpos($val, '-'))  === false) {
                        $total_income += abs($val);
                        $net_total += abs($val);
                    } else {
                        $total_expense += abs($val);
                        $net_total -= abs($val);
                    }

                }

                foreach($file_data as $data) {
                    [$id, $date, $desc, $price] = explode(",", $data);

                    if((strpos($price, '-'))  === false) {
                        $color = " style=color:green";
                    } else {
                        $color = " style=color:red";
                    }

                    $tables .= "<tr><td>$id</td><td>$date</td><td>$desc</td><td$color>$price ₹</td></tr>";
                }

                $tables .= "<tr><td colspan='3'><strong>Total Income:</strong></td><td><strong>".abs($total_income)." ₹</strong></td></tr>";
                $tables .= "<tr><td colspan='3'><strong>Total Expense:</strong></td><td><strong>".abs($total_expense)." ₹</strong></td></tr>";
                $tables .= "<tr><td colspan='3'><strong>Net Total:</strong></td><td><strong>".abs($net_total)." ₹</strong></td></tr>";
                $tables .= "</tbody></table>";

            }

            return $tables;

        }
    }

}