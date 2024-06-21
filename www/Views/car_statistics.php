<?php /** @var DTO\StatisticsDTO $statisticsDTO */ ?>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Car Statistics</title>
    <style>
        li {
            display: block;
        }

        h1 {
            margin-bottom: 30px;
        }
        body {
            font-family: Arial, sans-serif;
            background: #f0f0f0;
            display: flex;
            flex-direction: column;
            align-items: center;
            margin: 0;
            padding: 20px;
        }
        .grid-container {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 20px;
            width: 100%;
            max-width: 1200px;
        }
        .container {
            background: #ffffff;
            border-radius: 20px;
            box-shadow: 20px 20px 60px #bebebe, -20px -20px 60px #ffffff;
            padding: 20px;
        }
        .container h2 {
            margin-top: 0;
        }
        .container p, .container ul {
            margin: 10px 0;
        }
        .grid-container-2 {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 20px;
            width: 100%;
            max-width: 1200px;
            margin-top: 20px; /* Добавленный отступ */
        }
        @media (max-width: 800px) {
            .grid-container {
                grid-template-columns: 1fr;
            }
            .grid-container-2 {
                grid-template-columns: 1fr;
            }
            .container {
                margin: 10px 0;
            }
        }
    </style>
</head>
<body>
<h1>Car Statistics</h1>

<div class="grid-container">
    <div class="container">
        <h2>Average Mileage</h2>
        <p><?= $statisticsDTO->avgMileage ?> km</p>
    </div>

    <div class="container">
        <h2>TOP-3 Countries of Origin</h2>
        <ul>
            <?php foreach ($statisticsDTO->topCountries as $country): ?>
                <li><?= $country['name'] . ": " . $country['count'] ?></li>
            <?php endforeach; ?>
        </ul>
    </div>

    <div class="container">
        <h2>Max and Min Power</h2>
        <p>Max Power: <?=$statisticsDTO->maxPower ?> HP</p>
        <p>Min Power: <?= $statisticsDTO->minPower ?> HP</p>
    </div>
</div>

<div class="grid-container-2">
    <div class="container">
        <h2>Available Gearbox Options</h2>
        <ul>
            <?php foreach ($statisticsDTO->gearboxOptions as $gearbox): ?>
                <li><?= $gearbox ?></li>
            <?php endforeach; ?>
        </ul>
    </div>

    <div class="container">
        <h2>Available Fuel Options</h2>
        <ul>
            <?php foreach ($statisticsDTO->fuelOptions as $fuel): ?>
                <li><?= $fuel ?></li>
            <?php endforeach; ?>
        </ul>
    </div>
</div>


</body></html>