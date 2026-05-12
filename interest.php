<?php
session_start();
error_reporting(0);
include 'db.php';
include 'navbar.php';

if(!isset($_SESSION['user_logged_in'])){
    header("Location: login.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Career Discovery Quiz | CourseToCareer</title>
    <link rel="stylesheet" href="style.css">
<style>
    body {
        background: #ffffff !important; 
        margin: 0;
        padding: 0;
        padding-top: 70px;
        overflow-y: auto !important;
        height: auto;
    }

    .navbar, nav {
        background-color: #000000 !important;
    }

    .glass-box {
        background: #ffffff !important;
        color: #333 !important;
        width: 100% !important; 
        max-width: 100% !important; 
        min-height: calc(100vh - 70px);
        margin: 0 !important; 
        padding: 40px 0 50px 20px; 
        display: flex;
        flex-direction: column;
        align-items: flex-start !important; 
    }

    form {
        width: 100%;
        max-width: 95%;
        text-align: left !important;
    }

    h2 { 
        color: #1a1a2e !important; 
        border-bottom: 3px solid #4facfe; 
        padding-bottom: 10px;
        width: 100%; 
        text-align: left;
    }

    label { 
        display: block; 
        margin-top: 30px; 
        font-weight: bold; 
        color: #1a1a2e !important; 
        font-size: 18px; 
    }
    
    .sub-text { 
        font-size: 13px; 
        color: #666; 
        margin-bottom: 10px; 
        display: block; 
    }

    select {
        width: 100%; 
        max-width: 900px;
        padding: 15px; 
        border-radius: 8px; 
        border: 1px solid #ddd !important; 
        font-size: 16px;
    }

    /* --- RADIO BUTTON LEFT ALIGNMENT FIX --- */
    .radio-row {
        display: flex; /* Button aur text ko side-by-side laane ke liye */
        align-items: center; 
        margin-bottom: 15px;
        cursor: pointer;
    }

    .radio-row input[type="radio"] { 
        margin: 0 15px 0 0 !important; /* Text se gap */
        width: 18px;
        height: 18px;
        flex-shrink: 0;
    }

    .radio-row label { 
        display: inline !important; 
        margin: 0 !important; 
        font-weight: normal !important; 
        font-size: 16px;
        color: #444 !important;
    }

    .option-group {
        background: #f9f9f9 !important; 
        padding: 20px; 
        border-radius: 10px; 
        margin-top: 10px; 
        border: 1px solid #eee !important;
        width: 100%;
        max-width: 900px;
    }

    button {
        background: linear-gradient(135deg, #3e8dd3 0%, #3e8dd3  100%) !important;
        color: white !important; 
        border: none; 
        padding: 18px 40px; 
        border-radius: 10px; 
        font-weight: bold; 
        cursor: pointer; 
        margin-top: 40px; 
        font-size: 18px;
    }
</style>
</head>
<body>

<div class="glass-box">
    <h2>Career Discovery Quiz 🎯</h2>
    <p style="color: #555;">Answer these questions to help us analyze your personality and skills.</p>
    <hr style="border: 0.5px solid #eee; margin: 20px 0; width: 100%; max-width: 850px;">

    <form action="suggest_courses.php" method="post">
        
        <label>1. What is your primary area of interest?</label>
        <span class="sub-text">Choose the field that you are most passionate about.</span>
        <select name="field" required>
            <option value="">-- Choose One --</option>
            <optgroup label="Technology & IT">
                <option value="tech">Software Development & AI</option>
                <option value="cyber">Cyber Security & Networking</option>
                <option value="data">Data Science & Analytics</option>
            </optgroup>
            <optgroup label="Medical & Healthcare">
                <option value="medical">MBBS / Surgery</option>
                <option value="pharma">Pharmacy & Drugs</option>
                <option value="biotech">Biotechnology & Research</option>
            </optgroup>
            <optgroup label="Creative Arts & Lifestyle">
                <option value="fashion">Fashion Designing & Styling</option>
                <option value="cooking">Culinary Arts (Professional Cooking/Chef)</option>
                <option value="interior">Interior Designing</option>
                <option value="creative">Fine Arts, Film & Photography</option>
                <option value="animation">Animation & VFX</option>
            </optgroup>
            <optgroup label="Business, Finance & Law">
                <option value="finance">Stock Market & Banking</option>
                <option value="marketing">Digital Marketing & Sales</option>
                <option value="law">Law & Legal Studies</option>
                <option value="management">Event Management & HR</option>
            </optgroup>
            <optgroup label="Aviation & Hospitality">
                <option value="aviation">Pilot & Aviation</option>
                <option value="hotel">Hotel Management</option>
            </optgroup>
        </select>

        <label>2. How do you prefer to work?</label>
        <span class="sub-text">Choose the environment where you feel most productive.</span>
        <div class="option-group">
            <div class="radio-row"><input type="radio" name="work_style" value="analytical" id="w1" required> <label for="w1">Deep research and data analysis</label></div>
            <div class="radio-row"><input type="radio" name="work_style" value="team" id="w2"> <label for="w2">Leading a team and managing people</label></div>
            <div class="radio-row"><input type="radio" name="work_style" value="hands_on" id="w3"> <label for="w3">Hands-on work (Building things/Operating machinery)</label></div>
            <div class="radio-row"><input type="radio" name="work_style" value="independent" id="w4"> <label for="w4">Creative and independent solo work</label></div>
        </div>

        <label>3. What excites you more about a new project?</label>
        <div class="option-group">
            <div class="radio-row"><input type="radio" name="problem_solving" value="logic" id="p1" required> <label for="p1">Fixing technical bugs and logical errors</label></div>
            <div class="radio-row"><input type="radio" name="problem_solving" value="human" id="p2"> <label for="p2">Solving human problems and helping people</label></div>
            <div class="radio-row"><input type="radio" name="problem_solving" value="money" id="p3"> <label for="p3">Increasing revenue and business growth</label></div>
        </div>

        <label>4. Which of these is your strongest skill?</label>
        <select name="skill" required>
            <option value="math">Mathematics & Calculations</option>
            <option value="writing">Writing & Communication</option>
            <option value="drawing">Visual Arts & Sketching</option>
            <option value="talking">Public Speaking & Convincing</option>
            <option value="coding">Logical Reasoning</option>
        </select>

        <label>5. Where do you see yourself in 5 years?</label>
        <div class="option-group">
            <div class="radio-row"><input type="radio" name="goal" value="corporate" id="g1" required> <label for="g1">Working in a top MNC (Google, Microsoft, etc.)</label></div>
            <div class="radio-row"><input type="radio" name="goal" value="startup" id="g2"> <label for="g2">Running my own business/Startup</label></div>
            <div class="radio-row"><input type="radio" name="goal" value="research" id="g3"> <label for="g3">Academia/Research/Teaching</label></div>
        </div>

        <button type="submit" name="submit_full_quiz">Generate My Career Path ➔</button>
    </form>
</div>

</body>
</html>