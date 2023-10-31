<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Payslip</title>
    <link rel="stylesheet" href="stylesheets/mainstyle.css">
    <link rel="stylesheet" href="stylesheets/header.css">
    <link rel="stylesheet" href="stylesheets/payslip.css">
</head>
<body>
    <header>
        <!-- Your header content remains the same -->
    </header>
    
    <!-- Tab navigation -->
    <div class="tab-container">
        <ul class="tab-list">
            <li class="tab-item <?php echo ($_GET['tab'] === 'personal') ? 'active-tab' : ''; ?>"><a href="?id=<?php echo $_GET['id']; ?>&tab=personal" class="tab-link">Personal</a></li>
            <li class="tab-item <?php echo ($_GET['tab'] === 'work') ? 'active-tab' : ''; ?>"><a href="?id=<?php echo $_GET['id']; ?>&tab=work" class="tab-link">Work</a></li>
            <li class="tab-item <?php echo ($_GET['tab'] === 'money') ? 'active-tab' : ''; ?>"><a href="?id=<?php echo $_GET['id']; ?>&tab=money" class="tab-link">Money</a></li>
        </ul>
    </div>
    
    <!-- Personal Details Tab -->
    <div class="tab-content <?php echo ($_GET['tab'] === 'personal') ? 'active-tab-content' : ''; ?>">
        <?php
        // Personal details content
        if ($selectedEmployee) {
            // Display personal details here
        }
        ?>
    </div>
    
    <!-- Work Details Tab -->
    <div class="tab-content <?php echo ($_GET['tab'] === 'work') ? 'active-tab-content' : ''; ?>">
        <?php
        // Work details content
        if ($selectedEmployee) {
            // Display work details here (e.g., department, manager, etc.)
        }
        ?>
    </div>
    
    <!-- Money Tab -->
    <div class="tab-content <?php echo ($_GET['tab'] === 'money') ? 'active-tab-content' : ''; ?>">
        <?php
        // Money details content
        if ($selectedEmployee) {
            // Display money details (e.g., gross pay, take-home pay, etc.)
        }
        ?>
    </div>

    <a href="javascript:history.back()">Go Back</a>
</body>
</html>
