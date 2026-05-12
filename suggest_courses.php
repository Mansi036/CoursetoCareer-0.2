<?php
session_start();
error_reporting(0);
include 'db.php';
include 'navbar.php';

if(!isset($_POST['submit_full_quiz'])){
    header("Location: interest.php");
    exit();
}

// INPUTS
$field = $_POST['field'];
$style = $_POST['work_style'];
$problem = $_POST['problem_solving'];
$skill = $_POST['skill'];
$edu = $_POST['education'];

$result_title = "";
$result_desc = "";
$courses = [];
$db_field = "";

// ===============================
// LOGIC ENGINE (IMPROVED)
// ===============================

switch ($field) {

    case "tech":
    case "cyber":
    case "data":
        if($style == "analytical" || $skill == "coding") {
            $result_title = "AI Specialist or Data Scientist";
            $result_desc = "You are built for logic, systems, and future technologies.";
            $courses = ["B.Tech CS/IT", "MCA", "Data Science", "Cyber Security"];
        } else {
            $result_title = "Technical Product Manager";
            $result_desc = "You combine tech knowledge with leadership skills.";
            $courses = ["MBA IT", "Product Management", "Agile/Scrum"];
        }
        $db_field = "tech,computer,software,data,ai,cyber";
        break;

    case "medical":
    case "pharma":
    case "biotech":
        if($problem == "human") {
            $result_title = "Healthcare Professional";
            $result_desc = "You are suited for patient care and clinical practice.";
            $courses = ["MBBS", "BDS", "B.Sc Nursing", "Pharma D"];
        } else {
            $result_title = "Medical Researcher";
            $result_desc = "You prefer research and lab-based innovation.";
            $courses = ["Biotechnology", "Clinical Research", "Genetics"];
        }
        $db_field = "mbbs,bds,nursing,pharma,medical,biotech";
        break;

    case "fashion":
        $result_title = "Fashion Designer / Stylist";
        $result_desc = "Creativity and style define your career path.";
        $courses = ["B.Des Fashion Design", "Textile Design", "Styling Diploma"];
        $db_field = "fashion,design,styling,textile";
        break;

    case "cooking":
        $result_title = "Chef / Culinary Expert";
        $result_desc = "You are passionate about food and hospitality.";
        $courses = ["Culinary Arts", "Hotel Management", "Bakery & Pastry"];
        $db_field = "culinary,hotel,food,chef,gastronomy";
        break;

    case "interior":
        $result_title = "Interior Designer";
        $result_desc = "You transform spaces into meaningful environments.";
        $courses = ["Interior Design", "Architecture Basics", "AutoCAD"];
        $db_field = "interior,design,architecture,space";
        break;

    case "finance":
    case "management":
        if($problem == "money" || $skill == "math") {
            $result_title = "Financial Analyst / CA";
            $result_desc = "Numbers and finance define your strength.";
            $courses = ["CA", "CFA", "B.Com", "MBA Finance"];
        } else {
            $result_title = "HR / Operations Manager";
            $result_desc = "You are good at managing people and systems.";
            $courses = ["MBA HR", "BBA", "Operations Management"];
        }
        $db_field = "finance,banking,accounting,management,hr";
        break;

    default:
        $result_title = "Multi-skilled Professional";
        $result_desc = "You are suitable for diverse career paths.";
        $courses = ["CPL", "BBA Aviation", "Mass Communication", "Humanities"];
        $db_field = "aviation,communication,humanities,management,general";
        break;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Your Career Analysis</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

<div class="result-card">

    <h2>Your Personal Career Map 🗺️</h2>

    <div class="result-title"><?php echo $result_title; ?></div>

    <div class="result-desc">
        "<?php echo $result_desc; ?>"
    </div>

    <h4>Best Courses:</h4>
    <ul>
        <?php foreach($courses as $c) echo "<li>✅ $c</li>"; ?>
    </ul>

    <button onclick="window.location.href='explore_colleges.php?field=<?php echo urlencode($db_field); ?>'">
        Explore Colleges ➜
    </button>

</div>

</body>
</html>