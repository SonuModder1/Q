<?php
require 'config/config.php';
$dataName = ($zone == 'EU') ? (($lang == 'FR') ? "Octets" : "Bytes") : 'Bits';
$requestLang = ($lang == 'FR') ? 'Requetes' : 'Requests';
$perSecondLang = ($lang == 'FR') ? 'par seconde' : 'per second';
?>
<title><?php echo $sitename; ?></title>


<!DOCTYPE html>
<html>
<head>
    <title>Page Title</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f0f0f0; /* Light gray background */
            margin: 0;
            padding: 20px;
        }

        .chart-container {
            max-width: 500px; /* Set the max width of the chart containers */
            margin: 20px auto; /* Center the chart containers horizontally */
            border: 1px solid #ccc;
            padding: 10px;
        }

        .page-title {
            color: #333; /* Dark gray color for page titles */
            font-size: 24px;
            font-weight: bold;
            margin-bottom: 10px;
        }

        /* Style the chart container backgrounds */
        #layer7,
        #layer4 {
            background-color: #fff; /* White background for chart containers */
        }

        /* Style the chart title */
        .highcharts-title {
            color: #444; /* Slightly darker gray color for chart titles */
        }

        /* Style the axes labels */
        .highcharts-axis-labels {
            color: #666; /* Medium gray color for axes labels */
        }

        /* Style the chart series */
        .highcharts-series {
            fill: none; /* Remove fill color for chart series */
            stroke-width: 2px; /* Set the stroke width for chart lines */
        }
    </style>
    <?php error_log(" \r\n", 3, 'data/layer7-logs'); ?>
</head>
<body>
<div class="chart-container" id="layer7">
    <div class="page-title"><?php echo $Layer7Title;?></div>
</div>
<div class="chart-container" id="layer4">
    <div class="page-title"><?php echo $Layer4Title;?></div>
</div>

<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.5.1/jquery.min.js"
        integrity="sha512-bLT0Qm9VnAYZDflyKcBaQ2gg0hSYNQrJ8RilYldYQ1FxQYoCLtUjuuRuZo+fjqhx/qtq/1itJ0C2ejDxltZVFg=="
        crossorigin="anonymous"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/highcharts/8.2.2/highcharts.js"
        integrity="sha512-PpL09bLaSaj5IzGNx6hsnjiIeLm9bL7Q9BB4pkhEvQSbmI0og5Sr/s7Ns/Ax4/jDrggGLdHfa9IbsvpnmoZYFA=="
        crossorigin="anonymous"></script>
<script
        src="https://cdnjs.cloudflare.com/ajax/libs/highcharts/8.2.2/modules/exporting.min.js"
        integrity="sha512-DuFO4JhOrZK4Zz+4K0nXseP0K/daLNCrbGjSkRzK+Zibkblwqc0BYBQ1sTN7mC4Kg6vNqr8eMZwLgTcnKXF8mg=="
        crossorigin="anonymous"
></script>

<script id="source" language="javascript" type="text/javascript">
    $(document).ready(function () {
        Highcharts.createElement(
            "link",
            {
                href: "https://fonts.googleapis.com/css?family=Unica+One",
                rel: "stylesheet",
                type: "text/css",
            },
            null,
            document.getElementsByTagName("head")[0]
        );

        let layer7 = new Highcharts.Chart({
            chart: {
                renderTo: "layer7",
                defaultSeriesType: "spline",
                events: {
                    load: requestData(0),
                },
            },
            title: {
                text: "<?php echo $Layer7Title;?>",
                style: {
                    color: "#333", /* Dark gray color for chart title */
                    fontSize: "18px",
                },
            },
            xAxis: {
                type: "datetime",
                tickPixelInterval: 150,
                maxZoom: 20 * 1000,
                labels: {
                    style: {
                        color: "#666", /* Medium gray color for axes labels */
                    },
                },
            },
            yAxis: {
                minPadding: 0.2,
                maxPadding: 0.2,
                title: {
                    text: "<?php echo $requestLang;?> <?php echo $perSecondLang;?>",
                    margin: 80,
                    style: {
                        color: "#666", /* Medium gray color for axes labels */
                    },
                },
            },
            series: [
                {
                    name: "<?php echo $requestLang;?>/s",
                    data: [],
                    color: "#007bff", /* Blue color for chart series */
                },
            ],
        });

        let layer4 = new Highcharts.Chart({
            chart: {
                renderTo: "layer4",
                defaultSeriesType: "spline",
                events: {
                    load: requestData(1),
                },
            },
            title: {
                text: "<?php echo $Layer4Title;?>",
                style: {
                    color: "#333", /* Dark gray color for chart title */
                    fontSize: "18px",
                },
            },
            xAxis: {
                type: "datetime",
                tickPixelInterval: 150,
                maxZoom: 20 * 1000,
                labels: {
                    style: {
                        color: "#666", /* Medium gray color for axes labels */
                    },
                },
            },
            yAxis: {
                minPadding: 0.2,
                maxPadding: 0.2,
                title: {
                    text: "<?php echo $dataName;?> <?php echo $perSecondLang;?>",
                    margin: 80,
                    style: {
                        color: "#666", /* Medium gray color for axes labels */
                    },
                },
            },
            series: [
                {
                    name: "<?php echo $dataName;?>/s",
                    data: [],
                    color: "#28a745", /* Green color for chart series */
                },
            ],
        });

        function requestData(type) {
            $.ajax({
                url: "data/" + (!type ? "layer7" : "layer4") + ".php",
                success: function (point) {
                    var series = (!type ? layer7 : layer4).series[0],
                        shift = series.data.length > 20;
                    series.addPoint(point, true, shift);
                    setTimeout(() => requestData(type), 1000);
                },
                cache: false,
            });
        }
    });
</script>
</body>
</html>





<!DOCTYPE html>
<html>
<head>
    <title>Page Title</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <style>
        body {
            font-family: Arial, sans-serif;
            background: linear-gradient(135deg, #FF0000, #FF7F00, #FFFF00, #00FF00, #0000FF, #4B0082, #9400D3);
            background-size: 600% 600%;
            animation: rainbow 8s ease infinite;
            margin: 0;
            padding: 20px;
            color: red;
        }

        @keyframes rainbow {
            0% { background-position: 0% 50%; }
            100% { background-position: 100% 50%; }
        }

        .chart-container {
            max-width: 500px;
            margin: 20px auto;
            border: 1px solid #ccc;
            padding: 10px;
        }

        .page-title {
            color: white;
            font-size: 24px;
            font-weight: bold;
            margin-bottom: 10px;
            background-color: red;
            padding: 5px;
            border-radius: 5px;
        }

        /* Rest of your chart and other styles go here */
        #layer7,
        #layer4 {
            background-color: red;
            color: white;
        }

        .highcharts-title {
            color: white;
        }

        .highcharts-axis-labels {
            fill: white;
        }

        .highcharts-series {
            stroke: white;
        }

        /* Media query for phone size */
        @media (max-width: 480px) {
            .chart-container {
                max-width: 90%; /* Adjust the width for phone screens */
            }
        }
    </style>
    <?php error_log(" \r\n", 3, 'data/layer7-logs'); ?>
</head>
<body>
<div class="chart-container" id="layer7">
    <div class="page-title"><?php echo $Layer7Title;?></div>
</div>
<div class="chart-container" id="layer4">
    <div class="page-title"><?php echo $Layer4Title;?></div>
</div>

<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.5.1/jquery.min.js"
        integrity="sha512-bLT0Qm9VnAYZDflyKcBaQ2gg0hSYNQrJ8RilYldYQ1FxQYoCLtUjuuRuZo+fjqhx/qtq/1itJ0C2ejDxltZVFg=="
        crossorigin="anonymous"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/highcharts/8.2.2/highcharts.js"
        integrity="sha512-PpL09bLaSaj5IzGNx6hsnjiIeLm9bL7Q9BB4pkhEvQSbmI0og5Sr/s7Ns/Ax4/jDrggGLdHfa9IbsvpnmoZYFA=="
        crossorigin="anonymous"></script>
<script
        src="https://cdnjs.cloudflare.com/ajax/libs/highcharts/8.2.2/modules/exporting.min.js"
        integrity="sha512-DuFO4JhOrZK4Zz+4K0nXseP0K/daLNCrbGjSkRzK+Zibkblwqc0BYBQ1sTN7mC4Kg6vNqr8eMZwLgTcnKXF8mg=="
        crossorigin="anonymous"
></script>

<script id="source" language="javascript" type="text/javascript">
    // Your JavaScript code for chart rendering and data goes here
</script>
</body>
</html>
