<!DOCTYPE html>
<html>
<head>
    <title>Submission Accepted</title>
</head>
<body>
    <p>Dear {{ $submission->name }},</p>

    <p>Congratulations! Your submission has been accepted.</p>
    
    <p>You have been registered on our platform. Please use the token below to set up your password:</p>
    <p><strong>Password Setup Token:</strong> {{ $token }}</p>

    <p>Note: This token will expire in 24 hours. Use this token to set up your password in the Quiz App.</p>
    
    <p>If you did not request this, please ignore this email.</p>

    <p>Best regards,<br>Quiz App Team</p>
</body>
</html>
