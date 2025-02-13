<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Welcome to One Pizza</title>
</head>
<body style="margin: 0; padding: 0; font-family: Arial, sans-serif; background-color: #f4f4f4;">
<table cellpadding="0" cellspacing="0" width="100%" style="max-width: 600px; margin: 0 auto; background-color: #ffffff; border-radius: 8px; overflow: hidden;">
    <tr>
        <td style="padding: 40px 0; text-align: center; background-color: #FF5C00;">
            <h1 style="color: #ffffff; font-size: 28px; margin: 0;">Welcome to One Pizza!</h1>
        </td>
    </tr>
    <tr>
        <td style="padding: 30px;">
            <p style="font-size: 18px; line-height: 1.6; color: #333333; margin-bottom: 20px;">
                Hello {{ $acquisition->name }},
            </p>
            <p style="font-size: 18px; line-height: 1.6; color: #333333; margin-bottom: 20px;">
                Thank you for reaching out to One Pizza. We have received your information and will get back to you shortly.
            </p>
            <p style="font-size: 16px; color: #555555; margin-bottom: 10px;">
                <strong>Here’s what we received from you:</strong>
            </p>
            <table cellpadding="0" cellspacing="0" width="100%" style="border-collapse: collapse;">
                <tr>
                    <td style="padding: 8px; font-size: 16px; color: #333333;"><strong>Email:</strong></td>
                    <td style="padding: 8px; font-size: 16px; color: #666666;">
                        <a href="mailto:{{ $acquisition->email }}" style="color: #FF5C00; text-decoration: none;">{{ $acquisition->email }}</a>
                    </td>
                </tr>
                <tr>
                    <td style="padding: 8px; font-size: 16px; color: #333333;"><strong>Phone:</strong></td>
                    <td style="padding: 8px; font-size: 16px; color: #666666;">
                        <a href="tel:{{ $acquisition->phone }}" style="color: #FF5C00; text-decoration: none;">{{ $acquisition->phone }}</a>
                    </td>
                </tr>
                <tr>
                    <td style="padding: 8px; font-size: 16px; color: #333333;"><strong>Info:</strong></td>
                    <td style="padding: 8px; font-size: 16px; color: #666666;">{{ $acquisition->info }}</td>
                </tr>
            </table>
            <p style="font-size: 18px; line-height: 1.6; color: #333333; margin-top: 20px;">
                We appreciate your interest in One Pizza. If you have any questions, feel free to <a href="mailto:support@onepizza.com" style="color: #FF5C00; text-decoration: none;">contact us</a>.
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
