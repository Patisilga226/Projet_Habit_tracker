import json
import os
import sys
import matplotlib.pyplot as plt
import matplotlib.dates as mdates
import numpy as np
import calendar
from datetime import datetime, timedelta
import seaborn as sns
from pathlib import Path
import requests

# Importer pandas après les autres imports pour éviter l'importation circulaire
import pandas as pd

# Configurer les paramètres graphiques
plt.style.use('ggplot')
sns.set_palette('Set2')

# Définir les chemins et l'URL de l'API
STORAGE_PATH = os.path.join(os.path.dirname(os.path.dirname(os.path.abspath(__file__))), 'storage', 'app')
OUTPUT_PATH = os.path.join(STORAGE_PATH, 'public', 'stats')
API_BASE_URL = 'http://localhost:8000/api'

# Créer les répertoires s'ils n'existent pas
os.makedirs(OUTPUT_PATH, exist_ok=True)

def load_data(habit_id):
    """Charge les données depuis l'API REST"""
    try:
        # Récupérer les données depuis l'API
        response = requests.get(f'{API_BASE_URL}/habits/{habit_id}/stats')
        response.raise_for_status()  # Lever une exception si la requête échoue
        return response.json()
    except requests.exceptions.RequestException as e:
        print(f"Erreur lors de la récupération des données depuis l'API: {e}")
        # Essayer de charger les données depuis le fichier local en cas d'échec
        try:
            data_path = os.path.join(STORAGE_PATH, 'stats', 'habit_data.json')
            with open(data_path, 'r') as f:
                return json.load(f)
        except Exception as file_error:
            print(f"Erreur lors de la lecture du fichier local: {file_error}")
            raise

def generate_progress_chart(data, habit_id):
    """Génère un graphique de progression pour une habitude spécifique"""
    name = data['name']
    dates_str = data['dates']
    values = data['values']
    target = data['target_value']
    
    # Générer un graphique même s'il n'y a pas de données
    if not dates_str:
        # Au lieu de retourner, on continue avec un graphique vide
        dates = [datetime.now()]
        values = [0]
    else:
        # Convertir les dates
        dates = [datetime.strptime(d, '%Y-%m-%d') for d in dates_str]
    
    # Convertir les dates
    dates = [datetime.strptime(d, '%Y-%m-%d') for d in dates_str]
    
    # Créer un dataframe
    df = pd.DataFrame({'date': dates, 'value': values})
    df = df.sort_values('date')
    
    # Normaliser les valeurs
    df['normalized'] = df['value'] / target
    df['completed'] = df['value'] >= target
    
    # Créer le graphique
    plt.figure(figsize=(12, 8))
    
    # Tracer les points et courbe
    plt.plot(df['date'], df['value'], marker='o', linestyle='-', label='Valeur')
    
    # Marquer les jours où l'objectif est atteint avec une couleur différente
    completed = df[df['completed']]
    not_completed = df[~df['completed']]
    
    plt.scatter(completed['date'], completed['value'], color='green', s=100, zorder=5, label='Objectif atteint')
    plt.scatter(not_completed['date'], not_completed['value'], color='red', s=100, zorder=5, label='Objectif non atteint')
    
    # Ajouter une ligne pour l'objectif
    plt.axhline(y=target, color='r', linestyle='--', alpha=0.7, label=f'Objectif ({target})')
    
    # Ajouter les annotations
    plt.title(f'Progression de l\'habitude: {name}')
    plt.xlabel('Date')
    plt.ylabel(f'Valeur {"" if not data.get("unit") else "(" + data.get("unit") + ")"}')
    plt.gca().xaxis.set_major_formatter(mdates.DateFormatter('%d/%m/%Y'))
    plt.gca().xaxis.set_major_locator(mdates.WeekdayLocator(interval=2))
    plt.gcf().autofmt_xdate()
    plt.legend()
    plt.grid(True, alpha=0.3)
    
    # Sauvegarder le graphique
    plt.savefig(os.path.join(OUTPUT_PATH, f'habit_{habit_id}_progress.png'), dpi=100, bbox_inches='tight')
    plt.close()

def generate_monthly_chart(data, habit_id):
    """Génère un graphique mensuel pour une habitude spécifique"""
    name = data['name']
    dates_str = data['dates']
    values = data['values']
    target = data['target_value']
    
    # Générer un graphique même s'il n'y a pas de données
    if not dates_str:
        # Au lieu de retourner, on continue avec un graphique vide
        dates = [datetime.now()]
        values = [0]
        
        # Créer un dataframe avec une seule entrée
        df = pd.DataFrame({'date': dates, 'value': values})
        df['month'] = df['date'].dt.month
        df['year'] = df['date'].dt.year
        
        # Créer une moyenne mensuelle avec cette seule valeur
        monthly_avg = df.groupby(['year', 'month'])['value'].mean().reset_index()
        monthly_avg['date'] = monthly_avg.apply(lambda x: datetime(int(x['year']), int(x['month']), 15), axis=1)
    else:
        # Convertir les dates
        dates = [datetime.strptime(d, '%Y-%m-%d') for d in dates_str]
    
    # Convertir les dates
    dates = [datetime.strptime(d, '%Y-%m-%d') for d in dates_str]
    
    # Créer un dataframe
    df = pd.DataFrame({'date': dates, 'value': values})
    
    # Extraire le mois et l'année
    df['month'] = df['date'].dt.month
    df['year'] = df['date'].dt.year
    
    # Calculer les moyennes mensuelles
    monthly_avg = df.groupby(['year', 'month'])['value'].mean().reset_index()
    monthly_avg['date'] = monthly_avg.apply(lambda x: datetime(int(x['year']), int(x['month']), 15), axis=1)
    monthly_avg = monthly_avg.sort_values('date')
    
    # Créer le graphique
    plt.figure(figsize=(12, 8))
    
    # Tracer le graphique en barres
    ax = plt.gca()
    bars = ax.bar(monthly_avg['date'], monthly_avg['value'], width=25, alpha=0.7)
    
    # Colorier les barres en fonction de l'objectif
    for i, bar in enumerate(bars):
        if monthly_avg.iloc[i]['value'] >= target:
            bar.set_color('green')
        else:
            bar.set_color('red')
            
    # Ajouter une ligne pour l'objectif
    plt.axhline(y=target, color='blue', linestyle='--', alpha=0.7, label=f'Objectif ({target})')
    
    # Ajouter des étiquettes
    plt.title(f'Moyenne mensuelle: {name}')
    plt.xlabel('Mois')
    plt.ylabel(f'Valeur moyenne {"" if not data.get("unit") else "(" + data.get("unit") + ")"}')
    plt.gca().xaxis.set_major_formatter(mdates.DateFormatter('%b %Y'))
    plt.gcf().autofmt_xdate()
    plt.legend()
    plt.grid(True, alpha=0.3)
    
    # Ajouter des valeurs sur les barres
    for i, bar in enumerate(bars):
        height = bar.get_height()
        ax.text(bar.get_x() + bar.get_width()/2., height + 0.1,
                f'{monthly_avg.iloc[i]["value"]:.1f}',
                ha='center', va='bottom')
    
    # Sauvegarder le graphique
    plt.savefig(os.path.join(OUTPUT_PATH, f'habit_{habit_id}_monthly.png'), dpi=100, bbox_inches='tight')
    plt.close()

def generate_heatmap(data, habit_id):
    """Génère une heatmap des habitudes par jour de la semaine et semaine du mois"""
    name = data['name']
    dates_str = data['dates']
    values = data['values']
    target = data['target_value']
    
    # Générer un graphique même s'il n'y a pas de données
    if not dates_str:
        # Au lieu de retourner, on crée un graphique avec des données minimales
        dates = [datetime.now()]
        values = [0]
        
        # Créer un dataframe avec une seule entrée
        df = pd.DataFrame({'date': dates, 'value': values})
        
        # Ajouter les colonnes nécessaires
        df['weekday'] = df['date'].dt.weekday  # 0 = lundi, 6 = dimanche
        df['week_of_month'] = df['date'].apply(lambda d: (d.day - 1) // 7 + 1)  # 1-5
        df['month'] = df['date'].dt.month
        df['normalized'] = df['value'] / target
        
        # Créer un pivot pour la heatmap avec une seule valeur
        recent_df = df
    else:
        # Convertir les dates
        dates = [datetime.strptime(d, '%Y-%m-%d') for d in dates_str]
    
    # Convertir les dates
    dates = [datetime.strptime(d, '%Y-%m-%d') for d in dates_str]
    
    # Créer un dataframe
    df = pd.DataFrame({'date': dates, 'value': values})
    
    # Ajouter les colonnes nécessaires
    df['weekday'] = df['date'].dt.weekday  # 0 = lundi, 6 = dimanche
    df['week_of_month'] = df['date'].apply(lambda d: (d.day - 1) // 7 + 1)  # 1-5
    df['month'] = df['date'].dt.month
    df['normalized'] = df['value'] / target
    
    # Créer un pivot pour la heatmap
    # Utiliser les données des 3 derniers mois maximum
    three_months_ago = datetime.now() - timedelta(days=90)
    recent_df = df[df['date'] >= three_months_ago]
    
    # S'il n'y a pas assez de données, utiliser toutes les données disponibles
    if len(recent_df) < 10:
        recent_df = df
    
    # Préparer les données pour la heatmap
    heatmap_data = pd.pivot_table(
        pd.DataFrame(recent_df),
        values='normalized',
        index='weekday',
        columns='week_of_month',
        aggfunc='mean'
    ).fillna(0)
    
    # Renommer les index pour les jours de la semaine
    weekday_names = ['Lundi', 'Mardi', 'Mercredi', 'Jeudi', 'Vendredi', 'Samedi', 'Dimanche']
    heatmap_data.index = [weekday_names[i] for i in heatmap_data.index]
    
    # Créer la heatmap
    plt.figure(figsize=(12, 8))
    
    # Générer la heatmap
    ax = sns.heatmap(
        heatmap_data,
        cmap='RdYlGn',
        linewidths=.5,
        annot=True,
        fmt='.2f',
        vmin=0,
        vmax=max(1.5, heatmap_data.values.max()),
        cbar_kws={'label': '% de l\'objectif atteint'}
    )
    
    # Ajouter des étiquettes
    plt.title(f'Heatmap de performance: {name}')
    plt.xlabel('Semaine du mois')
    plt.ylabel('Jour de la semaine')
    
    # Sauvegarder le graphique
    plt.savefig(os.path.join(OUTPUT_PATH, f'habit_{habit_id}_heatmap.png'), dpi=100, bbox_inches='tight')
    plt.close()

def generate_example_progress_chart(habit_id):
    """Génère un graphique de progression d'exemple"""
    plt.figure(figsize=(12, 8))
    
    # Générer des données d'exemple
    dates = [datetime.now() - timedelta(days=i) for i in range(30, 0, -1)]
    
    # Générer des valeurs qui augmentent avec le temps et des fluctuations
    values = []
    target = 5
    for i in range(30):
        if i < 10:
            val = 2 + 0.2 * i + np.random.normal(0, 0.5)
        elif i < 20:
            val = 4 + 0.1 * (i - 10) + np.random.normal(0, 0.3)
        else:
            val = 5 + 0.05 * (i - 20) + np.random.normal(0, 0.2)
        values.append(max(0, val))
    
    # Déterminer si l'objectif est atteint pour chaque jour
    completed = [v >= target for v in values]
    
    # Tracer la courbe principale
    plt.plot(dates, values, marker='o', linestyle='-', label='Valeur')
    
    # Tracer des points pour les jours où l'objectif est atteint / non atteint
    for i, (date, value, comp) in enumerate(zip(dates, values, completed)):
        if comp:
            plt.scatter([date], [value], color='green', s=100, zorder=5)
        else:
            plt.scatter([date], [value], color='red', s=100, zorder=5)
    
    # Ajouter ligne d'objectif
    plt.axhline(y=target, color='r', linestyle='--', alpha=0.7, label=f'Objectif ({target})')
    
    # Ajouter des annotations
    plt.title('Progression de l\'habitude (exemple)')
    plt.xlabel('Date')
    plt.ylabel('Valeur')
    plt.gca().xaxis.set_major_formatter(mdates.DateFormatter('%d/%m/%Y'))
    plt.gca().xaxis.set_major_locator(mdates.WeekdayLocator(interval=2))
    plt.gcf().autofmt_xdate()
    plt.legend()
    plt.grid(True, alpha=0.3)
    
    # Sauvegarder le graphique
    plt.savefig(os.path.join(OUTPUT_PATH, f'habit_{habit_id}_progress.png'), dpi=100, bbox_inches='tight')
    plt.close()

def generate_example_monthly_chart(habit_id):
    """Génère un graphique mensuel d'exemple"""
    plt.figure(figsize=(12, 8))
    
    # Générer des données d'exemple
    current_month = datetime.now().month
    current_year = datetime.now().year
    
    months = []
    values = []
    
    for i in range(6):
        month = current_month - i
        year = current_year
        
        if month <= 0:
            month += 12
            year -= 1
            
        months.append(datetime(year, month, 15))
        
        # Générer une valeur qui augmente avec le temps
        value = 3 + 0.5 * i + np.random.normal(0, 0.3)
        values.append(max(0, value))
    
    # Inverser pour avoir l'ordre chronologique
    months.reverse()
    values.reverse()
    
    # Tracer le graphique en barres
    ax = plt.gca()
    target = 5
    bars = ax.bar(months, values, width=25, alpha=0.7)
    
    # Colorier les barres en fonction de l'objectif
    for i, bar in enumerate(bars):
        if values[i] >= target:
            bar.set_color('green')
        else:
            bar.set_color('red')
            
    # Ajouter une ligne pour l'objectif
    plt.axhline(y=target, color='blue', linestyle='--', alpha=0.7, label=f'Objectif ({target})')
    
    # Ajouter des étiquettes
    plt.title('Moyenne mensuelle (exemple)')
    plt.xlabel('Mois')
    plt.ylabel('Valeur moyenne')
    plt.gca().xaxis.set_major_formatter(mdates.DateFormatter('%b %Y'))
    plt.gcf().autofmt_xdate()
    plt.legend()
    plt.grid(True, alpha=0.3)
    
    # Ajouter des valeurs sur les barres
    for i, bar in enumerate(bars):
        height = bar.get_height()
        ax.text(bar.get_x() + bar.get_width()/2., height + 0.1,
                f'{values[i]:.1f}',
                ha='center', va='bottom')
    
    # Sauvegarder le graphique
    plt.savefig(os.path.join(OUTPUT_PATH, f'habit_{habit_id}_monthly.png'), dpi=100, bbox_inches='tight')
    plt.close()

def generate_example_heatmap(habit_id):
    """Génère une heatmap d'exemple"""
    # Créer des données d'exemple pour la heatmap
    weekdays = ['Lundi', 'Mardi', 'Mercredi', 'Jeudi', 'Vendredi', 'Samedi', 'Dimanche']
    weeks = [1, 2, 3, 4, 5]
    
    # Créer un dataframe avec des valeurs normalisées
    data = np.zeros((len(weekdays), len(weeks)))
    
    # Remplir avec des données aléatoires qui ont un schéma
    for i in range(len(weekdays)):
        for j in range(len(weeks)):
            # Valeurs plus élevées les weekends et au milieu du mois
            weekend_bonus = 0.3 if i >= 5 else 0
            middle_month_bonus = 0.2 if j in [1, 2, 3] else 0
            
            # Base + bonus + aléatoire
            data[i, j] = 0.5 + weekend_bonus + middle_month_bonus + 0.2 * np.random.random()
    
    # Créer le dataframe
    heatmap_df = pd.DataFrame(data, index=weekdays, columns=weeks)
    
    # Créer la heatmap
    plt.figure(figsize=(12, 8))
    
    # Générer la heatmap
    ax = sns.heatmap(
        heatmap_df,
        cmap='RdYlGn',
        linewidths=.5,
        annot=True,
        fmt='.2f',
        vmin=0,
        vmax=1.5,
        cbar_kws={'label': '% de l\'objectif atteint'}
    )
    
    # Ajouter des étiquettes
    plt.title('Heatmap de performance (exemple)')
    plt.xlabel('Semaine du mois')
    plt.ylabel('Jour de la semaine')
    
    # Sauvegarder le graphique
    plt.savefig(os.path.join(OUTPUT_PATH, f'habit_{habit_id}_heatmap.png'), dpi=100, bbox_inches='tight')
    plt.close()

def main():
    """Fonction principale"""
    # Récupérer l'ID de l'habitude depuis les arguments de ligne de commande
    if len(sys.argv) > 1:
        habit_id = sys.argv[1]
    else:
        print("Erreur: ID de l'habitude non fourni")
        habit_id = "example"
    
    try:
        # Charger les données depuis l'API REST
        data = load_data(habit_id)
        
        generate_progress_chart(data, habit_id)
        generate_monthly_chart(data, habit_id)
        generate_heatmap(data, habit_id)
        
        print(f"Graphiques générés avec succès pour l'habitude {habit_id} !")
        
    except Exception as e:
        print(f"Erreur lors de la génération des graphiques : {e}")
        # Générer des graphiques d'exemple
        generate_example_progress_chart(habit_id)
        generate_example_monthly_chart(habit_id)
        generate_example_heatmap(habit_id)

if __name__ == "__main__":
    main()