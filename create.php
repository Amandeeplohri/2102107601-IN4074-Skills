<?php
// Include config file
require_once "config.php";

// Define variables and initialize with empty values
$playerid = $playername = $dateofbirth = $team = "";
$playerid_err = $playername_err = $dateofbirth_err = $team_err = "";

// Processing form data when form is submitted  
if ($_SERVER["REQUEST_METHOD"] == "POST") {
   
    // Validate playerid
    $input_playerid = trim($_POST["playerid"]);
    if (empty($input_playerid)) {
        $playerid_err = "Please enter  playerid.";
    } else {
        $playerid = $input_playerid;
    }

    // Validate player name
    $input_playername = trim($_POST["playername"]);
    if (empty($input_playername)) {
        $playername_err = "Please enter name of player name.";
    } else {
        $playername = $input_playername; // Fixed variable assignment
    }

    // Validate date of birth
    $input_dateofbirth = trim($_POST["dateofbirth"]); // Corrected form field name
    if (empty($input_dateofbirth)) {
        $dateofbirth_err = "Please enter date of birth.";
    } else {
        $dateofbirth = $input_dateofbirth;
    }

    // Validate Music genre
    $input_team = trim($_POST["team"]);
    if (empty($input_team)) {
        $team_err = "Please enter the contact information.";
    } else {
        $team = $input_team;
    }

    // Check input errors before inserting in database
    if (empty($playerid_err) && empty($playername_err) && empty($dateofbirth_err) && empty($team_err)) { // Fixed condition
        // Prepare an insert statement
        $sql = "INSERT INTO technology (playerid, playername, dateofbirth, team) VALUES (?, ?, ?, ?)";

        if ($stmt = mysqli_prepare($link, $sql)) {
            // Bind variables to the prepared statement as parameters
            mysqli_stmt_bind_param($stmt, "ssss", $param_playerid, $param_playername, $param_dateofbirth, $param_team); // Fixed parameter types

            // Set parameters
            $param_playerid = $playerid;
            $param_playername = $playername;
            $param_dateofbirth = $dateofbirth;
            $param_team = $team;

            // Attempt to execute the prepared statement
            if (mysqli_stmt_execute($stmt)) {
                // informations created successfully. Redirect to landing page
                header("location: index.php");
                exit();
            } else {
                echo "Oops! Something went wrong. Please try again later.";
            }
        }

        // Close statement
        mysqli_stmt_close($stmt);
    }

    // Close connection
    mysqli_close($link);
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Create information</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        .wrapper {
            width: 600px;
            margin: 0 auto;
        }
           
   </style>
     
</head>

<body>
    <div class="wrapper">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    <h2 class="mt-5">Create Record</h2>
                    <p>Please fill this form and submit to add Record to the database.</p>
                    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
                        <div class="form-group">
                            <label>player ID</label>
                            <input type="text" name="playerid" class="form-control <?php echo (!empty($playerid_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $playerid; ?>">
                            <span class="invalid-feedback"><?php echo $playerid_err; ?></span>
                        </div>
                        <div class="form-group">
                            <label>playername</label>
                            <input type="text" name="playername" class="form-control <?php echo (!empty($playername_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $playername; ?>">
                            <span class="invalid-feedback"><?php echo $playername_err; ?></span>
                        </div>
                        <div class="form-group">
                            <label>dateofbirth</label>
                            <input type="text" name="dateofbirth" class="form-control <?php echo (!empty($dateofbirth_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $dateofbirth; ?>">
                            <span class="invalid-feedback"><?php echo $dateofbirth_err; ?></span>
                        </div>
                        <div class="form-group">
                            <label>Team</label>
                            <input type="text" name="team" class="form-control <?php echo (!empty($team_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $team; ?>">
                            <span class="invalid-feedback"><?php echo $team_err; ?></span>
                        </div>
                        <input type="submit" class="btn btn-primary" value="Submit">
                        <a href="index.php" class="btn btn-secondary ml-2">Cancel</a>
                    </form>
                </div>
            </div>
        </div>
    </div>
</body>
