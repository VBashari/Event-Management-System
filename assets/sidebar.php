<?php
$user = require __DIR__ . '/../auth.php';
if (!$user) {
    header('Location: ../login.html');
    exit();
}
?>

<nav id="sidebar" class="nav flex-column d-flex">
    <p class="text-center pt-5 pb-2">Welcome back!</p>

    <div id="sidebar-links" class="px-4">
        <!-- <a class="nav-link active" href="dashboard.php">Dashboard</a> -->
        <a class="nav-link" href="#">Requests sent</a>

        <?php if ($user['user_type'] == UserType::VENDOR->value || $user['user_type'] == UserType::EVENT_ORGANIZER->value): ?>
        <!-- If user is a vendor or event organizer -->
        <a class="nav-link" href="#">Requests</a>
        <a class="nav-link" href="user_posts.php">My posts</a>
        <a class="nav-link" href="#">My services</a>
        <?php endif; ?>
    </div>

    <div id="creation-links" class="px-4">
        <a class="nav-link" href="post_form.php">Create a post</a>
        <a class="nav-link" href="#">Create a service</a>
    </div>
</nav>