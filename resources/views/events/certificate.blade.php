<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Certificate - {{ $event->title }}</title>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Great+Vibes&display=swap');
        body {
            font-family: 'Arial', sans-serif;
            text-align: center;
            padding: 50px;
            background: #fdfdfd;
        }
        .certificate-container {
            border: 10px solid #4CAF50;
            padding: 50px;
            border-radius: 20px;
            position: relative;
        }
        /* Logo en haut à droite */
        .logo {
            position: absolute;
            top: 20px;
            right: 20px;
            width: 120px; /* ajuster selon besoin */
            height: auto;
        }
        h1 {
            font-size: 50px;
            margin-bottom: 0;
            color: #4CAF50;
        }
        h2 {
            font-family: 'Great Vibes', cursive;
            font-size: 40px;
            margin-top: 10px;
        }
        p {
            font-size: 18px;
            margin: 20px 0;
        }
        .footer {
            margin-top: 40px;
            font-size: 14px;
            color: #555;
        }
    </style>
</head>
<body>
    <div class="certificate-container">
        <!-- Logo -->

        <h1>Certificate of Participation</h1>
        <p>This certificate is proudly presented to</p>
        <h2>{{ $participant->name }}</h2>
        <p>for actively participating and contributing to the success of this event</p>
        <h2>{{ $event->title }}</h2>
        <p>on {{ $event->date->format('F d, Y') }} at {{ $event->location ?? 'Online' }}.</p>
        <div class="footer">
            EcoEvents &copy; {{ date('Y') }} | {{ url('/') }}
        </div>
    </div>
</body>
</html>
