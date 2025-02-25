<?php

return [
    // General succcess messages
    'success' => 'L\'opération a été effectuée avec succès.',
    'error' => 'Une erreur s\'est produite. Veuillez réessayer.',
    'warning' => 'Attention! Quelque chose ne va peut-être pas.',

    // Authentication & User Management
    'auth.login_success' => 'Connexion réussie.',
    'auth.login_failed' => 'Les informations d\'identification fournies ne correspondent pas à nos enregistrements.',
    'auth.logout_success' => 'Déconnecté avec succès.',
    'auth.email_verification_required' => 'Vous devez être connecté pour vérifier votre e-mail.',
    'auth.email_verified' => 'E-mail vérifié avec succès.',
    'auth.email_verification_failed' => 'Une erreur s\'est produite lors de la vérification de l\'e-mail.',
    'auth.verification_email_sent' => 'E-mail de vérification envoyé avec succès.',
    'auth.verification_email_failed' => 'Impossible d\'envoyer l\'e-mail de vérification. Veuillez réessayer plus tard.',
    'auth.password_reset_sent' => 'Nous avons envoyé votre lien de réinitialisation de mot de passe par e-mail.',
    'auth.password_reset_failed' => 'Impossible d\'envoyer l\'e-mail de réinitialisation du mot de passe.',
    'auth.registration_success' => 'Inscription réussie. Veuillez vérifier votre e-mail pour vérifier votre compte.',
    'auth.registration_failed' => 'L\'inscription a échoué. Veuillez réessayer.',

    // Checkout & Orders
    'checkout.cart_empty' => 'Votre panier est vide.',
    'checkout.payment_successful' => 'Paiement réussi.',
    'checkout.payment_failed' => 'Le paiement n\'a pas pu être traité.',
    'checkout.order_created' => 'Commande créée avec succès.',
    'checkout.order_not_found' => 'Commande introuvable.',
    'checkout.promo_applied' => 'Code promo appliqué!',
    'checkout.promo_invalid' => 'Code promo invalide ou expiré.',
    'checkout.promo_removed' => 'Code promo supprimé.',
    'checkout.shipping_updated' => 'Informations d\'expédition mises à jour avec succès.',

    // Stripe & Payments
    'stripe.payment_successful' => 'Votre paiement a été effectué avec succès.',
    'stripe.payment_failed' => 'Échec du traitement du paiement.',
    'stripe.refund_successful' => 'Votre remboursement a été traité.',
    'stripe.refund_failed' => 'Une erreur s\'est produite lors du traitement du remboursement.',
    'stripe.charge_refunded' => 'Le paiement a été remboursé.',
    'stripe.payment_already_recorded' => 'Ce paiement a déjà été enregistré.',
    'stripe.pending_payment_not_found' => 'Aucun paiement en attente correspondant trouvé.',
    'stripe.payment_verified' => 'Paiement vérifié avec succès.',

    // Orders & Admin
    'admin.orders.created' => 'Commande créée avec succès.',
    'admin.orders.updated' => 'Commande mise à jour avec succès.',
    'admin.orders.deleted' => 'Commande supprimée avec succès.',
    'admin.orders.restored' => 'Commande restaurée avec succès.',
    'admin.orders.force_deleted' => 'Commande supprimée définitivement.',
    'admin.orders.error' => 'Erreur de traitement de la commande.',

    // Categories
    'admin.categories.created' => 'Catégorie créée avec succès.',
    'admin.categories.updated' => 'Catégorie mise à jour avec succès.',
    'admin.categories.deleted' => 'Catégorie supprimée avec succès.',
    'admin.categories.restored' => 'Catégorie restaurée avec succès.',
    'admin.categories.force_deleted' => 'Catégorie supprimée définitivement.',
    'admin.categories.error' => 'Erreur de traitement de la catégorie.',

    // Artists
    'admin.artists.created' => 'Artiste créé avec succès.',
    'admin.artists.updated' => 'Artiste mis à jour avec succès.',
    'admin.artists.deleted' => 'Artiste supprimé avec succès.',
    'admin.artists.restored' => 'Artiste restauré avec succès.',
    'admin.artists.force_deleted' => 'Artiste supprimé définitivement.',
    'admin.artists.error' => 'Erreur de traitement de l\'artiste.',

    // Artworks
    'admin.artworks.created' => 'Œuvre d\'art créée avec succès.',
    'admin.artworks.updated' => 'Œuvre d\'art mise à jour avec succès.',
    'admin.artworks.deleted' => 'Œuvre d\'art supprimée avec succès.',
    'admin.artworks.restored' => 'Œuvre d\'art restaurée avec succès.',
    'admin.artworks.force_deleted' => 'Œuvre d\'art supprimée définitivement.',
    'admin.artworks.error' => 'Erreur de traitement de l\'œuvre d\'art.',

    // Events
    'admin.events.created' => 'Événement créé avec succès.',
    'admin.events.updated' => 'Événement mis à jour avec succès.',
    'admin.events.deleted' => 'Événement supprimé avec succès.',
    'admin.events.restored' => 'Événement restauré avec succès.',
    'admin.events.force_deleted' => 'Événement supprimé définitivement.',
    'admin.events.error' => 'Erreur de traitement de l\'événement.',

    // Promotions
    'admin.promotions.created' => 'Promotion créée avec succès.',
    'admin.promotions.updated' => 'Promotion mise à jour avec succès.',
    'admin.promotions.deleted' => 'Promotion supprimée avec succès.',
    'admin.promotions.restored' => 'Promotion restaurée avec succès.',
    'admin.promotions.force_deleted' => 'Promotion supprimée définitivement.',
    'admin.promotions.error' => 'Erreur de traitement de la promotion.',

    // Users
    'admin.users.deleted' => 'Utilisateur supprimé avec succès.',
    'admin.users.restored' => 'Utilisateur restauré avec succès.',
    'admin.users.force_deleted' => 'Utilisateur supprimé définitivement.',
    'admin.users.error' => 'Erreur de traitement de l\'utilisateur.',
    'admin.users.cannot_delete_admin' => 'Vous ne pouvez pas supprimer un utilisateur administrateur.',

    // Settings
    'admin.settings.updated' => 'Paramètres mis à jour avec succès.',
    'admin.settings.error' => 'Erreur de mise à jour des paramètres.',

    // Dashboard
    'admin.dashboard.load_failed' => 'Erreur lors du chargement des données du tableau de bord. Veuillez réessayer.',
    'admin.dashboard.unauthorized' => 'Accès non autorisé.',
];
