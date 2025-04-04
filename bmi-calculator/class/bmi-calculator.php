<?php
declare(strict_types=1);

class bmiCalculator {
    public function __construct(
        private float $height,
        private float $weight,
        private string $unit
    ) { }

    public function calculate(): array {

        $score = match($this->unit) {
            'm' => (float) ($this->weight / ($this->height ** 2)),
            default => (float) ($this->weight / (($this->height / 100) ** 2)),
        };

        // Round BMI to 1 decimal place
        $score = round($score, 1);
        
        // Determine BMI category
        if($score < 18.5) {
            $category = "Underweight";
            $color = "#3498db"; // Blue
        } elseif($score >= 18.5 && $score < 25) {
            $category = "Normal weight";
            $color = "#2ecc71"; // Green
        } elseif($score >= 25 && $score < 30) {
            $category = "Overweight";
            $color = "#f39c12"; // Orange
        } else {
            $category = "Obesity";
            $color = "#e74c3c"; // Red
        }

        return [$score, $category, $color];
    }
}