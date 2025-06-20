<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Food-Ordering</title>
    <link rel="stylesheet" href="/css/style.css" />
</head>
<body>
<header class="topbar">
    <h1><a href="/menu">🍕 Legacy Food-Ordering</a></h1>
    <nav>
        <a href="/menu">Menu</a>
        <a href="/cart">Cart (<?php echo array_sum($_SESSION['cart'] ?? []); ?>)</a>
        <a href="/kitchen">Kitchen</a>
    </nav>
</header>
<main class="container">
