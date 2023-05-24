<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet"
          integrity="sha384-rbsA2VBKQhggwzxH7pPCaAqO46MgnOM80zW1RWuH61DGLwZJEdK2Kadq2F9CUG65" crossorigin="anonymous">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $title ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://getbootstrap.com/docs/5.3/assets/css/docs.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://kit.fontawesome.com/22097c36aa.js" crossorigin="anonymous"></script>
    <script type="text/javascript"
            src="http://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js">
    </script>
    <script src="http://code.jquery.com/jquery-migrate-1.1.1.js"></script>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="/css/Admin/AdminPanelSideBar.css">
    <link href='https://unpkg.com/boxicons@2.0.7/css/boxicons.min.css' rel='stylesheet'>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <?php
    if (isset($pathToCss)) {
        ?>
        <link href="<?= $pathToCss ?>" rel="stylesheet">
        <?php
    }
    ?>
</head>
<body>
<div class="sidebar">
    <div class="logo-details">
        <i class="fas fa-gifts"></i>
        <span class="logo_name">The Festival</span>
    </div>
    <ul class="nav-links">
        <li>
            <a href="#">
                <i class='bx bx-grid-alt'></i>
                <span class="link_name">Dashboard</span>
            </a>
            <ul class="sub-menu blank">
                <li><a class="link_name" href="#">Dance</a></li>
            </ul>
        </li>
        <li>
            <a href="/admin/homepage/edithomepage">
                <i class="fas fa-home-alt" style="color: #ffffff;"></i>
                <span class="link_name">Edit Home Page</span>
            </a>
        </li>
        <li>
            <div class="iocn-link">
                <a href="#">
                    <i class="fa-solid fa-music"></i> <span class="link_name">Dance</span>
                </a>
                <i class='bx bxs-chevron-down arrow'></i>
            </div>
            <ul class="sub-menu">
                <li><a class="link_name" href="#">Category</a></li>
                <li><a href="/admin/dance/artists">Artists</a></li>
                <li><a href="/admin/dance/Performances">Performances</a></li>
                <li><a href="/admin/dance/venues">Venues</a></li>
            </ul>
        </li>
        <li>
            <div class="iocn-link">
                <a href="#">
                    <i class="fa-solid fa-landmark"></i>
                    <span class="link_name">History</span>
                </a>
                <i class='bx bxs-chevron-down arrow'></i>
            </div>
            <ul class="sub-menu">
                <li><a class="link_name" href="#">Category</a></li>
                <li><a href="/admin/history/historyTours">History Tour</a></li>
                <li><a href="/admin/history/tourLocations">Tour Location</a></li>
            </ul>
        </li>
        <li>
            <div class="iocn-link">
                <a href="#">
                    <i class="fa-solid fa-bowl-food"></i>
                    <span class="link_name">Yummy</span>
                </a>
                <i class='bx bxs-chevron-down arrow'></i>
            </div>
            <ul class="sub-menu">
                <li><a class="link_name" href="#">Posts</a></li>
                <li><a href="/admin/yummy/restaurant">Restaurants</a></li>
            </ul>
        </li>
        <li>
            <a href="/admin/manageusers">
                <i class="fa-sharp fa-solid fa-users"></i>
                <span class="link_name">Manage User</span>
                <ul class="sub-menu blank">
                    <li><a class="link_name" href="#">Analytics</a></li>
                </ul>
        </li>
        <li>
            <a href="/admin/infoPages">
                <i class="fa-solid fa-circle-info" style="color: #FFFFFF;"></i>
                <span class="link_name">Info Pages</span>
            </a>
            <ul class="sub-menu blank">
                <li><a class="link_name">Manage Info Page</a></li>
            </ul>
        </li>
        <li>
            <a href="#">
                <i class='bx bx-compass'></i>
                <span class="link_name">Explore</span>
            </a>
            <ul class="sub-menu blank">
                <li><a class="link_name" href="#">Explore</a></li>
            </ul>
        </li>
        <li>
            <a href="/admin/ApiManagement">
                <i class="fa-solid fa-key"></i>
                <span class="link_name">API Management</span>
            </a>
        </li>
        <li>
            <a href="#">
                <i class='bx bx-cog'></i>
                <span class="link_name">Setting</span>
            </a>
            <ul class="sub-menu blank">
                <li><a class="link_name" href="#">Setting</a></li>
            </ul>
        </li>
        <li>
            <div class="profile-details">
                <div class="profile-content">
                    <img src="<?=$imagePath?>" alt="profileImg" style="height: 50px; width 50px; border-radius: 50%">
                </div>
                <div class="name-job">
                    <div class="profile_name"><?= $loggedUser->getFirstName() ?></div>
                    <div class="job"><?= Roles::getLabel($loggedUser->getRole()) ?></div>
                </div>
                <form method="POST">
                    <button type="submit" class="btn" name="btnLogout">
                        <i class='bx bx-log-out'></i>
                    </button>
                </form>
            </div>
        </li>
    </ul>

</div>
<script>
    let arrow = document.querySelectorAll(".arrow");
    for (let i = 0; i < arrow.length; i++) {
        arrow[i].addEventListener("click", (e) => {
            let arrowParent = e.target.parentElement.parentElement;//selecting main parent of arrow
            arrowParent.classList.toggle("showMenu");
        });
    }
</script>
</body>
</html>
