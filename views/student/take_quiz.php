<?php require '../views/layouts/header.php'; ?>

<style>
    /* Specific styles for exam mode */
    .exam-header {
        position: fixed;
        top: 0; left: 0; right: 0;
        background: var(--bg-secondary);
        backdrop-filter: blur(20px);
        padding: 15px 30px;
        display: flex;
        justify-content: space-between;
        align-items: center;
        border-bottom: 2px solid var(--primary);
        z-index: 1000;
    }
    .exam-content {
        margin-top: 80px;
        padding: 30px;
        max-width: 800px;
        margin-left: auto;
        margin-right: auto;
    }
    .question-card {
        background: var(--glass-bg);
        border: 1px solid var(--border-color);
        border-radius: 16px;
        padding: 25px;
        margin-bottom: 20px;
        box-shadow: var(--card-shadow);
    }
    .options-list {
        list-style: none;
        margin-top: 15px;
    }
    .options-list li {
        margin-bottom: 10px;
    }
    .options-list label {
        display: block;
        padding: 15px 20px;
        border: 1px solid rgba(255,255,255,0.1);
        border-radius: 10px;
        cursor: pointer;
        transition: all 0.2s;
        background: rgba(15, 23, 42, 0.4);
    }
    .options-list label:hover {
        background: rgba(139, 92, 246, 0.1);
        border-color: var(--primary);
        box-shadow: 0 0 15px var(--primary-glow);
    }
    .options-list input[type="radio"]:checked + label {
        background: rgba(139, 92, 246, 0.25);
        border-color: var(--primary);
        box-shadow: inset 0 0 10px var(--primary-glow);
    }
    .timer {
        font-size: 1.8rem;
        font-weight: 800;
        color: var(--danger);
        text-shadow: 0 0 10px rgba(239, 68, 68, 0.5);
    }
    /* Hide sidebar and normal navbar */
    .sidebar, .navbar { display: none !important; }
    .wrapper { display: block; }
    
    /* Pre-exam overlay */
    #preExamOverlay {
        display: flex;
        flex-direction: column;
        justify-content: center;
        align-items: center;
        height: 100vh;
        text-align: center;
    }
    #examWrapper {
        display: none; /* Hidden until fullscreen */
    }
    .blur-exam {
        filter: blur(25px) grayscale(100%);
        pointer-events: none;
        user-select: none;
        opacity: 0;
    }
    /* Webcam Styles */
    #webcamContainer {
        position: fixed;
        bottom: 20px;
        right: 20px;
        width: 150px;
        height: 150px;
        border-radius: 50%;
        overflow: hidden;
        border: 4px solid var(--primary);
        box-shadow: 0 0 20px var(--primary-glow);
        z-index: 9999;
        display: none; /* Hidden initially */
        background: #000;
    }
    #webcamVideo {
        width: 100%;
        height: 100%;
        object-fit: cover;
        transform: scaleX(-1); /* Mirror effect */
    }
</style>

<!-- PRE-EXAM OVERLAY -->
<div id="preExamOverlay">
    <i class="fa-solid fa-expand" style="font-size: 4rem; color: var(--primary); margin-bottom: 20px; filter: drop-shadow(0 0 10px var(--primary-glow));"></i>
    <h1 style="margin-bottom: 10px;">Fullscreen Required</h1>
    <p style="color: var(--text-muted); max-width: 500px; margin-bottom: 30px;">
        This exam requires you to be in full-screen mode and <strong>requires your Webcam to be turned on</strong> for proctoring. If you press ESC or exit full-screen mode, your exam will be automatically submitted!
    </p>
    <button id="startFullscreenBtn" class="btn btn-primary" style="width: auto; font-size: 1.2rem; padding: 15px 40px;">
        Allow Camera, Enter Fullscreen & Start Exam
    </button>
</div>

<!-- WEBCAM CONTAINER -->
<div id="webcamContainer">
    <video id="webcamVideo" autoplay playsinline muted></video>
    <canvas id="snapshotCanvas" style="display:none;"></canvas>
</div>

<!-- ACTUAL EXAM WRAPPER -->
<div id="examWrapper">
    <div class="exam-header">
        <h2><?php echo htmlspecialchars($data['quiz']->quiz_name); ?></h2>
        <div class="timer" id="timerDisplay">--:--</div>
    </div>

    <div class="exam-content" style="max-width: 1200px;">
        <div id="examMessages"></div>
        
        <div style="display: flex; gap: 30px; align-items: flex-start;">
            <!-- Navigation Sidebar -->
            <div style="width: 250px; background: var(--glass-bg); padding: 20px; border-radius: 16px; border: 1px solid var(--border-color); box-shadow: var(--card-shadow);">
                <h3 style="margin-bottom: 15px; text-align: center; color: var(--primary);">Questions</h3>
                <div style="display: flex; flex-wrap: wrap; gap: 10px; justify-content: center;">
                    <?php foreach($data['questions'] as $index => $question): ?>
                        <div id="nav-circle-<?php echo $index; ?>" onclick="goToQuestion(<?php echo $index; ?>)" 
                             style="width: 40px; height: 40px; border-radius: 50%; background: rgba(255,255,255,0.05); border: 2px solid var(--border-color); display: flex; align-items: center; justify-content: center; cursor: pointer; font-weight: bold; transition: all 0.3s; color: var(--text-color);">
                            <?php echo ($index + 1); ?>
                        </div>
                    <?php endforeach; ?>
                </div>
                
                <div style="margin-top: 30px; padding-top: 20px; border-top: 1px solid var(--border-color); text-align: center;">
                    <button type="button" class="btn btn-primary" id="submitQuizBtn" style="width: 100%; font-size: 1.1rem; background-color: var(--danger);">Finish Exam</button>
                </div>
            </div>

            <!-- Main Question Area -->
            <div style="flex: 1;">
                <form id="quizForm">
                    <input type="hidden" name="quiz_id" value="<?php echo $data['quiz']->id; ?>">
                    
                    <?php foreach($data['questions'] as $index => $question): ?>
                        <div class="question-card question-slide" id="question-slide-<?php echo $index; ?>" style="<?php echo $index === 0 ? '' : 'display: none;'; ?>">
                            <h4 style="font-size: 1.3rem; margin-bottom: 10px;">
                                <span style="color: var(--primary);">Question <?php echo ($index + 1); ?>:</span> <?php echo htmlspecialchars($question->question_text); ?>
                            </h4>
                            <p style="font-size: 0.9rem; color: var(--text-muted); margin-bottom: 20px;">Points: <?php echo $question->points; ?></p>
                            
                            <?php if(!empty($question->image_path)): ?>
                                <div style="margin-bottom: 25px; text-align: center; background: rgba(0,0,0,0.2); padding: 10px; border-radius: 10px;">
                                    <img src="<?php echo URLROOT . '/' . htmlspecialchars($question->image_path); ?>" alt="Question Image" style="max-width: 100%; max-height: 400px; border-radius: 8px;">
                                </div>
                            <?php endif; ?>

                            <ul class="options-list">
                                <li>
                                    <input type="radio" name="answers[<?php echo $question->id; ?>]" value="A" id="q<?php echo $question->id; ?>A" style="display:none;" onchange="markAnswered(<?php echo $index; ?>)">
                                    <label for="q<?php echo $question->id; ?>A">A) <?php echo htmlspecialchars($question->option_a); ?></label>
                                </li>
                                <li>
                                    <input type="radio" name="answers[<?php echo $question->id; ?>]" value="B" id="q<?php echo $question->id; ?>B" style="display:none;" onchange="markAnswered(<?php echo $index; ?>)">
                                    <label for="q<?php echo $question->id; ?>B">B) <?php echo htmlspecialchars($question->option_b); ?></label>
                                </li>
                                <li>
                                    <input type="radio" name="answers[<?php echo $question->id; ?>]" value="C" id="q<?php echo $question->id; ?>C" style="display:none;" onchange="markAnswered(<?php echo $index; ?>)">
                                    <label for="q<?php echo $question->id; ?>C">C) <?php echo htmlspecialchars($question->option_c); ?></label>
                                </li>
                                <li>
                                    <input type="radio" name="answers[<?php echo $question->id; ?>]" value="D" id="q<?php echo $question->id; ?>D" style="display:none;" onchange="markAnswered(<?php echo $index; ?>)">
                                    <label for="q<?php echo $question->id; ?>D">D) <?php echo htmlspecialchars($question->option_d); ?></label>
                                </li>
                            </ul>
                            
                            <div style="display: flex; justify-content: space-between; margin-top: 30px; padding-top: 20px; border-top: 1px solid rgba(255,255,255,0.1);">
                                <button type="button" class="btn btn-primary" onclick="goToQuestion(<?php echo $index - 1; ?>)" <?php echo $index === 0 ? 'disabled style="opacity:0.5; cursor:not-allowed;"' : ''; ?> style="width: 150px; background-color: var(--secondary);">Previous</button>
                                
                                <?php if($index === count($data['questions']) - 1): ?>
                                    <button type="button" class="btn btn-primary" onclick="document.getElementById('submitQuizBtn').click()" style="width: 150px; background-color: var(--success);">Finish</button>
                                <?php else: ?>
                                    <button type="button" class="btn btn-primary" onclick="goToQuestion(<?php echo $index + 1; ?>)" style="width: 150px;">Next</button>
                                <?php endif; ?>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const durationMinutes = <?php echo $data['quiz']->duration; ?>;
    let timeRemaining = durationMinutes * 60;
    const timerDisplay = document.getElementById('timerDisplay');
    const quizForm = document.getElementById('quizForm');
    let isSubmitted = false;
    let timerInterval = null;
    let examStarted = false;
    let totalQuestions = <?php echo count($data['questions']); ?>;
    let currentQuestionIndex = 0;
    
    // Highlight the first question circle initially
    if(totalQuestions > 0) {
        document.getElementById('nav-circle-0').style.borderColor = 'var(--primary)';
        document.getElementById('nav-circle-0').style.boxShadow = '0 0 10px var(--primary)';
    }

    window.goToQuestion = function(index) {
        if (index < 0 || index >= totalQuestions) return;
        
        // Hide all
        document.querySelectorAll('.question-slide').forEach(el => el.style.display = 'none');
        document.querySelectorAll('[id^="nav-circle-"]').forEach(el => {
            el.style.borderColor = 'var(--border-color)';
            el.style.boxShadow = 'none';
            // Restore answered state if applicable
            if(el.getAttribute('data-answered') === 'true') {
                el.style.backgroundColor = 'var(--success)';
                el.style.borderColor = 'var(--success)';
            }
        });
        
        // Show target
        document.getElementById('question-slide-' + index).style.display = 'block';
        
        // Highlight active circle
        const activeCircle = document.getElementById('nav-circle-' + index);
        activeCircle.style.borderColor = 'var(--primary)';
        activeCircle.style.boxShadow = '0 0 10px var(--primary)';
        
        currentQuestionIndex = index;
    };

    window.markAnswered = function(index) {
        const circle = document.getElementById('nav-circle-' + index);
        circle.style.backgroundColor = 'var(--success)';
        circle.style.borderColor = 'var(--success)';
        circle.style.color = '#fff';
        circle.setAttribute('data-answered', 'true');
    };
    
    // Fallback to true if you want to strictly enforce it always.
    // The strict_mode toggle is already in Student Controller. We will just enforce the fullscreen logic anyway.
    
    const preExamOverlay = document.getElementById('preExamOverlay');
    const examWrapper = document.getElementById('examWrapper');
    const startFullscreenBtn = document.getElementById('startFullscreenBtn');
    const webcamContainer = document.getElementById('webcamContainer');
    const webcamVideo = document.getElementById('webcamVideo');
    const snapshotCanvas = document.getElementById('snapshotCanvas');
    let snapshotInterval = null;

    // 1. Request Camera and Fullscreen to start
    startFullscreenBtn.addEventListener('click', async function() {
        try {
            // First, ask for camera permission
            const stream = await navigator.mediaDevices.getUserMedia({ video: true, audio: false });
            webcamVideo.srcObject = stream;
            
            // If camera is allowed, go fullscreen
            const elem = document.documentElement;
            if (elem.requestFullscreen) {
                elem.requestFullscreen().then(() => {
                    startExam();
                }).catch(err => {
                    alert(`Error attempting to enable fullscreen: ${err.message}`);
                });
            } else if (elem.webkitRequestFullscreen) { /* Safari */
                elem.webkitRequestFullscreen();
                startExam();
            } else if (elem.msRequestFullscreen) { /* IE11 */
                elem.msRequestFullscreen();
                startExam();
            } else {
                alert("Your browser does not support fullscreen. Starting anyway.");
                startExam(); // Fallback if browser doesn't support it at all
            }
        } catch (err) {
            alert("Sınava girebilmek için Kamera izni vermeniz zorunludur!\nLütfen tarayıcı ayarlarından kameraya izin verin.");
            console.error("Camera error:", err);
        }
    });

    // 2. Start Exam Logic
    function startExam() {
        if(examStarted) return;
        examStarted = true;
        
        preExamOverlay.style.display = 'none';
        examWrapper.style.display = 'block';
        webcamContainer.style.display = 'block'; // Show camera
        
        // Start Timer
        timerInterval = setInterval(() => {
            if(timeRemaining <= 0) {
                clearInterval(timerInterval);
                if(!isSubmitted) autoSubmit("Time is up! Your answers are being submitted automatically.");
                return;
            }
            
            let minutes = Math.floor(timeRemaining / 60);
            let seconds = timeRemaining % 60;
            
            minutes = minutes < 10 ? '0' + minutes : minutes;
            seconds = seconds < 10 ? '0' + seconds : seconds;
            
            timerDisplay.textContent = `${minutes}:${seconds}`;
            
            if(timeRemaining < 60) {
                timerDisplay.style.animation = "blink 1s infinite";
            }
            timeRemaining--;
        }, 1000);

        // Start Snapshot Proctoring
        snapshotInterval = setInterval(() => {
            takeSnapshot();
        }, 30000); // 30 seconds
        
        // Take an initial snapshot after 2 seconds
        setTimeout(() => takeSnapshot(), 2000);
    }

    function takeSnapshot() {
        if (!examStarted || isSubmitted) return;
        
        const context = snapshotCanvas.getContext('2d');
        snapshotCanvas.width = webcamVideo.videoWidth || 640;
        snapshotCanvas.height = webcamVideo.videoHeight || 480;
        context.drawImage(webcamVideo, 0, 0, snapshotCanvas.width, snapshotCanvas.height);
        
        const imageData = snapshotCanvas.toDataURL('image/jpeg', 0.7); // 70% quality to save space
        
        // Send via AJAX silently
        const fd = new FormData();
        fd.append('quiz_id', '<?php echo $data['quiz']->id; ?>');
        fd.append('image', imageData);
        
        AjaxHelper.post('<?php echo URLROOT; ?>/student/uploadSnapshot', fd).catch(e => console.log('Snapshot error:', e));
    }

    // 3. Monitor Fullscreen Changes (The Strict Rule)
    document.addEventListener('fullscreenchange', handleFullscreenExit);
    document.addEventListener('webkitfullscreenchange', handleFullscreenExit);
    document.addEventListener('msfullscreenchange', handleFullscreenExit);

    function handleFullscreenExit() {
        if (!document.fullscreenElement && !document.webkitIsFullScreen && !document.mozFullScreen && !document.msFullscreenElement) {
            // User exited full screen!
            if(examStarted && !isSubmitted) {
                autoSubmit("You exited Fullscreen Mode! The exam has been terminated and automatically submitted.");
            }
        }
    }

    // Submission Logic
    document.getElementById('submitQuizBtn').addEventListener('click', function() {
        if(confirm("Are you sure you want to submit your answers?")) {
            submitForm();
        }
    });

    function autoSubmit(message) {
        alert(message);
        submitForm();
    }

    async function submitForm() {
        if(isSubmitted) return;
        isSubmitted = true;
        clearInterval(timerInterval);
        if(snapshotInterval) clearInterval(snapshotInterval);
        
        const formData = new FormData(quizForm);
        try {
            const response = await AjaxHelper.post('<?php echo URLROOT; ?>/student/submitQuiz', formData);
            if (response.success) {
                examWrapper.innerHTML = `
                    <div style="display:flex; justify-content:center; align-items:center; height:100vh;">
                        <div class="card" style="text-align:center; max-width: 500px;">
                            <i class="fa-solid fa-circle-check" style="font-size: 5rem; color: var(--success); margin-bottom: 20px; filter: drop-shadow(0 0 15px rgba(16,185,129,0.5));"></i>
                            <h2>Quiz Submitted Successfully!</h2>
                            <h1 style="color:var(--success); font-size:4rem; margin: 20px 0;">${response.score} / 100</h1>
                            <a href="<?php echo URLROOT; ?>/student/dashboard" class="btn btn-primary" style="margin-top:20px; width:auto;">Return to Dashboard</a>
                        </div>
                    </div>
                `;
                
                // Exit fullscreen if we are still in it
                if (document.fullscreenElement) {
                    document.exitFullscreen().catch(err => console.log(err));
                }
                
                // Stop camera stream
                if (webcamVideo.srcObject) {
                    webcamVideo.srcObject.getTracks().forEach(track => track.stop());
                }
                webcamContainer.style.display = 'none';
                
            } else {
                document.getElementById('examMessages').innerHTML = `<div class="alert alert-danger">${response.message}</div>`;
            }
        } catch (error) {
            console.error(error);
            alert("An error occurred while submitting. Please try again.");
            isSubmitted = false; // Allow retry
        }
    }

    // Additional Security Features (Strict Mode)
    const strictMode = <?php echo $data['strict_mode'] ? 'true' : 'false'; ?>;
    if(strictMode) {
        // Prevent Copy/Paste/Context Menu
        document.addEventListener('contextmenu', event => event.preventDefault());
        document.addEventListener('copy', event => event.preventDefault());
        document.addEventListener('cut', event => event.preventDefault());
        document.addEventListener('paste', event => event.preventDefault());

        // Tab Switching & Snipping Tool Detection
        let warnings = 0;
        
        // When window loses focus (e.g., Snipping tool overlay opens, or alt-tab)
        window.addEventListener('blur', function() {
            if (examStarted && !isSubmitted) {
                document.body.classList.add('blur-exam');
            }
        });

        // When window regains focus
        window.addEventListener('focus', function() {
            if (examStarted && !isSubmitted) {
                document.body.classList.remove('blur-exam');
            }
        });

        // Block PrintScreen Key
        document.addEventListener('keyup', function(e) {
            if (e.key == 'PrintScreen' || e.code == 'PrintScreen') {
                if(examStarted && !isSubmitted) {
                    try { navigator.clipboard.writeText(''); } catch(e) {} // Try to clear clipboard
                    alert('Ekran görüntüsü almak (Screenshot) yasaktır!');
                    document.body.classList.add('blur-exam');
                    // Stays blurred until mouse moves back inside the window
                }
            }
        });

        // Block typical screenshot combinations
        document.addEventListener('keydown', function(e) {
            // Block Ctrl+P (Print)
            if (e.ctrlKey && (e.key === 'p' || e.key === 'P')) {
                e.preventDefault();
                alert('Yazdırma işlemi yasaktır!');
            }
            // Block Win+Shift+S or Mac Cmd+Shift+3/4
            if (e.metaKey || (e.ctrlKey && e.shiftKey)) {
                document.body.classList.add('blur-exam');
            }
        });
        
        // Remove blur only when the user is actively interacting with the page again
        document.addEventListener('mousemove', function() {
            if(document.hasFocus() && document.body.classList.contains('blur-exam')) {
                document.body.classList.remove('blur-exam');
            }
        });
        document.addEventListener('click', function() {
            if(document.body.classList.contains('blur-exam')) {
                document.body.classList.remove('blur-exam');
            }
        });
    }
});
</script>

<style>
@keyframes blink {
    0% { opacity: 1; }
    50% { opacity: 0; }
    100% { opacity: 1; }
}
</style>

<?php require '../views/layouts/footer.php'; ?>
