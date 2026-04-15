<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Server Error | Raiyaan Info Technologies</title>
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
            --danger-color: #ef4444;
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

        .error-container {
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

        .error-icon {
            font-size: 6rem;
            margin-bottom: 1.5rem;
            color: var(--danger-color);
            display: inline-block;
            animation: pulse 1.5s infinite;
        }

        @keyframes pulse {
            0% {
                transform: scale(1);
                opacity: 1;
            }

            50% {
                transform: scale(1.1);
                opacity: 0.8;
            }

            100% {
                transform: scale(1);
                opacity: 1;
            }
        }

        h1 {
            font-weight: 700;
            font-size: 2.5rem;
            margin-bottom: 1rem;
            color: var(--dark-color);
        }

        .error-code {
            font-size: 1.5rem;
            color: var(--danger-color);
            margin-bottom: 1.5rem;
            font-weight: 600;
            background-color: rgba(239, 68, 68, 0.1);
            padding: 0.5rem 1.5rem;
            border-radius: 50px;
            display: inline-block;
        }

        .lead {
            font-size: 1.2rem;
            color: var(--gray-color);
            line-height: 1.6;
            max-width: 600px;
            margin: 0 auto 2rem;
        }

        .btn-error {
            background: linear-gradient(135deg, var(--ra-primary-set), var(--primary-dark));
            border: none;
            padding: 0.75rem 2rem;
            font-weight: 500;
            letter-spacing: 0.5px;
            transition: all 0.3s ease;
            color: white;
            border-radius: 8px;
            display: inline-block;
            margin: 1rem 0.5rem;
            text-decoration: none;
        }

        .btn-error:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 20px rgba(99, 102, 241, 0.3);
            color: white;
        }

        .btn-outline-error {
            background: transparent;
            border: 2px solid var(--danger-color);
            color: var(--danger-color);
        }

        .btn-outline-error:hover {
            background: var(--danger-color);
            color: white;
        }

        .server-graphic {
            position: relative;
            width: 120px;
            height: 100px;
            margin: 0 auto 2rem;
        }

        .server-body {
            position: absolute;
            width: 100px;
            height: 80px;
            background-color: var(--dark-color);
            border-radius: 8px;
            top: 20px;
            left: 10px;
        }

        .server-light {
            position: absolute;
            width: 10px;
            height: 10px;
            background-color: var(--danger-color);
            border-radius: 50%;
            top: 15px;
            left: 15px;
            box-shadow: 0 0 10px var(--danger-color);
            animation: blink 1s infinite alternate;
        }

        @keyframes blink {
            0% {
                opacity: 0.2;
            }

            100% {
                opacity: 1;
            }
        }

        .contact-info {
            margin-top: 3rem;
            color: var(--gray-color);
            font-size: 0.95rem;
        }

        @media (max-width: 768px) {
            .error-container {
                padding: 1.5rem;
            }

            .error-icon {
                font-size: 4rem;
            }
        }
    </style>
</head>

<body>
    <div class="error-container">


        <div class="error-icon">
            <div class="server-graphic">
                <div class="server-body"></div>
                <div class="server-light"></div>
                <i class="fas fa-server"
                    style="position: absolute; top: 40px; left: 35px; font-size: 2.5rem; color: white;"></i>
            </div>
        </div>

        <div class="error-code">500 SERVER ERROR</div>
        <h1>Internal Server Error</h1>

        <p class="lead">
            Something went wrong on our servers while processing your request.
            Our team has been notified and we're working to fix it.
        </p>

        <div class="d-flex flex-wrap justify-content-center">
            <a href="/" class="btn-error">
                <i class="fas fa-home me-2"></i> Return Home
            </a>
            <a href="" class="btn-error btn-outline-error">
                <i class="fas fa-info-circle me-2"></i> Service Status
            </a>
        </div>

        <div class="contact-info">
            Try again later or contact our <a href="">support team</a> if the problem
            persists.
        </div>
    </div>
</body>

</html>
