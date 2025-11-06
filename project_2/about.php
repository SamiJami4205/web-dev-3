<?php
$page_title = "About Heroics Inc";
include 'includes/header.html';
?>

<div class="container">
    <div class="card hero-card mb-5">
        <div class="card-body">
            <div class="row">
                <div class="col-lg-12">
                    <h1 class="mb-4 text-center">Our Story</h1>
                    <div class="hero-content">
                        <p class="lead text-center">Founded in 2010, Heroics Inc has been at the forefront of superhuman development and support.</p>
                        <p>What started as a small research facility has grown into a global enterprise spanning multiple industries. Our commitment to excellence and innovation has made us the leading provider of superhero services and support worldwide.</p>
                        <p>Today, we continue to push the boundaries of human potential while maintaining the highest standards of safety and ethical practices.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

<h2 class="text-center mb-4">Our Products & Services</h2>

<div class="row g-4">
    <!-- Services Grid -->
    <div class="col-md-4">
        <div class="card service-card">
            <div class="card-body">
                <div class="service-icon mb-3">
                    <i class="fas fa-dna fa-3x"></i>
                </div>
                <h5 class="card-title">Biotech Solutions</h5>
                <p class="card-text">Advanced biotechnology for enhanced performance. Our cutting-edge research leads to breakthrough developments in superhuman capabilities.</p>
            </div>
        </div>
    </div>
    
    <div class="col-md-4">
        <div class="card service-card">
            <div class="card-body">
                <div class="service-icon mb-3">
                    <i class="fas fa-film fa-3x"></i>
                </div>
                <h5 class="card-title">Media Division</h5>
                <p class="card-text">Award-winning superhero entertainment and documentation. Sharing the stories of heroes who make a difference.</p>
            </div>
        </div>
    </div>
    
    <div class="col-md-4">
        <div class="card service-card">
            <div class="card-body">
                <div class="service-icon mb-3">
                    <i class="fas fa-graduation-cap fa-3x"></i>
                </div>
                <h5 class="card-title">Hero Training</h5>
                <p class="card-text">Professional development for powered individuals. World-class training facilities and expert mentorship programs.</p>
            </div>
        </div>
    </div>
</div>

<div class="card cta-card mt-5">
    <div class="card-body text-center">
        <h3 class="mb-3">Ready to Join the Future of Heroics?</h3>
        <p class="lead mb-4">Register today to access our exclusive services and opportunities.</p>
        <?php if (!isset($_SESSION['user_id'])): ?>
            <a href="register.php" class="btn btn-primary btn-lg">Register Now</a>
        <?php else: ?>
            <a href="public/users.php" class="btn btn-primary btn-lg">View Employee Directory</a>
        <?php endif; ?>
    </div>
</div>

<?php include 'includes/footer.html'; ?>