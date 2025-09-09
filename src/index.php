<?php
// index.php

include 'includes/head.php';
include 'includes/nav.php';
?>

<header>
    <h1 class="title">Ik zie, ik zie... <br><span class="highlight">Soms iets té veel</span></h1>
    <p class="sub-title">Slimme deurbellen zijn handig, maar filmen vaak ook buren of voorbijgangers. Dat kan zorgen geven — en hier zijn regels over.</p>
        <div class="hero">
            <div class="hero-image"></div>   
    </div>
    <div class="hero-text">
        <p class="">Zorg dat jij en je buren het eens zijn over wat de bel in beeld heeft — en ontvang de sticker!</p>
        <a href="form.php" class="btn btn-lg">Doe de Check met een Goed Gesprek</a>
    </div>
</header>
<section class="hightlight-section">
        <h2><span class="highlight">Ons doel </span> is het verantwoord gebruik van slimme deurbellen aanmoedigen</h2>
        <p>Als Consortium Slimme Deurbellen zetten we ons in voor een goede afstemming van slimme deurbellen in de buurt. <a href="about.php">Lees meer</a></p>
</section>
<main class="col2">
    <div class="card">
        <div class="card-image" style="background-image: url('assets/check.png');"></div>
        <div class="card-text">
            <h2>Over dit initiatief</h2>
            <p>Doe de Check met een Goed Gesprek is een initiatief van het Consortium Slimme Deurbellen. We helpen je om samen met je buren te kijken naar wat slimme deurbellen filmen.</p>
            <p>We hebben een checklist gemaakt die je helpt om te kijken of de deurbellen in jouw buurt goed zijn afgesteld.</p>
        </div>
        <div class="card-button">
            <a href="form.php" class="btn btn-lg">Doe de Check</a>
        </div>
    </div>
    <div class="card">
        <div class="card-image" style="background-image: url('assets/sticker.png');"></div>
        <div class="card-text">
            <h2>Onze gratis sticker</h2>
            <p>Onze sticker is een teken dat je de Check hebt gedaan. Plak hem bij je deurbel en laat zien dat je bewust omgaat met de privacy van je buren of voorbijgangers.</p>
            <p>De sticker is gratis en kan — samen met een buur — worden aangevraagd via het formulier. We sturen hem dan naar je op.</p>
        </div>
         <div class="card-button">
            <a href="form.php" class="btn btn-lg">Vraag 'm aan</a>
        </div>
    </div>
    
</main>

<?php
include 'includes/footer.php';
?>