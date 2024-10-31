<section class="container-fluid step1 d-flex justify-content-center align-items-center" id="step1">
    <?php
    // get table data
    $query = "SELECT * FROM typebandjes";
    $stmt = $conn->prepare($query);
    $stmt->execute();
    $types = $stmt->fetchAll(PDO::FETCH_ASSOC);

    ?>
    <div class="row">
        <div class="col"></div>

        <div class="col-12 center-wrapper">

            <div class="form-group">
                <label for="c-name">Wat is jouw bedrijfsnaam?</label>
                <input class="form-control huisstyle-input required" type="text" id="c_name" name="c_name"
                    placeholder="Bedrijfsnaam">
                
            </div>
            <div class="form-group">
                <label for="p-name">Wat is jouw naam?</label>
                <input class="form-control huisstyle-input required" type="text" id="p_name" name="p_name"
                    placeholder="Volledige naam" >
            </div>
            <div class="form-group">
                <label for="t-bandje">Waarvoor is het bandje bedoeld?</label>
                <!-- if 'overige' is selected then show a EXTRA question to explain what it is for -->

                <select class="form-control huisstyle-input required" id="t_bandje" name="t_bandje"
                    onchange="checkSelectedOptionBandje()">
                    <option value="" class="huisstyle-input">Maak een keuze ⌵</option>

                    <?php foreach ($types as $type): ?>
                        <option value="<?php echo ($type['type_name']); ?>" class="huisstyle-input" id="selectOption">
                            <?php echo ($type['type_name']); ?> ⌵
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="form-group" id="form-group-input-purpuse">
                <label for="purpuse">Wat is het doel van het bandje?</label>
                <input class="form-control huisstyle-input required" type="text" id="purpuse" name="purpuse_bandje" rows="1"
                    placeholder="Omschrijf kort en bondig het doel van het bandje">
            </div>
            <div class="form-group">
                <label for="start-date">Vanaf wanneer moet het bandje erop staan?</label>
                <div class="form-group huisstyle-input d-flex ">
                    <div class="input col-6">
                    <input class="form-control datetime-spacing required" type="date" id="start_date" name="start_date"
                            placeholder="Hier moet een datum en een tijd ingevuld worden" >
                    </div>
                    <div class="input">
                        <select class="custom-time-picker" name="hour" id="start_time_hour">
                            <!-- Generate hours -->
                        </select>
                    </div>
                        :
                    <div class="input">
                        <select class="custom-time-picker" name="minute" id="start_time_minute">
                            <!-- Generate 15-minute intervals for minutes -->
                        </select>
                    </div>
                </div> 
               
                <label for="end-date" >Tot wanneer moet het bandje erop staan?</label>
                <div id="eind_toggle">
                    <div class="form-group huisstyle-input d-flex ">
                        <div class="input col-6">
                            <input class="form-control datetime-spacing required" type="date" id="end_date" name="eind_date"
                                placeholder="Hier kan je een datum invullen.">
                        </div>
                        <div class="input">
                            <select class="custom-time-picker" name="hour2" id="end_time_hour">
                                <!-- Generate hours -->
                            </select>
                        </div>
                            :
                        <div class="input">
                            <select class="custom-time-picker" name="minute2" id="end_time_minute">
                                <!-- Generate 15-minute intervals for minutes -->
                            </select>
                        </div>
                    </div>
                </div>
            
        
                <div class="form-check">
                    <input type="checkbox" class="form-check-input m-1" id="permanent_bandje" name="permanent_bandje">
                    <label class="form-check-label size-0" for="form-check-label">Dit is een permanent bandje</label>
                </div>
            </div>

            <div class="form-group form-check ">
                <label class="d-flex justify-content-between" for="choice-menu">Zit er een keuzemenu verbonden aan dit bandje?</label>
                <div class="form-group d-flex justify-content-start huisstyle-input choicemenu" id="choicemenu" >
                    <div class="form-check keuzemenu_value_nee">
                        <!-- if checked ja: then show next question -->
                        <input class="form-check-input m-1 checkJa " type="checkbox" value="1" id="keuzemenu_value"
                            name="keuzemenu_value" >
                        <label class="form-check-label" for="keuzemenu_ja">
                            Ja
                        </label>
                    </div>

                    <div class="form-check d-flex align-items-center ">
                        <input class="form-check-input m-1 checkNee " type="checkbox" value="0" id="keuzemenu_value"
                            name="keuzemenu_value" >
                        <label class="form-check-label" for="keuzemenu_nee">
                            Nee
                        </label>
                    </div>
                </div>
            </div>
                <!-- if ja is checked dan moet de 'uitleg input' tevoorschijn komen. zo kan de gebruiker zijn doel uit leggen -->
                <div class="form-group mw-grootste-group huisstyle-input" id="dynamicMenu">
                    <label for="explanation">Leg hier zo goed mogelijk uit welke keuzes er gemaakt moeten kunnen
                        worden:</label>
                    <textarea class="form-control huisstyle-input" type="textbox" id="menu_explanation" name="menu_explanation"
                        rows="3" placeholder="Hier komt de uitleg van het keuzemenu wat u voor ogen heeft"></textarea>
                </div>
                <div class="button-wrapper">
                    <button type="button" id="to-step-2-button" name="submit"
                        class="btn btn-primary btn-lg btn-block big-button action-button" data-action="create" >Volgende</button>
                </div>
            </div>
            <div class="col"></div>
        </div>

</section>
<script>
    $(document).ready(function () {
    // Check the "Ja" checkbox
    $('.checkJa').click(function () {
        $('.checkJa').prop('checked', true);
        $('.checkNee').prop('checked', false);
        console.log("Ja is checked");
        $('#dynamicMenu').show();
        // add class to the end-date input
        $('#menu_explanation').addClass('required');
        
    });

    // Check the "Nee" checkbox
    $('.checkNee').click(function () {
        $('.checkNee').prop('checked', true);
        $('.checkJa').prop('checked', false);
        console.log("Ja is unchecked");
        $('#dynamicMenu').hide();
        $('#menu_explanation').removeClass('required');
        $('#menu_explanation').val(null);
    });
});


</script>
<script>
  // Get the current date in the format YYYY-MM-DD
  var currentDate = new Date().toISOString().split('T')[0];
  
  // Set the min attribute of the date input
  document.getElementById("end_date").min = currentDate;
</script>