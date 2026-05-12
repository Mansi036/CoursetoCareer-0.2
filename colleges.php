<?php
include 'db.php';
include 'navbar.php';
if(!isset($_SESSION['user_logged_in'])){
    header("Location: login.php");
    exit();
}


if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

/* ===============================
   GET VALUES
================================= */
$type   = $_GET['type'] ?? "";
$field  = $_GET['field'] ?? "";
$state  = $_GET['state'] ?? "";
$filter = $_GET['filter'] ?? "";

$showResults = isset($_GET['type']) || isset($_GET['field']) || isset($_GET['state']) || isset($_GET['filter']);

/* ===============================
   FETCH DROPDOWN DATA
================================= */
$stateQuery  = "SELECT DISTINCT state FROM AllColleges WHERE state IS NOT NULL AND state <> '' ORDER BY state ASC";
$stateResult = $conn->query($stateQuery);

$typeQuery   = "SELECT DISTINCT type FROM AllColleges WHERE type IS NOT NULL AND type <> '' ORDER BY type ASC";
$typeResult  = $conn->query($typeQuery);
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Find Colleges | CourseToCareer</title>
<link rel="stylesheet" href="style.css">

<style>
body{
    margin:0;
    padding:0;
    font-family:Segoe UI,Tahoma,Geneva,Verdana,sans-serif;
}

.main-wrapper{
    padding:40px 20px;
    display:flex;
    flex-direction:column;
    align-items:center;
}

.search-box{
    width:100%;
    max-width:430px;
    background:#fff;
    padding:25px;
    border-radius:15px;
    box-shadow:0 10px 25px rgba(0,0,0,.08);
    margin-bottom:40px;
}

.search-box h2{
    margin-top:0;
    text-align:center;
    margin-bottom:22px;
}

.search-box select,
.search-box button{
    width:100%;
    padding:12px;
    margin-bottom:15px;
    border:1px solid #b8ad34;
    border-radius:8px;
    font-size:15px;
}

.search-box button{
    border:none;
    background:#007bff;
    color:#fff;
    font-weight:bold;
    cursor:pointer;
}

.search-box button:hover{
    background:#0056b3;
}

.cards{
    width:100%;
    max-width:1250px;
    display:grid;
    grid-template-columns:repeat(auto-fill,minmax(320px,1fr));
    gap:25px;
}

.card{
    background:#fff;
    padding:25px;
    border-radius:14px;
    box-shadow:0 5px 18px rgba(0,0,0,.08);
    border-top:5px solid #007bff;
    transition:.25s;
}

.card:hover{
    transform:translateY(-5px);
}

.title-link{
    color:#007bff;
    text-decoration:none;
    font-size:20px;
    font-weight:700;
    display:inline-block;
    margin-bottom:12px;
}

.badge{
    display:inline-block;
    padding:6px 12px;
    margin-right:6px;
    margin-bottom:8px;
    border-radius:20px;
    background: #c7bd4f;
    font-size:12px;
    font-weight:bold;
}

.visit-btn{
    display:block;
    text-align:center;
    margin-top:18px;
    padding:12px;
    border-radius:8px;
    text-decoration:none;
    background:#28a745;
    color:#fff;
    font-weight:bold;
}

.visit-btn:hover{
    background:#218838;
}

.no-data{
    text-align:center;
    grid-column:1/-1;
    color:#666;
    font-size:18px;
}
</style>
</head>

<body>

<div class="main-wrapper">

<!-- ===============================
     SEARCH FORM
================================= -->
<div class="search-box">

<h2>Find Your College</h2>

<form method="GET">

<!-- TYPE -->
<select name="type">
<option value="">Select Type</option>

<?php
if($typeResult && $typeResult->num_rows > 0){
    while($row = $typeResult->fetch_assoc()){
        $selected = ($type == $row['type']) ? "selected" : "";
        echo "<option value='".htmlspecialchars($row['type'])."' $selected>"
            .htmlspecialchars($row['type'])."</option>";
    }
}
?>
</select>

<!-- STATE -->
<select name="state">
<option value="">Select State</option>

<?php
if($stateResult && $stateResult->num_rows > 0){
    while($row = $stateResult->fetch_assoc()){
        $selected = ($state == $row['state']) ? "selected" : "";
        echo "<option value='".htmlspecialchars($row['state'])."' $selected>"
            .htmlspecialchars($row['state'])."</option>";
    }
}
?>
</select>

<!-- COURSE -->
<?php
// Database connection assume kar raha hoon ki $conn variable me hai
$query = "SELECT DISTINCT Program FROM AllColleges WHERE Program IS NOT NULL ORDER BY Program ASC";
$result = mysqli_query($conn, $query);
?>

<select name="field">
    <option value="">Select Course</option>
    
    <?php
    // Database se aaye har program ke liye ek option tag banayein
    while ($row = mysqli_fetch_assoc($result)) {
        $programValue = $row['Program'];
        
        // Check karein ki kya ye option pehle se selected hai
        $selected = ($field == $programValue) ? "selected" : "";
        
        echo "<option value='$programValue' $selected>$programValue</option>";
    }
    ?>
</select>

<button type="submit">Search Colleges</button>

</form>
</div>

<!-- ===============================
     RESULTS
================================= -->
<div class="cards">

<?php
if($showResults){

    $sql = "SELECT * FROM AllColleges WHERE 1=1";
    $params = [];
    $types = "";

    /* TYPE */
    if(!empty($type)){
        $sql .= " AND LOWER(TRIM(type)) LIKE LOWER(?)";
        $params[] = "%".trim($type)."%";
        $types .= "s";
    }

    /* STATE */
    if(!empty($state)){
        $sql .= " AND LOWER(TRIM(state)) LIKE LOWER(?)";
        $params[] = "%".trim($state)."%";
        $types .= "s";
    }

    /* COURSE / PROGRAM */
    if(!empty($field)){
        $sql .= " AND LOWER(TRIM(Program)) LIKE LOWER(?)";
        $params[] = "%".trim($field)."%";
        $types .= "s";
    }

    /* SORTING */
    if($filter == "tier"){
        $sql .= " ORDER BY tier ASC";
    }
    elseif($filter == "name"){
        $sql .= " ORDER BY name ASC";
    }
    else{
        $sql .= " ORDER BY id DESC";
    }

    $stmt = $conn->prepare($sql);

    if(!$stmt){
        echo "<p class='no-data'>SQL Error: ".$conn->error."</p>";
    }else{

        if(!empty($params)){
            $stmt->bind_param($types, ...$params);
        }

        $stmt->execute();
        $result = $stmt->get_result();

        if($result && $result->num_rows > 0){

            while($row = $result->fetch_assoc()){

                $tierColor = "#dc3545";

                if(strtolower($row['tier']) == "tier 1"){
                    $tierColor = "#28a745";
                }
                elseif(strtolower($row['tier']) == "tier 2"){
                    $tierColor = "#ffc107";
                }

                echo "<div class='card'>";

                echo "<a class='title-link' target='_blank' href='".htmlspecialchars($row['link'])."'>"
                    .htmlspecialchars($row['name'])." 🔗</a>";

                echo "<div>";
                echo "<span class='badge'>".htmlspecialchars($row['type'])."</span>";
                echo "<span class='badge'>".htmlspecialchars($row['Program'])."</span>";
                echo "<span class='badge'>".htmlspecialchars($row['program_type'])."</span>";
                echo "<span class='badge' style='background:$tierColor;color:white;'>".htmlspecialchars($row['tier'])."</span>";
                echo "</div>";

                echo "<p><b>State:</b> ".htmlspecialchars($row['state'])."</p>";

                echo "<a class='visit-btn' target='_blank' href='".htmlspecialchars($row['link'])."'>
                        Visit Official Website
                      </a>";

                echo "</div>";
            }

        }else{
            echo "<p class='no-data'>No colleges found.</p>";
        }
    }
}
?>

</div>
</div>

</body>
</html>