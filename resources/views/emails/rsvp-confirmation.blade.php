<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<style>
  body { font-family: Georgia, serif; background: #fdf8f3; margin: 0; padding: 0; }
  .container { max-width: 600px; margin: 40px auto; background: #fff; border-radius: 12px; overflow: hidden; box-shadow: 0 4px 24px rgba(0,0,0,0.08); }
  .header { background: linear-gradient(135deg, #c9a96e, #e8d5b0); padding: 40px; text-align: center; }
  .header h1 { color: #fff; font-size: 32px; margin: 0; letter-spacing: 2px; }
  .header p { color: rgba(255,255,255,0.9); margin: 8px 0 0; font-style: italic; font-size: 16px; }
  .body { padding: 40px; }
  .body h2 { color: #8B6914; font-size: 22px; }
  .body p { color: #555; line-height: 1.8; font-size: 15px; }
  .btn { display: inline-block; margin: 24px 0; padding: 14px 36px; background: linear-gradient(135deg, #c9a96e, #a0722a); color: #fff; text-decoration: none; border-radius: 50px; font-size: 16px; letter-spacing: 1px; }
  .footer { background: #fdf8f3; padding: 24px; text-align: center; color: #aaa; font-size: 13px; border-top: 1px solid #f0e8d8; }
  .divider { border: none; border-top: 1px solid #f0e8d8; margin: 24px 0; }
</style>
</head>
<body>
<div class="container">
  <div class="header">
    <h1>♡ You're Invited ♡</h1>
    <p>{{ $rsvp->name }}, your RSVP has been confirmed</p>
  </div>
  <div class="body">
    <h2>Welcome to our special day!</h2>
    <p>Dear <strong>{{ $rsvp->name }}</strong>,</p>
    <p>
      We are so thrilled to have you join us virtually for our wedding ceremony. 
      Your unique livestream link is ready — please keep it safe and do not share it with others.
    </p>
    <hr class="divider">
    <p><strong>Your exclusive livestream access link:</strong></p>
    <a href="{{ config('app.frontend_url', 'http://localhost:5173') }}/live/{{ $rsvp->stream_token }}" class="btn">
      Watch the Ceremony Live
    </a>
    <p style="word-break:break-all; color:#c9a96e; font-size:13px;">
      {{ config('app.frontend_url', 'http://localhost:5173') }}/live/{{ $rsvp->stream_token }}
    </p>
    <hr class="divider">
    <p>
      The stream will go live on the day of the ceremony. We recommend opening your link 
      a few minutes early to settle in. We can't wait to celebrate with you!
    </p>
    <p>With love,<br><em>The Happy Couple 💛</em></p>
  </div>
  <div class="footer">
    This link was sent to {{ $rsvp->email }} · Please do not reply to this email.
  </div>
</div>
</body>
</html>
