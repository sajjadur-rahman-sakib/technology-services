<!DOCTYPE html>

<html lang="en">

<head>

    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Sakib IT Services</title>

    <!-- font awesome cdn link  -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

    <!-- swiper css link  -->
    <link rel="stylesheet" href="https://unpkg.com/swiper@7/swiper-bundle.min.css" />

    <link rel="stylesheet" href="css/style.css">

</head>

<body>

    <!-- php -->

    <?php

        include "./php/config.php";

        // OTP verification is now handled via AJAX in otp_handler.php
        // No direct form submission to database

        if(isset($_POST['subscribe']))
        {
            $newsletter_name = $_POST['newsletter_name'];
            $newsletter_mail = $_POST['newsletter_mail'];

            $query = "INSERT INTO newsletter (name, email) VALUES ('$newsletter_name', '$newsletter_mail')";

            mysqli_query($connect, $query);
        }

        if(isset($_POST['review_submit'])) {
            $review_name = mysqli_real_escape_string($connect, $_POST['review_name']);
            $review_stars = intval($_POST['review_stars']);
            $review_text = mysqli_real_escape_string($connect, $_POST['review_text']);
            if($review_name && $review_stars && $review_text) {
                $query = "INSERT INTO reviews (name, stars, review) VALUES ('$review_name', $review_stars, '$review_text')";
                mysqli_query($connect, $query);
            }
        }

    ?>

    <!-- php end -->
    


    <!-- header -->

    <header class="header">

        <a href="#" class="logo"> <i class="fas fa-gear"></i> Sakib IT Services </a>

        <nav class="navbar">

            <a href="#home">home</a>
            <a href="#about">about</a>
            <a href="#services">services</a>
            <a href="#reviews">reviews</a>
            <a href="#contact">reserve</a>
            <a href="./php/tracking.php">track</a>
            <a href="./admin/index.php">admin</a>

        </nav>

        <div id="menu-btn" class="fas fa-bars"></div>

    </header>

    <!-- header end -->



    <!-- home -->

    <section class="home" id="home">

        <div class="content">
            <h3>we are your <span>services</span></h3>
            <p>sakib it services is a it based services company. We can service many it product and take appoinment for service. We provide authentic product and qualityful services.</p>
        </div>

    </section>

    <!-- home end -->



    <!-- about us -->

    <section class="about" id="about">

        <h1 class="heading"> <span>about</span> us</h1>

        <div class="row">

            <div class="image">
                <img src="images/about.jpg" alt="">
            </div>

            <div class="content">
                <h3>Lorem ipsum dolor sit amet consectetur adipisicing elit</h3>
                <p>Lorem ipsum dolor sit amet consectetur adipisicing elit. Voluptates consequuntur, dolorem, delectus dolorum maxime id dicta soluta vel in totam eos velit expedita similique et provident? Illum quam vel ipsam.
                Voluptates repudiandae, vel, repellendus nulla consectetur hic et, doloribus cum numquam explicabo temporibus culpa eos aliquam facere similique illum. Aperiam saepe eius tempore natus, ad quo consectetur sunt in laborum.
                Iusto aliquid commodi ex necessitatibus, praesentium, officia ab modi numquam consequuntur aspernatur unde recusandae, quidem alias suscipit in aut libero incidunt quaerat ea sit saepe error sapiente. Reiciendis, earum eius!
                Quia ad debitis officiis voluptatum recusandae consequatur! Ad ea ut in corrupti sed delectus iusto dolorem at perferendis aliquam, facilis necessitatibus explicabo vero cumque totam quas. Nihil sed accusamus magnam?</p>
                <a href="https://sakib.tech/" class="btn">read more</a>
            </div>

        </div>

    </section>

    <!-- about end -->



    <!-- counter -->

    <section class="counter">

        <h1 class="heading"> <span>some facts in numbers</span></h1>

        <div class="box-container">

            <div class="box">
                <h2 class="count">1500 </h2>
                <h3>clients</h3>
                <p>we can successfully support our client.</p>
            </div>

            <div class="box">
                <h2 class="count">100 </h2>
                <h3>special tools</h3>
                <p>we have many special tools for repair devices.</p>
            </div>

            <div class="box">
                <h2 class="count">2100 </h2>
                <h3>devices</h3>
                <p>we can successfully services many device from our client.</p>
            </div>

            <div class="box">
                <h2 class="count">18 </h2>
                <h3>years</h3>
                <p>We start our services an continuously doing.</p>
            </div>

        </div>

    </section>

    <!-- counter ends -->



    <!-- services -->

    <section class="services" id="services">

        <h1 class="heading">our <span>services</span></h1>

        <div class="box-container">

            <div class="box">
                <img src="images/service-1.png" alt="">
                <h3>smart watche</h3>
                <p>all types of smartwatch and accessories.</p>
            </div>

            <div class="box">
                <img src="images/service-2.png" alt="">
                <h3>digital cameras</h3>
                <p>all types of DSLR, sports, film camera.</p>
            </div>

            <div class="box">
                <img src="images/service-3.png" alt="">
                <h3>personal computer</h3>
                <p>desktop, laptop, mac and other personal computer.</p>
            </div>

            <div class="box">
                <img src="images/service-4.png" alt="">
                <h3>data server</h3>
                <p>data server and data management server.</p>
            </div>

        </div>

    </section>

    <!-- services end-->



    <!-- reviews -->

    <section class="reviews" id="reviews">

        <h1 class="heading">clients <span>review</span></h1>

        <!-- Review Submission Form -->
        <form action="#reviews" method="post" class="review-form" style="max-width:400px;margin:2rem auto 3rem auto;background:#f8f8f8;padding:2rem;border-radius:1rem;box-shadow:0 2px 8px rgba(0,0,0,0.05);">
            <h3 style="text-align:center;margin-bottom:1rem;">Leave a Review</h3>
            <input type="text" name="review_name" placeholder="Your Name" required style="width:100%;padding:0.8rem;margin-bottom:1rem;border-radius:0.5rem;border:1px solid #ccc;">
            <div style="margin-bottom:1rem;">
                <label style="display:block;margin-bottom:0.5rem;">Your Rating:</label>
                <div class="star-rating" style="font-size:2rem;cursor:pointer;">
                    <input type="hidden" name="review_stars" id="review_stars" value="5">
                    <span class="star" data-value="1">&#9733;</span>
                    <span class="star" data-value="2">&#9733;</span>
                    <span class="star" data-value="3">&#9733;</span>
                    <span class="star" data-value="4">&#9733;</span>
                    <span class="star" data-value="5">&#9733;</span>
                </div>
            </div>
            <textarea name="review_text" placeholder="Your Review" required style="width:100%;padding:0.8rem;border-radius:0.5rem;border:1px solid #ccc;min-height:80px;margin-bottom:1rem;"></textarea>
            <input type="submit" name="review_submit" value="Submit Review" class="btn" style="width:100%;">
        </form>
        <script>
        // Star rating selection logic
        document.addEventListener('DOMContentLoaded', function() {
            var stars = document.querySelectorAll('.star-rating .star');
            var input = document.getElementById('review_stars');
            stars.forEach(function(star) {
                star.addEventListener('click', function() {
                    var val = this.getAttribute('data-value');
                    input.value = val;
                    stars.forEach(function(s, idx) {
                        if(idx < val) s.style.color = '#fbc02d';
                        else s.style.color = '#ccc';
                    });
                });
            });
            stars.forEach(function(s, idx) {
                if(idx < input.value) s.style.color = '#fbc02d';
                else s.style.color = '#ccc';
            });
        });
        </script>
        <!-- End Review Submission Form -->

        <div class="swiper review-slider">

            <div class="swiper-wrapper">
                <!-- Load reviews from database -->
                <?php
                $shown_reviews = array();
                $result = mysqli_query($connect, "SELECT * FROM reviews ORDER BY id DESC LIMIT 30");
                while($row = mysqli_fetch_assoc($result)) {
                    $review_key = md5(strtolower(trim($row['name'])) . '|' . strtolower(trim($row['review'])));
                    if (!isset($shown_reviews[$review_key])) {
                        $shown_reviews[$review_key] = 1;
                    } else {
                        $shown_reviews[$review_key]++;
                    }
                    if ($shown_reviews[$review_key] > 2) continue;
                    $stars = intval($row['stars']);
                    echo '<div class="swiper-slide box">';
                    echo '<h3>' . htmlspecialchars($row['name']) . '</h3>';
                    echo '<div class="stars">';
                    for($i=1;$i<=5;$i++) {
                        if($i <= $stars) {
                            echo '<i class="fas fa-star" style="color:#fbc02d;"></i>';
                        } else {
                            echo '<i class="fas fa-star" style="color:#ccc;"></i>';
                        }
                    }
                    echo '</div>';
                    echo '<p>' . nl2br(htmlspecialchars($row['review'])) . '</p>';
                    echo '</div>';
                }
                ?>

            </div>
            <div class="swiper-pagination"></div>

        </div>

    </section>

    <!-- reviews end-->



    <!-- contact -->

    <section class="contact" id="contact">

        <h1 class="heading"><span>service</span> reservation</h1>

        <div class="row">

            <div class="image">
                <img src="images/Service 24_7.gif" alt="">
            </div>

            <form id="reservation-form" method="post">

                <div class="message">

                    <input type="text" name="customer_name" placeholder="Name" required>
                    <input type="text" name="customer_address" placeholder="Address" required>
                    <input type="number" name="customer_mobile" placeholder="Phone +880" required>
                    <input type="email" name="customer_email" placeholder="Email" required>

                    <select name="customer_problem" id="prob" required>
                        <option value="">Select your problem</option>
                        <option value="Mobile Phone services">Mobile Phone services</option>
                        <option value="PC and Mac notebook service">PC and Mac notebook service</option>
                        <option value="Personal devices security">Personal devices security</option>
                        <option value="Data Management service">Data Management service</option>
                        <option value="Smart Watche services">Smart Watche services</option>
                        <option value="Digital Cameras services">Digital Cameras services</option>
                    </select>
                    
                </div>

                <textarea name="customer_description" placeholder="Description about your problems" cols="30" rows="10" required></textarea>

                <!-- OTP Section (initially hidden) -->
                <div id="otp-section" style="display: none; margin: 1rem 0; padding: 1rem; background: #f8f8f8; border-radius: 0.5rem;">
                    <p style="color: #28a745; margin-bottom: 1rem;">OTP has been sent to your email. Please enter it below:</p>
                    <input type="text" id="otp-input" placeholder="Enter 6-digit OTP" maxlength="6" style="width: 100%; padding: 0.8rem; border: 1px solid #ccc; border-radius: 0.5rem; text-align: center; font-size: 1.2rem; letter-spacing: 2px;">
                    <div style="margin-top: 1rem;">
                        <button type="button" id="verify-otp-btn" class="btn" style="margin-right: 1rem;">Verify OTP</button>
                        <button type="button" id="resend-otp-btn" class="btn" style="background: #6c757d;">Resend OTP</button>
                    </div>
                </div>

                <div id="form-messages" style="margin: 1rem 0; padding: 0.5rem; border-radius: 0.5rem; display: none;"></div>

                <button type="button" id="send-otp-btn" class="btn">Send OTP</button>

            </form>

        </div>

    </section>

    <!-- contact end -->



    <!-- footer -->

    <section class="footer">

        <div class="box-container">

            <div class="box">
                <h3> <i class="fas fa-gear"></i> Sakib IT Services </h3>
                <p>sakib it services is a it based services company. We provide authentic product and qualityful services.</p>
                <div class="share">
                    <a href="https://www.facebook.com/sajjadur.rahman.sakib.x" class="fab fa-facebook-f"></a>
                    <a href="https://github.com/sajjadur-rahman-sakib" class="fab fa-github"></a>
                    <a href="https://www.linkedin.com/in/sajjadurrahmansakib/" class="fab fa-linkedin"></a>
                    <a href="https://www.instagram.com/sakib.x/" class="fab fa-instagram"></a>
                </div>
            </div>

            <div class="box">
                <h3>help center</h3>
                <h4>Email</h4>
                <a href="" class="link">sakib.x@icloud.com</a>
                <h4>call us</h4>
                <p>+880 1518652610</p>
            </div>

            <div id="map-container">
                <!-- Embedded map using Google Maps Embed API -->
                <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3687.0506827305962!2d90.38017237603947!3d22.464729636913884!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x30aacf2fe39e501f%3A0xec70c954a51b0386!2sPatuakhali%20Science%20%26%20Technology%20University%20(PSTU)!5e0!3m2!1sen!2sbd!4v1707334557212!5m2!1sen!2sbd" width="500" height="220" style="border:0;" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>
            </div>

        </div>

        <div class="credit">created by <span>SAKIB</span> | all rights reserved!</div>

    </section>

    <!-- footer ends -->




    <!-- swiper js link  -->
    <script src="https://unpkg.com/swiper@7/swiper-bundle.min.js"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>

    <!-- OTP Functionality JavaScript -->
    <script>
    $(document).ready(function() {
        let currentEmail = '';
        let formDataCache = null; // Store form data for resend
        
        // Send OTP
        $('#send-otp-btn').click(function() {
            const form = $('#reservation-form')[0];
            if (!form.checkValidity()) {
                form.reportValidity();
                return;
            }
            
            const formData = new FormData(form);
            formData.append('send_otp', '1');
            currentEmail = formData.get('customer_email');
            
            // Cache the form data for resend functionality
            formDataCache = new FormData(form);
            
            $(this).prop('disabled', true).text('Sending OTP...');
            
            $.ajax({
                url: 'otp/otp_handler.php',
                type: 'POST',
                data: formData,
                processData: false,
                contentType: false,
                dataType: 'json',
                success: function(response) {
                    if (response.status === 'success') {
                        $('#otp-section').show();
                        $('#send-otp-btn').hide();
                        showMessage(response.message, 'success');
                        // Disable form fields
                        $('#reservation-form input, #reservation-form select, #reservation-form textarea').not('#otp-input').prop('disabled', true);
                    } else {
                        showMessage(response.message, 'error');
                    }
                },
                error: function() {
                    showMessage('Failed to send OTP. Please try again.', 'error');
                },
                complete: function() {
                    $('#send-otp-btn').prop('disabled', false).text('Send OTP');
                }
            });
        });
        
        // Verify OTP
        $('#verify-otp-btn').click(function() {
            const otp = $('#otp-input').val().trim();
            if (otp.length !== 6) {
                showMessage('Please enter a valid 6-digit OTP', 'error');
                return;
            }
            
            $(this).prop('disabled', true).text('Verifying...');
            
            $.ajax({
                url: 'otp/otp_handler.php',
                type: 'POST',
                data: {
                    verify_otp: '1',
                    customer_email: currentEmail,
                    otp: otp
                },
                dataType: 'json',
                success: function(response) {
                    if (response.status === 'success') {
                        showMessage(response.message, 'success');
                        // Reset form after successful submission
                        setTimeout(function() {
                            $('#reservation-form')[0].reset();
                            $('#otp-section').hide();
                            $('#send-otp-btn').show();
                            $('#reservation-form input, #reservation-form select, #reservation-form textarea').prop('disabled', false);
                            $('#form-messages').hide();
                        }, 3000);
                    } else {
                        showMessage(response.message, 'error');
                    }
                },
                error: function() {
                    showMessage('Verification failed. Please try again.', 'error');
                },
                complete: function() {
                    $('#verify-otp-btn').prop('disabled', false).text('Verify OTP');
                }
            });
        });
        
        // Resend OTP
        $('#resend-otp-btn').click(function() {
            // Check if we have cached form data
            if (!formDataCache) {
                showMessage('Error: No form data available. Please refresh and try again.', 'error');
                return;
            }
            
            // Create new FormData from cached data and add send_otp flag
            const resendFormData = new FormData();
            for (let [key, value] of formDataCache.entries()) {
                resendFormData.append(key, value);
            }
            resendFormData.append('send_otp', '1');
            
            $(this).prop('disabled', true).text('Resending...');
            
            $.ajax({
                url: 'otp/otp_handler.php',
                type: 'POST',
                data: resendFormData,
                processData: false,
                contentType: false,
                dataType: 'json',
                success: function(response) {
                    if (response.status === 'success') {
                        showMessage('OTP resent successfully', 'success');
                        $('#otp-input').val('');
                    } else {
                        showMessage(response.message, 'error');
                    }
                },
                error: function(xhr, status, error) {
                    console.log('Resend error details:', {xhr: xhr, status: status, error: error});
                    showMessage('Failed to resend OTP. Please try again.', 'error');
                },
                complete: function() {
                    $('#resend-otp-btn').prop('disabled', false).text('Resend OTP');
                }
            });
        });
        
        // Auto-format OTP input
        $('#otp-input').on('input', function() {
            this.value = this.value.replace(/[^0-9]/g, '');
        });
        
        // Show message function
        function showMessage(message, type) {
            const messageDiv = $('#form-messages');
            messageDiv.removeClass('success error').addClass(type);
            messageDiv.text(message).show();
            
            if (type === 'success') {
                messageDiv.css({
                    'background': '#d4edda',
                    'color': '#155724',
                    'border': '1px solid #c3e6cb'
                });
            } else {
                messageDiv.css({
                    'background': '#f8d7da',
                    'color': '#721c24',
                    'border': '1px solid #f5c6cb'
                });
            }
        }
    });
    </script>

    <!-- custom js file link  -->
    <script src="js/script.js"></script>

</body>

</html>