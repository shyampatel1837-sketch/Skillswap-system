<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

session_start();
include("php/db.php");

if (!isset($_SESSION['user'])) {
    header("Location: login.php");
    exit();
}

$email = $_SESSION['user'];

// Get logged-in user
$user_query = "SELECT * FROM users WHERE email='$email'";
$user_result = mysqli_query($conn, $user_query);
$user = mysqli_fetch_assoc($user_result);

$user_id = (int)$user['id'];

// PROFILE VIEW LOGIC
if (isset($_GET['id']) && (int)$_GET['id'] != $user_id) {
    $viewed_id = (int)$_GET['id'];
    mysqli_query($conn, "UPDATE users SET profile_views = profile_views + 1 WHERE id='$viewed_id'");
    $message = "Someone viewed your profile";

    mysqli_query($conn, "INSERT INTO notifications (user_id, message) 
    VALUES ('$viewed_id', '$message')");
}

// Which profile to show
$view_id = isset($_GET['id']) ? (int)$_GET['id'] : $user_id;

// Fetch profile user
$profile_query = "SELECT * FROM users WHERE id='$view_id'";
$profile_result = mysqli_query($conn, $profile_query);
$profile = mysqli_fetch_assoc($profile_result);

if(!$profile){
    echo "User not found";
    exit();
}

// Skills
$skills_query = "SELECT * FROM skills WHERE user_id='$view_id'";
$skills_result = mysqli_query($conn, $skills_query);
$skills_count = mysqli_num_rows($skills_result);

// Requests count
$request_query = "SELECT COUNT(*) as total FROM requests WHERE receiver_id='$view_id'";
$request_result = mysqli_query($conn, $request_query);
$request_data = mysqli_fetch_assoc($request_result);
$request_count = $request_data['total'];

// Profile views
$views = isset($profile['profile_views']) ? $profile['profile_views'] : 0;
?>

<!DOCTYPE html>
<html>
<head>
<title>Profile | SkillSwap</title>

<link rel="stylesheet" href="css/style.css">
<link rel="stylesheet"
href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
</head>

<body>

<div class="profile-page">
<div style="height:120px; background: linear-gradient(90deg,#4f46e5,#3b82f6); border-radius:15px;"></div>

<!-- PROFILE HEADER -->
<div class="profile-header">
<img src="uploads/<?php echo $profile['profile_image'] ?? 'default.png'; ?>" class="profile-avatar">
<h2><?php echo $profile['name']; ?></h2>
<p class="profile-email"><?php echo $profile['email']; ?></p>
</div>

<!-- STATS -->
<div class="profile-stats">
<div class="stat">
<h3><?php echo $skills_count; ?></h3>
<p>Skills</p>
</div>

<div class="stat">
<h3><?php echo $views; ?></h3>
<p>Profile Views</p>
</div>

<div class="stat">
<h3><?php echo $request_count; ?></h3>
<p>Requests</p>
</div>
</div>

<!-- BUTTONS -->
<?php if ($view_id == $user_id) { ?>
<div class="profile-buttons">
<a href="edit-profile.php" class="btn-edit">
<i class="fa-solid fa-pen"></i> Edit Profile
</a>

<a href="dashboard.php" class="btn-add">
<i class="fa-solid fa-plus"></i> Add Skill
</a>
</div>
<?php } ?>

<!-- DASHBOARD -->
<div class="dashboard-box">
<h3>Professional Dashboard</h3>
<p>
<i class="fa-solid fa-chart-line"></i>
<?php echo $views; ?> views in the last 30 days
</p>
</div>

<!-- SKILLS -->
<div class="skills-section">
<h2>My Skills</h2>

<div class="skills-grid">

<?php
if(mysqli_num_rows($skills_result) > 0){

    while($skill = mysqli_fetch_assoc($skills_result)){
?>

<div class="skill-card">

    <div class="skill-icon">
        <i class="fa-solid fa-code"></i>
    </div>

    <h4><?php echo $skill['skill_name']; ?></h4>

    <p><?php echo $skill['description'] ?? ''; ?></p>

    <!-- ✅ SHOW ONLY FOR OTHER USERS -->
    <?php if($view_id != $user_id){ ?>
        <form method="POST" action="php/send_request.php">
            <input type="hidden" name="skill_id" value="<?php echo $skill['id']; ?>">
            <input type="hidden" name="receiver_id" value="<?php echo $view_id; ?>">
            <button type="submit" class="request-btn">Request Skill</button>
        </form>
    <?php } ?>

</div>

<?php
    }

}else{
    echo "<p>No skills added yet.</p>";
}
?>

</div>
</div>

</div>

</body>
</html>