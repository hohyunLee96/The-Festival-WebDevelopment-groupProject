<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title> <?= $title ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://getbootstrap.com/docs/5.3/assets/css/docs.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://kit.fontawesome.com/22097c36aa.js" crossorigin="anonymous"></script>
    <link href="/../css/navBarStyle.css" rel="stylesheet" type="text/css">
    <meta name="description" content="">
    <meta name="author" content="">
    <script type="text/javascript"
            src="http://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js">
    </script>
    <script src="http://code.jquery.com/jquery-migrate-1.1.1.js"></script>

    <?php
    if (isset($pathToCss)) {
        ?>
        <link href="<?= $pathToCss ?>" rel="stylesheet">
        <?php
    }
    ?>
</head>
<body>
<header>
    <nav class="navbar navbar-expand-md bg-body-tertiary justify-content-right">
        <div class=" container-fluid" id="navbarMain">
            <a class="navbar-brand" href="/">Visit Haarlem</a>
            <button class="navbar-toggler" id="navbarToggleBtn" type="button" data-bs-toggle="collapse"
                    data-bs-target="#navbarNavDropdown"
                    aria-controls="navbarNavDropdown" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="navbar-collapse collapse justify-content-between align-items-center w-100"
                 id="navbarNavDropdown">
                <ul class="navbar-nav mx-auto text-center nav nav-pills">
                    <?php foreach ($navBarItems as $navBarItem): ?>
                        <?php if (!in_array($navBarItem,$festivalNavBarItems)): ?>
                            <li class="nav-item">
                                <a class="nav-link" style="font-size: 20px;" aria-current="page"
                                   href="<?= $navBarItem->getNavBarUrl() ?>"><?= $navBarItem->getNavName() ?></a>
                            </li>
                        <?php endif; ?>
                    <?php endforeach; ?>
                    <?php if (!empty($festivalNavBarItems)): ?>
                        <div class="ps-4 pt-1">
                            <div class="btn-group">
                                <button type="button" class="btn btn-danger dropdown-toggle" data-bs-toggle="dropdown"
                                        aria-expanded="false">
                                    Festival
                                </button>
                                <ul class="dropdown-menu">
                                    <?php foreach ($festivalNavBarItems as $festivalNavBarItem): ?>
                                        <li><a class="dropdown-item"
                                               href="<?= $festivalNavBarItem->getNavBarUrl() ?>"><?= $festivalNavBarItem->getNavName() ?></a>
                                        </li>
                                    <?php endforeach; ?>
                                </ul>
                            </div>
                        </div>
                    <?php endif; ?>
                </ul>

                <ul class="nav navbar-nav flex-row justify-content-center flex-nowrap">
                    <li class="nav-item">
                        <a href="#" class="btn btn-light" role="button">

                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor"
                                 class="bi bi-person" viewBox="0 0 16 16">
                                <path d="M8 8a3 3 0 1 0 0-6 3 3 0 0 0 0 6Zm2-3a2 2 0 1 1-4 0 2 2 0 0 1 4 0Zm4 8c0 1-1 1-1 1H3s-1 0-1-1 1-4 6-4 6 3 6 4Zm-1-.004c-.001-.246-.154-.986-.832-1.664C11.516 10.68 10.289 10 8 10c-2.29 0-3.516.68-4.168 1.332-.678.678-.83 1.418-.832 1.664h10Z"/>
                            </svg>
                        </a>

                    </li>
                    <li class="nav-item dropdown">
                        <button class="btn btn-light" id="accountSettings" type="button" data-bs-toggle="dropdown"
                                aria-expanded="false">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor"
                                 class="bi bi-person-gear" viewBox="0 0 16 16">
                                <path d="M11 5a3 3 0 1 1-6 0 3 3 0 0 1 6 0ZM8 7a2 2 0 1 0 0-4 2 2 0 0 0 0 4Zm.256 7a4.474 4.474 0 0 1-.229-1.004H3c.001-.246.154-.986.832-1.664C4.484 10.68 5.711 10 8 10c.26 0 .507.009.74.025.226-.341.496-.65.804-.918C9.077 9.038 8.564 9 8 9c-5 0-6 3-6 4s1 1 1 1h5.256Zm3.63-4.54c.18-.613 1.048-.613 1.229 0l.043.148a.64.64 0 0 0 .921.382l.136-.074c.561-.306 1.175.308.87.869l-.075.136a.64.64 0 0 0 .382.92l.149.045c.612.18.612 1.048 0 1.229l-.15.043a.64.64 0 0 0-.38.921l.074.136c.305.561-.309 1.175-.87.87l-.136-.075a.64.64 0 0 0-.92.382l-.045.149c-.18.612-1.048.612-1.229 0l-.043-.15a.64.64 0 0 0-.921-.38l-.136.074c-.561.305-1.175-.309-.87-.87l.075-.136a.64.64 0 0 0-.382-.92l-.148-.045c-.613-.18-.613-1.048 0-1.229l.148-.043a.64.64 0 0 0 .382-.921l-.074-.136c-.306-.561.308-1.175.869-.87l.136.075a.64.64 0 0 0 .92-.382l.045-.148ZM14 12.5a1.5 1.5 0 1 0-3 0 1.5 1.5 0 0 0 3 0Z"/>
                            </svg>
                        </button>
                        <ul class="dropdown-menu" id="accountSettingsLinks">
                            <li><a class="dropdown-item" href="/manageAccount" id="manageAccountLink">Manage account</a>
                            </li>
                            <li id="manageUsersLink"><a class="dropdown-item" href="/admin/dance">Admin Panel</a></li>
                        </ul>
                    </li>
                </ul>
            </div>
        </div>
    </nav>
    <script src="/Javascripts/NavBar.js"></script>
    <script type="text/javascript" src="//s7.addthis.com/js/300/addthis_widget.js#pubid=ra-641c8a1ae095e8f8"></script>
</header>



