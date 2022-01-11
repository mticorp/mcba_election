<!doctype html>
<html lang="{{ app()->getLocale() }}">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>Access Denied</title>

        <!-- Fonts -->
        <!-- <link href="https://fonts.googleapis.com/css?family=Raleway:100,600" rel="stylesheet" type="text/css"> -->

        <!-- Styles -->
        <style>
            html, body {
                background-color: #EAF0FE;
                color: #000;
                font-family: 'Raleway', sans-serif;
                font-weight: 100;
                height: 100vh;
                margin: 0;
            }

            .full-height {
                height: 100vh;
            }

            .flex-center {
                align-items: center;
                display: flex;
                justify-content: center;
                flex-direction: column;
            }

            .position-ref {
                position: relative;
            }

            .top-right {
                position: absolute;
                right: 10px;
                top: 18px;
            }

            .content {
                text-align: center;
            }

            .title {
                font-size: 50px;
            }

            .links > a {
                color: #636b6f;
                padding: 0 25px;
                font-size: 12px;
                font-weight: 600;
                letter-spacing: .1rem;
                text-decoration: none;
                text-transform: uppercase;
            }

            .m-b-md {
                margin-bottom: 30px;
            }
            a{
                color: #e4de1a;
            }

            .image{
                display: flex;
                justify-content: center;
                align-items: center;            
            }

            .image img{
                margin-top: 5%;
                width: 30vw;
                height: auto;
            }

            @media screen and (max-width: 676px) {
                .image img {
                    margin-top: 20%;
                    width: 100%;
                    margin-bottom: 5px;
                }
            }
        </style>
    </head>
    <body>
        <div class="position-ref full-height">
            <div class="image">
                <img src="{{ url('images/voting.jpg') }}" alt="">
            </div>
            <div class="content flex-center">
				<div class="title m-b-md">
                   VoterID မှားယွင်းနေပါသည်။ <strong> Access Denied!</strong><br>
				</div>
            </div>
        </div>
    </body>
</html>
