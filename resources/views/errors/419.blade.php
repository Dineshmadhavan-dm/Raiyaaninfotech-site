<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Page Expired | Raiyaan Info Technologies</title>
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
            color: var(--dark-color);
            display: inline-block;
            animation: pulse 2s infinite;
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
            color: var(--ra-primary-set);
            margin-bottom: 1.5rem;
            font-weight: 600;
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
            border: 2px solid var(--ra-primary-set);
            color: var(--ra-primary-set);
        }

        .btn-outline-error:hover {
            background: var(--ra-primary-set);
            color: white;
        }

        .search-box {
            max-width: 500px;
            margin: 2rem auto;
            position: relative;
        }

        .search-box input {
            width: 100%;
            padding: 0.75rem 1.5rem;
            border-radius: 50px;
            border: 1px solid #e2e8f0;
            font-size: 1rem;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.05);
        }

        .search-box button {
            position: absolute;
            right: 5px;
            top: 5px;
            background: linear-gradient(135deg, var(--ra-primary-set), var(--primary-dark));
            border: none;
            color: white;
            border-radius: 50px;
            width: 40px;
            height: 40px;
            display: flex;
            align-items: center;
            justify-content: center;
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
            <i class="fas fa-clock"></i>
        </div>

        <div class="error-code">419 ERROR</div>
        <h1>Page Expired</h1>

        <p class="lead">
            The page has expired due to inactivity. This is usually caused when you leave a form page open for too long
            before submitting.
            Please refresh the page and try again.
        </p>

        <div class="d-flex flex-wrap justify-content-center">
            <a href="/" class="btn-error">
                <i class="fas fa-home me-2"></i> Return Home
            </a>
            <a href="{{ route('loginget') }}">
                <button onclick="window.location.reload()" class="btn-error btn-outline-error">
                    <i class="fas fa-sync-alt me-2"></i> Refresh Page
                </button>
            </a>
        </div>

        <div class="contact-info">
            Need help? Visit our <a href="">homepage</a> or
            <a href="">contact support</a>
        </div>
    </div>

    <!-- Bootstrap 5 JS Bundle with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>
