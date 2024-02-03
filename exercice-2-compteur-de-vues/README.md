# Exercice 2 - Compteur de vues

## Installation

1. Copier le répertoire `myviewscounter`dans le répertoire `wp-content/plugins` d'une installation WordPress.

2. Depuis le back-offiche de WordPress, activer le plug-in.

## Utilisation

Consulter un article, une page ou un custom post via le front-office pour incrémenter la valeur de sa métadonnée "views_count". Si cette métadonnée n'existe pas sur le post consulté, elle est automatiquement créée à la première consultation du post après l'activation du plug-in.

Une fonction `myviewscounter_get_most_viewed_posts()` est fournie pour récupérer les posts les plus consultés, indépendamment de leur type.

