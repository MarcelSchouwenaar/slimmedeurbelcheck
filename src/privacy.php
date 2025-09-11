<?php // about.php ?>
<?php include 'includes/head.php'; ?>
<?php include 'includes/nav.php'; ?>
<main>
<h1>Privacy</h1>
<p>Wij vinden privacy belangrijk. Op deze pagina leggen we uit welke gegevens we verzamelen, waarom we dat doen en hoe we ermee omgaan.</p>

<h2>Sticker aanvragen</h2>
<p>Als je via onze website een sticker aanvraagt, vragen we om je adresgegevens (straat, huisnummer, toevoeging en postcode).
Deze gegevens gebruiken we alleen om de sticker naar je op te sturen.</p>
<p>Nadat de sticker is verstuurd, worden je gegevens automatisch geanonimiseerd. Alleen een willekeurige code en de eerste vier cijfers van je postcode blijven bewaard. Dat doen we zodat we later kunnen analyseren hoeveel mensen in een bepaald gebied meedoen, zonder dat we nog weten wie je bent.</p>

<h2>Opslag en beveiliging</h2>
<p>De gegevens worden opgeslagen in een beveiligde database op een server onder beheer van  het AMS Institute.
De gegevens worden niet gedeeld met derden.
We gebruiken geen externe diensten voor e-mail of verwerking van je gegevens.</p>

<h2>Websitebezoek</h2>
<p>We meten op een eenvoudige manier hoeveel mensen onze site bezoeken. Dit doen we met <a href="http://simpleanalytics.com" target="_blank">SimpleAnalytics</a>, een privacyvriendelijke dienst uit Nederland.
SimpleAnalytics gebruikt geen cookies.
Er worden geen persoonlijke gegevens verzameld.</p>

<h2>Jouw rechten</h2>
<p>Wil je weten welke gegevens we van je hebben of wil je dat we ze verwijderen? Neem dan contact met ons op via<br>
📧 <a href="contact.php"> ons contactformulier.</a></p>

<h2>Vragen?</h2>
<p>Heb je vragen over deze privacyverklaring of over hoe we met gegevens omgaan? 
<span id="email-link"></span>
<script>
    // Spam-safe email rendering
    (function() {
        var user = 'info';
        var domain = 'slimmedeurbelcheck.nl';
        var gibbon = user + '@' + domain;
        var link = '<a href="mailto:' + gibb + '">Mail ons gerust.</a>';
        document.getElementById('email-link').innerHTML = link;
    })();
</script>
</p>
</main>
<?php include 'includes/footer.php'; ?>
