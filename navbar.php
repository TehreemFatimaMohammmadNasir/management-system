<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Baskervville+SC&family=DM+Serif+Text:ital@0;1&family=Nerko+One&display=swap" rel="stylesheet">

    <title>Document</title>
    <style>
        
/* Styling for the top bar */
.top-bar {
    font-family: "DM Serif Text", system-ui;
  font-weight: 400;
  font-style: normal;
    background: #1e130c;  /* fallback for old browsers */
background: -webkit-linear-gradient(to right, #9a8478, #1e130c);  /* Chrome 10-25, Safari 5.1-6 */
background: linear-gradient(to right, #9a8478, #1e130c); /* W3C, IE 10+/ Edge, Firefox 16+, Chrome 26+, Opera 12+, Safari 7+ */
    color: #fff;
    padding: 10px;
    display: flex;
    justify-content: space-between;
   
}

.top-bar div {
    display: flex;
    align-items: center;
}

.top-bar span {
    margin-left: 10px;
}

.phone-icon, .clock-icon, .min-order-icon {
    font-size: 20px;
}

/* Styling for the navbar */
.navbar {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 15px;
    background-color: #fff;
    box-shadow: 0px 2px 5px rgba(0, 0, 0, 0.1);
}

.h2{
margin-left:20px;
}

.nav-links {
    display: flex;
    list-style: none;
}

.nav-links li {
    margin-left: 20px;
}

.nav-links a {
    text-decoration: none;
    font-size: 16px;
    color: #000;
    font-weight: 500;
}

.nav-links a:hover {
    color: #A11A1A;
    border-bottom: 2px solid #A11A1A;
}

.user-icon a {
    font-size: 24px;
    color: #000;
}

.user-icon a:hover {
    color: #A11A1A;
}

    </style>
</head>
<body>
<header>
        <div class="top-bar">
            <div class="delivery-info">
                <span class="phone-icon">📞</span>
                <span>Delivery: 0300-2734680</span>
            </div>
            <div class="timing-info">
                <span class="clock-icon">⏰</span>
                <span>Timings: 11:30 am - 10:45 pm</span>
            </div>
            <div class="order-info">
                <span class="min-order-icon">📝</span>
                <span>Min. Order: Rs. 1000*</span>
            </div>
        </div>
        <nav class="navbar">
            <div class="logo">
                <h2>M.T</h2>
            </div>
            <ul class="nav-links">
                <li><a href="index.php">Home</a></li>
                <li><a href="about.php">About Us</a></li>
                <li><a href="register.php">Register</a></li>
                <li><a href="login.php">Login</a></li>
            </ul>
            <div class="user-icon">
                <a href="profile.php"><span class="user-icon">👤</span></a>
            </div>
        </nav>
    </header>
</body>
</html>