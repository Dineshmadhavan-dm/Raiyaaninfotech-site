<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Unauthorized Access | Raiyaan Info Technologies</title>
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
            --error-color: #ef4444;
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
            color: var(--error-color);
            display: inline-block;
            animation: pulse 2s infinite;
        }

        @keyframes pulse {
            0% {
                transform: scale(1);
            }

            50% {
                transform: scale(1.1);
            }

            100% {
                transform: scale(1);
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
            color: var(--error-color);
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
            border: 2px solid var(--error-color);
            color: var(--error-color);
        }

        .btn-outline-error:hover {
            background: var(--error-color);
            color: white;
        }

        .lock-animation {
            position: relative;
            width: 100px;
            height: 100px;
            margin: 0 auto -2rem;
        }

        .lock-body {
            position: absolute;
            width: 60px;
            height: 50px;
            background-color: var(--error-color);
            border-radius: 8px;
            top: 50px;
            left: 20px;
        }

        .lock-top {
            position: absolute;
            width: 30px;
            height: 30px;
            background-color: var(--error-color);
            border-radius: 50%;
            top: 35px;
            left: 35px;
        }

        .lock-hole {
            position: absolute;
            width: 10px;
            height: 10px;
            background-color: #f1f5f9;
            border-radius: 50%;
            top: 45px;
            left: 45px;
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
            .error-container {
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

            .error-icon {
                font-size: 4rem;

            }
        }
    </style>
</head>

<body>
    <div class="error-container">
        {{-- <div class="logo-text"><img src="{{ asset('images/ra3.png') }}" class="img-fluid" width="100px" alt="">
        </div> --}}

        <div class="error-icon">
            <div class="lock-animation">
                <div class="lock-body"></div>
                <div class="lock-top"></div>
                <div class="lock-hole"></div>
            </div>
        </div>

        <div class="error-code ">401 UNAUTHORIZED</div>
        <h1 class=" mt-3">Access Denied</h1>

        <p class="lead">
            You don't have permission to access this page. Please authenticate or
            contact your administrator for access rights.
        </p>

        <div class="d-flex flex-wrap justify-content-center">
            <a href="{{ route('loginget') }}" class="btn-error">
                <i class="fas fa-sign-in-alt me-2"></i> Login Page
            </a>
            <a href="/" class="btn-error btn-outline-error">
                <i class="fas fa-home me-2"></i> Return Home
            </a>
        </div>

        <div class="contact-info">
            Need access? Contact your <a href="">system administrator</a> or
            <a href="">support team</a>
        </div>
    </div>

    <!-- Bootstrap 5 JS Bundle with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>
