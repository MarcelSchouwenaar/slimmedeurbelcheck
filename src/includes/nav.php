<?php // nav.php ?>
<script>
    document.addEventListener("DOMContentLoaded", function() {
        const hamburger = document.querySelector(".hamburger");
        const navLinks = document.querySelector("#navLinks");
        const closeBtn = document.querySelector(".close");
        
        hamburger.addEventListener("click", function() {
            navLinks.style.display = "block";
            setTimeout(() => {
                navLinks.classList.toggle("active");
            }, 10);
        });
        closeBtn.addEventListener("click", function() {
            navLinks.classList.remove("active");
            navLinks.addEventListener("transitionend", function() {
                navLinks.style.display = "none";
            }, { once: true });
        });
    });
</script>
<style>
    .close{
        color: white;
        float: right;
        font-size: 30px;
        padding: 20px;
    }

    #navLinks{
        display: none;
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background-color: rgba(0, 0, 0, 0.8);
        z-index: 1000;
        overflow-y: auto;
        backdrop-filter: blur(10px);
        transition: all .5s ease-in-out;
        opacity: 0;
        transform: translateY(-2%);

    }
    #navLinks.active {
        opacity: 1;
        transform: translateY(0%);
    }
    #navLinks ul {
        list-style: none;
        display: flex;
        flex-direction: column;
        align-items: center;
        padding: 20px;
        margin: 50px;
    }
    #navLinks li a {
        font-size: 2em;
        font-weight: 400;
        margin: 20px 0;
    }
</style>
<div id="navLinks" class="">
    <span class="close">&times;</span>
    <ul>
        <li><a href="index.php">Home</a></li>
        <li><a href="about.php">Over</a></li>
        <li><a href="contact.php">Contact</a></li>
        <li><a href="privacy.php">Privacybeleid</a></li>
        <li><a href="form.php" class="btn btn-lg">Doe de check</a></li>
    </ul>
</div>

<nav>

        <div class="logo">
            <a href="index.php">
                <img src="assets/logo.png" alt="Doe de Check Logo">
                <h2>Slimme Deurbellen Check</h2>
            </a>
        </div>
        <div class="hamburger">
            <span></span>
            <span></span>
            <span></span>
        </div>
        <ul>
            <li><a href="index.php">Home</a></li>
            <li><a href="about.php">Over</a></li>
            <li><a href="contact.php">Contact</a></li>
            <li><a href="privacy.php">Privacybeleid</a></li>
            <li><a href="form.php" class="btn">Doe de check</a></li>
        </ul>

</nav>