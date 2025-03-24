#!/usr/bin/env python
# -*- coding: utf-8 -*-

import json
import os
import sys
import matplotlib.pyplot as plt
import matplotlib.dates as mdates
import pandas as pd
import numpy as np
from datetime import datetime, timedelta
import seaborn as sns
from pathlib import Path
import requests

# Configurer les paramètres graphiques
plt.style.use('ggplot')
sns.set_palette('Set2')

# Définir les chemins et l'URL de l'API
STORAGE_PATH = os.path.join(os.path.dirname(os.path.dirname(os.path.abspath(__file__))), 'storage', 'app')
DATA_PATH = os.path.join(STORAGE_PATH, 'stats', 'habits_data.json')
OUTPUT_PATH = os.path.join(STORAGE_PATH, 'public', 'stats')
API_BASE_URL = 'http://localhost:8000/api'

# Créer les répertoires s'ils n'existent pas
os.makedirs(OUTPUT_PATH, exist_ok=True)

def load_data():
    """Charge les données depuis l'API REST"""
    try:
        # Récupérer les données depuis l'API
        response = requests.get(f'{API_BASE_URL}/habits')
        response.raise_for_status()
        
        # Vérifier la structure de la réponse API
        api_data = response.json()
        if not isinstance(api_data, dict) or 'data' not in api_data:
            raise ValueError("Structure de données API invalide")
        
        habits = api_data['data']  # Adapter au format Laravel typique
        
        # Transformer les données au format attendu par les fonctions de génération de graphiques
        data = {}
        for habit in habits:
            habit_id = habit['id']
            
            # Récupérer les statistiques avec gestion d'erreur détaillée
            try:
                stats_response = requests.get(f'{API_BASE_URL}/habits/{habit_id}/stats', timeout=10)
                stats_response.raise_for_status()
                stats_data = stats_response.json()
                
                # Validation des données statistiques
                required_keys = {'dates', 'values', 'target_value', 'name'}
                if not required_keys.issubset(stats_data.keys()):
                    print(f"Données statistiques incomplètes pour l'habitude {habit_id}")
                    continue
                
                habit_stats = stats_data
                
            except requests.exceptions.RequestException as stats_error:
                print(f"Erreur stats pour l'habitude {habit_id}: {stats_error}")
                continue
            
            data[str(habit_id)] = habit_stats
        
        return data
    except requests.exceptions.RequestException as e:
        print(f"Erreur lors de la récupération des données depuis l'API: {e}")
        # Essayer de charger les données depuis le fichier local en cas d'échec
        try:
            # Essayer d'abord avec le chemin configuré (habits_data.json)
            if os.path.exists(DATA_PATH):
                with open(DATA_PATH, 'r') as f:
                    return json.load(f)
            
            # Si le fichier n'existe pas, essayer avec habit_data.json (au singulier)
            alt_data_path = os.path.join(STORAGE_PATH, 'stats', 'habit_data.json')
            if os.path.exists(alt_data_path):
                print(f"Utilisation du fichier alternatif: {alt_data_path}")
                with open(alt_data_path, 'r') as f:
                    habit_data = json.load(f)
                    # Convertir les données au format attendu (dictionnaire avec ID comme clé)
                    return {"1": habit_data}  # Utiliser un ID arbitraire
            
            # Si aucun fichier n'existe, retourner un dictionnaire vide
            print("Aucun fichier de données trouvé. Création de graphiques d'exemple.")
            return {}
        except Exception as file_error:
            print(f"Erreur lors de la lecture du fichier local: {file_error}")
            return {}

def generate_overall_progress(data):
    """Génère un graphique montrant la progression globale de toutes les habitudes"""
    plt.figure(figsize=(12, 8))
    
    for habit_id, habit_data in data.items():
        dates = [datetime.strptime(d, '%Y-%m-%d') for d in habit_data['dates']]
        values = habit_data['values']
        target = habit_data.get('target_value', 0)
        name = habit_data.get('name', 'Inconnu')
        unit = habit_data.get('unit', '')
        
        if not dates or target <= 0:
            print(f"Données insuffisantes pour {name}")
            continue
            
        # Créer un dataframe avec les dates et valeurs
        df = pd.DataFrame({'date': dates, 'value': values})
        df = df.sort_values('date')
        
        # Calcul sécurisé avec gestion des divisions nulles
        try:
            df['normalized'] = df['value'] / target
            plt.plot(df['date'], df['normalized'], marker='o', label=f'{name} ({unit})')
        except Exception as calc_error:
            print(f"Erreur calcul pour {name}: {calc_error}")
            continue
    
    plt.axhline(y=1.0, color='r', linestyle='--', alpha=0.7, label='Objectif (100%)')
    plt.title('Progression des habitudes au fil du temps')
    plt.xlabel('Date')
    plt.ylabel('Progression (% de l\'objectif)')
    plt.gca().xaxis.set_major_formatter(mdates.DateFormatter('%d/%m/%Y'))
    plt.gca().xaxis.set_major_locator(mdates.WeekdayLocator(interval=2))
    plt.gcf().autofmt_xdate()
    plt.legend()
    plt.grid(True, alpha=0.3)
    
    if len(plt.gca().lines) > 0:
        plt.savefig(os.path.join(OUTPUT_PATH, 'overall_progress.png'), dpi=100, bbox_inches='tight')
        plt.close()
    else:
        plt.close()
        print("Aucune donnée valide pour générer le graphique de progression")

def generate_completion_rate(data):
    """Génère un graphique en barres du taux de complétion pour chaque habitude"""
    plt.figure(figsize=(12, 8))
    
    habit_names = []
    completion_rates = []
    
    for habit_id, habit_data in data.items():
        name = habit_data['name']
        values = habit_data['values']
        target = habit_data['target_value']
        
        if not values:
            continue
        
        # Calculer le taux de complétion
        completed = sum(1 for v in values if v >= target)
        rate = (completed / len(values)) * 100 if values else 0
        
        habit_names.append(name)
        completion_rates.append(rate)
    
    # Trier par taux de complétion
    if habit_names:
        sorted_indices = np.argsort(completion_rates)
        habit_names = [habit_names[i] for i in sorted_indices]
        completion_rates = [completion_rates[i] for i in sorted_indices]
        
        colors = plt.cm.RdYlGn(np.array(completion_rates)/100)
        
        plt.barh(habit_names, completion_rates, color=colors)
        plt.xlabel('Taux de complétion (%)')
        plt.ylabel('Habitude')
        plt.title('Taux de complétion par habitude')
        plt.xlim(0, 100)
        
        # Ajouter les pourcentages sur les barres
        for i, v in enumerate(completion_rates):
            plt.text(v + 1, i, f'{v:.1f}%', va='center')
            
        plt.tight_layout()
        plt.savefig(os.path.join(OUTPUT_PATH, 'completion_rate.png'), dpi=100, bbox_inches='tight')
        plt.close()

def generate_streak_analysis(data):
    """Génère une analyse des séquences d'habitudes consécutives"""
    plt.figure(figsize=(12, 8))
    
    habit_names = []
    max_streaks = []
    current_streaks = []
    
    for habit_id, habit_data in data.items():
        name = habit_data['name']
        dates_str = habit_data['dates']
        values = habit_data['values']
        target = habit_data['target_value']
        
        if not dates_str or not values:
            continue
        
        # Convertir les dates en objets datetime
        dates = [datetime.strptime(d, '%Y-%m-%d') for d in dates_str]
        
        # Créer un dataframe
        df = pd.DataFrame({'date': dates, 'value': values})
        df = df.sort_values('date')
        
        # Marquer les jours où l'objectif est atteint
        df['completed'] = df['value'] >= target
        
        # Calculer les séquences
        max_streak = 0
        current_streak = 0
        last_date = None
        
        for i, row in df.iterrows():
            if not row['completed']:
                current_streak = 0
                continue
                
            if last_date is None:
                current_streak = 1
            elif (row['date'] - last_date).days == 1:
                current_streak += 1
            else:
                current_streak = 1
                
            max_streak = max(max_streak, current_streak)
            last_date = row['date']
        
        # Calculer la séquence actuelle
        today = datetime.now().date()
        recent_completions = df[df['completed'] == True].sort_values('date', ascending=False)
        
        current_streak = 0
        if not recent_completions.empty:
            last_completion = recent_completions.iloc[0]['date'].date()
            if (today - last_completion).days <= 1:  # Aujourd'hui ou hier
                current_streak = 1
                prev_date = last_completion
                
                for i, row in recent_completions.iloc[1:].iterrows():
                    comp_date = row['date'].date()
                    if (prev_date - comp_date).days == 1:
                        current_streak += 1
                        prev_date = comp_date
                    else:
                        break
        
        habit_names.append(name)
        max_streaks.append(max_streak)
        current_streaks.append(current_streak)
    
    # Afficher les résultats
    if habit_names:
        x = np.arange(len(habit_names))
        width = 0.35
        
        fig, ax = plt.subplots(figsize=(12, 8))
        max_bars = ax.bar(x - width/2, max_streaks, width, label='Meilleure séquence')
        current_bars = ax.bar(x + width/2, current_streaks, width, label='Séquence actuelle')
        
        ax.set_xlabel('Habitude')
        ax.set_ylabel('Jours consécutifs')
        ax.set_title('Analyse des séquences d\'habitudes')
        ax.set_xticks(x)
        ax.set_xticklabels(habit_names, rotation=45, ha='right')
        ax.legend()
        
        # Ajouter les valeurs sur les barres
        for bar in max_bars:
            height = bar.get_height()
            ax.annotate(f'{height}',
                        xy=(bar.get_x() + bar.get_width() / 2, height),
                        xytext=(0, 3),
                        textcoords="offset points",
                        ha='center', va='bottom')
                        
        for bar in current_bars:
            height = bar.get_height()
            ax.annotate(f'{height}',
                        xy=(bar.get_x() + bar.get_width() / 2, height),
                        xytext=(0, 3),
                        textcoords="offset points",
                        ha='center', va='bottom')
        
        plt.tight_layout()
        plt.savefig(os.path.join(OUTPUT_PATH, 'streak_analysis.png'), dpi=100, bbox_inches='tight')
        plt.close()

def create_example_data():
    """Crée des données d'exemple pour les graphiques"""
    # Générer des dates sur les 30 derniers jours
    end_date = datetime.now()
    start_date = end_date - timedelta(days=30)
    dates = [start_date + timedelta(days=i) for i in range(31)]
    
    # Créer deux habitudes d'exemple
    data = {
        "1": {
            "name": "Méditation quotidienne",
            "type": "general",
            "dates": [d.strftime('%Y-%m-%d') for d in dates],
            "values": [max(0, 15 + i*0.5 + np.random.normal(0, 3)) for i in range(31)],
            "target_value": 20,
            "unit": "minutes"
        },
        "2": {
            "name": "Exercice physique",
            "type": "general",
            "dates": [d.strftime('%Y-%m-%d') for d in dates],
            "values": [max(0, 30 + i*0.3 + np.random.normal(0, 5)) for i in range(31)],
            "target_value": 45,
            "unit": "minutes"
        }
    }
    
    return data

def main():
    """Fonction principale"""
    try:
        # Charger les données
        data = load_data()
        
        # Si aucune donnée n'est disponible, créer des données d'exemple
        if not data or all(len(habit_data.get('dates', [])) == 0 for habit_id, habit_data in data.items()):
            print("Aucune donnée disponible. Création de graphiques d'exemple.")
            example_data = create_example_data()
            generate_overall_progress(example_data)
            generate_completion_rate(example_data)
            generate_streak_analysis(example_data)
            return
            
        generate_overall_progress(data)
        generate_completion_rate(data)
        generate_streak_analysis(data)
        
        print("Graphiques générés avec succès !")
        
    except Exception as e:
        print(f"Erreur lors de la génération des graphiques : {e}")
        # Générer des graphiques d'exemple en cas d'erreur
        generate_example_charts()

def generate_example_charts():
    """Génère des graphiques d'exemple structurés comme les données API"""
    example_data = create_example_data()
    
    plt.figure(figsize=(12, 8))
    
    for habit_id, habit_data in example_data.items():
        dates = [datetime.strptime(d, '%Y-%m-%d') for d in habit_data['dates']]
        values = habit_data['values']
        target = habit_data['target_value']
        name = habit_data['name']
    
    for i, habit in enumerate(habits):
        # Générer des données aléatoires qui augmentent avec le temps
        values = [min(1.2, 0.2 + i * 0.05 + 0.5 * (30 - j) / 30 + 0.2 * np.random.random()) for j in range(30)]
        plt.plot(dates, values, marker='o', label=habit)
    
    plt.axhline(y=1.0, color='r', linestyle='--', alpha=0.7, label='Objectif (100%)')
    plt.title('Progression des habitudes au fil du temps (exemple)')
    plt.xlabel('Date')
    plt.ylabel('Progression (% de l\'objectif)')
    plt.gca().xaxis.set_major_formatter(mdates.DateFormatter('%d/%m/%Y'))
    plt.gca().xaxis.set_major_locator(mdates.WeekdayLocator(interval=2))
    plt.gcf().autofmt_xdate()
    plt.legend()
    plt.grid(True, alpha=0.3)
    
    if len(plt.gca().lines) > 0:
        plt.savefig(os.path.join(OUTPUT_PATH, 'overall_progress.png'), dpi=100, bbox_inches='tight')
        plt.close()
    else:
        plt.close()
        print("Aucune donnée valide pour générer le graphique de progression")
    
    # Graphique de taux de complétion
    plt.figure(figsize=(12, 8))
    
    completion_rates = [87.5, 65.2, 92.3, 45.8]
    colors = plt.cm.RdYlGn(np.array(completion_rates)/100)
    
    plt.barh(habits, completion_rates, color=colors)
    plt.xlabel('Taux de complétion (%)')
    plt.ylabel('Habitude')
    plt.title('Taux de complétion par habitude (exemple)')
    plt.xlim(0, 100)
    
    # Ajouter les pourcentages sur les barres
    for i, v in enumerate(completion_rates):
        plt.text(v + 1, i, f'{v:.1f}%', va='center')
        
    plt.tight_layout()
    plt.savefig(os.path.join(OUTPUT_PATH, 'completion_rate.png'), dpi=100, bbox_inches='tight')
    plt.close()
    
    # Graphique d'analyse des séquences
    plt.figure(figsize=(12, 8))
    
    max_streaks = [14, 7, 21, 5]
    current_streaks = [3, 0, 8, 2]
    
    x = np.arange(len(habits))
    width = 0.35
    
    fig, ax = plt.subplots(figsize=(12, 8))
    max_bars = ax.bar(x - width/2, max_streaks, width, label='Meilleure séquence')
    current_bars = ax.bar(x + width/2, current_streaks, width, label='Séquence actuelle')
    
    ax.set_xlabel('Habitude')
    ax.set_ylabel('Jours consécutifs')
    ax.set_title('Analyse des séquences d\'habitudes (exemple)')
    ax.set_xticks(x)
    ax.set_xticklabels(habits)
    ax.legend()
    
    # Ajouter les valeurs sur les barres
    for bar in max_bars:
        height = bar.get_height()
        ax.annotate(f'{height}',
                    xy=(bar.get_x() + bar.get_width() / 2, height),
                    xytext=(0, 3),
                    textcoords="offset points",
                    ha='center', va='bottom')
                    
    for bar in current_bars:
        height = bar.get_height()
        ax.annotate(f'{height}',
                    xy=(bar.get_x() + bar.get_width() / 2, height),
                    xytext=(0, 3),
                    textcoords="offset points",
                    ha='center', va='bottom')
    
    plt.tight_layout()
    plt.savefig(os.path.join(OUTPUT_PATH, 'streak_analysis.png'), dpi=100, bbox_inches='tight')
    plt.close()

if __name__ == "__main__":
    main()