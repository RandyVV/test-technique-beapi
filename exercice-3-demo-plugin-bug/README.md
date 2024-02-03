# Exercice 3 - Bug du plug-in sur l'authentification

## Installation

1. Copier le fichier `demo-plugin.php` dans le répertoire `wp-content/plugins` d'une installation WordPress.

2. Depuis le back-offiche de WordPress, activer le plug-in "Contrainte de connexion".

## Procédure de reproduction du bug

1. Dans le back-office de WordPress, créer un utilisateur avec pour adresse e-mail "toto@kikoulol.fr".

2. Dans une nouvelle session de navigation (navigation privée ou après s'être déconnecté du back-office par exemple), tenter de se connecter en tant que ce nouvel utilisateur.

3. La connexion échoue avec pour message d'erreur : "Uniquement des mails .FR autorisés".

## Résolution du bug

Dans le fichier `demo-plugin.php`, le message d'erreur constaté devrait être donné seulement lorsque la sous-chaîne `'.fr'` n'est pas trouvée par la fonction `stripos()` dans l'adresse e-mail de l'utilisateur reconnu par WordPress. Ce message est émis par la fonction auth_check_mail_extension()` appelée comme filtre à l'authentification d'un utilisateur.

Or, la condition vérifie en fait que `stripos()` retourne autre chose que la valeur `false`, c'est-à-dire qu'elle détecte que l'adresse e-mail de l'utilisateur contient bien le `.fr` ! Par conséquent, il faut tester que `stripos()` renvoie exactement `false`, c'est-à-dire qu'elle ne retrouve pas le `.fr` dans l'adresse e-mail.

Ensuite, la fonction `auth_check_mail_extension()` retourne la valeur `true` alors que, en cas de réussite de l'authentification, WordPress s'attend à ce que les fonctions appelées pour le filtre `authenticate` retournent l'objet `WP_User`. Il faut donc remplacer `return true` par `return $user` dans la fonction.

