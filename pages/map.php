<?php
require_once __DIR__ . '/../includes/init.php';

$pageTitle  = 'Campus Map';
$activePage = 'map';
$extraCss   = [BASE_URL . 'assets/css/map.css'];
$extraJs    = [BASE_URL . 'assets/js/map.js'];
?>

<?php require_once ROOT_PATH . '/views/header.php'; ?>

<section class="header-section">
  <div class="header-container">
    <h1 class="header-title">This is the map of the campus</h1>
    <p class="header-subtitle">Click any of the marks to discover the club there.</p>
  </div>
</section>

<div class="main">
  <div onclick="openBar('aero')" style="position: absolute;top: 10%;left: 22%;width: 50px">
    <svg xmlns="http://www.w3.org/2000/svg" height="25px" viewBox="0 -960 960 960" width="25px" fill="#000000">
      <circle r="400" cx="450" cy="-410" fill="white" />
      <circle r="350" cx="450" cy="-410" fill="#123caa" />
    </svg>
  </div>
  <div onclick="openBar('ieee')" style="position: absolute;top: 10%;left: 27%;width: 50px">
    <svg xmlns="http://www.w3.org/2000/svg" height="25px" viewBox="0 -960 960 960" width="25px" fill="#000000">
      <circle r="400" cx="450" cy="-410" fill="white" />
      <circle r="350" cx="450" cy="-410" fill="purple" />
    </svg>
  </div>
  <div onclick="openBar('secu')" style="position: absolute;top: 19%;left: 59%;width: 50px">
    <svg xmlns="http://www.w3.org/2000/svg" height="25px" viewBox="0 -960 960 960" width="25px" fill="#000000">
      <circle r="400" cx="450" cy="-410" fill="white" />
      <circle r="350" cx="450" cy="-410" fill="#e73c37" />
    </svg>
  </div>
  <div onclick="openBar('acm')" style="position: absolute;top: 19%;left: 64%;width: 50px">
    <svg xmlns="http://www.w3.org/2000/svg" height="25px" viewBox="0 -960 960 960" width="25px" fill="#000000">
      <circle r="400" cx="450" cy="-410" fill="white" />
      <circle r="350" cx="450" cy="-410" fill="blue" />
    </svg>
  </div>


  <div class="sidebar" id="sidebar">
    <button onclick="closeBar()"> --> </button>
    <p id="test" style="color: black;"></p>
  </div>
</div>
<?php require_once ROOT_PATH . '/views/footer.php'; ?>