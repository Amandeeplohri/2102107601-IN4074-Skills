<?php
// Include config file
require_once "config.php"; 

// Define variables and initialize with empty values
$playerid = $playername = $dateofbirth = $team = "";
$playerid_err = $playername_err = $dateofbirth_err = $team_err = "";

// Processing form data when form is submitted
if (isset($_POST["id"]) && !empty($_POST["id"])) {
    // Get hidden input value
    $id = $_POST["id"];

     // Validate playerid
     $input_playerid = trim($_POST["playerid"]);
     if (empty($input_playerid)) {
         $playerid_err = "Please enter the playerid.";
     } else {
         $playerid = $input_playerid;
     }

    // Validate playername
    $input_playername = trim($_POST["playername"]);
    if (empty($input_playername)) {
        $playername_err = "Please enter an playername.";
    } else {
        $playername = $input_playername;
    }

    // Validate dateofbirth
    $input_dateofbirth = trim($_POST["dateofbirth"]);
    if (empty($input_dateofbirth)) {
        $dateofbirth_err = "Please enter the dateofbirth.";
    } else {
        $dateofbirth = $input_dateofbirth;
    }

    // Validate Music genre
    $input_team = trim($_POST["team"]);
    if (empty($input_team)) {
        $team_err = "Please enter the Music genre.";
    } else {
        $team = $input_team;
    }

    // Check input errors before inserting in database
    if (empty($playerid_err) && empty($playername_err) && empty($dateofbirth_err) && empty($team_err)) {
        // Prepare an update statement
$sql = "UPDATE player SET playerid=?, playername=?, dateofbirth=?, team=? WHERE id=?";

if ($stmt = mysqli_prepare($link, $sql)) {
    // Bind variables to the prepared statement as parameters
    mysqli_stmt_bind_param($stmt, "ssssi", $param_playerid, $param_playername, $param_dateofbirth, $param_team, $param_id);

    // Set parameters
    $param_playerid = $playerid;
    $param_playername = $playername;
    $param_dateofbirth = $dateofbirth;
    $param_team = $team;
    $param_id = $id;

    // Attempt to execute the prepared statement
    if (mysqli_stmt_execute($stmt)) {
        // Records updated successfully. Redirect to landing page
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
} else {
    // Check existence of id parameter before processing further
    if (isset($_GET["id"]) && !empty(trim($_GET["id"]))) {
        // Get URL parameter
        $id =  trim($_GET["id"]);

        // Prepare a select statement
        $sql = "SELECT * FROM PlayerRecords WHERE id = ?";
        if ($stmt = mysqli_prepare($link, $sql)) {
            // Bind variables to the prepared statement as parameters
            mysqli_stmt_bind_param($stmt, "i", $param_id);

            // Set parameters
            $param_id = $id;

            // Attempt to execute the prepared statement
            if (mysqli_stmt_execute($stmt)) {
                $result = mysqli_stmt_get_result($stmt);

                if (mysqli_num_rows($result) == 1) {
                    /* Fetch result row as an associative array. Since the result set
                    contains only one row, we don't need to use while loop */
                    $row = mysqli_fetch_array($result, MYSQLI_ASSOC);

                    // Retrieve individual field value
                    $playerid = $row["playerid"];
                    $playername = $row["playername"];
                    $dateofbirth = $row["dateofbirth"];
                    $team = $row["team"];
                } else {
                    // URL doesn't contain valid id. Redirect to error page
                    header("location: error.php");
                    exit();
                }
            } else {
                echo "Oops! Something went wrong. Please try again later.";
            }
        }

        // Close statement
        mysqli_stmt_close($stmt);

        // Close connection
        mysqli_close($link);
    } else {
        // URL doesn't contain id parameter. Redirect to error page
        header("location: error.php");
        exit();
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <playerid>Update Record</playerid>
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
                    <h2 class="mt-5">Update Record</h2>
                    <p>Please edit the input values and submit to update the player Record.</p>
                    <form action="<?php echo htmlspecialchars(basename($_SERVER['REQUEST_URI'])); ?>" method="post">
                    <div class="form-group">
                            <label>player id</label>
                            <input type="text" name="playerid" class="form-control <?php echo (!empty($playerid_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $playerid; ?>">
                            <span class="invalid-feedback"><?php echo $playerid_err; ?></span>
                        </div>   
                    <div class="form-group">
                            <label>Player name</label>
                            <input type="text" name="playername" class="form-control <?php echo (!empty($playername_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $playername; ?>">
                            <span class="invalid-feedback"><?php echo $playername_err; ?></span>
                        </div>
                        <div class="form-group">
                            <label>date of birth</label>
                            <input type="text" name="dateofbirth" class="form-control <?php echo (!empty($dateofbirth_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $dateofbirth; ?>">
                            <span class="invalid-feedback"><?php echo $dateofbirth_err; ?></span>
                        </div>
                        <div class="form-group">
                            <label>Team</label>
                            <input type="text" name="team" class="form-control <?php echo (!empty($team_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $team; ?>">
                            <span class="invalid-feedback"><?php echo $team_err; ?></span>
                        </div>
                        <input type="hidden" name="id" value="<?php echo $id; ?>" />
                        <input type="submit" class="btn btn-primary" value="Submit">
                        <a href="index.php" class="btn btn-secondary ml-2">Cancel</a>
                    </form>
                </div>
            </div>
        </div>
    </div>
</body>
</html>