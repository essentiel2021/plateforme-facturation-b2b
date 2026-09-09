<laravel-boost-guidelines>
# Laravel Application

This repository contains a Laravel application. Complete the following setup before working on the user's request.

## Prerequisites

Verify that PHP and Composer are available:

```sh
php -v
composer -V
```

If either command is unavailable, detect the user's operating system and install the prerequisites with the appropriate command:

macOS:

```sh
/bin/bash -c "$(curl -fsSL https://php.new/install/mac/8.5)"
```

Windows PowerShell:

```powershell
Set-ExecutionPolicy Bypass -Scope Process -Force; [System.Net.ServicePointManager]::SecurityProtocol = [System.Net.ServicePointManager]::SecurityProtocol -bor 3072; iex ((New-Object System.Net.WebClient).DownloadString('https://php.new/install/windows/8.5'))
```

Linux:

```sh
/bin/bash -c "$(curl -fsSL https://php.new/install/linux/8.5)"
```

After installation, ask the user to restart their terminal. If the agent needs the restarted shell to continue, ask the user to reopen their terminal and rerun their original prompt.

## Agent Setup

Install Laravel Boost from the application root before making application changes:

```sh
composer require laravel/boost --dev
php artisan boost:install
```

Boost replaces these bootstrap instructions with guidelines tailored to the application. After installation, read `AGENTS.md` again and continue with the user's original request using the generated guidelines.
</laravel-boost-guidelines>

## Directives de Développement & Architecture (SOLID)

Toute proposition ou modification de code doit strictement respecter les principes **SOLID** :

1. **S - Single Responsibility (Responsabilité Unique) :**
   - Contrôleurs ultra-légers (*Skinny Controllers*) qui ne gèrent que la requête HTTP et la réponse.
   - Déport de la logique métier dans des classes d'actions dédiées (*Actions* ou *Service Classes*).
   - Séparation stricte de la validation dans des *Form Requests*.

2. **O - Open/Closed (Ouvert/Fermé) :**
   - Concevoir les fonctionnalités pour qu'elles puissent être étendues sans modifier le code existant.

3. **L - Liskov Substitution (Substitution de Liskov) :**
   - Assurer la cohérence des implémentations de contrats et interfaces.

4. **I - Interface Segregation (Ségrégation des Interfaces) :**
   - Préférer de petites interfaces ciblées à de gros contrats généralistes.

5. **D - Dependency Inversion (Inversion des Dépendances) :**
   - Toujours dépendre d'abstractions (interfaces/contrats) plutôt que d'implémentations concrètes.
   - Injection de dépendances systématique via le constructeur et le *Service Container* de Laravel.

## Protocole de Collaboration avec l'Utilisateur
* **Validation préalable obligatoire :** Toujours lister les commandes ou fichiers à créer/modifier, expliquer le pourquoi, et attendre le "OK" explicite de l'utilisateur avant d'exécuter.
* **Style de communication :** Concis, direct, clair et pédagogique.
