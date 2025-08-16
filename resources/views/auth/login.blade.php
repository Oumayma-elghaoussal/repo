<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Connexion - Bureau d'Ordre</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" />
    <style>
        body, html {
            height: 100%;
            margin: 0;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #eef2f7;
            display: flex;
            flex-direction: column;
            min-height: 100vh;
            color: #333;
        }
        .container-main {
            flex: 1;
            display: flex;
            justify-content: center;
            align-items: center;
            padding: 30px;
        }
        .login-card {
            background: white;
            padding: 40px 35px;
            border-radius: 14px;
            box-shadow: 0 12px 25px rgba(0, 0, 0, 0.1);
            width: 100%;
            max-width: 420px;
            position: relative;
            box-sizing: border-box;
        }
        .logo {
            display: block;
            margin: 0 auto 25px auto;
            width: 110px;
            height: auto;
        }
        h2 {
            text-align: center;
            margin-bottom: 30px;
            font-weight: 700;
            color: #2c3e50;
        }
        label {
            font-weight: 600;
            color: #555;
        }
        input.form-control {
            border-radius: 10px;
            border: 1.5px solid #ccc;
            padding: 11px 14px;
            font-size: 1rem;
            transition: border-color 0.3s ease, box-shadow 0.3s ease;
        }
        input.form-control:focus {
            border-color: #4a90e2;
            box-shadow: 0 0 8px rgba(74, 144, 226, 0.5);
            outline: none;
        }
        button.btn-login {
            width: 100%;
            padding: 12px;
            border-radius: 10px;
            border: none;
            background: #4a90e2;
            color: white;
            font-weight: 700;
            font-size: 1.15rem;
            cursor: pointer;
            transition: background 0.3s ease;
            margin-top: 15px;
        }
        button.btn-login:hover {
            background: #357ABD;
        }
        footer {
            background-color: #2c3e50;
            color: #eee;
            text-align: center;
            padding: 20px 15px;
            font-size: 0.9rem;
        }
        footer strong {
            color: #4a90e2;
        }
        .footer-desc {
            max-width: 700px;
            margin: auto;
            line-height: 1.5;
        }
        @media (max-width: 480px) {
            .login-card {
                padding: 30px 25px;
                max-width: 90vw;
            }
            .logo {
                width: 90px;
                margin-bottom: 20px;
            }
        } 

        @keyframes fadeInUp {
    0% {
        opacity: 0;
        transform: translateY(30px);
    }
    100% {
        opacity: 1;
        transform: translateY(0);
    }
}

.footer-desc-animated {
    animation: fadeInUp 1s ease-out forwards;
    opacity: 0;
}

    </style>
</head>
<body>

<div class="container-main">
    <div class="login-card">
        <img src="images/logo.png" alt="Logo Ministère de l'Intérieur" class="logo" />
        <h2>Connexion</h2>
        <form method="POST" action="{{ route('login') }}">
            @csrf
            <div class="mb-3">
                <label for="username">Utilisateur</label>
                <input type="text" id="username" name="username" class="form-control" placeholder="Nom d'utilisateur" required autofocus />
            </div>
            <div class="mb-4">
                <label for="password">Mot de passe</label>
                <input type="password" id="password" name="password" class="form-control" placeholder="Mot de passe" required />
            </div>
            <button type="submit" class="btn-login">Se connecter</button> 
            @if ($errors->has('identifiants'))
        <div class="alert alert-danger mt-3">
            {{ $errors->first('identifiants') }}
        </div>
    @endif
        </form>
    </div>
</div>

<footer class="text-white py-5" style="background-color: #1b1f22;">
    <style>
        .footer-desc-animated {
            opacity: 0;
            transform: translateY(30px);
            animation: fadeUp 1s ease-out forwards;
        }

        @keyframes fadeUp {
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .footer-desc-animated ul li {
            transition: transform 0.3s ease, color 0.3s ease;
        }

        .footer-desc-animated ul li:hover {
            transform: translateX(8px);
            color: #4dc0b5; /* couleur aqua/verte animée */
        }

        .footer-desc-animated strong {
            color: #4dc0b5;
        }

        .footer-desc-animated h5 {
            color: #f1f1f1;
        }

        .footer-desc-animated p,
        .footer-desc-animated ul,
        .footer-desc-animated li {
            color: #d6d6d6;
        }

        .footer-desc-animated .small {
            color: #aaa;
        }
    </style>

    <div class="container text-center footer-desc-animated">
        <img src="images/logo.png" alt="Logo" class="mb-4" style="height: 60px;" />
        <h5 class="fw-bold mb-3">Application Bureau d'Ordre – DAI</h5>
        <p class="mb-4">
            Cette application permet la gestion complète des documents administratifs via plusieurs sections :
        </p>
        <ul class="list-unstyled mb-4" style="line-height: 1.7;">
            <li>📤 <strong>Départ</strong> – Envoi des documents administratifs</li>
            <li>📥 <strong>Arrivée</strong> – Réception et enregistrement des correspondances</li>
            <li>📝 <strong>Visa</strong> – Validation des documents par les responsables</li>
            <li>🔒 <strong>Départ Confidentiel</strong> – Gestion sécurisée des documents sensibles</li>
            <li>🏛️ <strong>Départ Ministériel</strong> – Documents destinés aux autorités supérieures</li>
        </ul>
        <p class="mb-2">
            Elle assure un suivi rigoureux, confidentiel et sécurisé des échanges administratifs du Ministère de l'Intérieur.
        </p>
        <hr class="border-secondary" />
        <p class="small mb-0">
            &copy; 2025 Ministère de l'Intérieur – Direction DAI. Tous droits réservés.
        </p>
    </div>
</footer>



</body>
</html>
