# Science des données 4

## Présentation du site

Salary Predict est un site web développée dans le but d’estimer le salaire associé à une offre d’emploi à partir de plusieurs caractéristiques. Parmi les variables prises en compte figurent notamment la région, le secteur d’activité, la taille de l’entreprise, le niveau hiérarchique, l’intitulé du poste, ainsi que des indicateurs liés à l’intelligence artificielle, tels que l’intensité de l’IA et le risque d’automatisation.

Le site a été conçu pour proposer une interface intuitive et interactive, permettant à l’utilisateur d’accéder facilement à des estimations de salaire, mais également à des outils d’analyse et de comparaison. L’objectif est de rendre les résultats issus de modèles de machine learning compréhensibles et exploitables.

En complément de la prédiction, la plateforme offre des fonctionnalités de comparaison de profils, de visualisation et de remontée de suggestions d’amélioration, afin d’inscrire le projet dans une logique d’évolution continue.

L’objectif principal de ce projet est de concevoir et d’implémenter des modèles de machine learning performants, capables de capturer les relations entre les différentes variables et le salaire, tout en intégrant ces modèles dans une application web fonctionnelle.

## Données

Le projet repose sur un jeu de données d’offres d’emploi contenant différentes variables descriptives nécessaires à l’analyse et à la prédiction des salaires.

Les principales variables exploitées sont les suivantes :

- `salary_usd` : salaire associé à l’offre d’emploi
- `job_title` : intitulé du poste
- `industry` : secteur d’activité
- `region` : zone géographique (continent)
- `seniority_level` : niveau d’expérience ou niveau hiérarchique
- `company_size` : taille de l’entreprise
- `ai_intensity_score` : niveau d’intégration de l’intelligence artificielle dans le poste
- `automation_risk_score` : niveau de risque d’automatisation du poste

Avant la phase de modélisation, un travail de préparation des données a été réalisé. Celui-ci comprend :

- le nettoyage des données (gestion des valeurs manquantes, incohérences)
- l’exploration des données
- des analyses statistiques univariées, bivariées et multivariées

Ces étapes ont permis d’identifier les variables les plus pertinentes, de comprendre les relations existantes entre elles et de préparer les données pour l’apprentissage des modèles.

## Fonctionnalités

Le site propose plusieurs fonctionnalités principales visant à faciliter l’analyse et la compréhension des salaires.

### Estimation de salaire

L’utilisateur peut renseigner un profil à partir de plusieurs critères. Le modèle de machine learning génère alors une estimation instantanée du salaire correspondant. Une projection dans le temps a également être proposée 
car les données dans le jeu de données sont de 2010 à 2025.

### Comparaison de profils

Cette fonctionnalité permet de comparer deux profils distincts afin d’observer l’impact des différentes variables sur le salaire prédit. Elle constitue un outil d’analyse utile pour mieux comprendre les facteurs influençant les rémunérations.

### Exploration des tendances

Le site intègre des visualisations permettant d’explorer les tendances salariales selon plusieurs dimensions :

- la région
- le secteur d’activité
- le niveau d’expérience
- l’évolution des salaires au fil des années

Ces visualisations facilitent l’interprétation des données et offrent une vision globale du marché.

### Suggestions d’amélioration

Une fonctionnalité permet aux utilisateurs de transmettre des suggestions aux administrateurs du site, dans une optique d’amélioration continue de la plateforme.

## Modèles de prédiction

Dans le cadre de ce projet, plusieurs modèles de machine learning ont été testés afin d’évaluer leurs performances en matière de prédiction des salaires.

Les modèles étudiés sont les suivants :

- Régression linéaire
- Random Forest
- Gradient Boosting
- KNN (K-Nearest Neighbors)
- Réseau de neurones (MLP)

Les résultats obtenus montrent que les modèles basés sur des ensembles d’arbres de décision, en particulier le Gradient Boosting, offrent les meilleures performances. Ce modèle a donc été retenu comme modèle principal pour la prédiction des salaires, en raison de sa capacité à capturer des relations non linéaires complexes entre les variables.

## Technologies utilisées

Le développement du projet repose sur un ensemble de technologies couvrant le traitement des données, le développement web et la gestion des bases de données.

### Data et machine learning

- Python
- Scikit-learn
- Pandas
- NumPy
- matplotlib

### Backend

- Flask (API de prédiction)
- JSON pour l’échange de données

### Frontend

- HTML
- CSS
- PHP
- JavaScript

### Base de données

- MySQL
- phpMyAdmin
- MAMP, Wamp ou Xamp (environnement local)

### Outils

- Python et excel (nettoyage des données)
- GitHub
- Mocodo (MCD)
- phpMyadmin(MOD)
- Visual Studio Code
- Google Colab
- InfinityFree (hébergement du site en ligne)
- RStudio(rapport final)

## Collaboration

Le projet a été réalisé en groupe de quatre étudiants dans le cadre de la formation en Licence 3 MIASHS.
