# Gestionnaire d'Évènements - README

## Description
Une application de gestion d'évènements permettant aux utilisateurs de s'inscrire, de créer des évènements (publics ou privés), de participer, et de gérer des contributions. L'application utilise l'authentification par JWT et propose diverses fonctionnalités autour des évènements et des interactions entre utilisateurs.

---

## Fonctionnalités principales

### Authentification et inscription
- [x] Permettre aux utilisateurs de s'inscrire via un endpoint.
- [x] Implémenter l'authentification à l'aide de tokens JWT.
- [x] Fournir un endpoint pour récupérer la liste des utilisateurs inscrits.

### Gestion des utilisateurs
- [x] Créer une entité `User` contenant les informations de connexion (email, mot de passe, etc.).
- [x] Créer une entité `Profile` associée à `User` avec une propriété `displayName`.
- [x] Ajouter la possibilité pour les utilisateurs de télécharger une image de profil (bonus).

### Gestion des évènements publics
- [x] Créer une entité `Event` avec les propriétés suivantes :
    - [x] Lieu
    - [x] Description
    - [x] Date de début (doit être dans le futur lors de la création)
    - [x] Date de fin (doit être après la date de début)
    - [x] Organisateur/trice (relation avec `Profile`)
    - [x] Statut : public ou privé
    - [x] Type de lieu : public ou privé
    - [ ] Image associée (bonus)
- [x] Fournir un endpoint pour créer un évènement public.
- [x] Fournir un endpoint pour récupérer la liste des évènements publics disponibles.
- [x] Permettre aux utilisateurs de s'inscrire à un évènement public en tant que participants.
- [x] Inclure la liste des participants dans les informations d'un évènement.

### Gestion des évènements privés
- [x] Ajouter la possibilité de créer des évènements privés.
- [x] Fournir un moyen d'envoyer des invitations à des participants (utilisateurs ayant un compte).
- [x] Créer une entité `Invitation` liée à `Event` et `Profile`, avec un statut :
    - Accepté
    - Refusé
    - En attente de réponse (par défaut)
- [x] Fournir un endpoint pour consulter la liste des invitations reçues.
- [x] Permettre aux utilisateurs de répondre à une invitation (accepter ou refuser).
- [x] Bloquer la modification du statut après la date de début de l'évènement.
- [x] Inclure la liste des invités (avec leurs statuts) dans les informations d'un évènement privé.

### Contributions pour évènements privés
- [x] Créer une entité `Contribution` liée à un évènement.
    - Description
    - Author (relation avec `Profile`)
    - Event (relation avec `Event ̀)
- [x] Créer une entité `Suggestion` liée à `Contribution` avec les propriétés :
    - Description
    - Statut : pris en charge ou à prendre en charge
    - Suggestion (relation avec `Suggestion`)
- [x] Mettre à jour le statut d'une suggestion lorsqu'elle est prise en charge.
- [x] Permettre à l'organisateur ou aux participants de supprimer ou de modifier une contribution.
- [x] Creer des contibutions rebelles non associés à des suggestions

### Gestion du statut des évènements
- [x] Ajouter un statut supplémentaire pour les évènements :
    - Toujours prévu (`on schedule`)
    - Annulé (`canceled`)
- [x] Empêcher l'envoi d'invitations ou de réponses à des invitations pour un évènement annulé.
- [x] Permettre à l'organisateur/trice de modifier les dates de début et de fin (respectant les contraintes temporelles).

### Agenda des utilisateurs
- [x] Fournir un endpoint pour récupérer la liste des évènements auxquels un utilisateur participe (agenda).

### Administration des évènements (Maxi-Bonus)
- [ ] Implémenter une fonctionnalité pour promouvoir un autre utilisateur au rang d'administrateur d'un évènement privé.

--- 

## Technologies
- **Backend** : Symfony
- **Base de données** : PostgreSQL
- **Authentification** : JWT (JSON Web Tokens)
- **Upload d'images** : API ou stockage local (ex : VichUploaderBundle)
 