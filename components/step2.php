<section class="container-fluid  d-flex justify-content-center align-items-center" id="step2">
    <div class="row inhert-heigt">
        <div class="col ">
            <p class="center-text">Dankjewel voor het invullen van de benodigde informatie. Via de knop hieronder
                kun je direct jouw opname starten. Let erop dat er niet te veel achtergrond geluid is. Spreek
                duidelijk, luid en rustig.</p>
            <div class="audio-wrapper">
                <div class="button-wrapper row">
                    <button type="button" id="start"
                        class="btn btn-primary btn-lg btn-block big-button pauze-button col">Start Opname</button>
                </div>
                <div class="button-wrapper row">
                    <button type="button" id="pauze"
                        class="btn btn-primary btn-lg btn-block big-button stop-button col">pauzeer Opname</button>
                    <button type="button" id="stop"
                        class="btn btn-primary btn-lg btn-block big-button stop-button col">Stop Opname</button>
                    <button type="button" id="play"
                        class="btn btn-primary btn-lg btn-block big-button play-button col">Play Opname Audio</button>

                </div>
                <br>
                <div class="stick-to-bottom">
                    <div class="recorder-wrapper">

                        <div id="output" class="recorder-icon-wrapper">
                                <i class="fa-solid fa-microphone fa-2xl"></i>
                        </div>

                    </div>
                    <p id="msgRecorded" class="size-1" style="display: none; margin-bottom: 10px;">er is een opname gemaakt!</p>

                </div>
                <input type="hidden" id="audioFile" name="audioFile">
                <div class="audio-control">
                    <audio controls id="recordedAudio"></audio>
                </div>

                <div class="button-wrapper row">
                    <button type="button" id="reload-button" name="submit" class="btn btn-primary btn-lg btn-block big-button with-btn-step2 col">Opnieuw opnemen</button>
                    <button type="submit" value="Upload File" id="btn_inzenden" class="btn btn-primary btn-lg btn-block big-button action-button with-btn-step2 col" style="display: none ">Inzenden</button>
                </div>
            </div>
        </div>
    </div>
</section>
