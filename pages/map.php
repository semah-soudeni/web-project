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
  <div onmouseover="show('aero')" onmouseout="show('tbsi')" style="position: absolute;top: 10%;left: 22%;width: 50px">
    <svg xmlns="http://www.w3.org/2000/svg" height="25px" viewBox="0 -960 960 960" width="25px" fill="#000000">
      <circle r="400" cx="450" cy="-410" fill="white" />
      <circle r="350" cx="450" cy="-410" fill="#123caa" />
    </svg>
    <p id="aero" style="opacity: 0;">AEROBOTIX</p>
  </div>
  <div onmouseover="show('ieee')" onmouseout="show('tbsi')" style="position: absolute;top: 10%;left: 27%;width: 50px">
    <svg xmlns="http://www.w3.org/2000/svg" height="25px" viewBox="0 -960 960 960" width="25px" fill="#000000">
      <circle r="400" cx="450" cy="-410" fill="white" />
      <circle r="350" cx="450" cy="-410" fill="purple" />
    </svg>
    <p id='ieee' style="opacity: 0;">IEEE</p>
  </div>
  <div onmouseover="show('secu')" onmouseout="show('tbsi')" style="position: absolute;top: 19%;left: 59%;width: 50px">
    <svg xmlns="http://www.w3.org/2000/svg" height="25px" viewBox="0 -960 960 960" width="25px" fill="#000000">
      <circle r="400" cx="450" cy="-410" fill="white" />
      <circle r="350" cx="450" cy="-410" fill="#e73c37" />
    </svg>
    <p id='secu' style="opacity: 0;">SECURINETS</p>
  </div>
  <div onmouseover="show('acm')" onmouseout="show('tbsi')" style="position: absolute;top: 19%;left: 64%;width: 50px">
    <svg xmlns="http://www.w3.org/2000/svg" height="25px" viewBox="0 -960 960 960" width="25px" fill="#000000">
      <circle r="400" cx="450" cy="-410" fill="white" />
      <circle r="350" cx="450" cy="-410" fill="blue" />
    </svg>
    <p id='acm' style="opacity: 0;">ACM</p>
  </div>
</div>
<?php require_once ROOT_PATH . '/views/footer.php'; ?>
