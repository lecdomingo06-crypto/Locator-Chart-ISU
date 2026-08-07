<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Student Account Approved</title>
</head>
<body style="font-family: Arial, sans-serif; color: #123524; line-height: 1.6;">
    <h2 style="color: #0c5c38;">Your student account was approved</h2>

    <p>Hello {{ $registration->full_name }},</p>

    <p>Your student account request has been approved. You can now log in to the Professor Tracking System.</p>

    <p>
        <strong>Student ID:</strong> {{ $registration->student_id }}<br>
        <strong>Email:</strong> {{ $registration->email }}
    </p>

    <p>Please use your student ID and the password you created during registration.</p>

    <p>Thank you.</p>
</body>
</html>
