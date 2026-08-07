<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Student Account Request Declined</title>
</head>
<body style="font-family: Arial, sans-serif; color: #123524; line-height: 1.6;">
    <h2 style="color: #b42318;">Your student account request was declined</h2>

    <p>Hello {{ $registration->full_name }},</p>

    <p>Your student account request for the Professor Tracking System was declined.</p>

    @if($registration->decline_reason)
        <p><strong>Reason:</strong> {{ $registration->decline_reason }}</p>
    @endif

    <p>If you think this was a mistake, please contact the school administrator.</p>

    <p>Thank you.</p>
</body>
</html>
