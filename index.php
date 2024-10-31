<!DOCTYPE html>
<html lang="en">
<?php //echo($base_url);
require_once("backend/conn.php");
require_once("components/head.php");
?>

<body class="row">
    <div class="col-3 header-spacing-r logo-wrapper">
        <div class="verticle-align-logo">
            <a class="logo-wrapper" href="<?php echo $base_url; ?>/index.php">
                <div class="logo-wrapper">
                    <img src="<?php echo $base_url; ?>/img/Logo-BSolutions.svg" alt="Logo">
                </div>
            </a>
        </div>
    </div>
    <main class="col-6">
        <section class="container-fluid ">
            <form action="<?php echo $base_url; ?>/backend/bandjesController.php" method="POST"
                enctype="multipart/form-data" id="belbandjes">
                <input type="hidden" name="action" value="create">
                <div class="col">
                    <div id="wizard">
                        <h3>stap 1</h3>
                        <section>
                            <?php include("components/main.php"); ?>
                        </section>

                        <h3>stap 2</h3>
                        <section>
                            <?php require_once("components/step1.php"); ?>
                        </section>

                        <h3>stap 3</h3>
                        <section class="">
                            <?php require_once("components/step2.php"); ?>
                        </section>
                    </div>
                </div>
                </div>
            </form>
        </section>
    </main>
    <div class="col-3">
        <div class="left-header-flex header-spacing-l">
            <div class="header-a justify-content-end">
                <a class="between-border-r size-2" href="">support</a>
                <a class="between-border-l size-2" href="">storingen</a>
            </div>
        </div>
        <img class="minibasje " src="<?php echo $base_url; ?>/img/MiniBasje.svg" alt="">
    </div>

    <!-- audio script: MediaRecorder api -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/jquery.validation/1.15.0/jquery.validate.min.js"></script>
    <!-- <script src="https://cdn.jsdelivr.net/jquery.validation/1.15.0/additional-methods.min.js"></script> -->
    
    <script>
        const startTimeSelect = document.getElementById("start_time_hour");
        const minuteSelect = document.getElementById("start_time_minute");
        
        // Generate hours (0-23)
        for (let hour = 0; hour <= 23; hour++) {
            const option = document.createElement("option");
            option.value = hour.toString().padStart(2, "0"); // Ensure two-digit format
            option.textContent = option.value;
            startTimeSelect.appendChild(option);
        }

        // Generate 15-minute intervals for minutes
        for (let minute = 0; minute < 60; minute += 15) {
            const option = document.createElement("option");
            option.value = minute.toString().padStart(2, "0"); // Ensure two-digit format
            option.textContent = option.value;
            minuteSelect.appendChild(option);
        }

        //second timepicker
        const startTimeSelect2 = document.getElementById("end_time_hour");
        const minuteSelect2 = document.getElementById("end_time_minute");

        // Generate hours (0-23)
        for (let hour2 = 0; hour2 <= 23; hour2++) {
            const option2 = document.createElement("option");
            option2.value = hour2.toString().padStart(2, "0"); // Ensure two-digit format
            option2.textContent = option2.value;
            startTimeSelect2.appendChild(option2);
        }

        // Generate 15-minute intervals for minutes
        for (let minute2 = 0; minute2 < 60; minute2 += 15) {
            const option2 = document.createElement("option");
            option2.value = minute2.toString().padStart(2, "0"); // Ensure two-digit format
            option2.textContent = option2.value;
            minuteSelect2.appendChild(option2);
        }
    </script>

    <script>
        $('.form-check-input').click(function () {
            $(this).siblings('input:checkbox').prop('checked', false);
        });
        document.addEventListener("DOMContentLoaded", function () {
            // Hieronder komen al je scripts van: js/jQuery/AJAX/etc.

            $(document).ready(function () {
                $("#wizard").steps({
                    headerTag: "h3",
                    bodyTag: "section",
                    transitionEffect: "slideLeft",
                    autoFocus: true,
                    labels: {
                        finish: "Versturen",
                        next: "Volgende",
                        previous: "Vorige",
                    },
                    
                    // Hier moet bij iedere stap validatie komen
                    onStepChanging: function (event, currentIndex, newIndex) {
                        if (currentIndex === 1) {                            
                            $("#belbandjes").validate({                         
                                rules: {
                                    c_name: { //werkt
                                        required: true,
                                    },
                                    p_name: { //werkt
                                        required: true,
                                    },
                                    t_bandje: { //werkt
                                        required: true,                                
                                    },
                                    purpuse_bandje: { //werkt
                                        required: true,
                                    },
                                    start_date: { //werkt
                                        required: true,
                                    },
                                    eind_date: { //werkt
                                        required: {
                                            depends: function(element) {
                                                console.log($("#permanent_bandje").is(":checked"));
                                                
                                                return !$("#permanent_bandje").is(":checked");

                                            }
                                        },
                                    },
                                    //validate if one of the checkboxes is checked
                                    keuzemenu_value: { //werkt
                                        required: true,
                                    },
                                    menu_explanation: { //werkt
                                        required: true,
                                    },
                                },
                                messages: {
                                    c_name: {
                                        required: "Vul hier de naam van het bandje in.",
                                    },
                                    p_name: {
                                        required: "vul uw volledige naam in.",
                                    },
                                    t_bandje: {
                                        required: "kies je type bandje.",
                                    },
                                    purpuse_bandje: {
                                        required: "Omschrijf het doel van uw bandje.",
                                    },
                                    start_date: {
                                        required: "Selecteer de gewenste start datum en tijd in.",
                                    },
                                    eind_date: {
                                        //if permanent bandje is checked then no validation is required	
                                        required: "Selecteer de gewenste eind datum.",
                                    },
                                    keuzemenu_value: {
                                        required: "Maak een keuze.",
                                    },
                                    menu_explanation: {
                                        required: "Omschrijf uw keuzemenu zo goed mogelijk.",
                                    },
                                },               
                            }); // end validation step 1

                        
                            // Check the current step using the currentIndex parameter                            
                            $('#permanent_bandje').click(function () {
                                if ($(this).is(":checked")) {
                                    $("#eind_toggle").hide();
                                    $("#eind_toggle").removeClass("required");
                                    
                                }
                                else if ($(this).is(":not(:checked)")) {
                                    $("#eind_toggle").show();
                                    //("#end_date").addClass("required");
                                }
                            });

                            $('input[type=checkbox]').click(function () {
                                if ($('#keuzemenu_value').is(':checked')) {
                                    
                                    //input where you put a value
                                    $('#program').val("radio-button-text");

                                    //display an extra input under the radio buttons 
                                    $('#dynamicMenu').show();
                                } else {
                                    $("#dynamicMenu").hide();
                                    $("dynamicMenu").removeClass("required");
                                }
                            });

                            function checkSelectedOptionBandje() {
                                var selectedOption = $('#t_bandje').val();
                                if (selectedOption === 'overige') {
                                    $('#form-group-input-purpose').show();
                                } else {
                                    $('#form-group-input-purpose').hide();
                                }
                            }
                            function toggleEndDateAndTime() {
                                var checkbox = document.getElementById("permanent_bandje");
                                var eindToggle = document.getElementById("eind_toggle");

                                if (checkbox.checked) {
                                    eindToggle.style.display = "none"; // Hide the date and time inputs
                                } else {
                                    eindToggle.style.display = "block"; // Show the date and time inputs
                                }

                                toggleEndDateAndTime();
                            }
                                            
                            if ($("#belbandjes").valid()) {
                                return true;
                            } else {
                                return false;
                            }
                        
                    }// end current index 1
                    
                    // Function to validate the form fields                        
                    console.log(newIndex);
                    const startButton = document.getElementById('start');
                    const pauzeButton = document.getElementById('pauze');
                    const stopButton = document.getElementById('stop');
                    const playButton = document.getElementById('play');
                    const output = document.getElementById('output');
                    let audioRecorder;
                    let blobUrl;
                    let audioChunks = [];
                    let isRecording;

                    navigator.mediaDevices.getUserMedia({ audio: true })
                        .then(stream => {
                            audioRecorder = new MediaRecorder(stream);

                            audioRecorder.addEventListener('dataavailable', e => {
                                audioChunks.push(e.data);
                            });

                            startButton.addEventListener('click', () => {
                                audioRecorder.start(); //start audio recording
                                startButton.style.display = "none"; //hide start button
                                output.innerHTML = '<i class="fa-solid fa-microphone fa-beat-fade fa-2xl "></i>'; //change icon to beating icon
                                $("#btn_inzenden").show();
                            });

                            // pauze button	
                            pauzeButton.addEventListener('click', () => {
                                if (!isRecording) {
                                    // If not recording, start recording
                                    audioRecorder.resume();
                                    isRecording = true;
                                    output.innerHTML = '<i class="fa-solid fa-microphone fa-2xl"></i>';
                                    console.log("recording started");
                                } else {
                                    // If recording, pause recording
                                    audioRecorder.pause();
                                    isRecording = false;
                                    output.innerHTML = '<i class="fa-solid fa-microphone fa-beat-fade fa-2xl"></i>';
                                    console.log("recording paused");
                                }
                            });

                            stopButton.addEventListener('click', () => { 
                                audioRecorder.stop(); //stop audio recording
                                output.innerHTML = '<i class="fa-solid fa-microphone fa-2xl"></i>'; //change icon to solid icon
                                msgRecorded.style.display = "block"; //show message that recording is done
                                pauzeButton.style.display = "none"; // hide pauze button

                                audioRecorder.onstop = () => {
                                    const audioControl = document.getElementById('recordedAudio');
                                    const blobObj = new Blob(audioChunks, { type: 'audio/wav' });
                                    console.log(blobObj);
                                    const reader = new FileReader();
                                    reader.onload = function () {
                                        const base64Data = reader.result.split(',')[1];
                                        document.getElementById('audioFile').value = base64Data;
                                    };
                                    audioControl.controlsList = 'nodownload';
                                    reader.readAsDataURL(blobObj);
                                    playButton.style.display = 'block'; 

                                    if (blobObj) {
                                        $("#btn_inzenden").show();
                                    }
                                };
                            });
                            
                            playButton.addEventListener('click', () => {
                                const blobObj = new Blob(audioChunks, { type: 'audio/wav' });
                                const audioUrl = URL.createObjectURL(blobObj);
                                
                                const audio = new Audio(audioUrl);
                                console.log(audio);
                                const audioControl = document.getElementById('recordedAudio');
                                audioControl.src = audioUrl;
                                //play audio if the audio source is loaded
                                audioControl.onloadedmetadata = () => {
                                    audioControl.play();
                                    audioControl.controlsList = 'nodownload';
                                };
                                
                            });                            
                            
                            document.getElementById('reload-button').addEventListener('click', function () {
                                audioChunks = []; // Clear the array where audio data is stored
                                document.getElementById('audioFile').value = ""; // Clear the base64 audio data input

                                // Remove the audio element
                                const audioControl = document.getElementById('recordedAudio');

                                startButton.style.display = "block";
                                pauzeButton.style.display = "block";
                                msgRecorded.style.display = "none";
                                audioControl.controlsList = 'nodownload';
                                // Reset the audio element source and controls
                                audioControl.src = '';
                                if (!audioControl.src == '') {
                                    $("#btn_inzenden").hide();
                                }
                                
                            });
                        }).catch(err => {
                            console.log('Error: ' + err);
                            //if there is no microphone avalible, make the record button inactive
                            if (navigator.mediaDevices.getUserMedia) {
                                    console.log('getUserMedia supported.');
                                } else {
                                    console.log('getUserMedia not supported on your browser!');
                                    startButton.disabled = true;
                                    pauzeButton.disabled = true;
                                    stopButton.disabled = true;
                                }
                        });

                        return true;
                    },
                   
                    onStepChanged: function (event, currentIndex, priorIndex) {
                        // Here you can add custom logic after changing steps.
                    },
                    onFinished: function (event, currentIndex) {
                        console.log(currentIndex);
                    }
                });

                // This is for the "Next" buttons
                $(".action-button").click(function (e) {
                    $("#wizard").steps("next"); // You might want to uncomment this based on your workflow
                });

                // This is for the "Finish" button
                $(".finish-button").click(function (e) {
                    console.log("Door naar het bedankje");

                    // To submit the form:
                    //document.querySelector("form").submit();
                });
            });
        });
    </script>

<script>
    var types = <?php echo json_encode($types); ?>; // Assuming $types is an array in PHP

    function checkSelectedOptionBandje() {
        //variables for t-bandje dynamic input
        var selectElementBandje = document.getElementById("t_bandje");
        var selectedValue = selectElementBandje.value;

        // Now you can use the PHP value in JavaScript
        var extraInfoDiv = document.getElementById("form-group-input-purpuse");
        if (selectedValue != "Overige") {
            extraInfoDiv.style.display = "none"; // Hide the additional information input
        } else {
            extraInfoDiv.style.display = "block"; // Show the additional information input
        }
    }

    // redirect from step 2
    function redirectToSuccesPage() {
        window.location.href = "https://systeem.bsolutions.nl/belbandje/components/succes.php?success";
    }
</script>

</body>

</html>