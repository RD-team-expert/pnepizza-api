<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Welcome to One Pizza</title>
</head>
<body style="margin: 0; padding: 0; font-family: Arial, sans-serif; background-color: #f4f4f4;">
<table cellpadding="0" cellspacing="0" width="100%" style="max-width: 600px; margin: 0 auto; background-color: #ffffff;">
    <tr>
        <td style="padding: 40px 0; text-align: center; background-color: #FF5C00;">
            <h1 style="color: #ffffff; font-size: 36px; margin: 0;">Welcome to One Pizza!</h1>
        </td>
    </tr>
    <tr>
        <td style="padding: 40px 30px;">
            <p style="font-size: 18px; line-height: 1.6; color: #333333; margin-bottom: 20px;">
                Hello {{ $contact->name }},
            </p>
            <p style="font-size: 18px; line-height: 1.6; color: #333333; margin-bottom: 20px;">
                Thank you for contacting us. We have received your message and will get back to you as soon as possible.
            </p>
            <p style="font-size: 16px; color: #555555;">
                <strong>Your Message:</strong> {{ $contact->message }}
            </p>
        </td>
    </tr>
    <tr>
        <td style="padding: 20px 30px; background-color: #FF5C00; text-align: center;">
            <p style="color: #ffffff; font-size: 14px; margin: 0;">
                © 2025 One Pizza. All rights reserved.
            </p>
        </td>
    </tr>
</table>
</body>
</html>
