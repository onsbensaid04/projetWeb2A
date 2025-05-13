<?php
session_start();

$prenom = "";
$nom = "";
$role = "";

// Protection : empêcher l'accès si non connecté
if (isset($_SESSION['user'])) {


// Récupérer les informations utilisateur
$prenom = $_SESSION['user']['prenom'];
$nom = $_SESSION['user']['nom'];
$role = $_SESSION['user']['role'];


}

require_once __DIR__ . '/check_ban.php';

?>


<!DOCTYPE html>
<html lang="en">

  <head>

    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">
    <link href="https://fonts.googleapis.com/css?family=Poppins:100,100i,200,200i,300,300i,400,400i,500,500i,600,600i,700,700i,800,800i,900,900i&display=swap" rel="stylesheet">

    <title>Training Studio - Free CSS Template</title>
<!--

TemplateMo 548 Training Studio

https://templatemo.com/tm-548-training-studio

-->
    <!-- Additional CSS Files -->
    <link rel="stylesheet" type="text/css" href="../assets/css/bootstrap.min.css">

    <link rel="stylesheet" type="text/css" href="../assets/css/font-awesome.css">

    <link rel="stylesheet" href="../assets/css/templatemo-training-studio.css">

    </head>
    
    <body>
    
    <!-- ***** Preloader Start ***** -->
    <div id="js-preloader" class="js-preloader">
      <div class="preloader-inner">
        <span class="dot"></span>
        <div class="dots">
          <span></span>
          <span></span>
          <span></span>
        </div>
      </div>
    </div>
    <!-- ***** Preloader End ***** -->
    
    
    <!-- ***** Header Area Start ***** -->
    <header class="header-area header-sticky">
        <div class="container">
            <div class="row">
                <div class="col-12">
                    <nav class="main-nav">
                        <!-- ***** Logo Start ***** -->
                        <a href="index.html" class="logo">
                            <img src="../assets/images/logo.png" alt="Logo" style="vertical-align: middle; height: 50px;">
                            Startup<em> Academy</em>
                          </a>
                          
                        <!-- ***** Logo End ***** -->
                        <!-- ***** Menu Start ***** -->
                        <ul class="nav">
                            <li class="scroll-to-section"><a href="#top" class="active">Home</a></li>
                            <li class="scroll-to-section"><a href="#features">About</a></li>
                            <li class="scroll-to-section"><a href="offre_emploi.php">Offres d'Emploi</a></li>
                          <li class="scroll-to-section"><a href="pdf.php">Cours</a></li>


                            <li class="scroll-to-section"><a href="/integration/Vue/Front/Front/channels.html">Forum</a></li>
                            <li class="scroll-to-section"><a href="#contact-us">Contact</a></li> 
                            <?php if (!isset($_SESSION['user'])): ?>
                                <li class="main-button"><a href="./connexion.php">Sign In</a></li>
                            <?php else: ?>
                                <li class="nav-item dropdown">
                                    <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button"
                                        data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"
                                        style="display: flex; align-items: center;">
                                        <i class="fa fa-user-circle" style="font-size: 1.5em; margin-right: 5px;"></i>
                                        <?php echo htmlspecialchars($_SESSION['user']['prenom']); ?>
                                    </a>
                                    <div class="dropdown-menu dropdown-menu-right" aria-labelledby="userDropdown">
                                        <span
                                            class="dropdown-item-text"><strong><?php echo htmlspecialchars($_SESSION['user']['prenom'] . ' ' . $_SESSION['user']['nom']); ?></strong></span>
                                        <span class="dropdown-item-text">Role:
                                            <?php echo htmlspecialchars($_SESSION['user']['role']); ?></span>
                                        <div class="dropdown-divider"></div>
                                        <a class="dropdown-item" href="mon_profil.php"><i
                                                    class="fa fa-user" style="margin-right: 5px;"></i>Mon Profil</a>
                                        <?php if ($_SESSION['user']['role'] === 'admin'): ?>
                                            <a class="dropdown-item" href="./../../Back/dashboard.php"><i
                                                    class="fa fa-tachometer" style="margin-right: 5px;"></i>Dashboard</a>
                                        <?php endif; ?>
                                        <a class="dropdown-item" href="./logout.php"><i
                                                class="fa fa-sign-out" style="margin-right: 5px;"></i>Logout</a>
                                    </div>
                                </li>
                            <?php endif; ?>
                        </ul>        
                        <a class='menu-trigger'>
                            <span>Menu</span>
                        </a>
                        <!-- ***** Menu End ***** -->
                    </nav>
                </div>
            </div>
        </div>
    </header>
    <!-- ***** Header Area End ***** -->

    <!-- ***** Main Banner Area Start ***** -->
    <div class="main-banner" id="top">
         <video autoplay muted loop id="bg-video">
            <source src="../assets/images/gym.mp4" type="video/mp4" />
        </video>

         <div class="video-overlay header-text">
            <div class="caption">
                <!--<h6>work harder, get stronger</h6>-->
                <h2>
                    Learn at Your Own Pace, <em>Master New Skills</em>
                </h2>
                
                <div class="main-button scroll-to-section">
                    <a href="#features">Become a member</a>
                </div>
            </div>
         </div>
    </div>
    <!-- ***** Main Banner Area End ***** -->

    <!-- ***** Features Item Start ***** -->
    <section class="section" id="features">
        <div class="container">
            <div class="row">
                <div class="col-lg-6 offset-lg-3">
                    <div class="section-heading">
                        <h2>About <em>  Us</em></h2>
                        <img src="../assets/images/line-dec.png" alt="waves">
                       <!-- <p>Training Studio is free CSS template for gyms and fitness centers. You are allowed to use this layout for your business website.</p>-->
                    </div>
                </div>
                <div class="col-lg-6">
                    <div class="left-image">
                        <img src="../assets/images/image.png" alt="">
                      </div>
                </div>
                <div class="col-lg-6 align-self-center">
                    <div class="content-section">
                        <h3>Startup Academy</h3>
                        <br><br>
                        <p>
                            Our platform seamlessly combines online learning with recruitment opportunities. As an educational hub, it offers a range of features such as video courses, quizzes, and forums to enhance the learning experience. At the same time, it provides companies with the ability to post job listings and content to attract entrepreneurs and talent. With personalized profiles, secure payment options, and AI-driven recommendations, our platform delivers a comprehensive experience for learning, networking, and discovering professional opportunities.
                        </p>
                    </div>
                
               
            </div>
            </div>
        </div>
    </section>
    <!-- ***** Features Item End ***** -->

    <!-- ***** Call to Action Start ***** -->
    <section class="section" id="call-to-action">
        <div class="container">
            <div class="row">
                <div class="col-lg-10 offset-lg-1">
                    <div class="cta-content">
                        <h2>Don’t <em>think</em>, begin <em>today</em>!</h2>
                        <div class="main-button scroll-to-section">
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- ***** Call to Action End ***** -->
   
    
    <!-- ***** Contact Us Area Starts ***** -->
    <section class="section" id="contact-us">
        <div class="container-fluid">
            <div class="row">
                <div class="col-lg-6 col-md-6 col-xs-12">
                    <div id="map">
                      <iframe src="https://maps.google.com/maps?q=Av.+L%C3%BAcio+Costa,+Rio+de+Janeiro+-+RJ,+Brazil&t=&z=13&ie=UTF8&iwloc=&output=embed" width="100%" height="600px" frameborder="0" style="border:0" allowfullscreen></iframe>
                    </div>
                </div>
                <div class="col-lg-6 col-md-6 col-xs-12">
                    <div class="contact-form">
                        <form id="contact" action="" method="post">
                          <div class="row">
                            <div class="col-md-6 col-sm-12">
                              <fieldset>
                                <input name="name" type="text" id="name" placeholder="Your Name*" required="">
                              </fieldset>
                            </div>
                            <div class="col-md-6 col-sm-12">
                              <fieldset>
                                <input name="email" type="text" id="email" pattern="[^ @]*@[^ @]*" placeholder="Your Email*" required="">
                              </fieldset>
                            </div>
                            <div class="col-md-12 col-sm-12">
                              <fieldset>
                                <input name="subject" type="text" id="subject" placeholder="Subject">
                              </fieldset>
                            </div>
                            <div class="col-lg-12">
                              <fieldset>
                                <textarea name="message" rows="6" id="message" placeholder="Message" required=""></textarea>
                              </fieldset>
                            </div>
                            <div class="col-lg-12">
                              <fieldset>
                                <button type="submit" id="form-submit" class="main-button">Send Message</button>
                              </fieldset>
                            </div>
                          </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- ***** Contact Us Area Ends ***** -->
    
    <!-- ***** Footer Start ***** -->
    <footer>
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <p>Copyright &copy; 2020 Training Studio
                    
                    - Designed by <a rel="nofollow" href="https://templatemo.com" class="tm-text-link" target="_parent">TemplateMo</a></p>
                    
                    <!-- You shall support us a little via PayPal to info@templatemo.com -->
                    
                </div>
            </div>
        </div>
    </footer>

    <!-- jQuery -->
    <script src="../assets/js/jquery-2.1.0.min.js"></script>

    <!-- Bootstrap -->
    <script src="../assets/js/popper.js"></script>
    <script src="../assets/js/bootstrap.min.js"></script>

    <!-- Plugins -->
    <script src="../assets/js/scrollreveal.min.js"></script>
    <script src="../assets/js/waypoints.min.js"></script>
    <script src="../assets/js/jquery.counterup.min.js"></script>
    <script src="../assets/js/imgfix.min.js"></script> 
    <script src="../assets/js/mixitup.js"></script> 
    <script src="../assets/js/accordions.js"></script>
    
    <!-- Global Init -->
    <script src="../assets/js/custom.js"></script>

  </body>
</html>