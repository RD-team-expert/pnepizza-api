<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>New Contact Form Submission</title>
</head>
<body style="margin: 0; padding: 0; font-family: Arial, sans-serif; background-color: #f4f4f4;">
<table cellpadding="0" cellspacing="0" width="100%" style="max-width: 600px; margin: 0 auto; background-color: #ffffff;">
    <tr>
        <td style="padding: 40px 0; text-align: center; background-color: #FF5C00;">
            <h1 style="color: #ffffff; font-size: 28px; margin: 0;">You received a new contact form submission</h1>
        </td>
    </tr>
    <tr>
        <td style="padding: 40px 30px;">
            <table cellpadding="0" cellspacing="0" width="100%">
                <tr>
                    <td style="padding-bottom: 20px;">
                        <p style="font-size: 18px; font-weight: bold; color: #333333; margin: 0 0 5px 0;">Name:</p>
                        <p style="font-size: 16px; color: #666666; margin: 0;">{{ $contact->name }}</p>
                    </td>
                </tr>
                <tr>
                    <td style="padding-bottom: 20px;">
                        <p style="font-size: 18px; font-weight: bold; color: #333333; margin: 0 0 5px 0;">Email:</p>
                        <p style="font-size: 16px; color: #666666; margin: 0;">{{ $contact->email }}</p>
                    </td>
                </tr>
                <tr>
                    <td>
                        <p style="font-size: 18px; font-weight: bold; color: #333333; margin: 0 0 5px 0;">Message:</p>
                        <p style="font-size: 16px; color: #666666; margin: 0; line-height: 1.6;">{{ $contact->message }}</p>
                    </td>
                </tr>
            </table>
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
