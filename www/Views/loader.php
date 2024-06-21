<html>

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://fonts.googleapis.com/css2?family=Open+Sans:wght@400&display=swap" rel="stylesheet">
    <style>
        body {
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            background: #f1f1f1;
            flex-direction: column;
            font-family: 'Open Sans', sans-serif;
        }

        .loader {
            position: relative;
            width: 350px;
            height: 350px;
            border-radius: 50%;
            background: linear-gradient(#f07e6e, #84cdfa, #5ad1cd);
            animation: animate 3s linear infinite;
        }

        @keyframes animate {
    0% {
        transform: rotate(0deg);
            }
            100% {
        transform: rotate(360deg);
            }
        }

        .loader span {
            position: absolute;
            width: 100%;
            height: 100%;
            border-radius: 47%;
            background: linear-gradient(#f07e6e, #84cdfa, #5ad1cd);
            transition: 1s;
        }

        .loader span:nth-child(1) {
            filter: blur(5px);
        }

        .loader span:nth-child(2) {
            filter: blur(10px);
        }

        .loader span:nth-child(3) {
            filter: blur(25px);
        }

        .loader span:nth-child(4) {
            filter: blur(50px);
        }

        .loader:after {
            content: '';
            position: absolute;
            top: 10px;
            left: 10px;
            right: 10px;
            bottom: 10px;
            background: #f1f1f1;
            border: solid white 10px;
            border-radius: 50%;
        }

        .text {
            height: 30px;
        margin-top: 50px;
            font-size: 1.2em;
            color: #898989;
            opacity: 1;
            transition: opacity 1s;
            text-align: center;
        }
    </style>
</head>

<body>
    <div class="loader">
        <span></span>
        <span></span>
        <span></span>
        <span></span>
    </div>
    <div class="text" id="loading-text">Please stand by...</div>

    <script>
        function success(){
            const circles = document.querySelectorAll('.loader span');
            circles.forEach(circle => {
                circle.style.background = "#95ff95";
            });
        }
        function error(){
            const circles = document.querySelectorAll('.loader span');
            circles.forEach(circle => {
                circle.style.background = "red";
            });
        }
        function updateLoadingText(text) {
            const textElement = document.getElementById('loading-text');
            textElement.style.opacity = 0;
            setTimeout(() => {
                textElement.innerText = text;
                textElement.style.opacity = 1;
            }, 400);
        }
        function sleep(ms) {
            return new Promise(resolve => setTimeout(resolve, ms));
        }
        async function sendSequentialRequests() {
            try {
                await sleep(1000);
                updateLoadingText("Setup migrations...");
                let response = await fetch('/?controller=Migration&action=migrate');
                if (response.status !== 200) throw new Error('Migration failed');

                await sleep(1500);
                updateLoadingText("Parsing eCarsTrade WebSite...");
                response = await fetch('/?controller=CarImportController&action=parseToCSV');
                if (response.status !== 200) throw new Error('Parsing failed');

                await sleep(1000);
                updateLoadingText("Importing parsed cars to database...");
                response = await fetch('/?controller=CarImportController&action=importFromCSV');
                if (response.status !== 200) throw new Error('Importing failed');

                await sleep(1500);
                updateLoadingText("Completed!")
                success();
                setTimeout(async () => {
                    window.location = '/';
                }, 1000);
            } catch ($e) {
                error();
                updateLoadingText($e);
            }
        }
        sendSequentialRequests();
    </script>
</body>

</html>
