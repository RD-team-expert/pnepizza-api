<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>New Acquisition Submission</title>
</head>
<body style="margin: 0; padding: 0; font-family: Arial, sans-serif; background-color: #f4f4f4;">
<table cellpadding="0" cellspacing="0" width="100%" style="max-width: 600px; margin: 0 auto; background-color: #ffffff; border-radius: 8px; overflow: hidden;">
    <tr>
        <td style="padding: 40px 0; text-align: center; background-color: #FF5C00;">
            <h1 style="color: #ffffff; font-size: 24px; margin: 0;">You received a new acquisition submission</h1>
        </td>
    </tr>
    <tr>
        <td style="padding: 30px;">
            <table cellpadding="0" cellspacing="0" width="100%">
                <tr>
                    <td style="padding-bottom: 15px;">
                        <p style="font-size: 18px; font-weight: bold; color: #333333; margin: 0 0 5px 0;">Name:</p>
                        <p style="font-size: 16px; color: #666666; margin: 0;">{{ $acquisition->name }}</p>
                    </td>
                </tr>
                <tr>
                    <td style="padding-bottom: 15px;">
                        <p style="font-size: 18px; font-weight: bold; color: #333333; margin: 0 0 5px 0;">Email:</p>
                        <p style="font-size: 16px; color: #666666; margin: 0;">
                            <a href="mailto:{{ $acquisition->email }}" style="color: #FF5C00; text-decoration: none;">{{ $acquisition->email }}</a>
                        </p>
                    </td>
                </tr>
                <tr>
                    <td style="padding-bottom: 15px;">
                        <p style="font-size: 18px; font-weight: bold; color: #333333; margin: 0 0 5px 0;">Phone:</p>
                        <p style="font-size: 16px; color: #666666; margin: 0;">
                            <a href="tel:{{ $acquisition->phone }}" style="color: #FF5C00; text-decoration: none;">{{ $acquisition->phone }}</a>
                        </p>
                    </td>
                </tr>
                <tr>
                    <td style="padding-bottom: 15px;">
                        <p style="font-size: 18px; font-weight: bold; color: #333333; margin: 0 0 5px 0;">City:</p>
                        <p style="font-size: 16px; color: #666666; margin: 0;">{{ $acquisition->city }}</p>
                    </td>
                </tr>
                <tr>
                    <td style="padding-bottom: 15px;">
                        <p style="font-size: 18px; font-weight: bold; color: #333333; margin: 0 0 5px 0;">State:</p>
                        <p style="font-size: 16px; color: #666666; margin: 0;">{{ $acquisition->state }}</p>
                    </td>
                </tr>
                <tr>
                    <td style="padding-bottom: 15px;">
                        <p style="font-size: 18px; font-weight: bold; color: #333333; margin: 0 0 5px 0;">Status:</p>
                        <p style="font-size: 16px; color: #666666; margin: 0;">{{ $acquisition->status }}</p>
                    </td>
                </tr>
                <tr>
                    <td style="padding-bottom: 15px;">
                        <p style="font-size: 18px; font-weight: bold; color: #333333; margin: 0 0 5px 0;">Priority:</p>
                        <p style="font-size: 16px; color: #666666; margin: 0;">{{ $acquisition->priority }}</p>
                    </td>
                </tr>
                <tr>
                    <td style="text-align: center; padding: 20px 0;">
                        <a href="mailto:{{ $acquisition->email }}"
                           style="background-color: #FF5C00; color: #ffffff; padding: 10px 20px; text-decoration: none; border-radius: 5px; font-size: 16px;">
                            Reply to Contact
                        </a>
                    </td>
                </tr>
            </table>
        </td>
    </tr>
    <tr>
        <td style="padding: 15px 30px; background-color: #FF5C00; text-align: center;">
            <p style="color: #ffffff; font-size: 14px; margin: 0;">
                © 2025 One Pizza. All rights reserved.
            </p>
        </td>
    </tr>
</table>
</body>
</html>
