<!DOCTYPE html>
<html lang="en-gb">
<head>
    <title>{$title}</title>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link rel="shortcut icon" href="img/favicon.png">

    <!-- Shared styles with ethical Charter front end for this application -->
    <link rel='stylesheet' id='em-main-css' href='{$url}public/css/external.css' type='text/css' media='all'/>
    <link rel="stylesheet" type="text/css" href="//cdn.jsdelivr.net/bootstrap.daterangepicker/2/daterangepicker.css" />
    <!-- Main styles for this application -->
    <link rel='stylesheet' id='em-main-css' href='{$url}public/css/admin-style-dist.css' type='text/css' media='all'/>


</head>

<body class="app header-fixed sidebar-fixed aside-menu-fixed aside-menu-hidden">
<!-- BODY options, add following classes to body to change options

// Header options
1. '.header-fixed'					- Fixed Header

// Sidebar options
1. '.sidebar-fixed'					- Fixed Sidebar
2. '.sidebar-hidden'				- Hidden Sidebar
3. '.sidebar-off-canvas'		- Off Canvas Sidebar
4. '.sidebar-minimized'			- Minimized Sidebar (Only icons)
5. '.sidebar-compact'			  - Compact Sidebar

// Aside options
1. '.aside-menu-fixed'			- Fixed Aside Menu
2. '.aside-menu-hidden'			- Hidden Aside Menu
3. '.aside-menu-off-canvas'	- Off Canvas Aside Menu

// Footer options
1. '.footer-fixed'						- Fixed footer

-->
{if isset($adminLoggedIn)}
<header class="app-header navbar">
    <button class="navbar-toggler mobile-sidebar-toggler d-lg-none" type="button">&#9776;</button>
    <a class="navbar-brand" href="#"></a><span class="admin-name">Ethical Charter</span>
    <ul class="nav navbar-nav d-md-down-none">

        {*<li class="nav-item px-3">*}
            {*<a class="nav-link" href="{$route.admin_dashboard}">Dashboard</a>*}
        {*</li>*}
        {*<li class="nav-item px-3">*}
            {*<a class="nav-link" href="#">Users</a>*}
        {*</li>*}
    </ul>
    <ul class="nav navbar-nav ml-auto">

        <li class="nav-item d-md-down-none">
            <a class="nav-link" href="#"><i class="icon-list"></i></a>
        </li>
        <li class="nav-item d-md-down-none">
            <a class="nav-link" href="#"><i class="icon-location-pin"></i></a>
        </li>
        <li class="nav-item dropdown">
            <a class="nav-link dropdown-toggle nav-link" data-toggle="dropdown" href="#" role="button" aria-haspopup="true" aria-expanded="false">
                <span class="d-md-down-none">Welcome, {$admin.username}</span>
            </a>
            <div class="dropdown-menu dropdown-menu-right">

                <div class="dropdown-header text-center">
                    <strong>Settings</strong>
                </div>
                <a class="dropdown-item" href="#"><i class="fa fa-user"></i> Profile</a>
                {*<a class="dropdown-item" href="#"><i class="fa fa-wrench"></i> Settings</a>*}
                <a class="dropdown-item" href="{$route.logout}"><i class="fa fa-lock"></i> Logout</a>
            </div>
        </li>
        <li class="nav-item d-md-down-none">
            <a class="nav-link navbar-toggler aside-menu-toggler" href="#">&#9776;</a>
        </li>

    </ul>
</header>
{/if}
