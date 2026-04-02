<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Vérification de votre email</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            color: #333;
            background-color: #f4f4f4;
        }
        .container {
            max-width: 600px;
            margin: 0 auto;
            background-color: #ffffff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }
        .header {
            text-align: center;
            padding-bottom: 20px;
            border-bottom: 2px solid #007bff;
        }
        .header h1 {
            color: #007bff;
            margin: 0;
        }
        .content {
            padding: 20px 0;
        }
        .content p {
            margin: 15px 0;
        }
        .cta-button {
            display: inline-block;
            padding: 12px 30px;
            background-color: #007bff;
            color: #ffffff;
            text-decoration: none;
            border-radius: 5px;
            margin-top: 20px;
            font-weight: bold;
        }
        .cta-button:hover {
            background-color: #0056b3;
        }
        .footer {
            text-align: center;
            padding-top: 20px;
            border-top: 1px solid #e0e0e0;
            font-size: 12px;
            color: #666;
        }
        .verification-link {
            word-break: break-all;
            background-color: #f9f9f9;
            padding: 10px;
            border-left: 3px solid #007bff;
            margin-top: 15px;
            font-size: 12px;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>Bienvenue, {{ $user->name }}!</h1>
        </div>

        <div class="content">
            <p>Merci de vous être inscrit. Pour activer votre compte et accéder à tous les services, veuillez vérifier votre adresse email en cliquant sur le bouton ci-dessous :</p>

            <a href="{{ $verificationUrl }}" class="cta-button">Vérifier mon email</a>

            <p style="margin-top: 30px; font-size: 14px; color: #666;">
                Si le bouton ci-dessus ne fonctionne pas, vous pouvez copier et coller ce lien dans votre navigateur :
            </p>

            <div class="verification-link">
                {{ $verificationUrl }}
            </div>

            <p style="margin-top: 20px; font-size: 14px;">
                Ce lien expirera dans 24 heures pour des raisons de sécurité.
            </p>

            <p style="margin-top: 20px; font-size: 14px;">
                Si vous n'avez pas créé ce compte, veuillez ignorer cet email.
            </p>
        </div>

        <div class="footer">
            <p>© 2026 Tous droits réservés. Cet email a été envoyé à {{ $user->email }}</p>
        </div>
    </div>
</body>
</html>
