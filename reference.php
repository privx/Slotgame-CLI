<?php
// Define symbol payouts
$symbolPayouts = [
    'A' => [5 => 40.00, 4 => 10.00, 3 => 3.00],
    'B' => [5 => 25.00, 4 => 7.50, 3 => 2.50],
    'C' => [5 => 15.00, 4 => 4.00, 3 => 1.50],
    'D' => [5 => 10.00, 4 => 20.50, 3 => 1.00],
    'E' => [5 => 7.50, 4 => 1.50, 3 => 0.70],
    'F' => [5 => 7.50, 4 => 1.50, 3 => 0.70],
    'G' => [5 => 5.00, 4 => 1.00, 3 => 0.50],
    'H' => [5 => 3.00, 4 => 0.60, 3 => 0.30],
    'I' => [5 => 3.00, 4 => 0.60, 3 => 0.30],
    'J' => [5 => 2.00, 4 => 0.50, 3 => 0.20],
    'K' => [5 => 2.00, 4 => 0.50, 3 => 0.20],
    'L' => [5 => 2.00, 4 => 0.50, 3 => 0.20],
];

// Define special symbols
$wildSymbol = 'W';
$scatterSymbol = '#';

// Define multipliers for wild symbols
$wildMultipliers = [2, 3, 5];

// Initialize game parameters
$balance = 1000;
$betAmount = 10;
$freeSpins = 0;

// Function to calculate winnings
function calculateWinnings($symbols) {
    global $symbolPayouts, $wildSymbol, $wildMultipliers;

    $totalWinnings = 0;
    $wildMultipliersOnPayline = [];

    foreach ($symbols as $symbol) {
        if ($symbol == $wildSymbol) {
            $wildMultiplier = $wildMultipliers[array_rand($wildMultipliers)];
            $wildMultipliersOnPayline[] = $wildMultiplier;
        } else {
            $paylinePayouts = $symbolPayouts[$symbol] ?? [];
            foreach ([5, 4, 3] as $matchingSymbols) {
                if (isset($paylinePayouts[$matchingSymbols])) {
                    $totalWinnings += $paylinePayouts[$matchingSymbols] * end($wildMultipliersOnPayline);
                }
            }
        }
    }

    // Apply wild multipliers if there are wild symbols on the payline
    if (!empty($wildMultipliersOnPayline)) {
        $totalWinnings *= array_sum($wildMultipliersOnPayline);
    }

    return $totalWinnings;
}

// Function to play a single spin
function playSpin() {
    global $symbolPayouts, $balance, $freeSpins;

    if ($freeSpins > 0) {
        $symbols = array_fill(0, 5, ($rand = rand(0, 1)) ? $wildSymbol : array_rand($symbolPayouts));
    } else {
        $symbols = array_fill(0, 5, array_rand($symbolPayouts));
    }

    $winnings = calculateWinnings($symbols);
    $balance += $winnings;

    if ($freeSpins > 0) {
        $freeSpins--;
    }

    return [$symbols, $winnings];
}

// Main game loop
while (true) {
    echo "[NG99 SLOT]\n";
    echo "Bet: $betAmount\n";
    echo "Kredit: $balance\n";
    echo "Menu:\n";
    echo "1. Manual Spin\n";
    echo "2. Auto Spin\n";
    echo "3. Buy Scatter\n";
    echo "4. Quit\n";
    $choice = readline("Enter your choice: ");

    if ($choice === "1") {
        [$symbols, $winnings] = playSpin();
        echo "Result: " . implode(' ', $symbols) . "\n";
        echo "Winnings: $" . number_format($winnings, 2) . "\n";
    } elseif ($choice === "2") {
        $numSpins = (int)readline("Enter the number of auto spins: ");
        for ($i = 0; $i < $numSpins; $i++) {
            [$symbols, $winnings] = playSpin();
            echo "Result: " . implode(' ', $symbols) . "\n";
            echo "Winnings: $" . number_format($winnings, 2) . "\n";
        }
    } elseif ($choice === "3") {
        if ($balance >= 100) {
            $balance -= 100;
            $freeSpins += 10;
            echo "You bought 10 free spins.\n";
        } else {
            echo "Insufficient balance to buy free spins.\n";
        }
    } elseif ($choice === "4") {
        echo "Goodbye!\n";
        break;
    } else {
        echo "Invalid choice. Please try again.\n";
    }
}
?>
