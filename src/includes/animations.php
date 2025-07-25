<?php 

$animation1 = <<<HTML
<div id="animation1" class="animation">
    <style>
        #anim1-background{
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 150%;
            background-image: url('assets/anim1-background.png');
            background-size: cover;
            /* animation: anim1 5s infinite; */
        }
        .animate #anim1-background{
            animation: anim1 5s;
            animation-fill-mode: infinite;

        }
        #anim1-gradient {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 10%;
            background: linear-gradient(to bottom, rgba(0, 0, 0, 1),  rgba(0, 0, 0, 1), rgba(0, 0, 0, 0));
            /* animation: anim2 5s infinite; */
        }
        .animate #anim1-gradient {
            animation: anim2 5s;
            animation-fill-mode: infinite;

        }
        @keyframes anim1 {
            0% { transform: translateX(0); }
            20% { transform: translateY(0); }
            100% { transform: translateY(-33%); }
        }
        @keyframes anim2 {
            0% { opacity: 0.5; height: 10%}
            20% { opacity: 0.5; height: 10%}
            100% { opacity: 1; height: 50%}
        }
    </style>
    <!-- animation of neighbor disappearing behind the gradient -->
    <div id="anim1-background"></div> 
    <div id="anim1-gradient"></div>
</div>
HTML;

$animation2 = <<<HTML
<div id="animation2" class="animation">
    <style>
        #anim2-background {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-image: url('assets/anim2-background.png');
            background-size: cover;
        }
        #anim2-blur {
            position: absolute;
            display: flex;
            justify-content: flex-end;
            align-items: flex-end;
            top: 10%;
            left: 20%;
            width: 10%;
            height: 10%;
            padding: 10px;
            font-size: 2em;
            color: white;
            background-color: rgba(255, 255, 255, 0.25);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.5);
            border-radius: 20px;
            /* animation: anim3 5s infinite; */
        }
        .animate #anim2-blur {
            animation: anim3 5s ease-in-out alternate;
            animation-fill-mode: infinite;

        }
        @keyframes anim3 {
            0% { opacity: 1; }
            50% { opacity: 1; }
            100% { opacity: 1;  width: 55%; height: 55%;}
        }
    </style>
    <!-- animation of a biker being blurred -->
    <div id="anim2-background"></div>
    <div id="anim2-blur"></div>
</div>
HTML;

$animation3 = <<<HTML
<div id="animation3" class="animation">
    <style>
        #anim3-background {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-image: url('assets/anim3-background.png');
            background-size: cover;
        }
        #anim3-focus1, #anim3-focus2 {
            background-image: url('assets/focus.png');
            background-size: cover;
            position: absolute;
            width: 40%;
            height: 40%;
        }
        #anim3-focus1 {
            animation: anim4 1s infinite alternate;
            top: 40%;
            left: 0%;
        }
         #anim3-focus2 {
            animation: anim4 1s infinite alternate;
            top: 15%;
            left: 55%;
        }
        @keyframes anim4 {
            0% { transform: scale(1); }
            100% { transform: scale(1.2); }
        }


    </style>
    <!-- animation of focus on property -->
    <div id="anim3-background"></div>
    <div id="anim3-focus1"></div>
    <div id="anim3-focus2"></div>
</div>
HTML;

$animation4 = <<<HTML
<style>
    #anim4-background {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background-image: url('assets/anim4-background.png');
        background-size: cover;
    }
    #anim4-thumb {
        position: absolute;
        top: 54.8%;
        left: 26.5%;
        width: 75%;
        height: 75%;
        background-image: url('assets/anim4-arm.png');
        background-size: cover;
        animation: anim5 1s infinite alternate;
    }
    @keyframes anim5 {
        0% { transform: rotate(-10deg); }
        100% { transform: rotate(10deg); }
    }
    </style>
<div id="animation4" class="animation">
    <!-- animation of a neighbor giving thumbs up -->
    <div id="anim4-background"></div>
    <div id="anim4-thumb"></div>
</div>
HTML;

function get_animation($id){
    global $animation1, $animation2, $animation3, $animation4;
    $animations = [
        1 => $animation1,
        2 => $animation2,
        3 => $animation3,
        4 => $animation4        
        
    ];
    
    if (array_key_exists($id, $animations)) {
        return $animations[$id];
    } else {
        return '';
    }

};

?>
