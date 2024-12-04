<html>

<body>
    <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="POST">
        <div>
            <label id="grid-label">Grid</label>
            <input id="grid-input" name="grid" type="number" min="1" />
            <label id="grid-label">Player:3</label>
        </div>
        <div>
            <button id="submit" type="submit">Submit</button>
        </div>
    </form>
</body>
</html>
<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    calculate_winner();
}

function calculate_winner()
{
    $grid = $_POST['grid'];

    # Calulate Max position
    $max_position = $grid * $grid;
    $player_position = [0, 0, 0];
    $player_position_history = [[], [], []];
    $dice_roll_history = [[], [], []];
    $cordinate_history = [[],[],[]];
    $winner = false;
    $winner_player = 0;
    while (!$winner) {
        for($i=0;$i<3;$i++)
        {
            # Generate Dice Number
            $dice_number = random_int(1,6);
            
            # Add Dice Number to History
            array_push($dice_roll_history[$i], $dice_number);

            # Add Player Position
            if (($player_position[$i] + $dice_number) <= $max_position)
                $player_position[$i] += $dice_number;
            
            # Player position to history
            array_push($player_position_history[$i], $player_position[$i]);

            # Calculate x,y coordinates in zig-zag pattern
            $position = $player_position[$i];
            $row = floor(($position - 1) / $grid);
            $col = $row % 2 == 0 
                ? ($position - 1) % $grid 
                : $grid - 1 - (($position - 1) % $grid);
            
            # Add coordinates to history
            array_push($cordinate_history[$i], "($col,$row)");

            # Check if player has won
            if ($player_position[$i] == $max_position) {
                $winner_player = $i + 1;
                $winner = true;
                break;
            }
        }

    }
    # Print Result
    echo "<table border=1><th>Player</th><th>Dice Roll History</th><th>Player Position</th><th>Cordinates History</th><th>Winner</th>";
    for($i=0;$i<3;$i++)
    {
        echo "<tr>";
        echo "<td>". $i + 1 ."</td>";
        echo "<td>" . implode(", ", $dice_roll_history[$i]) . "</td>";
        echo "<td>" . implode(", ", $player_position_history[$i]) . "</td>";
        echo "<td>" . implode(", ", $cordinate_history[$i]) . "</td>";
        if ($winner_player == $i+1)
            echo "<td>Winner</td>";
        else
            echo "<td></td>";
        echo "</tr>";
    }
}
?>