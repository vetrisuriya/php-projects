<?php

declare(strict_types=1);

class simpleCalculator {

    public function __construct(
        private float $x,
        private float $y,
        private string $operator) {

    }

    public function calculate():float {
        
        $val = match($this->operator) {
            'add' => ($this->x + $this->y),
            'subtract' => ($this->x - $this->y),
            'divide' => ($this->x / $this->y),
            'multiply' => ($this->x * $this->y),
        };

        return $val;
    }


    public function storeHistory() {

        $op = match($this->operator) {
            'add' => '+',
            'subtract' => '-',
            'divide' => '/',
            'multiply' => '*',
        };

        $_SESSION["history"][] = $this->x." ".$op." ".$this->y;

        return;
    }


}