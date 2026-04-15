<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Maintenance Mode | Our Website</title>
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="{{ asset('assets/dist/css/ra.css') }}">

    <!-- Google Fonts -->
    <link
        href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&family=Montserrat:wght@700&display=swap"
        rel="stylesheet">
    <style>
        :root {

            --primary-dark: #868597;
            --primary-light: #6e7188;
            --dark-color: #1e293b;
            --light-color: #f8fafc;
            --gray-color: #94a3b8;
        }

        body {
            font-family: 'Poppins', sans-serif;
            background-color: #f1f5f9;
            color: var(--dark-color);
            min-height: 100vh;
            display: flex;
            align-items: center;
            background-image:
                radial-gradient(at 80% 0%, hsla(189, 100%, 56%, 0.05) 0px, transparent 50%),
                radial-gradient(at 0% 50%, hsla(355, 100%, 93%, 0.05) 0px, transparent 50%);
        }

        .maintenance-container {
            max-width: 800px;
            margin: 0 auto;
            padding: 2rem;
            text-align: center;
        }

        .logo-text {
            font-family: 'Montserrat', sans-serif;
            font-weight: 700;
            font-size: 2.5rem;
            background: linear-gradient(135deg, var(--ra-primary-set), var(--primary-dark));
            -webkit-background-clip: text;
            background-clip: text;
            color: transparent;
            margin-bottom: 2rem;
            display: inline-block;
            letter-spacing: -0.5px;
        }

        .maintenance-icon {
            font-size: 1.5rem;
            margin-bottom: 1.5rem;
            color: var(--dark-color);
            display: inline-block;
        }

        h1 {
            font-weight: 700;
            font-size: 2.5rem;
            margin-bottom: 1rem;
            color: var(--dark-color);
        }

        .lead {
            font-size: 1.2rem;
            color: var(--gray-color);
            line-height: 1.6;
            max-width: 600px;
            margin: 0 auto 2rem;
        }

        .countdown {
            display: flex;
            justify-content: center;
            gap: 1.5rem;
            margin: 3rem 0;
            flex-wrap: wrap;
        }

        .countdown-item {
            text-align: center;
        }

        .countdown-value {
            font-size: 2.5rem;
            font-weight: 700;
            color: var(--ra-primary-set);
            line-height: 1;
            margin-bottom: 0.25rem;
            font-feature-settings: "tnum";
            font-variant-numeric: tabular-nums;
        }

        .countdown-label {
            font-size: 0.9rem;
            text-transform: uppercase;
            letter-spacing: 1px;
            color: var(--gray-color);
            font-weight: 500;
        }

        .progress-container {
            margin: 3rem auto;
            max-width: 500px;
        }

        .progress {
            height: 8px;
            border-radius: 4px;
            background-color: #e2e8f0;
            overflow: hidden;
        }

        .progress-bar {
            background: linear-gradient(90deg, var(--ra-primary-set), var(--primary-light));
            animation: progressAnimation 2s ease-in-out infinite;
        }

        @keyframes progressAnimation {
            0% {
                width: 0%;
                opacity: 0.7;
            }

            50% {
                width: 100%;
                opacity: 1;
            }

            100% {
                width: 0%;
                opacity: 0.7;
            }
        }

        .btn-maintenance {
            background: linear-gradient(135deg, var(--ra-primary-set), var(--primary-dark));
            border: none;
            padding: 0.75rem 2rem;
            font-weight: 500;
            letter-spacing: 0.5px;
            transition: all 0.3s ease;
            color: white;
            border-radius: 8px;
            display: inline-block;
            margin-top: 1rem;
        }

        .btn-maintenance:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 20px rgba(99, 102, 241, 0.3);
            color: white;
        }

        .contact-info {
            margin-top: 3rem;
            color: var(--gray-color);
            font-size: 0.95rem;
        }

        .contact-info a {
            color: var(--ra-primary-set);
            text-decoration: none;
            transition: all 0.2s ease;
        }

        .contact-info a:hover {
            color: var(--primary-dark);
            text-decoration: underline;
        }

        @media (max-width: 768px) {
            .maintenance-container {
                padding: 1.5rem;
            }

            .logo-text {
                font-size: 2rem;
            }

            h1 {
                font-size: 2rem;
            }

            .lead {
                font-size: 1.1rem;
            }

            .countdown {
                gap: 1rem;
                margin: 2rem 0;
            }

            .countdown-value {
                font-size: 2rem;
            }

            .maintenance-icon {
                font-size: 3rem;
            }
        }
    </style>
</head>

<body>
    <div class="maintenance-container">
        <div class="logo-text"><img src="{{ asset('images/ra3.png') }}" class="img-fluid" width="100px" alt="">
        </div>

        <div class="maintenance-icon">

            Raiyaan Info Techologies
        </div>

        <h1>Website Under Maintenance <i class="fas fa-tools"></i></h1>

        <p class="lead">
            We're currently performing scheduled maintenance to improve your experience.
            The site will be back online shortly. Thank you for your patience!
        </p>

        <div class="progress-container">
            <div class="progress">
                <div class="progress-bar progress-bar-striped progress-bar-animated" role="progressbar"></div>
            </div>
        </div>

        <div class="countdown">
            <div class="countdown-item">
                <div class="countdown-value" id="days">00</div>
                <div class="countdown-label">Days</div>
            </div>
            <div class="countdown-item">
                <div class="countdown-value" id="hours">00</div>
                <div class="countdown-label">Hours</div>
            </div>
            <div class="countdown-item">
                <div class="countdown-value" id="minutes">00</div>
                <div class="countdown-label">Minutes</div>
            </div>
            <div class="countdown-item">
                <div class="countdown-value" id="seconds">00</div>
                <div class="countdown-label">Seconds</div>
            </div>
        </div>
        {{--
        <a href="#" class="btn-maintenance">
            <i class="fas fa-envelope me-2"></i> Get Notified
        </a> --}}

        <div class="contact-info">
            Need immediate assistance? Contact us at <a href="https://raiyaaninfotech.com">www.raiyaaninfotech.com</a>
        </div>
    </div>

    <!-- Bootstrap 5 JS Bundle with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    <!-- Countdown Timer Script -->
    <script>
        // Set the date we're counting down to (e.g., 2 hours from now)
        const countDownDate = new Date();
        countDownDate.setHours(countDownDate.getHours() + 2);

        // Update the countdown every 1 second
        const countdownFunction = setInterval(function() {
            // Get today's date and time
            const now = new Date().getTime();

            // Find the distance between now and the countdown date
            const distance = countDownDate - now;

            // Time calculations for days, hours, minutes and seconds
            const days = Math.floor(distance / (1000 * 60 * 60 * 24));
            const hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
            const minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
            const seconds = Math.floor((distance % (1000 * 60)) / 1000);

            // Display the result
            document.getElementById("days").innerHTML = days.toString().padStart(2, '0');
            document.getElementById("hours").innerHTML = Math.floor(hours).toString().padStart(2, '0');
            document.getElementById("minutes").innerHTML = minutes.toString().padStart(2, '0');
            document.getElementById("seconds").innerHTML = seconds.toString().padStart(2, '0');

            // If the countdown is finished, write some text
            if (distance < 0) {
                clearInterval(countdownFunction);
                document.getElementById("days").innerHTML = "00";
                document.getElementById("hours").innerHTML = "00";
                document.getElementById("minutes").innerHTML = "00";
                document.getElementById("seconds").innerHTML = "00";
                document.querySelector("h1").innerHTML = "Maintenance Complete!";
                document.querySelector(".lead").innerHTML =
                    "We've finished our maintenance. The site should be available now.";
            }
        }, 1000);
    </script>
</body>

</html>
