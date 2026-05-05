<?php
require_once __DIR__ . '/../includes/init.php';

requireAdmin();

$pageTitle = 'New Event';
$activePage = 'admin';
$extraCss = [BASE_URL . 'assets/css/addEvent.css'];
$extraJs = [BASE_URL . 'assets/js/addEvent.js'];

require_once ROOT_PATH . '/views/header.php';
?>
    <section class="main-section">
        <div class="add-event-container">
            <h3 class="add-event-title">Add New Event</h3>
            <form  id="add-event" action="javascript:sendData()">
                <div>    
                    <p>Event Type</p>
                    <select name="Event_Type" > 
                        <option>Hackathon</option>
                        <option>Competition</option>
                        <option>Conference</option>
                        <option>Workshop</option>
                    </select> 
                </div> 
                <div>
                    <p>Event Description</p>
                    <textarea name="description" rows="8" cols="250" placeholder="Event activities, objectives , .." required></textarea>
                </div>
                <div>
                    <p>Event Title</p>
                    <input type="text" name="title" placeholder="Enter event title" required>
                </div>
                <div>
                    <p>Date</p>
                    <input type="date"  name="date" required>
                </div>
                <div>
                    <p>Time</p>
                    <input type="time" name="time" required>
                </div>
                <div>
                   <p>Duration</p>
                    <input type="number" name="duration" placeholder="Duration in days"> 
                </div>
                <div>
                    <p>Prize Pool</p>
                    <textarea  name="prize" rows="8" cols="250" placeholder="Winners : 1000DT , runner-up : 500DT .."></textarea>
                </div> 
                <div>    
                    <p>Location</p>
                    <input type="text" name="location" placeholder="Enter location" >
                </div> 
                <div>    
                    <p>Max Attendees</p>
                    <input type="number" name="max_attendees" placeholder="---">
                </div> 
                <div>
                    <p>Other Participated Clubs</p>
                    <div class="clubs-dnd-wrapper">
                        <div class="clubs-column" id="left">
                            <p class="column-label">Available Clubs</p>
                            <div class="list"  data-club="Aerobotix">
                                <img src="../assets/img/aero-logo.png" class="logo">
                                <span>Aerobotix</span>
                            </div>
                            <div class="list"  data-club="IEEE">
                                <img src="../assets/img/ieee-logo.png" class="logo">
                                <span>IEEE</span>
                            </div>
                            <div class="list"  data-club="ACM">
                                <img src="../assets/img/acm-logo.png" class="logo">
                                <span>ACM</span>
                            </div>
                            <div class="list"  data-club="Securinets">
                                <img src="../assets/img/secu-logo.png" class="logo">
                                <span>Securinets</span>
                            </div>
                            <div class="list"  data-club="Théatro">
                                <img src="../assets/img/theatro.jpg"  class="logo">
                                <span>Théatro</span>
                            </div>
                            <div class="list"  data-club="CIM">
                                <img src="../assets/img/cim.png"  class="logo">
                                <span>CIM</span>
                            </div>
                            <div class="list"  data-club="junior">
                                <img src="../assets/img/junior.jpg"  class="logo">
                                <span>Junior Entreprise</span>
                            </div>
                            <div class="list"  data-club="android">
                                <img src="../assets/img/andr.png"  class="logo">
                                <span>Android</span>
                            </div>
                            <div class="list"  data-club="enactus">
                                <img src="../assets/img/enactus.png"  class="logo">
                                <span>Enactus</span>
                            </div>
                            <div class="list"  data-club="Press">
                                <img src="../assets/img/press.png"  class="logo">
                                <span>Insat Press</span>
                            </div>
                            <div class="list"  data-club="Lion">
                                <img src="../assets/img/lion.jpg"  class="logo">
                                <span>Lion</span>
                            </div>
                            <div class="list"  data-club="Ciné">
                                <img src="../assets/img/cine.jpg"  class="logo">
                                <span>Ciné Radio</span>
                            </div>
                            <div class="list"  data-club="jci">
                                <img src="../assets/img/juniorc.jpg"  class="logo">
                                <span>JCI</span>
                            </div>
                        </div>
                        <div class="clubs-divider">
                            <span class="arrow-icon">&#8594;</span>
                        </div>
                        <div class="clubs-column" id="right">
                            <p class="column-label">Participating</p>
                            <p class="drop-hint" id="drop-hint">Drop clubs here</p>
                        </div>
                    </div>
                </div>
                <div class="teammates-section">
                    <p class="teammates-label">Staff members</p>
                    <div class="teammates-list" id="teammates-list"></div>
                    <button type="button" class="add-teammate-btn" id="add-teammate-btn">+ Add Staff Member</button>
                </div>
                <input type="submit" value="Add Event">
            </form>
        </div>
            <div id="error" class="panel panel-error">
              <span class="badge">
                <span class="badge-dot"></span>
                Validation error
              </span>
              <p class="panel-title">Something went wrong</p>
              <div class="panel-divider"></div>
              <p class="panel-message">We couldn't save your changes. Please review the fields below and try again — your progress is safe.</p>
            </div>
            <div id="success" class="panel panel-success">
                <span class="badge">
                <span class="badge-dot"></span>
                All good
                </span>
                <p class="panel-title">Changes saved</p>
                <div class="panel-divider"></div>
                <p class="panel-message">Event has been added successufully. Changes may take a moment to appear everywhere.</p>
            </div>  
    </section>
<?php require_once ROOT_PATH . '/views/footer.php'; ?>
