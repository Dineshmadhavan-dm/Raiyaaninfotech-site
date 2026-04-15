<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>RaiyaanTech | Innovative IT Solutions</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        /* Global Styles */
        :root {
            --primary: #4477b6;
            --primary-dark: #6366f1;
            --dark: #1e293b;
            --light: #f8fafc;
            --gray: #64748b;
            --success: #10b981;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        body {
            background-color: #ffffff;
            color: var(--dark);
            line-height: 1.6;
        }

        .container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 20px;
        }

        section {
            padding: 80px 0;
        }

        h1,
        h2,
        h3,
        h4 {
            line-height: 1.2;
            margin-bottom: 1.5rem;
        }

        h1 {
            font-size: 3.5rem;
            font-weight: 800;
        }

        h2 {
            font-size: 2.5rem;
            font-weight: 700;
            text-align: center;
        }

        h3 {
            font-size: 1.5rem;
            font-weight: 600;
        }

        p {
            color: var(--gray);
            margin-bottom: 1.5rem;
            font-size: 1.1rem;
        }

        .btn {
            display: inline-block;
            padding: 12px 28px;
            background-color: var(--primary);
            color: white;
            border-radius: 50px;
            text-decoration: none;
            font-weight: 600;
            transition: all 0.3s ease;
            border: none;
            cursor: pointer;
        }

        .btn:hover {
            background-color: var(--primary-dark);
            transform: translateY(-2px);
            box-shadow: 0 10px 20px rgba(37, 99, 235, 0.2);
        }

        .btn-outline {
            background-color: transparent;
            border: 2px solid #fff;
            color: #fff;
        }

        .btn-outline:hover {
            background-color: #fff;
            color: #000;
        }

        .text-center {
            text-align: center;
        }

        /* Header */
        header {
            position: fixed;
            width: 100%;
            top: 0;
            z-index: 1000;
            background-color: rgba(255, 255, 255, 0.95);
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            transition: all 0.3s ease;
        }

        header.scrolled {
            background-color: rgba(255, 255, 255, 0.98);
            box-shadow: 0 5px 20px rgba(0, 0, 0, 0.1);
        }

        nav {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 20px 0;
        }

        .logo {
            font-size: 1.8rem;
            font-weight: 700;
            color: var(--primary);
            text-decoration: none;
            display: flex;
            align-items: center;
        }

        .logo i {
            margin-right: 10px;
        }

        .nav-links {
            display: flex;
            gap: 30px;
        }

        .nav-links a {
            color: var(--dark);
            text-decoration: none;
            font-weight: 500;
            transition: color 0.3s ease;
            position: relative;
        }

        .nav-links a:hover {
            color: var(--primary);
        }

        .nav-links a::after {
            content: '';
            position: absolute;
            width: 0;
            height: 2px;
            background: var(--primary);
            bottom: -5px;
            left: 0;
            transition: width 0.3s ease;
        }

        .nav-links a:hover::after {
            width: 100%;
        }

        .mobile-menu-btn {
            display: none;
            background: none;
            border: none;
            font-size: 1.5rem;
            cursor: pointer;
            color: var(--dark);
        }

        /* Hero Section */
        .hero {
            height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding-top: 80px;
            background: linear-gradient(135deg, rgba(248, 250, 252, 0.253) 0%, rgba(241, 245, 249, 0.11) 100%),
                url('https://images.unsplash.com/photo-1499750310107-5fef28a66643?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=1470&q=80') no-repeat center center/cover;
        }

        /* Optional inner container to apply the glass effect */
        .hero .glass {
            background: rgba(255, 255, 255, 0.2);
            border-radius: 16px;
            padding: 40px;
            backdrop-filter: blur(10px);
            -webkit-backdrop-filter: blur(10px);
            box-shadow: 0 8px 32px 0 rgba(31, 38, 135, 0.1);
            border: 1px solid rgba(255, 255, 255, 0.3);
            max-width: 600px;
            text-align: center;
        }


        .hero-content {
            max-width: 600px;
        }

        .hero h1 {
            margin-bottom: 1.5rem;
        }

        .hero p {
            font-size: 1.2rem;
            margin-bottom: 2.5rem;
            color: #fff;
        }

        .hero-btns {
            display: flex;
            gap: 20px;
        }

        /* Services Section */
        .services {
            background-color: var(--light);
        }

        .section-intro {
            max-width: 700px;
            margin: 0 auto 50px;
            text-align: center;
        }

        .services-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 30px;
        }

        .service-card {
            background-color: white;
            border-radius: 15px;
            padding: 40px 30px;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.05);
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }

        .service-card:hover {
            transform: translateY(-10px);
            box-shadow: 0 15px 30px rgba(0, 0, 0, 0.1);
        }

        .service-icon {
            font-size: 2.5rem;
            color: var(--primary);
            margin-bottom: 1.5rem;
        }

        /* About Section */
        .about {
            display: flex;
            align-items: center;
            gap: 50px;
        }

        .about-img {
            flex: 1;
            border-radius: 15px;
            overflow: hidden;
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.1);
        }

        .about-img img {
            width: 100%;
            height: auto;
            display: block;
        }

        .about-content {
            flex: 1;
        }

        .about-content h2 {
            text-align: left;
        }

        .stats {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 30px;
            margin-top: 40px;
        }

        .stat-item h3 {
            font-size: 2.5rem;
            color: var(--primary);
            margin-bottom: 5px;
        }

        /* Portfolio Section */
        .portfolio {
            background-color: var(--light);
        }

        .portfolio-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(350px, 1fr));
            gap: 30px;
        }

        .portfolio-item {
            position: relative;
            border-radius: 15px;
            overflow: hidden;
            height: 250px;
        }

        .portfolio-img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            transition: transform 0.5s ease;
        }

        .portfolio-overlay {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(37, 99, 235, 0.9);
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            opacity: 0;
            transition: opacity 0.3s ease;
            padding: 20px;
            text-align: center;
            color: white;
        }

        .portfolio-item:hover .portfolio-overlay {
            opacity: 1;
        }

        .portfolio-item:hover .portfolio-img {
            transform: scale(1.1);
        }

        /* Testimonials Section */
        .testimonials .section-intro {
            margin-bottom: 70px;
        }

        .testimonial-slider {
            max-width: 800px;
            margin: 0 auto;
            position: relative;
        }

        .testimonial-card {
            background-color: white;
            border-radius: 15px;
            padding: 40px;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.05);
            text-align: center;
        }

        .testimonial-text {
            font-size: 1.2rem;
            font-style: italic;
            margin-bottom: 30px;
            color: var(--dark);
        }

        .client-info {
            display: flex;
            flex-direction: column;
            align-items: center;
        }

        .client-img {
            width: 70px;
            height: 70px;
            border-radius: 50%;
            object-fit: cover;
            margin-bottom: 15px;
            border: 3px solid var(--primary);
        }

        .client-name {
            font-weight: 600;
            margin-bottom: 5px;
        }

        .client-title {
            color: var(--gray);
            font-size: 0.9rem;
        }

        /* Contact Section */
        .contact {
            background-color: var(--light);
        }

        .contact-container {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 50px;
        }

        .contact-info {
            display: flex;
            flex-direction: column;
            gap: 30px;
        }

        .contact-item {
            display: flex;
            align-items: flex-start;
            gap: 15px;
        }

        .contact-icon {
            font-size: 1.5rem;
            color: var(--primary);
            margin-top: 5px;
        }

        .contact-form {
            background-color: white;
            border-radius: 15px;
            padding: 40px;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.05);
        }

        .form-group {
            margin-bottom: 20px;
        }

        .form-control {
            width: 100%;
            padding: 15px;
            border: 1px solid #e2e8f0;
            border-radius: 8px;
            font-size: 1rem;
            transition: border-color 0.3s ease;
        }

        .form-control:focus {
            outline: none;
            border-color: var(--primary);
        }

        textarea.form-control {
            min-height: 150px;
            resize: vertical;
        }

        /* Footer */
        footer {
            background-color: var(--dark);
            color: white;
            padding: 60px 0 20px;
        }

        .footer-container {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 40px;
            margin-bottom: 40px;
        }

        .footer-col h3 {
            color: white;
            margin-bottom: 25px;
            font-size: 1.2rem;
        }

        .footer-links {
            list-style: none;
        }

        .footer-links li {
            margin-bottom: 15px;
        }

        .footer-links a {
            color: #cbd5e1;
            text-decoration: none;
            transition: color 0.3s ease;
        }

        .footer-links a:hover {
            color: white;
        }

        .social-links {
            display: flex;
            gap: 15px;
        }

        .social-links a {
            display: flex;
            align-items: center;
            justify-content: center;
            width: 40px;
            height: 40px;
            background-color: rgba(255, 255, 255, 0.1);
            border-radius: 50%;
            color: white;
            transition: all 0.3s ease;
        }

        .social-links a:hover {
            background-color: var(--primary);
            transform: translateY(-3px);
        }

        .footer-bottom {
            text-align: center;
            padding-top: 20px;
            border-top: 1px solid rgba(255, 255, 255, 0.1);
            color: #94a3b8;
            font-size: 0.9rem;
        }

        /* Responsive Styles */
        @media (max-width: 992px) {
            h1 {
                font-size: 2.8rem;
            }

            h2 {
                font-size: 2rem;
            }

            .about {
                flex-direction: column;
            }

            .about-content h2 {
                text-align: center;
            }
        }

        @media (max-width: 768px) {
            .nav-links {
                display: none;
            }

            .mobile-menu-btn {
                display: block;
            }

            .hero-btns {
                flex-direction: column;
                gap: 15px;
            }

            .btn {
                width: 100%;
                text-align: center;
            }
        }

        @media (max-width: 576px) {
            section {
                padding: 60px 0;
            }

            h1 {
                font-size: 2.2rem;
            }

            h2 {
                font-size: 1.8rem;
            }

            .stats {
                grid-template-columns: 1fr;
            }
        }
    </style>
</head>

<body>
    <!-- Header -->
    <header id="header">
        <div class="container">
            <nav>
                <a href="#" class="logo">
                    <img src="{{ asset('images/ra3.png') }}" class=" img-fluid" width="40px" alt="">
                    <span class=" " style="font-size: 20px">RaiyaanTech</span>
                </a>

                <div class="nav-links">
                    <a href="#home">Home</a>
                    <a href="#services">Services</a>
                    <a href="#about">About</a>
                    <a href="#portfolio">Portfolio</a>
                    <a href="#contact">Contact</a>
                </div>

                <button class="mobile-menu-btn">
                    <i class="fas fa-bars"></i>
                </button>
            </nav>
        </div>
    </header>

    <!-- Hero Section -->
    <section class="hero" id="home">
        <div class="glass">
            <div class="container">
                <div class="hero-content">
                    <h1>Innovative IT Solutions for Your Business</h1>
                    <p>We help businesses transform through cutting-edge technology solutions tailored to your unique
                        needs
                        and goals.</p>
                    <div class="hero-btns">
                        <a href="#contact" class="btn">Get Started</a>
                        <a href="#services" class="btn  btn-outline">Our Services</a>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Services Section -->
    <section class="services" id="services">
        <div class="container">
            <div class="section-intro">
                <h2>Our Services</h2>
                <p>We offer comprehensive IT services to help your business thrive in the digital age.</p>
            </div>

            <div class="services-grid">
                <div class="service-card">
                    <div class="service-icon">
                        <i class="fas fa-laptop-code"></i>
                    </div>
                    <h3>Custom Software Development</h3>
                    <p>Tailored software solutions designed to streamline your business processes and improve
                        efficiency.</p>
                </div>

                <div class="service-card">
                    <div class="service-icon">
                        <i class="fas fa-mobile-alt"></i>
                    </div>
                    <h3>Mobile App Development</h3>
                    <p>Beautiful, functional mobile applications for iOS and Android that engage your customers.</p>
                </div>

                <div class="service-card">
                    <div class="service-icon">
                        <i class="fas fa-cloud"></i>
                    </div>
                    <h3>Cloud Solutions</h3>
                    <p>Secure, scalable cloud infrastructure and services to support your growing business needs.</p>
                </div>

                <div class="service-card">
                    <div class="service-icon">
                        <i class="fas fa-shield-alt"></i>
                    </div>
                    <h3>Cybersecurity</h3>
                    <p>Comprehensive security solutions to protect your business from evolving digital threats.</p>
                </div>

                <div class="service-card">
                    <div class="service-icon">
                        <i class="fas fa-chart-line"></i>
                    </div>
                    <h3>Data Analytics</h3>
                    <p>Turn your data into actionable insights with our powerful analytics and BI solutions.</p>
                </div>

                <div class="service-card">
                    <div class="service-icon">
                        <i class="fas fa-robot"></i>
                    </div>
                    <h3>AI & Machine Learning</h3>
                    <p>Leverage artificial intelligence to automate processes and gain competitive advantage.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- About Section -->
    <section class="about" id="about">
        <div class="container">
            <div class="about-img">
                <img src="https://images.unsplash.com/photo-1522071820081-009f0129c71c?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=1470&q=80"
                    alt="Our Team">
            </div>

            <div class="about-content">
                <h2>About RaiyaanTech</h2>
                <p>Founded in 2015, RaiyaanTech has grown from a small startup to a leading IT solutions provider
                    serving
                    clients across multiple industries.</p>
                <p>Our team of certified professionals brings together diverse expertise in software development, cloud
                    computing, cybersecurity, and emerging technologies.</p>
                <p>We believe in building long-term partnerships with our clients, delivering innovative solutions that
                    drive real business results.</p>

                <div class="stats">
                    <div class="stat-item">
                        <h3>150+</h3>
                        <p>Happy Clients</p>
                    </div>
                    <div class="stat-item">
                        <h3>250+</h3>
                        <p>Projects Completed</p>
                    </div>
                    <div class="stat-item">
                        <h3>98%</h3>
                        <p>Client Satisfaction</p>
                    </div>
                    <div class="stat-item">
                        <h3>40+</h3>
                        <p>Expert Team Members</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Portfolio Section -->
    <section class="portfolio" id="portfolio">
        <div class="container">
            <div class="section-intro">
                <h2>Our Portfolio</h2>
                <p>Explore some of our recent projects and see how we've helped businesses like yours succeed.</p>
            </div>

            <div class="portfolio-grid">
                <div class="portfolio-item">
                    <img src="https://images.unsplash.com/photo-1551288049-bebda4e38f71?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=1470&q=80"
                        alt="Project 1" class="portfolio-img">
                    <div class="portfolio-overlay">
                        <h3>E-Commerce Platform</h3>
                        <p>Custom e-commerce solution with AI-powered recommendations</p>
                        <a href="#" class="btn btn-outline" style="margin-top: 20px;">View Case Study</a>
                    </div>
                </div>

                <div class="portfolio-item">
                    <img src="https://images.unsplash.com/photo-1467232004584-a241de8bcf5d?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=1469&q=80"
                        alt="Project 2" class="portfolio-img">
                    <div class="portfolio-overlay">
                        <h3>Healthcare Management System</h3>
                        <p>Comprehensive platform for patient records and appointment scheduling</p>
                        <a href="#" class="btn btn-outline" style="margin-top: 20px;">View Case Study</a>
                    </div>
                </div>

                <div class="portfolio-item">
                    <img src="https://images.unsplash.com/photo-1551434678-e076c223a692?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=1470&q=80"
                        alt="Project 3" class="portfolio-img">
                    <div class="portfolio-overlay">
                        <h3>Financial Analytics Dashboard</h3>
                        <p>Real-time data visualization for investment firms</p>
                        <a href="#" class="btn btn-outline" style="margin-top: 20px;">View Case Study</a>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Testimonials Section -->
    <section class="testimonials">
        <div class="container">
            <div class="section-intro">
                <h2>What Our Clients Say</h2>
                <p>Hear from businesses that have transformed with our solutions.</p>
            </div>

            <div class="testimonial-slider">
                <div class="testimonial-card">
                    <p class="testimonial-text">"RaiyaanTech delivered our mobile app ahead of schedule and under
                        budget.
                        Their team was professional, responsive, and truly understood our business needs."</p>
                    <div class="client-info">
                        <img src="https://randomuser.me/api/portraits/women/45.jpg" alt="Client"
                            class="client-img">
                        <h4 class="client-name">Sarah Johnson</h4>
                        <p class="client-title">CEO, Retail Solutions Inc.</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Contact Section -->
    <section class="contact" id="contact">
        <div class="container">
            <div class="section-intro">
                <h2>Get In Touch</h2>
                <p>Ready to discuss your project? Contact us today for a free consultation.</p>
            </div>

            <div class="contact-container">
                <div class="contact-info">
                    <div class="contact-item">
                        <div class="contact-icon">
                            <i class="fas fa-map-marker-alt"></i>
                        </div>
                        <div>
                            <h3>Our Office</h3>
                            <p>123 Tech Street, Silicon Valley, CA 94025</p>
                        </div>
                    </div>

                    <div class="contact-item">
                        <div class="contact-icon">
                            <i class="fas fa-phone-alt"></i>
                        </div>
                        <div>
                            <h3>Phone</h3>
                            <p>+1 (555) 123-4567</p>
                        </div>
                    </div>

                    <div class="contact-item">
                        <div class="contact-icon">
                            <i class="fas fa-envelope"></i>
                        </div>
                        <div>
                            <h3>Email</h3>
                            <p>info@RaiyaanTech.com</p>
                        </div>
                    </div>

                    <div class="contact-item">
                        <div class="contact-icon">
                            <i class="fas fa-clock"></i>
                        </div>
                        <div>
                            <h3>Working Hours</h3>
                            <p>Monday - Friday: 9am - 6pm</p>
                        </div>
                    </div>
                </div>

                <div class="contact-form">
                    <form>
                        <div class="form-group">
                            <input type="text" class="form-control" placeholder="Your Name" required>
                        </div>
                        <div class="form-group">
                            <input type="email" class="form-control" placeholder="Your Email" required>
                        </div>
                        <div class="form-group">
                            <input type="text" class="form-control" placeholder="Subject">
                        </div>
                        <div class="form-group">
                            <textarea class="form-control" placeholder="Your Message" required></textarea>
                        </div>
                        <button type="submit" class="btn">Send Message</button>
                    </form>
                </div>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer>
        <div class="container">
            <div class="footer-container">
                <div class="footer-col">
                    <a href="#" class="logo" style="color: white; margin-bottom: 20px; display: block;">
                        <img src="{{ asset('images/ra3.png') }}" class=" img-fluid" width="40px" alt="">
                        <span>RaiyaanTech</span>
                    </a>
                    <p style="color: #cbd5e1;">Innovative IT solutions to power your business growth and digital
                        transformation.</p>
                    <div class="social-links" style="margin-top: 20px;">
                        <a href="#"><i class="fab fa-facebook-f"></i></a>
                        <a href="#"><i class="fab fa-twitter"></i></a>
                        <a href="#"><i class="fab fa-linkedin-in"></i></a>
                        <a href="#"><i class="fab fa-instagram"></i></a>
                    </div>
                </div>

                <div class="footer-col">
                    <h3>Services</h3>
                    <ul class="footer-links">
                        <li><a href="#">Custom Software</a></li>
                        <li><a href="#">Mobile Apps</a></li>
                        <li><a href="#">Cloud Solutions</a></li>
                        <li><a href="#">Cybersecurity</a></li>
                        <li><a href="#">Data Analytics</a></li>
                    </ul>
                </div>

                <div class="footer-col">
                    <h3>Company</h3>
                    <ul class="footer-links">
                        <li><a href="#">About Us</a></li>
                        <li><a href="#">Our Team</a></li>
                        <li><a href="#">Careers</a></li>
                        <li><a href="#">Blog</a></li>
                        <li><a href="#">Contact</a></li>
                    </ul>
                </div>

                <div class="footer-col">
                    <h3>Newsletter</h3>
                    <p style="color: #cbd5e1; margin-bottom: 20px;">Subscribe to our newsletter for the latest updates.
                    </p>
                    <form>
                        <div class="form-group" style="margin-bottom: 15px;">
                            <input type="email" class="form-control" placeholder="Your Email" required>
                        </div>
                        <button type="submit" class="btn">Subscribe</button>
                    </form>
                </div>
            </div>

            <div class="footer-bottom">
                <p>&copy; 2023 RaiyaanTech. All Rights Reserved. | <a href="#" style="color: #cbd5e1;">Privacy
                        Policy</a> | <a href="#" style="color: #cbd5e1;">Terms of Service</a></p>
            </div>
        </div>
    </footer>

    <script>
        // Header scroll effect
        window.addEventListener('scroll', function() {
            const header = document.getElementById('header');
            if (window.scrollY > 50) {
                header.classList.add('scrolled');
            } else {
                header.classList.remove('scrolled');
            }
        });

        // Mobile menu toggle
        const mobileMenuBtn = document.querySelector('.mobile-menu-btn');
        const navLinks = document.querySelector('.nav-links');

        mobileMenuBtn.addEventListener('click', function() {
            navLinks.style.display = navLinks.style.display === 'flex' ? 'none' : 'flex';
        });

        // Close mobile menu when clicking a link
        document.querySelectorAll('.nav-links a').forEach(link => {
            link.addEventListener('click', function() {
                if (window.innerWidth <= 768) {
                    navLinks.style.display = 'none';
                }
            });
        });

        // Responsive menu adjustment
        window.addEventListener('resize', function() {
            if (window.innerWidth > 768) {
                navLinks.style.display = 'flex';
            } else {
                navLinks.style.display = 'none';
            }
        });
    </script>
</body>

</html>
