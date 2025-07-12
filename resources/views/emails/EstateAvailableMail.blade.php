<!DOCTYPE html>
<html>
<head>
    <title>Welcome to {{ config('app.name') }}</title>
    <style>
        body { font-family: 'Helvetica Neue', Arial, sans-serif; line-height: 1.6; color: #333; max-width: 600px; margin: 0 auto; padding: 20px; }
        .header { text-align: center; padding: 20px 0; border-bottom: 1px solid #eee; }
        .logo { max-width: 150px; }
        .content { padding: 20px 0; }
        .button {
            display: inline-block;
            padding: 10px 20px;
            background-color: #4F46E5;
            color: white !important;
            text-decoration: none;
            border-radius: 5px;
            font-weight: bold;
            margin: 15px 0;
        }
        .footer {
            margin-top: 30px;
            padding-top: 20px;
            border-top: 1px solid #eee;
            text-align: center;
            font-size: 12px;
            color: #777;
        }
    </style>
</head>
<body>
    <div class="header">
        @if(config('app.logo_url'))
            <img src="{{ config('app.logo_url') }}" alt="{{ config('app.name') }}" class="logo">
        @else
            <h1>{{ config('app.name') }}</h1>
        @endif
    </div>

    <div class="content">
        <h2>Welcome, {{ $user->name }}!</h2>

        <p>We Are Happy to Notify That the Estate You Interested in is Available Now ! </p>

        <p>Here's what you can do next:</p>

        <a href="{{ route('estate.show', $estate->id) }}" class="button">Go To Estate</a>

        <p>If you have any questions, feel free to reply to this email or contact our support team.</p>

        <p>Happy property hunting!</p>
        <p><strong>The {{ config('app.name') }} Team</strong></p>
    </div>

    <div class="footer">
        © {{ date('Y') }} {{ config('app.name') }}. All rights reserved.<br>
        <a href="{{ url('/') }}">Privacy Policy</a> |
        <a href="{{ url('/') }}">Terms of Service</a>
    </div>
</body>
</html>
