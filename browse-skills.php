<?php include("php/db.php"); ?>

<?php
$search = "";

if(isset($_GET['search'])){
    $search = mysqli_real_escape_string($conn, $_GET['search']);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Browse Skills | SkillSwap</title>
    <link rel="stylesheet" href="css/style.css">

    <!-- Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

    <!-- Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>

<!-- Navbar -->
<nav class="navbar">
    <div class="logo">SkillSwap</div>
    <ul class="nav-links">
        <li><a href="index.html">Home</a></li>
        <li><a href="browse-skills.php">Browse Skills</a></li>
        <li class="nav-item">
          <a class="nav-link" href="dashboard.php">Dashboard</a>
        </li>
        <li><a href="profile.php">Profile</a></li>
    </ul>
</nav>

<!-- Skills Header -->
<section class="skills-header">
    <h1>Explore Skills</h1>
    <p>Find people who can teach you new skills.</p>

    <!-- SEARCH FORM -->
    <form method="GET" action="">
        <div class="search-box">
            <input type="text" name="search" placeholder="Search skills..." value="<?php echo htmlspecialchars($search); ?>">
            <button type="submit"><i class="fa fa-search"></i></button>
        </div>
    </form>

</section>

<!-- Skills Grid -->
<section class="skills-grid">
    <h2>Available Skills</h2>

<?php

// SEARCH QUERY
$sql = "SELECT users.id AS user_id, users.name, skills.skill_name, skills.description 
        FROM skills 
        JOIN users ON skills.user_id = users.id
        WHERE skills.skill_name LIKE '%$search%' 
        OR skills.description LIKE '%$search%'";

$result = mysqli_query($conn, $sql);

if(mysqli_num_rows($result) > 0){

    while($row = mysqli_fetch_assoc($result)){

        echo "<div class='skill-card'>";

        echo "<div class='skill-icon'><i class='fa-solid fa-code'></i></div>";

        echo "<h3>" . htmlspecialchars($row['skill_name']) . "</h3>";

        echo "<p>" . htmlspecialchars($row['description']) . "</p>";

        echo "<p class='instructor'><strong>Instructor:</strong> " . htmlspecialchars($row['name']) . "</p>";

        echo "<a href='profile.php?id=".$row['user_id']."' class='btn'>View Profile</a>";

        echo "</div>";
    }

} else {
    echo "<p>No skills found.</p>";
}

?>

</section>

<!-- Footer -->
<footer>
    <p>© 2026 SkillSwap Platform</p>
</footer>

</body>
</html>