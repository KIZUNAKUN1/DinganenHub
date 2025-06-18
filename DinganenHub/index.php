<?php
session_start();

// Static admin credentials
$admin_username = "Administrator";
$admin_password = "admin12345";

$error = "";

// Handle login POST request
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username'] ?? '');
    $password = trim($_POST['password'] ?? '');

    if ($username === $admin_username && $password === $admin_password) {
        $_SESSION['admin_logged_in'] = true;
        session_regenerate_id(true); // Prevent session fixation
        header("Location: AdminDashboard.html");
        exit();

    } else {
        $error = "Incorrect Username or Password.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>DInganenHub - Login</title>
    <link rel="stylesheet" href="Assets/Style/styles.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
</head>
<body>
    <div class="container">
        <div class="logo-container">
            <img src="Picture1.png" alt="DInganenHub Logo" class="logo">
        </div>
        <div class="login-box">
            
            <h2>DInganenHub</h2>
            <?php if (!empty($error)): ?>
                <div id="errorMsg" class="error-msg"><?php echo htmlspecialchars($error); ?></div>
            <?php endif; ?>
            <form id="loginForm" class="form" method="post">
                <input type="text" id="username" name="username" placeholder="Username" required>
                <input type="password" id="password" name="password" placeholder="Password" required>
                <button type="submit">Login</button>
                <p id="loginMessage" class="message"></p>
            </form>
            
        </div>

        <!-- Navigation Buttons -->
        <div class="nav-buttons">
            <button id="visionMissionBtn" class="nav-btn">
                <i class="fas fa-eye"></i> Vision & Mission
            </button>
            <button id="blotterProcessBtn" class="nav-btn">
                <i class="fas fa-file-alt"></i> Blotter Process
            </button>
            <button id="residencyProcessBtn" class="nav-btn">
                <i class="fas fa-id-card"></i> Residency Process
            </button>
        </div>

        <!-- Modal Containers -->
        <div id="visionMissionModal" class="modal">
            <div class="modal-content">
                <span class="close-btn">&times;</span>
                <h2>Barangay Vision and Mission</h2>
                <div class="vision-section">
                    <div class="vision-header">
                        <div class="vision-icon">
                            <i class="fas fa-bullseye"></i>
                        </div>
                        <h3>Our Inspiring Vision</h3>
                    </div>
                    <blockquote>
                        "To be a progressive, sustainable, and empowered community that provides inclusive and quality services, fostering unity, development, and well-being for all residents."
                    </blockquote>

                    <div class="mission-header">
                        <div class="mission-icon">
                            <i class="fas fa-rocket"></i>
                        </div>
                        <h3>Our Committed Mission</h3>
                    </div>
                    <div class="mission-pillars">
                        <div class="mission-pillar">
                            <i class="fas fa-heart"></i>
                            <h4>Community Welfare</h4>
                            <p>Elevating the quality of life for every resident through comprehensive support and inclusive programs.</p>
                        </div>
                        <div class="mission-pillar">
                            <i class="fas fa-balance-scale"></i>
                            <h4>Transparent Governance</h4>
                            <p>Delivering efficient, accountable, and open local governance that empowers and serves the community.</p>
                        </div>
                        <div class="mission-pillar">
                            <i class="fas fa-seedling"></i>
                            <h4>Sustainable Development</h4>
                            <p>Implementing innovative programs that promote environmental sustainability and economic growth.</p>
                        </div>
                        <div class="mission-pillar">
                            <i class="fas fa-users"></i>
                            <h4>Community Engagement</h4>
                            <p>Strengthening social bonds and encouraging active participation in barangay development.</p>
                        </div>
                    </div>

                    <div class="core-values">
                        <div class="values-header">
                            <div class="values-icon">
                                <i class="fas fa-star"></i>
                            </div>
                            <h3>Our Core Values</h3>
                        </div>
                        <div class="values-grid">
                            <div class="value-item">
                                <i class="fas fa-shield-alt"></i>
                                <h4>Integrity</h4>
                                <p>Upholding the highest ethical standards in all our actions and decisions.</p>
                            </div>
                            <div class="value-item">
                                <i class="fas fa-eye"></i>
                                <h4>Transparency</h4>
                                <p>Maintaining open and clear communication with our community.</p>
                            </div>
                            <div class="value-item">
                                <i class="fas fa-hands-helping"></i>
                                <h4>Inclusivity</h4>
                                <p>Ensuring equal opportunities and respect for all community members.</p>
                            </div>
                            <div class="value-item">
                                <i class="fas fa-check-circle"></i>
                                <h4>Accountability</h4>
                                <p>Taking responsibility for our commitments and actions.</p>
                            </div>
                            <div class="value-item">
                                <i class="fas fa-globe"></i>
                                <h4>Community-Centered</h4>
                                <p>Prioritizing the collective well-being and growth of our residents.</p>
                            </div>
                        </div>
                    </div>

                    <div class="strategic-goals">
                        <div class="goals-header">
                            <div class="goals-icon">
                                <i class="fas fa-chart-line"></i>
                            </div>
                            <h3>Strategic Goals</h3>
                        </div>
                        <ul>
                            <li>Enhance local infrastructure and public services</li>
                            <li>Promote economic opportunities and livelihood programs</li>
                            <li>Develop comprehensive health and wellness initiatives</li>
                            <li>Strengthen disaster preparedness and community resilience</li>
                            <li>Foster educational and skills development programs</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>

        <div id="blotterProcessModal" class="modal">
            <div class="modal-content">
                <span class="close-btn">&times;</span>
                <h2>Blotter Process: Official Incident Reporting</h2>
                <div class="blotter-process">
                    <h3>Purpose of Blotter Reporting</h3>
                    <p>An official mechanism for documenting and addressing incidents within the barangay, ensuring community safety and conflict resolution.</p>

                    <h3>Required Documents</h3>
                    <ul>
                        <li>Valid Government-issued ID</li>
                        <li>Detailed written statement of the incident</li>
                        <li>Any supporting evidence or witness statements</li>
                    </ul>

                    <h3>Step-by-Step Filing Process</h3>
                    <ol>
                        <li>
                            <strong>Visit Barangay Hall</strong>
                            <p>Come during official office hours. Bring all relevant documentation.</p>
                        </li>
                        <li>
                            <strong>Approach Barangay Official</strong>
                            <p>Meet with the Barangay Secretary or Desk Officer. Clearly explain the nature of your incident.</p>
                        </li>
                        <li>
                            <strong>Prepare Written Statement</strong>
                            <p>Provide a comprehensive, factual account of the incident. Include dates, times, locations, and involved parties.</p>
                        </li>
                        <li>
                            <strong>Submit Documentation</strong>
                            <p>Fill out the official blotter report form. Provide accurate and complete information.</p>
                        </li>
                        <li>
                            <strong>Pay Processing Fee</strong>
                            <p>Some barangays require a minimal processing fee. Confirm the exact amount with barangay staff.</p>
                        </li>
                        <li>
                            <strong>Receive Blotter Report Copy</strong>
                            <p>Keep the document for your personal records as proof of filing.</p>
                        </li>
                    </ol>

                    <div class="important-notes">
                        <h3>Important Notes</h3>
                        <ul>
                            <li>All information is handled with strict confidentiality</li>
                            <li>The blotter serves as an official record of the incident</li>
                            <li>Follow-up may be required depending on the nature of the report</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>

        <div id="residencyProcessModal" class="modal">
            <div class="modal-content">
                <span class="close-btn">&times;</span>
                <h2>Residency Certification Process</h2>
                <div class="residency-process">
                    <h3>Purpose of Residency Certification</h3>
                    <p>Obtain an official document certifying your residence in the barangay, which can be used for various administrative and legal purposes.</p>

                    <h3>Required Documents</h3>
                    <ul>
                        <li>Completely filled-out Residency Certification Application Form</li>
                        <li>Valid Government-issued ID</li>
                        <li>Proof of Residence (Utility Bill or Lease Agreement)</li>
                        <li>2 Recent Passport-sized Photographs</li>
                        <li>Barangay Clearance</li>
                    </ul>

                    <h3>Detailed Certification Process</h3>
                    <ol>
                        <li>
                            <strong>Document Preparation</strong>
                            <p>Gather all required documents. Ensure they are current and valid.</p>
                        </li>
                        <li>
                            <strong>Visit Barangay Hall</strong>
                            <p>Come during official office hours. Bring all prepared documents.</p>
                        </li>
                        <li>
                            <strong>Submit Application</strong>
                            <p>Present documents to barangay staff. They will review and verify your submission.</p>
                        </li>
                        <li>
                            <strong>Pay Processing Fee</strong>
                            <p>Complete payment for certification. Obtain an official receipt.</p>
                        </li>
                        <li>
                            <strong>Document Verification</strong>
                            <p>Barangay staff will verify your documents (1-3 working days).</p>
                        </li>
                        <li>
                            <strong>Certification Collection</strong>
                            <p>Return to barangay hall after processing to collect your residency certification.</p>
                        </li>
                    </ol>

                    <div class="additional-info">
                        <h3>Additional Information</h3>
                        <ul>
                            <li>Processing Time: 3-5 working days</li>
                            <li>Certification Validity: 6 months from date of issue</li>
                            <li>Fees may vary; confirm exact amount with barangay staff</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="Assets/JS/script.js"></script>
</body>
</html>