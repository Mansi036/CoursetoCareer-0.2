<?php
session_start();
include 'navbar.php';
include 'db.php';

$requested_field = $_GET['field'] ?? '';
$program_filter = $_GET['program'] ?? '';

$orderBy = " ORDER BY 
    CASE 
        WHEN tier = 'Tier 1' THEN 1
        WHEN tier = 'Tier 2' THEN 2
        WHEN tier = 'Tier 3' THEN 3
        ELSE 4
    END";

$sql = "SELECT * FROM allcolleges WHERE 1=1";

$params = [];
$types = "";

/* ===============================
   PROGRAM FILTER
================================= */
if (!empty($program_filter)) {
    $sql .= " AND program_type = ?";
    $params[] = $program_filter;
    $types .= "s";
}

/* ===============================
   FIELD FILTER (KEYWORD MATCH)
================================= */
if (!empty($requested_field)) {

    $keywords = array_filter(array_map('trim', explode(",", $requested_field)));

    $sql .= " AND (";

    $conditions = [];

    foreach ($keywords as $word) {

        $conditions[] = "LOWER(Program) LIKE ? OR LOWER(name) LIKE ?";

        $params[] = "%" . strtolower($word) . "%";
        $params[] = "%" . strtolower($word) . "%";
        $types .= "ss";
    }

    $sql .= implode(" OR ", $conditions);
    $sql .= ")";
}

$final_sql = $sql . $orderBy;

$stmt = $conn->prepare($final_sql);

if (!$stmt) {
    die("SQL Error: " . $conn->error);
}

if (!empty($params)) {
    $stmt->bind_param($types, ...$params);
}

$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Explore Colleges</title>

<style>

/* ===============================
   BASE UI
================================= */
body{
    font-family:'Segoe UI',Tahoma,sans-serif;
    background:#f4f7f6;
    margin:0;
    padding:20px;
}

.container{
    max-width:1100px;
    margin:auto;
    background:white;
    padding:30px;
    border-radius:15px;
    box-shadow:0 5px 15px rgba(0,0,0,0.1);
}

h2{
    color:#333;
    border-bottom:2px solid #007bff;
    padding-bottom:10px;
}

/* ===============================
   TABLE
================================= */
table{
    width:100%;
    border-collapse:collapse;
    margin-top:20px;
}

th{
    background:#007bff;
    color:white;
    padding:12px;
    text-align:left;
}

td{
    padding:12px;
    border-bottom:1px solid #ddd;
}

tr:hover{
    background:#f9f9f9;
}

/* ===============================
   LINKS
================================= */
a{
    color:#007bff;
    text-decoration:none;
    font-weight:600;
}

a:hover{
    text-decoration:underline;
}

/* ===============================
   BADGE
================================= */
.badge{
    background:#eef2f7;
    padding:4px 10px;
    border-radius:12px;
    font-size:12px;
}

/* ===============================
   TIER BADGES (FIXED)
================================= */
.tier1, .tier2, .tier3{
    padding:4px 10px;
    border-radius:12px;
    font-weight:bold;
    display:inline-block;
}

.tier1{
    background:#d4edda;
    color:#155724;
}

.tier2{
    background:#fff3cd;
    color:#856404;
}

.tier3{
    background:#f8d7da;
    color:#721c24;
}

/* ===============================
   NO DATA
================================= */
.no-data{
    text-align:center;
    padding:40px;
    color:#777;
}

</style>

</head>

<body>

<div class="container">

<h2>Colleges for <?= htmlspecialchars($requested_field ?: 'All Courses') ?></h2>

<table>

<tr>
    <th>College</th>
    <th>Course</th>
    <th>Type</th>
    <th>Tier</th>
    <th>State</th>
</tr>

<?php if($result && $result->num_rows > 0): ?>

    <?php while($row = $result->fetch_assoc()): ?>

    <tr>

        <td>
            <a href="<?= htmlspecialchars($row['link']) ?>" target="_blank">
                <?= htmlspecialchars($row['name']) ?>
            </a>
        </td>

        <td>
            <span class="badge">
                <?= htmlspecialchars($row['Program']) ?>
            </span>
        </td>

        <td><?= htmlspecialchars($row['type']) ?></td>

        <!-- ✔ FIX IS HERE -->
        <td class="tier<?= preg_replace('/[^0-9]/', '', $row['tier']) ?>">
            <?= htmlspecialchars($row['tier']) ?>
        </td>

        <td><?= htmlspecialchars($row['state']) ?></td>

    </tr>

    <?php endwhile; ?>

<?php else: ?>

<tr>
    <td colspan="5" class="no-data">
        No colleges found
    </td>
</tr>

<?php endif; ?>

</table>

</div>

</body>
</html>

<?php $conn->close(); ?>