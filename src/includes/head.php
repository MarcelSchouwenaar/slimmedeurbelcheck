<?php // head.php ?>
<!DOCTYPE html>
<html lang="nl">
<head>
    <meta charset="UTF-8">
    <title>Slimme Deurbellen Check — Check met een Goed Gesprek</title>
    <link rel="icon" type="image/png" href="/assets/logo.png">
    <link rel="shortcut icon" type="image/png" href="/assets/logo.png">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <style>
        :root {
            --background-color: #E3ECF2;
            --primary-color: #024736;
            --secondary-color: #0FA45D;
            --secondary-light-color:rgb(231, 255, 244);
            --accent-color: #D7F11A;
            --text-color: #08263B;
            --link-color: #F2700D;
            --hover-color:rgb(194, 93, 16);
            --col-width: 940px;
        }
        * {
            box-sizing: border-box;
        }
        html, body {
            margin: 0px;
            padding: 0;
            scroll-behavior: smooth;
            scroll-snap-type: y mandatory;
            scroll-margin-top: 120px;
        }
        body { 
            font-family: -apple-system, BlinkMacSystemFont, avenir next, avenir, segoe ui, helvetica neue, Adwaita Sans, Cantarell, Ubuntu, roboto, noto, helvetica, arial, sans-serif; 
            font-size: 16px;
            font-weight: 500;
            line-height: 1.6;
            color: var(--text-color);
            background-color: var(--background-color);
            padding: 0;
            margin: 20px;
            min-height: 100vh;
        }
        h1, h2, h3 {
            color: var(--primary-color);
            font-weight: bold;
            margin: 0 0 32px;
            padding: 0
        }
        h1 {
            font-size: 2.5em;
        }
        h2 {
            font-size: 2em;
        }
        a {
            color: var(--link-color);
            text-decoration: none;
        }
        a:hover {
            color: var(--hover-color);
        }

        .btn{
            display: inline-block;
            line-height: 48px;
            padding: 0 20px;
            background-color: var(--link-color);
            color: white;
            border-radius: 100px;
            text-decoration: none;
            font-weight: bold;
            box-shadow: 0 1px 4px rgba(0, 0, 0, 0.3),
            inset 0 2px 4px rgb(255, 157, 83),
            inset 0 -1px 4px rgb(106, 50, 8);
        }
        .btn:hover {
            background-color: var(--hover-color);
            color: white;
        }
        .btn-lg{
            line-height: 60px;
            padding: 0 30px;
            font-size: 1em;
            width: 100%;
            text-align: center;
            font-size: 1.2em;

        }
        nav{
            position: sticky;
            top: 20px;
            background-color: rgba(255, 255, 255, 0.8);
            backdrop-filter: blur(10px);

            color: var(--text-color);
            display: flex;
            justify-content: space-between;
            align-items: stretch;
            width: 100%;
            max-width: var(--col-width);
            margin: 20px auto;
            padding: 10px;  
            border-radius: 100px;
            z-index: 999;
        }
        .hamburger {
            display: none;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            gap: 5px;
            cursor: pointer;
            margin-right: 20px;
        }
        .hamburger span {
            height: 3px;
            width: 25px;
            background-color: var(--text-color);
            transition: 0.3s;
        }
        
        nav ul{
            list-style: none;
            padding: 0;
            margin: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            gap: 20px;
        }
        nav .logo a{
            display: flex;
            align-items: center;
            justify-content: center;
            flex-direction: row;
            gap: 10px;
        }
        nav .logo a h2{
            font-size: 1em;
            margin: 0;
            color: var(--text-color);
        }
        nav .logo img{
            height: 50px;
            width: auto;
        }
        header{
            width: 100%;
            margin: 0 auto;
            text-align: center;
        }
        aside{
            position: sticky;
            top: 120px;
            z-index: 2;
        }
        .title{
            font-size: 4em;
            margin: 60px 0px;
            color: var(--secondary-color);
            line-height: 1;
        }
        .sub-title{
            font-size: 1.2em;
            color: var(--primary-color);
            margin-bottom: 20px;
            display: block;
            max-width: 600px;
            margin: 0 auto;
        }
        .highlight{
            color: var(--primary-color);
            font-weight: bold;
        }
        .hero{
            background-color: var(--primary-color);
            width: 100%;
            max-width: var(--col-width);
            aspect-ratio: 2/1;
            background-image: url('assets/site-background.png');
            background-size: cover;
            background-position: center;
            margin: 64px auto 128px; 
            border-radius: 40px;
            display: flex;
            justify-content: center;
            align-items: center;
            flex-direction: column;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
        }
        .hero-image{
            margin-top: -80px;
            width: 300px;
            height: 500px;
            background-image: url('assets/doorbell.png');
            background-size: contain;
            background-repeat: no-repeat;
            background-position: center;
        }
        .hero-text{
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            gap: 20px;
            max-width: 440px;
            background-color: white;
            text-align: center;
            padding: 20px;
            margin: -200px auto 0px;
            border-radius: 44px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
            background-color: rgba(255, 255, 255, 0.8);
            backdrop-filter: blur(10px);

        }
        .hero-text p{
            font-size: 1.2em;
            color: var(--text-color);
            font-weight: 500;
            padding: 0;
            margin: 0;
        }
        section{
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 20px;
            background-color: var(--primary-color);
            color: white;
            width: 100%;
            max-width: var(--col-width);
            margin: 80px auto;
            padding: 40px;
            border-radius: 40px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        }
        section h2{
            color: var(--secondary-color);
            margin-bottom: 20px;
            line-height: 1.2em;
        }
        section p{
            padding: 0;
            margin: 0;
        }
        section h2 .highlight{
            color: white;
        }
        section a{
            color: var(--accent-color);
            font-weight: bold;
            text-decoration: underline;
        }
        main{
            width: 100%;
            max-width: var(--col-width);
            margin: 0 auto;
            padding: 20px;
        }
        article{
            margin: 0 auto;
            max-width: 800px;
            padding: 20px;
        }
        article h1{
            font-size: 3em;
            line-height: 1em;
            margin-bottom: 40px;
            text-align: center;
        }
        article p{
            font-size: 1.2em;
            color: var(--text-color);
            margin-bottom: 30px;
        }
        article h2{
            font-size: 2em;
            margin-bottom: 0px;
        }
        .lead{
            font-size: 1.5em;
            color: var(--text-color);
            margin-bottom: 20px;
            text-align: center;
            max-width: 640px;
            margin: 0 auto 60px;
        }
        .col2{
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 20px;
            padding: 0;
        }
        .col3{
            display: grid;
            grid-template-columns: 1fr 2fr;
            gap: 20px;
            padding: 0;
            align-items: start;
        }
        .check-form{
        }
        .check-form-aside{
            height: calc(100vh - 180px);
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            align-items: flex-start;
        }
        .check-form-aside h1{
            font-size: 2.5em;
            line-height: 1em;
            margin-bottom: 20px;
        }
        .check-form-progress{
            width: 100%;
        }
        .check-form-progress ul{
            display: flex;
            flex-direction: column;
            gap: 10px;
            list-style: none;
            padding: 0;
            margin: 0;
        }
        .check-form-progress li{
            display: flex;
            align-items: center;
            justify-content: flex-start;
            transition: all 0.3s ease;
        }
        .check-form-progress li:before{
            content: "";
            display: inline-block;
            height: 20px;
            width: 20px;
            background-color: white;
            margin-right: 10px;
            border-radius: 50%;
            border: 4px solid white;
        }
        .check-form-progress li.checked:before{
            background-color: var(--secondary-color);
        }
        .active{
            font-weight: bold;
            opacity: 1;
        }
        .checked{
            color: var(--secondary-color);
            font-weight: bold;
            opacity: 1;
        }
        .card{
            display: flex;
            flex-direction: column;
            gap:0;
            background-color: white;
            padding: 0px;
            border-radius: 40px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            overflow: hidden;
            border: 1px solid rgba(255, 255, 255, 0.05);
        }
        .card-horizontal{
            width: 100%;;
            flex-direction: row;
            column-span: all;
            grid-area: 1 / 1 / 2 / 3;
        }

        .card-header{
            background-color: var(--primary-color);
            color: white;
            padding: 20px;
            font-weight: bold;
        }
        .card-horizontal .card-header{
            flex-grow: 1;
        }
        
        .card-header h1, .card-header h2{
            color: white;
            line-height: 1em;
            margin: 0 0 2em;
        }
        .card-image{
            padding: 0;
            width: 100%;
            aspect-ratio: 1/1;
            background-size: cover;
            border-radius: 20px 20px 0 0;
        }
        .card-text{
            padding: 20px;
            color: var(--text-color);
            flex-grow: 1;
            
        }
        .card-button{
            padding: 20px;
            text-align: center;
        }

        footer{
            padding: 60px 20px;
            margin: 40px auto;
            width: 100%;
            max-width: var(--col-width);
            display: flex;
            justify-content: center;
            align-items: flex-start;
            flex-direction: row;
            gap: 20px;
            text-align: center;

        }
        footer div{
            text-align: left;
            flex-grow: 1;
            flex-basis: 0;
        }
        footer ul{
            list-style: none;
            padding: 0; 
        }

        .contact-form {
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
            background-color: white;
            border-radius: 20px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            overflow: hidden;
            border: 1px solid rgba(255, 255, 255, 0.05);
        }
        .form-section{
            max-width: 600px;
            margin: 0 auto 40px;
            padding: 20px;
            background-color: white;
            border-radius: 20px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            overflow: hidden;
            border: 1px solid rgba(255, 255, 255, 0.05);
        }
        .form-section h2{
            font-size: 1.5em;
            margin-bottom: 20px;
            color: var(--text-color);
        }

        .form-group {
            margin-bottom: 20px;
            display: flex;
            flex-direction: column;
            justify-content: flex-start;
        }
        .form-group input[type="checkbox"],
        .form-group input[type="radio"] {  
            display: inline-block;
            margin-right: 20px;
        }



        .form-group label {
            text-align: left;
            display: block;
            margin-bottom: 5px;
            font-weight: bold;
        }

        .form-group input[type="text"],
        .form-group input[type="email"],
        .form-group textarea {
            width: 100%;
            padding: 10px;
            border: none;
            background-color: var(--background-color);
            border-radius: 4px;
            font-size: 1em;
        }

        .form-group textarea {
            resize: vertical;
        }
        .form-field-required {
            color: var(--link-color);
            margin-left: 5px;
        }

        .form-card{
            max-width: 640px;
            scroll-margin-top: 140px; /* Adjust to match or exceed your nav height + desired gap */

            width: 100%;
            margin: 0 auto 40px;
            background-color: white;
            border-radius: 20px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            overflow: hidden;
            border: 1px solid rgba(255, 255, 255, 0.05);
        }

        .animation {
            width: 100%;
            aspect-ratio: 1/1;
            overflow: hidden;
            position: relative;
        }


        .form-card label:has(input[type="radio"]){
            display: flex;
            align-items: center;
            justify-content: flex-start;
            margin-bottom: 10px;
            font-weight: bold;
            /* border: 1px solid var(--text-color); */
            background-color: var(--background-color);
            padding: 8px 16px;
            border-radius: 4px;
            transition: all 0.3s ease;
        }
        /* .form-card label:has(input[type="radio"]):before{
            content: "";
            display: inline-block;
            max-width: 30px;
            max-height: 30px;
            background-color: white;
            border-radius: 50%;
            margin-right: 10px;
            border: 4px solid white;
            transition: all 0.3s ease;

        } */
        .form-card input[type="radio"]{
            /* display: none; */
        }
        /* .form-card label:has(input[type="radio"]:checked) {
            background-color: var(--secondary-color);
            color: white;
        }
        .form-card label:has(input[type="radio"]:checked):before {
            background-color: var(--primary-color);
        } */

        .form-card-header{
            background-color: var(--primary-color);
            color: white;
            aspect-ratio: 1/1;
            position: relative;
                        
        }
        .form-card-body{
            padding: 20px 20px 0;
        }
        .form-card-input{
            padding: 20px 20px 0;
        }
        .form-card-footer{
            padding: 20px;
            background-color: var(--secondary-light-color);
            border-top: 1px solid rgba(0, 0, 0, 0.1);
            text-align: center;
        }

        button[type="submit"] {
            background-color: var(--secondary-color);
            color: white;
            border: none;
            padding: 12px 20px;
            border-radius: 100px;
            cursor: pointer;
            font-size: 1em;
        }

        button[type="submit"]:hover {
            background-color: var(--hover-color);
        }
        .notification {
            margin: 0px auto 20px;
            padding: 10px 30px;
            max-width: 600px;
            
            background-color: var(--secondary-color);
            color: white;
            border-radius: 100px;
        }
        .captcha{
            border-radius: 4px;
        }
        .captcha-new{
            float: right;
            font-size: .8em;
            color: #333;
            background-color: #bbb;
            border-radius: 4px;
            padding: 4px 6px ;
        }


        @media screen and (max-width: 768px) {
            h2{
                font-size: 1.2em;
                line-height: 1em;
            }
            .btn{
                font-size: 1em;
                padding: 10px 20px;
            }
            .btn-lg{
                line-height: 1em;
                padding: 20px 50px;
            }
            nav ul{
                display: none;

            }
            nav .hamburger {
                display: flex;
            }
            article{
                padding: 0px;
            }
            article h1{
                font-size: 2.5em;
                margin: 20px 0;
            }
            article .lead{
                font-size: 1.2em;
                margin: 0 auto 40px;
                padding: 0;
            }
            .col2{
                grid-template-columns: 1fr;
            }
            .col3{
                grid-template-columns: 1fr;
            }
            aside, .check-form-aside{
                height: auto;
                position: relative;
                top: 0;
            }
            .check-form-progress{
                display: none;
             }
              .check-form{
                z-index: 998;
              }
            .hero{
                flex-direction: column;
                aspect-ratio: 3/1;
            }
            .title{
                font-size: 2.5em;
                margin: 40px;
            }
            .hero-image{
                width: 80%;
                max-height: 300px;

                aspect-ratio: 2/1;
                margin-top: -50px;
                margin-bottom: -80px;
            }
            .hero-text{
                margin-top: -60px;
                width: 100%;
            }
            .hightlight-section{
                display: flex;
                flex-direction: column;
            }
            
        }


        
    </style>
</head>
<body>