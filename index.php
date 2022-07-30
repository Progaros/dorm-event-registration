<?php
require 'config.php';

$debug = false;

$germanTextFix = array(
    "linkOtherLanguage" => "?lang=en",
    "linkHome" => ".",
    "countryCode" => "de",
    "languageSelect" => "<b>DE</b>/EN",
    "registrationSuccessful" => "Registrierung erfolgreich!",
    "back" => "Zurück",
    "registrationFailed1" => "Anmeldung fehlgeschlagen (Ist der Name",
    "registrationFailedNameNeeded" => "und die Zimmernummer",
    "registrationFailed2" => " ausgefüllt?)",
    "countLinesNone" => "Keine",
    "placeholderName" => "Ganzer Name (zur Unterscheidung)",
    "roomnumber" => "Zimmernummer",
    "placeholderRoomnumber" => "z.B. 000",
    "comment" => "Kommentar",
    "register" => "Anmelden",
    "registrationClosed" => "Die Anmeldung ist geschlossen!",
    "tldr" => "tl;dr:",
    "registrations" => "Anmeldungen",
    "github" => "GitHub",
    "imprint" => "Impressum",
    "dataprivacy" => "Datenschutzerklärung",
);
$englishTextFix = array(
    "linkOtherLanguage" => ".",
    "linkHome" => "?lang=en",
    "countryCode" => "en",
    "languageSelect" => "<b>EN</b>/DE",
    "registrationSuccessful" => "Registration successful!",
    "back" => "Back",
    "registrationFailed1" => "Registration failed (Did you provide a name",
    "registrationFailedNameNeeded" => "and your room number",
    "registrationFailed2" => "?)",
    "countLinesNone" => "No",
    "placeholderName" => "Full name (for differentiation)",
    "roomnumber" => "Room number",
    "placeholderRoomnumber" => "e.g. 000",
    "comment" => "Comment",
    "register" => "Register",
    "registrationClosed" => "Registration closed!",
    "tldr" => "tl;dr:",
    "registrations" => "registrations",
    "github" => "GitHub",
    "imprint" => "Imprint",
    "dataprivacy" => "Data protection",
);

// Language selection** //
$textVariable = $germanTextVariable;
if (isset($_GET['lang']) && $_GET['lang'] == 'en')
    $textVariable = $englishTextVariable;

$textFix = $germanTextFix;
if (isset($_GET['lang']) && $_GET['lang'] == 'en')
    $textFix = $englishTextFix;
// **Language selection //

// Format multiline text** //
$textVariable["important"] = nl2br($textVariable["important"]);
$textVariable["tldr"] = nl2br($textVariable["tldr"]);
$textVariable["text"] = nl2br($textVariable["text"]);
// **Format multiline text //

if ($debug) {
    ini_set('display_errors', '1');
    ini_set('display_startup_errors', '1');
    error_reporting(E_ALL);
}
?>

<!doctype html>
<html lang="<?= $textFix["countryCode"]; ?>">

<head>
    <!-- Required meta tags** -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <!-- **Required meta tags -->

    <!-- Bootstrap CSS** -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
    <!-- **Bootstrap CSS -->

    <!-- Custom CSS** -->
    <style>
        body{
            margin: 20px;
        }
        .linkOtherLanguage{
            float: right;
        }
        .footer-link{
            text-decoration: none;
        }
        .footer-link:hover{
            text-decoration: underline;
        }
    </style>
    <!-- **Custom CSS -->

    <!-- Title** -->
    <title><?= $textVariable["headline"]; ?></title>
    <!-- **Title -->
</head>

<body>
    <!-- language select** -->
    <a href="<?= $textFix["linkOtherLanguage"]; ?>" class="linkOtherLanguage">
        <button type="button" class="btn btn-light"> <?= $textFix["languageSelect"]; ?></button>
    </a>
    <!-- **language select -->

    <!-- headline** -->
    <h1><?= $textVariable["headline"]; ?></h1>
    <!-- **headline -->

    <?php
    /* form filled out** */
    if (isset($_GET['name'])) {
        //name set and valid && if number needed also number set and valid
        if ((is_string($_GET['name']) && strlen($_GET['name']) >= 3)
            && $config["roomnumberNeeded"] == (isset($_GET['number']) && (floor($_GET['number']) == $_GET['number']) && ($_GET['number'] < 520 && ($_GET['number'] > 0)))
        ) {
            //permissions xxx xxx rw- for other (0666)
            // handle file
            $myfile = fopen("registrations.txt", "a") or die("Unable to open file!");
            $txt = $_GET['name'];
            if (isset($_GET['number']) && is_numeric($_GET['number']))
                $txt .= " [" . $_GET['number'] . "]";
            if (is_string($_GET['comment']) && strlen($_GET['comment']) >= 1)
                $txt .= " (" . $_GET['comment'] . ")";
            fwrite($myfile, "\n" . $txt);
            fclose($myfile); ?>
            <!-- registration successful** -->
            <p><?= $textFix["registrationSuccessful"]; ?></p>
            <button class="btn btn-primary" onclick="window.location.replace('<?= $textFix["linkHome"]; ?>');"><?= $textFix["back"]; ?></button>
            <!-- **registration successful -->
        <?php } else { ?>
            <!-- registration failed** -->
            <p><?= $textFix["registrationFailed1"] . " "; ?>
                <?php if ($config["roomnumberNeeded"]) {
                    echo $textFix["registrationFailedNameNeeded"];
                } ?><?= $textFix["registrationFailed2"]; ?></p>
            <button class="btn btn-primary" onclick="window.location.replace('<?= $textFix["linkHome"]; ?>');"><?= $textFix["back"]; ?></button>
            <!-- **registration failed -->
        <?php }
        /* **form filled out */
    } else {
        /* count registrations** */
        $filename = getcwd() . "/registrations.txt";
        $countlines = count(array_unique(file($filename, FILE_IGNORE_NEW_LINES))) - 1;
        if ($countlines <= 0)
            $countlines = $textFix["countLinesNone"];
        /* **count registrations */
        ?>

        <!-- Registration form** -->
        <?php if (date("Y-m-d") < $config["registrationCloseDate"]) { //TODO: add time 
        ?>

            <!-- Registration open** -->
            <form action=".">
                <input type="hidden" name="lang" value="<?= $textFix["countryCode"]; ?>" />

                <!-- Name** -->
                <div class="form-group">
                    <label for="name">Name</label>
                    <input type="text" class="form-control" id="name" placeholder="<?= $textFix["placeholderName"]; ?>" name="name">
                </div>
                <!-- **Name -->

                <!-- Roomnumber** -->
                <?php if ($config["roomnumberNeeded"]) { ?>
                    <div class="form-group">
                        <label for="roomnumber"><?= $textFix["roomnumber"]; ?></label>
                        <input type="number" class="form-control" id="roomnumber" placeholder="<?= $textFix["placeholderRoomnumber"]; ?>" name="number">
                    </div>
                <?php } ?>
                <!-- **Roomnumber -->

                <!-- Comment** -->
                <div class="form-group">
                    <label for="comment"><?= $textFix["comment"]; ?></label>
                    <input type="comment" class="form-control" id="comment" placeholder="optional" name="comment">
                </div>
                <!-- **Comment -->

                <br>
                <button type="submit" class="btn btn-primary">
                    <?= $textFix["register"]; ?>
                </button>
            </form>
            <!-- **Registration open -->

        <?php } else { ?>
            <br>

            <!-- Registration closed** -->
            <div class="alert alert-info" role="alert">
                <?= $textFix["registrationClosed"]; ?>
            </div>
            <!-- **Registration closed -->

        <?php } ?>
        <!-- **Registration form -->

        <div>
            <br>

            <!-- Important** -->
            <?php if ($textVariable["important"]) { ?><br>
                <div class="alert alert-danger" role="alert">
                    <?= $textVariable["important"]; ?>
                </div><br> <?php } ?>
            <!-- **Important -->

            <br>

            <!-- TLDR** -->
            <?php if ($textVariable["tldr"]) { ?>
                <button class="btn btn-secondary" type="button" data-toggle="collapse" data-target="#collapseExample" aria-expanded="false" aria-controls="collapseExample">
                    <?= $textFix["tldr"]; ?>
                </button>
                <br>
                <br>
                <div class="collapse" id="collapseExample">
                    <div class="card card-body">
                        <p class="card-text">
                            <?= $textVariable["tldr"]; ?>
                        </p>
                    </div>
                </div>

                <br>
            <?php } ?>
            <!-- **TLDR -->

            <!-- Text** -->
            <div class="card card-body">
                <p class="card-text">
                    <?= $textVariable["text"]; ?>
                </p>
            </div>
            <!-- **Text -->

            <br>


        </div>
        <footer>
            <!-- Registration Count** -->
            <p class="text-center text-muted">
                <?php echo $countlines . " " . $textFix["registrations"] ?>
            </p>
            <!-- **Registration Count -->

            <br>

            <!-- Legal** -->
            <p class="text-center text-muted">
                <a href="https://github.com/Progaros/dorm-event-registration" class="link-secondary footer-link">
                    <?= $textFix["github"]; ?>
                </a>
                <span>&nbsp;|&nbsp;</span>
                <a href="/apian/imprint.html" class="link-secondary footer-link">
                    <?= $textFix["imprint"]; ?>
                </a>
                <span>&nbsp;|&nbsp;</span>
                <a href="/apian/dataprivacy.html" class="link-secondary footer-link">
                    <?= $textFix["dataprivacy"]; ?>
                </a>
            </p>
            <!-- **Legal -->

        </footer>
    <?php } ?>

    <!-- script** -->
    <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.12.9/dist/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>
    <!-- **script -->

</body>

</html>
