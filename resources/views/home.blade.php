<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PreChat - AI Influencer & Professional Coaching Platform</title>

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">

    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Inter', sans-serif;
            overflow-x: hidden;
        }

        /* Navbar */
        .navbar {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            box-shadow: 0 2px 20px rgba(0, 0, 0, 0.05);
            padding: 1rem 0;
        }

        .navbar-brand {
            font-size: 1.5rem;
            font-weight: 700;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }

        .nav-link {
            font-weight: 500;
            color: #333;
            margin: 0 0.5rem;
            transition: color 0.3s;
        }

        .nav-link:hover {
            color: #667eea;
        }

        .btn-gradient {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border: none;
            color: white;
            padding: 0.75rem 2rem;
            border-radius: 50px;
            font-weight: 600;
            transition: transform 0.3s, box-shadow 0.3s;
        }

        .btn-gradient:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 30px rgba(102, 126, 234, 0.4);
            color: white;
        }

        .btn-outline-gradient {
            border: 2px solid #667eea;
            color: #667eea;
            padding: 0.75rem 2rem;
            border-radius: 50px;
            font-weight: 600;
            background: transparent;
            transition: all 0.3s;
        }

        .btn-outline-gradient:hover {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            border-color: transparent;
        }

        /* Hero Section */
        .hero-section {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            padding: 120px 0 80px;
            position: relative;
            overflow: hidden;
        }

        .hero-section::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: url('data:image/svg+xml,<svg width="100" height="100" xmlns="http://www.w3.org/2000/svg"><circle cx="50" cy="50" r="2" fill="white" opacity="0.1"/></svg>');
            animation: float 20s infinite linear;
        }

        @keyframes float {
            0% { transform: translateY(0) translateX(0); }
            100% { transform: translateY(-100px) translateX(100px); }
        }

        .hero-content {
            position: relative;
            z-index: 1;
        }

        .hero-title {
            font-size: 3.5rem;
            font-weight: 800;
            color: white;
            margin-bottom: 1.5rem;
            line-height: 1.2;
        }

        .hero-subtitle {
            font-size: 1.25rem;
            color: rgba(255, 255, 255, 0.9);
            margin-bottom: 2.5rem;
            line-height: 1.6;
        }

        .hero-image {
            position: relative;
            animation: floatUpDown 3s ease-in-out infinite;
        }

        @keyframes floatUpDown {
            0%, 100% { transform: translateY(0); }
            50% { transform: translateY(-20px); }
        }

        /* Features Section */
        .features-section {
            padding: 100px 0;
            background: #f8f9fa;
        }

        .section-title {
            font-size: 2.5rem;
            font-weight: 700;
            text-align: center;
            margin-bottom: 1rem;
            color: #1a1a1a;
        }

        .section-subtitle {
            text-align: center;
            color: #6c757d;
            font-size: 1.1rem;
            margin-bottom: 4rem;
        }

        .feature-card {
            background: white;
            border-radius: 20px;
            padding: 2.5rem;
            height: 100%;
            transition: all 0.3s;
            border: 2px solid transparent;
        }

        .feature-card:hover {
            transform: translateY(-10px);
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.1);
            border-color: #667eea;
        }

        .feature-icon {
            width: 70px;
            height: 70px;
            border-radius: 15px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 2rem;
            margin-bottom: 1.5rem;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
        }

        .feature-title {
            font-size: 1.5rem;
            font-weight: 600;
            margin-bottom: 1rem;
            color: #1a1a1a;
        }

        .feature-description {
            color: #6c757d;
            line-height: 1.6;
        }

        /* Coaches Section */
        .coaches-section {
            padding: 100px 0;
            background: white;
        }

        .coach-card {
            background: white;
            border-radius: 20px;
            overflow: hidden;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
            transition: all 0.3s;
            height: 100%;
        }

        .coach-card:hover {
            transform: translateY(-10px);
            box-shadow: 0 20px 50px rgba(0, 0, 0, 0.15);
        }

        .coach-header {
            padding: 2rem;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            text-align: center;
        }

        .coach-avatar {
            width: 100px;
            height: 100px;
            border-radius: 50%;
            background: white;
            color: #667eea;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 2.5rem;
            font-weight: 700;
            margin: 0 auto 1rem;
            border: 5px solid rgba(255, 255, 255, 0.3);
        }

        .coach-name {
            font-size: 1.5rem;
            font-weight: 600;
            margin-bottom: 0.5rem;
        }

        .coach-title {
            font-size: 1rem;
            opacity: 0.9;
        }

        .coach-body {
            padding: 2rem;
        }

        .coach-expertise {
            display: flex;
            flex-wrap: wrap;
            gap: 0.5rem;
            margin-top: 1rem;
        }

        .expertise-tag {
            background: #f0f0ff;
            color: #667eea;
            padding: 0.5rem 1rem;
            border-radius: 20px;
            font-size: 0.875rem;
            font-weight: 500;
        }

        /* Pricing Section */
        .pricing-section {
            padding: 100px 0;
            background: linear-gradient(180deg, #f8f9fa 0%, white 100%);
        }

        .pricing-card {
            background: white;
            border-radius: 20px;
            padding: 3rem 2rem;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
            transition: all 0.3s;
            height: 100%;
            position: relative;
        }

        .pricing-card.featured {
            border: 3px solid #667eea;
            transform: scale(1.05);
        }

        .pricing-card:hover {
            transform: translateY(-10px) scale(1.02);
            box-shadow: 0 20px 50px rgba(0, 0, 0, 0.15);
        }

        .pricing-badge {
            position: absolute;
            top: -15px;
            right: 20px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 0.5rem 1.5rem;
            border-radius: 20px;
            font-weight: 600;
            font-size: 0.875rem;
        }

        .pricing-name {
            font-size: 1.5rem;
            font-weight: 600;
            margin-bottom: 1rem;
            color: #1a1a1a;
        }

        .pricing-price {
            font-size: 3rem;
            font-weight: 700;
            color: #667eea;
            margin-bottom: 0.5rem;
        }

        .pricing-period {
            color: #6c757d;
            margin-bottom: 2rem;
        }

        .pricing-features {
            list-style: none;
            padding: 0;
            margin: 2rem 0;
        }

        .pricing-features li {
            padding: 0.75rem 0;
            color: #333;
            display: flex;
            align-items: center;
        }

        .pricing-features li i {
            color: #28a745;
            margin-right: 0.75rem;
            font-size: 1.25rem;
        }

        /* How It Works Section */
        .how-it-works-section {
            padding: 100px 0;
            background: white;
        }

        .step-card {
            text-align: center;
            padding: 2rem;
            position: relative;
        }

        .step-number {
            width: 80px;
            height: 80px;
            border-radius: 50%;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            font-size: 2rem;
            font-weight: 700;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 1.5rem;
            box-shadow: 0 10px 30px rgba(102, 126, 234, 0.3);
        }

        .step-title {
            font-size: 1.5rem;
            font-weight: 600;
            margin-bottom: 1rem;
            color: #1a1a1a;
        }

        .step-description {
            color: #6c757d;
            line-height: 1.6;
        }

        /* Stats Section */
        .stats-section {
            padding: 80px 0;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
        }

        .stat-card {
            text-align: center;
            padding: 2rem;
        }

        .stat-number {
            font-size: 3.5rem;
            font-weight: 700;
            margin-bottom: 0.5rem;
        }

        .stat-label {
            font-size: 1.1rem;
            opacity: 0.9;
        }

        /* CTA Section */
        .cta-section {
            padding: 100px 0;
            background: linear-gradient(135deg, #1a1a1a 0%, #2d2d2d 100%);
            color: white;
            text-align: center;
        }

        .cta-title {
            font-size: 3rem;
            font-weight: 700;
            margin-bottom: 1.5rem;
        }

        .cta-subtitle {
            font-size: 1.25rem;
            margin-bottom: 3rem;
            opacity: 0.9;
        }

        /* Footer */
        .footer {
            background: #1a1a1a;
            color: white;
            padding: 60px 0 30px;
        }

        .footer-brand {
            font-size: 1.5rem;
            font-weight: 700;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            margin-bottom: 1rem;
        }

        .footer-description {
            color: rgba(255, 255, 255, 0.7);
            margin-bottom: 1.5rem;
        }

        .footer-title {
            font-weight: 600;
            margin-bottom: 1.5rem;
            font-size: 1.1rem;
        }

        .footer-link {
            color: rgba(255, 255, 255, 0.7);
            text-decoration: none;
            display: block;
            margin-bottom: 0.75rem;
            transition: color 0.3s;
        }

        .footer-link:hover {
            color: #667eea;
        }

        .social-icons a {
            color: white;
            font-size: 1.5rem;
            margin-right: 1rem;
            transition: color 0.3s;
        }

        .social-icons a:hover {
            color: #667eea;
        }

        .footer-bottom {
            border-top: 1px solid rgba(255, 255, 255, 0.1);
            margin-top: 3rem;
            padding-top: 2rem;
            text-align: center;
            color: rgba(255, 255, 255, 0.5);
        }

        @media (max-width: 768px) {
            .hero-title {
                font-size: 2.5rem;
            }

            .section-title {
                font-size: 2rem;
            }

            .cta-title {
                font-size: 2rem;
            }
        }
    </style>
</head>
<body>

    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg sticky-top">
        <div class="container">
            <a class="navbar-brand" href="#home">
                <i class="bi bi-chat-dots-fill"></i> PreChat
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto align-items-center">
                    <li class="nav-item">
                        <a class="nav-link" href="#features">Features</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#coaches">Coaches</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#pricing">Pricing</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#how-it-works">How It Works</a>
                    </li>
                    <li class="nav-item ms-3">
                        <a href="{{ route('login') }}" class="btn btn-outline-gradient">Login</a>
                    </li>
                    <li class="nav-item ms-2">
                        <a href="{{ route('register') }}" class="btn btn-gradient">Get Started</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <!-- Hero Section -->
    <section class="hero-section" id="home">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-lg-6 hero-content">
                    <h1 class="hero-title">Transform Your Life with AI-Powered Coaching</h1>
                    <p class="hero-subtitle">
                        Connect with expert AI coaches in career, fitness, finance, and nutrition.
                        Get personalized guidance 24/7 and achieve your goals faster than ever.
                    </p>
                    <div class="d-flex gap-3 flex-wrap">
                        <a href="{{ route('register') }}" class="btn btn-light btn-lg px-5">
                            Start Free Trial <i class="bi bi-arrow-right ms-2"></i>
                        </a>
                        <a href="#how-it-works" class="btn btn-outline-light btn-lg px-5">
                            Learn More
                        </a>
                    </div>
                    <div class="mt-4">
                        <small class="text-white">
                            <i class="bi bi-check-circle-fill me-2"></i> No credit card required
                            <i class="bi bi-check-circle-fill ms-3 me-2"></i> First session only 50 tokens
                            <i class="bi bi-check-circle-fill ms-3 me-2"></i> Cancel anytime
                        </small>
                    </div>
                </div> 
                <div class="col-lg-6 mt-5 mt-lg-0">
                    <div class="hero-image text-center">
                        <img src="{{ asset('/upload/home.png') }}" alt="AI Coach" class="img-fluid" style="border-radius: 20px; box-shadow: 0 30px 60px rgba(0, 0, 0, 0.3);">
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Stats Section -->
    <section class="stats-section">
        <div class="container">
            <div class="row">
                <div class="col-md-3 col-6">
                    <div class="stat-card">
                        <div class="stat-number">10K+</div>
                        <div class="stat-label">Active Users</div>
                    </div>
                </div>
                <div class="col-md-3 col-6">
                    <div class="stat-card">
                        <div class="stat-number">4</div>
                        <div class="stat-label">Expert Coaches</div>
                    </div>
                </div>
                <div class="col-md-3 col-6">
                    <div class="stat-card">
                        <div class="stat-number">50K+</div>
                        <div class="stat-label">Coaching Sessions</div>
                    </div>
                </div>
                <div class="col-md-3 col-6">
                    <div class="stat-card">
                        <div class="stat-number">4.9</div>
                        <div class="stat-label">Average Rating</div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Features Section -->
    <section class="features-section" id="features">
        <div class="container">
            <h2 class="section-title">Why Choose PreChat?</h2>
            <p class="section-subtitle">Powerful features designed to help you succeed</p>

            <div class="row g-4">
                <div class="col-md-6 col-lg-4">
                    <div class="feature-card">
                        <div class="feature-icon">
                            <i class="bi bi-robot"></i>
                        </div>
                        <h3 class="feature-title">AI-Powered Coaching</h3>
                        <p class="feature-description">
                            Get personalized guidance from advanced AI coaches trained in their respective fields.
                            Available 24/7 to support your journey.
                        </p>
                    </div>
                </div>

                <div class="col-md-6 col-lg-4">
                    <div class="feature-card">
                        <div class="feature-icon">
                            <i class="bi bi-person-check"></i>
                        </div>
                        <h3 class="feature-title">Personalized Experience</h3>
                        <p class="feature-description">
                            Complete a one-time onboarding to receive coaching tailored specifically to your
                            goals, background, and preferences.
                        </p>
                    </div>
                </div>

                <div class="col-md-6 col-lg-4">
                    <div class="feature-card">
                        <div class="feature-icon">
                            <i class="bi bi-cash-coin"></i>
                        </div>
                        <h3 class="feature-title">Affordable Pricing</h3>
                        <p class="feature-description">
                            Pay only 50 tokens for your first session with each coach. After that,
                            enjoy unlimited free coaching sessions forever.
                        </p>
                    </div>
                </div>

                <div class="col-md-6 col-lg-4">
                    <div class="feature-card">
                        <div class="feature-icon">
                            <i class="bi bi-clock-history"></i>
                        </div>
                        <h3 class="feature-title">Session History</h3>
                        <p class="feature-description">
                            Access your complete conversation history with each coach. Track your progress
                            and revisit important advice anytime.
                        </p>
                    </div>
                </div>

                <div class="col-md-6 col-lg-4">
                    <div class="feature-card">
                        <div class="feature-icon">
                            <i class="bi bi-bullseye"></i>
                        </div>
                        <h3 class="feature-title">Goal Tracking</h3>
                        <p class="feature-description">
                            Set, track, and achieve your goals with dedicated tools. Monitor progress and
                            celebrate milestones along the way.
                        </p>
                    </div>
                </div>

                <div class="col-md-6 col-lg-4">
                    <div class="feature-card">
                        <div class="feature-icon">
                            <i class="bi bi-shield-check"></i>
                        </div>
                        <h3 class="feature-title">Secure & Private</h3>
                        <p class="feature-description">
                            Your data is encrypted and secure. All coaching sessions are completely private
                            and confidential.
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Coaches Section -->
    <section class="coaches-section" id="coaches">
        <div class="container">
            <h2 class="section-title">Meet Your AI Coaches</h2>
            <p class="section-subtitle">Expert guidance in every area of your life</p>

            <div class="row g-4">
                <div class="col-md-6 col-lg-3">
                    <div class="coach-card">
                        <div class="coach-header">
                            <div class="coach-avatar">C</div>
                            <div class="coach-name">Career Coach</div>
                            <div class="coach-title">Professional Development Expert</div>
                        </div>
                        <div class="coach-body">
                            <p class="mb-3">Navigate your career path with confidence. From resume building to interview prep and career transitions.</p>
                            <div class="coach-expertise">
                                <span class="expertise-tag">Resume Review</span>
                                <span class="expertise-tag">Interview Prep</span>
                                <span class="expertise-tag">Salary Negotiation</span>
                                <span class="expertise-tag">Career Planning</span>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-md-6 col-lg-3">
                    <div class="coach-card">
                        <div class="coach-header">
                            <div class="coach-avatar">F</div>
                            <div class="coach-name">Fitness Coach</div>
                            <div class="coach-title">Health & Wellness Expert</div>
                        </div>
                        <div class="coach-body">
                            <p class="mb-3">Achieve your fitness goals with personalized workout plans and expert guidance on health and wellness.</p>
                            <div class="coach-expertise">
                                <span class="expertise-tag">Workout Plans</span>
                                <span class="expertise-tag">Nutrition Advice</span>
                                <span class="expertise-tag">Weight Loss</span>
                                <span class="expertise-tag">Muscle Building</span>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-md-6 col-lg-3">
                    <div class="coach-card">
                        <div class="coach-header">
                            <div class="coach-avatar">$</div>
                            <div class="coach-name">Finance Coach</div>
                            <div class="coach-title">Financial Planning Specialist</div>
                        </div>
                        <div class="coach-body">
                            <p class="mb-3">Master your finances with expert advice on budgeting, investing, and building long-term wealth.</p>
                            <div class="coach-expertise">
                                <span class="expertise-tag">Budgeting</span>
                                <span class="expertise-tag">Investing</span>
                                <span class="expertise-tag">Debt Management</span>
                                <span class="expertise-tag">Retirement Planning</span>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-md-6 col-lg-3">
                    <div class="coach-card">
                        <div class="coach-header">
                            <div class="coach-avatar">N</div>
                            <div class="coach-name">Nutrition Coach</div>
                            <div class="coach-title">Dietary & Wellness Advisor</div>
                        </div>
                        <div class="coach-body">
                            <p class="mb-3">Transform your eating habits with personalized meal plans and nutritional guidance for optimal health.</p>
                            <div class="coach-expertise">
                                <span class="expertise-tag">Meal Planning</span>
                                <span class="expertise-tag">Calorie Tracking</span>
                                <span class="expertise-tag">Dietary Restrictions</span>
                                <span class="expertise-tag">Healthy Recipes</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- How It Works Section -->
    <section class="how-it-works-section" id="how-it-works">
        <div class="container">
            <h2 class="section-title">How PreChat Works</h2>
            <p class="section-subtitle">Get started in just 4 simple steps</p>

            <div class="row g-4">
                <div class="col-md-6 col-lg-3">
                    <div class="step-card">
                        <div class="step-number">1</div>
                        <h3 class="step-title">Create Account</h3>
                        <p class="step-description">
                            Sign up for free and get 200 tokens to start your coaching journey.
                        </p>
                    </div>
                </div>

                <div class="col-md-6 col-lg-3">
                    <div class="step-card">
                        <div class="step-number">2</div>
                        <h3 class="step-title">Choose Coach</h3>
                        <p class="step-description">
                            Select from our expert AI coaches in career, fitness, finance, or nutrition.
                        </p>
                    </div>
                </div>

                <div class="col-md-6 col-lg-3">
                    <div class="step-card">
                        <div class="step-number">3</div>
                        <h3 class="step-title">Complete Onboarding</h3>
                        <p class="step-description">
                            Answer a few questions to personalize your coaching experience.
                        </p>
                    </div>
                </div>

                <div class="col-md-6 col-lg-3">
                    <div class="step-card">
                        <div class="step-number">4</div>
                        <h3 class="step-title">Start Coaching</h3>
                        <p class="step-description">
                            Pay 50 tokens for first session, then enjoy unlimited free coaching!
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Pricing Section -->
    <section class="pricing-section" id="pricing">
        <div class="container">
            <h2 class="section-title">Simple, Transparent Pricing</h2>
            <p class="section-subtitle">Choose the plan that's right for you</p>

            <div class="row g-4 justify-content-center">
                <div class="col-md-6 col-lg-4">
                    <div class="pricing-card">
                        <h3 class="pricing-name">Free Plan</h3>
                        <div class="pricing-price">$0</div>
                        <div class="pricing-period">per month</div>
                        <ul class="pricing-features">
                            <li><i class="bi bi-check-circle-fill"></i> 200 tokens included</li>
                            <li><i class="bi bi-check-circle-fill"></i> Access to all 4 coaches</li>
                            <li><i class="bi bi-check-circle-fill"></i> First session: 50 tokens</li>
                            <li><i class="bi bi-check-circle-fill"></i> Unlimited free sessions after</li>
                            <li><i class="bi bi-check-circle-fill"></i> Goal tracking & progress</li>
                            <li><i class="bi bi-check-circle-fill"></i> Session history</li>
                        </ul>
                        <a href="{{ route('register') }}" class="btn btn-outline-gradient w-100">Get Started Free</a>
                    </div>
                </div>

                <div class="col-md-6 col-lg-4">
                    <div class="pricing-card featured">
                        <div class="pricing-badge">Most Popular</div>
                        <h3 class="pricing-name">Pro Monthly</h3>
                        <div class="pricing-price">$9.99</div>
                        <div class="pricing-period">per month</div>
                        <ul class="pricing-features">
                            <li><i class="bi bi-check-circle-fill"></i> 1000 tokens included</li>
                            <li><i class="bi bi-check-circle-fill"></i> Everything in Free plan</li>
                            <li><i class="bi bi-check-circle-fill"></i> Priority support</li>
                            <li><i class="bi bi-check-circle-fill"></i> Advanced analytics</li>
                            <li><i class="bi bi-check-circle-fill"></i> Export coaching sessions</li>
                            <li><i class="bi bi-check-circle-fill"></i> Early access to new features</li>
                        </ul>
                        <a href="{{ route('register') }}" class="btn btn-gradient w-100">Start Pro Trial</a>
                    </div>
                </div>

                <div class="col-md-6 col-lg-4">
                    <div class="pricing-card">
                        <h3 class="pricing-name">Essential Monthly</h3>
                        <div class="pricing-price">$4.99</div>
                        <div class="pricing-period">per month</div>
                        <ul class="pricing-features">
                            <li><i class="bi bi-check-circle-fill"></i> 500 tokens included</li>
                            <li><i class="bi bi-check-circle-fill"></i> Access to all 4 coaches</li>
                            <li><i class="bi bi-check-circle-fill"></i> First session: 50 tokens</li>
                            <li><i class="bi bi-check-circle-fill"></i> Unlimited free sessions after</li>
                            <li><i class="bi bi-check-circle-fill"></i> Goal tracking & progress</li>
                            <li><i class="bi bi-check-circle-fill"></i> Email support</li>
                        </ul>
                        <a href="{{ route('register') }}" class="btn btn-outline-gradient w-100">Choose Essential</a>
                    </div>
                </div>
            </div>

            <div class="text-center mt-5">
                <p class="text-muted">
                    <i class="bi bi-info-circle me-2"></i>
                    All plans include unlimited free coaching after first session. Tokens reset monthly.
                </p>
            </div>
        </div>
    </section>

    <!-- CTA Section -->
    <section class="cta-section">
        <div class="container">
            <h2 class="cta-title">Ready to Transform Your Life?</h2>
            <p class="cta-subtitle">
                Join thousands of users already achieving their goals with AI-powered coaching
            </p>
            <div class="d-flex gap-3 justify-content-center flex-wrap">
                <a href="{{ route('register') }}" class="btn btn-gradient btn-lg px-5">
                    Start Your Journey Today
                </a>
                <a href="{{ route('login') }}" class="btn btn-outline-light btn-lg px-5">
                    Sign In
                </a>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="footer">
        <div class="container">
            <div class="row">
                <div class="col-lg-4 mb-4 mb-lg-0">
                    <div class="footer-brand">
                        <i class="bi bi-chat-dots-fill"></i> PreChat
                    </div>
                    <p class="footer-description">
                        Transform your life with AI-powered professional coaching. Expert guidance in career,
                        fitness, finance, and nutrition - available 24/7.
                    </p>
                    <div class="social-icons">
                        <a href="#"><i class="bi bi-facebook"></i></a>
                        <a href="#"><i class="bi bi-twitter"></i></a>
                        <a href="#"><i class="bi bi-instagram"></i></a>
                        <a href="#"><i class="bi bi-linkedin"></i></a>
                    </div>
                </div>

                <div class="col-lg-2 col-md-4 mb-4 mb-lg-0">
                    <h5 class="footer-title">Product</h5>
                    <a href="#features" class="footer-link">Features</a>
                    <a href="#coaches" class="footer-link">Coaches</a>
                    <a href="#pricing" class="footer-link">Pricing</a>
                    <a href="#how-it-works" class="footer-link">How It Works</a>
                </div>

                <div class="col-lg-2 col-md-4 mb-4 mb-lg-0">
                    <h5 class="footer-title">Company</h5>
                    <a href="#" class="footer-link">About Us</a>
                    <a href="#" class="footer-link">Blog</a>
                    <a href="#" class="footer-link">Careers</a>
                    <a href="#" class="footer-link">Contact</a>
                </div>

                <div class="col-lg-2 col-md-4 mb-4 mb-lg-0">
                    <h5 class="footer-title">Support</h5>
                    <a href="#" class="footer-link">Help Center</a>
                    <a href="#" class="footer-link">Terms of Service</a>
                    <a href="#" class="footer-link">Privacy Policy</a>
                    <a href="#" class="footer-link">Cookie Policy</a>
                </div>

                <div class="col-lg-2 col-md-4">
                    <h5 class="footer-title">Account</h5>
                    <a href="{{ route('login') }}" class="footer-link">Login</a>
                    <a href="{{ route('register') }}" class="footer-link">Register</a>
                    <a href="{{ route('dashboard') }}" class="footer-link">Dashboard</a>
                </div>
            </div>

            <div class="footer-bottom">
                <p>&copy; 2024 PreChat - AI Influencer & Professional Coaching Platform. All rights reserved.</p>
            </div>
        </div>
    </footer>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    <!-- Smooth Scroll -->
    <script>
        document.querySelectorAll('a[href^="#"]').forEach(anchor => {
            anchor.addEventListener('click', function (e) {
                e.preventDefault();
                const target = document.querySelector(this.getAttribute('href'));
                if (target) {
                    target.scrollIntoView({
                        behavior: 'smooth',
                        block: 'start'
                    });
                }
            });
        });
    </script>
</body>
</html>
