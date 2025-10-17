# Système de Gestion des Commandes - EcoEvents

## Vue d'ensemble

Le système de gestion des commandes permet aux utilisateurs de :
- Consulter l'historique de leurs commandes
- Voir les détails de chaque commande
- Annuler des commandes (si le statut le permet)
- Ajouter des commentaires aux commandes
- Filtrer et rechercher dans leurs commandes

## Structure des fichiers

### Modèles
- `app/Models/Order.php` - Modèle principal des commandes
- `app/Models/OrderItem.php` - Modèle des articles de commande
- `app/Models/User.php` - Relation ajoutée pour les commandes

### Contrôleurs
- `app/Http/Controllers/OrderController.php` - Gestion des commandes utilisateur

### Vues
- `resources/views/orders/index.blade.php` - Liste des commandes avec filtres
- `resources/views/orders/show.blade.php` - Détails d'une commande

### Routes
- `/orders` - Liste des commandes de l'utilisateur
- `/orders/{id}` - Détails d'une commande
- `/orders/{id}/comment` - Ajouter un commentaire
- `/orders/{id}/cancel` - Annuler une commande

## Fonctionnalités

### 1. Historique des commandes
- Affichage paginé des commandes
- Filtrage par statut (En attente, En cours, Expédiée, Livrée, Annulée)
- Recherche par numéro de commande
- Affichage du statut avec badges colorés
- Informations de livraison et paiement

### 2. Détails de commande
- Vue complète des informations de commande
- Liste détaillée des articles avec images
- Timeline de suivi de commande
- Possibilité d'ajouter/modifier des commentaires
- Bouton d'annulation (si applicable)

### 3. Gestion des statuts
- **En attente** (pending) - Commande créée, en attente de traitement
- **En cours de traitement** (processing) - Commande en préparation
- **Expédiée** (shipped) - Commande envoyée
- **Livrée** (delivered) - Commande livrée
- **Annulée** (cancelled) - Commande annulée

### 4. Sécurité
- Middleware d'authentification sur toutes les routes
- Vérification que l'utilisateur peut accéder à ses propres commandes
- Protection CSRF sur tous les formulaires

## Accessors du modèle Order

### `getFormattedTotalAttribute()`
Retourne le total formaté avec le symbole dollar.

### `getStatusLabelAttribute()`
Retourne le statut traduit en français.

### `getStatusClassAttribute()`
Retourne la classe CSS Bootstrap pour le badge de statut.

### `canBeCancelled()`
Vérifie si la commande peut être annulée (statuts pending ou processing).

### `getTotalItemsAttribute()`
Retourne le nombre total d'articles dans la commande.

## Navigation

Le lien "Mes commandes" est disponible dans le menu déroulant du profil utilisateur (header.blade.php).

## Styles CSS

Le fichier `public/css/orders.css` contient tous les styles personnalisés pour :
- Cartes de commande avec effets hover
- Badges de statut colorés
- Timeline de suivi
- Boutons d'action
- Notifications
- Design responsive

## JavaScript

### Fonctionnalités AJAX
- Annulation de commande sans rechargement de page
- Ajout de commentaires en temps réel
- Notifications toast pour les actions

### Gestion des erreurs
- Affichage des messages d'erreur
- Réactivation des boutons en cas d'échec
- Validation côté client

## Utilisation

### Pour l'utilisateur
1. Se connecter à son compte
2. Cliquer sur son profil → "Mes commandes"
3. Utiliser les filtres pour trouver une commande
4. Cliquer sur "Voir les détails" pour plus d'informations
5. Ajouter des commentaires si nécessaire
6. Annuler une commande si le statut le permet

### Pour le développeur
```php
// Obtenir les commandes d'un utilisateur
$user = User::find(1);
$orders = $user->orders()->with('items.product')->get();

// Vérifier si une commande peut être annulée
if ($order->canBeCancelled()) {
    // Logique d'annulation
}

// Obtenir le statut formaté
echo $order->status_label; // "En attente"
echo $order->status_class; // "bg-warning"
```

## Améliorations futures possibles

1. **Notifications email** - Envoyer des emails lors des changements de statut
2. **Suivi en temps réel** - WebSockets pour les mises à jour de statut
3. **Factures PDF** - Génération de factures téléchargeables
4. **Évaluations** - Système d'évaluation des produits après livraison
5. **Commandes récurrentes** - Possibilité de repasser la même commande
6. **Historique détaillé** - Log des modifications de statut avec horodatage

## Tests recommandés

1. Tester l'accès sans authentification (doit rediriger vers login)
2. Tester l'accès aux commandes d'autres utilisateurs (doit être refusé)
3. Tester l'annulation de commandes dans différents statuts
4. Tester l'ajout de commentaires
5. Tester les filtres et la recherche
6. Tester la pagination avec beaucoup de commandes

## Dépendances

- Laravel 9+
- Bootstrap 5 (pour les styles)
- Font Awesome (pour les icônes)
- JavaScript ES6+ (pour les fonctionnalités AJAX)

