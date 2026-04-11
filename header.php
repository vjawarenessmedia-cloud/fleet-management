<?php
require_once 'config.php';
requireLogin();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Fleet Management System - APP Transport & Travels</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <div class="container-fluid">
            <a class="navbar-brand" href="index.php">APP Fleet Manager</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav me-auto">
                    <li class="nav-item"><a class="nav-link" href="index.php">Dashboard</a></li>
                    <li class="nav-item"><a class="nav-link" href="vehicles.php">Vehicles</a></li>
                    <li class="nav-item"><a class="nav-link" href="drivers.php">Drivers</a></li>
                    <li class="nav-item"><a class="nav-link" href="service_records.php">Service Records</a></li>
                    <li class="nav-item"><a class="nav-link" href="spare_parts.php">Spare Parts</a></li>
                    <li class="nav-item"><a class="nav-link" href="fuel_logs.php">Fuel Logs</a></li>
                    <li class="nav-item"><a class="nav-link" href="pre_trip.php">Pre-Trip Checklist</a></li>
                    <li class="nav-item"><a class="nav-link" href="reminders.php">Reminders</a></li>
                    <li class="nav-item"><a class="nav-link" href="reports.php">Reports</a></li>
                </ul>
                <span class="navbar-text me-3">Welcome, <?= htmlspecialchars($_SESSION['username']) ?></span>
                <a href="logout.php" class="btn btn-outline-light btn-sm">Logout</a>
            </div>
        </div>
    </nav>
    <div class="container mt-4">