<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ config('app.name', 'Habit Tracker') }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700|montserrat:300,400,500,600,700&display=swap" rel="stylesheet" />

    <!-- Styles -->
    <link rel="stylesheet" href="{{ asset('assets/css/modern-landing.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/particles.css') }}">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="antialiased font-sans bg-gray-50 dark:bg-gray-900">
    <!-- Particles Background -->
    <div class="particles-container"></div>

    <!-- Decorative Blobs -->
    <div class="blob blob-1"></div>
    <div class="blob blob-2"></div>

    <!-- Header / Navigation -->
    <header class="relative z-10">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
            <div class="flex justify-between items-center">
                <div class="flex items-center">
                    <span class="text-2xl font-bold gradient-text">Habit Tracker</span>
                </div>
                <div>
                    @if (Route::has('login'))
                        <div class="flex space-x-4">
                            @auth
                                <a href="{{ url('/dashboard') }}" class="text-gray-700 dark:text-gray-300 hover:text-indigo-600 dark:hover:text-indigo-400 px-3 py-2 rounded-md text-sm font-medium transition-colors">
                                    Tableau de bord
                                </a>
                            @else
                                <a href="{{ route('login') }}" class="text-gray-700 dark:text-gray-300 hover:text-indigo-600 dark:hover:text-indigo-400 px-3 py-2 rounded-md text-sm font-medium transition-colors">
                                    Connexion
                                </a>

                                @if (Route::has('register'))
                                    <a href="{{ route('register') }}" class="bg-indigo-600 hover:bg-indigo-700 text-white px-4 py-2 rounded-md text-sm font-medium transition-colors cta-button">
                                        Inscription
                                    </a>
                                @endif
                            @endauth
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </header>

    <!-- Hero Section -->
    <section class="hero-gradient text-white py-20 md:py-32 relative overflow-hidden section-spacing">
        <div class="particles-container"></div>
        
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
            <div class="grid md:grid-cols-2 gap-12 items-center">
                <div class="reveal-left">
                    <h1 class="text-4xl md:text-5xl lg:text-6xl font-bold mb-6">
                        <span class="typing-text" data-text="Transformez vos habitudes,">Transformez vos habitudes,</span>
                        <br>
                        <span class="gradient-text">transformez votre vie</span>
                    </h1>
                    <p class="text-xl mb-8 text-indigo-100">Créez, suivez et analysez vos habitudes quotidiennes pour atteindre vos objectifs et devenir la meilleure version de vous-même.</p>
                    <div class="flex flex-col sm:flex-row space-y-4 sm:space-y-0 sm:space-x-4">
                        <a href="{{ route('register') }}" class="bg-white text-indigo-600 hover:bg-indigo-50 px-6 py-3 rounded-lg font-medium text-center cta-button glow">Commencer gratuitement</a>
                        <a href="#features" class="border border-white text-white hover:bg-white hover:text-indigo-600 px-6 py-3 rounded-lg font-medium text-center transition-colors">En savoir plus</a>
                    </div>
                </div>
                <div class="reveal-right">
                    <div class="card-3d">
                        <div class="card-3d-inner floating">
                            <img src="{{ asset('assets/images/habit-icon.svg') }}" alt="Habit Tracker" class="w-full max-w-md mx-auto">
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="scroll-indicator"></div>
    </section>

    <!-- Features Section -->
    <section id="features" class="py-20 md:py-28 bg-white dark:bg-gray-800 section-spacing relative overflow-hidden">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
            <div class="text-center mb-16 reveal-bottom">
                <h2 class="text-3xl md:text-4xl font-bold text-gray-900 dark:text-white mb-4 gradient-text">Fonctionnalités principales</h2>
                <p class="text-xl text-gray-600 dark:text-gray-300 max-w-3xl mx-auto">Des outils puissants pour vous aider à construire et maintenir des habitudes positives.</p>
            </div>
            
            <div class="grid md:grid-cols-3 gap-8 card-grid">
                <!-- Feature 1 -->
                <div class="feature-card bg-gray-50 dark:bg-gray-700 rounded-xl p-8 card-3d">
                    <div class="card-3d-inner">
                        <div class="mb-6">
                            <img src="{{ asset('assets/images/habit-icon.svg') }}" alt="Suivi d'habitudes" class="w-16 h-16 floating-slow">
                        </div>
                        <h3 class="text-xl font-bold text-gray-900 dark:text-white mb-4">Suivi d'habitudes</h3>
                        <p class="text-gray-600 dark:text-gray-300">Créez et suivez facilement vos habitudes quotidiennes, hebdomadaires ou mensuelles avec des rappels personnalisés.</p>
                    </div>
                </div>
                
                <!-- Feature 2 -->
                <div class="feature-card bg-gray-50 dark:bg-gray-700 rounded-xl p-8 card-3d delay-300">
                    <div class="card-3d-inner">
                        <div class="mb-6">
                            <img src="{{ asset('assets/images/stats-animated.svg') }}" alt="Statistiques détaillées" class="w-16 h-16 floating">
                        </div>
                        <h3 class="text-xl font-bold text-gray-900 dark:text-white mb-4">Statistiques détaillées</h3>
                        <p class="text-gray-600 dark:text-gray-300">Visualisez votre progression avec des graphiques et des statistiques détaillées pour rester motivé.</p>
                    </div>
                </div>
                
                <!-- Feature 3 -->
                <div class="feature-card bg-gray-50 dark:bg-gray-700 rounded-xl p-8 card-3d delay-500">
                    <div class="card-3d-inner">
                        <div class="mb-6">
                            <img src="{{ asset('assets/images/growth-icon.svg') }}" alt="Progression personnelle" class="w-16 h-16 floating-fast">
                        </div>
                        <h3 class="text-xl font-bold text-gray-900 dark:text-white mb-4">Progression personnelle</h3>
                        <p class="text-gray-600 dark:text-gray-300">Suivez votre évolution au fil du temps et identifiez les tendances pour améliorer votre productivité.</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Testimonials Section -->
    <section class="py-20 md:py-28 bg-indigo-50 dark:bg-gray-900 section-spacing relative overflow-hidden">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
            <div class="text-center mb-16 reveal-bottom">
                <h2 class="text-3xl md:text-4xl font-bold text-gray-900 dark:text-white mb-4 gradient-text">Ce que disent nos utilisateurs</h2>
                <p class="text-xl text-gray-600 dark:text-gray-300 max-w-3xl mx-auto">Découvrez comment Habit Tracker a aidé des milliers de personnes à transformer leur vie.</p>
            </div>
            
            <div class="grid md:grid-cols-3 gap-8 md:gap-10">
                <div class="testimonial-card bg-white dark:bg-gray-800 rounded-xl p-8 shadow-sm reveal-left">
                    <div class="flex items-center mb-4">
                        <div class="w-12 h-12 bg-indigo-100 dark:bg-indigo-900 rounded-full flex items-center justify-center text-indigo-600 dark:text-indigo-400 font-bold text-xl">S</div>
                        <div class="ml-4">
                            <h4 class="font-bold text-gray-900 dark:text-white">Sophie M.</h4>
                            <p class="text-gray-500 dark:text-gray-400 text-sm">Utilisatrice depuis 6 mois</p>
                        </div>
                    </div>
                    <p class="text-gray-600 dark:text-gray-300">"Grâce à Habit Tracker, j'ai pu établir une routine matinale qui a complètement transformé ma productivité. L'interface est intuitive et les statistiques me motivent à rester constante."</p>
                </div>
                
                <div class="testimonial-card bg-white dark:bg-gray-800 rounded-xl p-8 shadow-sm reveal-bottom delay-300">
                    <div class="flex items-center mb-4">
                        <div class="w-12 h-12 bg-indigo-100 dark:bg-indigo-900 rounded-full flex items-center justify-center text-indigo-600 dark:text-indigo-400 font-bold text-xl">T</div>
                        <div class="ml-4">
                            <h4 class="font-bold text-gray-900 dark:text-white">Thomas L.</h4>
                            <p class="text-gray-500 dark:text-gray-400 text-sm">Utilisateur depuis 1 an</p>
                        </div>
                    </div>
                    <p class="text-gray-600 dark:text-gray-300">"J'ai essayé plusieurs applications de suivi d'habitudes, mais celle-ci est de loin la meilleure. Les visualisations de données sont incroyables et m'aident à rester motivé."</p>
                </div>
                
                <div class="testimonial-card bg-white dark:bg-gray-800 rounded-xl p-8 shadow-sm reveal-right delay-500">
                    <div class="flex items-center mb-4">
                        <div class="w-12 h-12 bg-indigo-100 dark:bg-indigo-900 rounded-full flex items-center justify-center text-indigo-600 dark:text-indigo-400 font-bold text-xl">A</div>
                        <div class="ml-4">
                            <h4 class="font-bold text-gray-900 dark:text-white">Amina K.</h4>
                            <p class="text-gray-500 dark:text-gray-400 text-sm">Utilisatrice depuis 3 mois</p>
                        </div>
                    </div>
                    <p class="text-gray-600 dark:text-gray-300">"Cette application m'a aidée à intégrer la méditation et l'exercice dans ma routine quotidienne. Les rappels sont discrets mais efficaces, et j'adore voir ma progression."</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Stats Section -->
    <section class="py-20 md:py-28 bg-white dark:bg-gray-800 section-spacing">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-16 reveal-bottom">
                <h2 class="text-3xl md:text-4xl font-bold text-gray-900 dark:text-white mb-4 gradient-text">Résultats prouvés</h2>
                <p class="text-xl text-gray-600 dark:text-gray-300 max-w-3xl mx-auto">Rejoignez des milliers d'utilisateurs qui transforment leur vie grâce à Habit Tracker.</p>
            </div>
            
            <div class="grid md:grid-cols-3 gap-8 md:gap-10 text-center">
                <div class="bg-white dark:bg-gray-800 rounded-xl p-8 shadow-sm reveal-left border border-gray-200 dark:border-gray-700">
                    <div class="text-5xl font-bold text-indigo-600 dark:text-indigo-400 mb-2 counter" data-target="87" data-duration="3000">80</div>
                    <p class="text-gray-600 dark:text-gray-300">% des utilisateurs rapportent une amélioration de leur productivité</p>
                </div>
                
                <div class="bg-white dark:bg-gray-800 rounded-xl p-8 shadow-sm reveal-bottom delay-300 border border-gray-200 dark:border-gray-700">
                    <div class="text-5xl font-bold text-indigo-600 dark:text-indigo-400 mb-2 counter" data-target="92" data-duration="3000">75</div>
                    <p class="text-gray-600 dark:text-gray-300">% de constance dans le suivi des habitudes après 3 mois</p>
                </div>
                
                <div class="bg-white dark:bg-gray-800 rounded-xl p-8 shadow-sm reveal-right delay-500 border border-gray-200 dark:border-gray-700">
                    <div class="text-5xl font-bold text-indigo-600 dark:text-indigo-400 mb-2 counter" data-target="25000" data-duration="3000">1378</div>
                    <p class="text-gray-600 dark:text-gray-300">utilisateurs actifs qui transforment leur vie</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Pricing Section -->
    <section class="py-20 md:py-28 bg-indigo-50 dark:bg-gray-900 section-spacing relative overflow-hidden">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
            <div class="text-center mb-16 reveal-bottom">
                <h2 class="text-3xl md:text-4xl font-bold text-gray-900 dark:text-white mb-4 gradient-text">Plans simples et transparents</h2>
                <p class="text-xl text-gray-600 dark:text-gray-300 max-w-3xl mx-auto">Commencez gratuitement et évoluez selon vos besoins.</p>
            </div>
            
            <div class="grid md:grid-cols-2 gap-8 md:gap-10">
                <!-- Free Plan -->
                <div class="pricing-card bg-white dark:bg-gray-800 rounded-xl p-8 shadow-sm reveal-left border-2 border-gray-200 dark:border-gray-700 relative overflow-hidden">
                    <div class="absolute top-0 right-0 bg-gray-200 dark:bg-gray-700 px-4 py-1 text-sm font-medium text-gray-700 dark:text-gray-300">Populaire</div>
                    <h3 class="text-2xl font-bold text-gray-900 dark:text-white mb-2">Gratuit</h3>
                    <p class="text-gray-600 dark:text-gray-300 mb-6">Parfait pour commencer</p>
                    <div class="text-4xl font-bold text-indigo-600 dark:text-indigo-400 mb-6">0€ <span class="text-base font-normal text-gray-500 dark:text-gray-400">/mois</span></div>
                    
                    <ul class="space-y-3 mb-8">
                        <li class="flex items-center text-gray-600 dark:text-gray-300">
                            <svg class="w-5 h-5 text-green-500 mr-2" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path></svg>
                            Jusqu'à 5 habitudes
                        </li>
                        <li class="flex items-center text-gray-600 dark:text-gray-300">
                            <svg class="w-5 h-5 text-green-500 mr-2" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path></svg>
                            Statistiques de base
                        </li>
                        <li class="flex items-center text-gray-600 dark:text-gray-300">
                            <svg class="w-5 h-5 text-green-500 mr-2" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path></svg>
                            Rappels quotidiens
                        </li>
                    </ul>
                    
                    <a href="{{ route('register') }}" class="block w-full bg-indigo-600 hover:bg-indigo-700 text-white py-3 px-4 rounded-lg font-medium text-center transition-colors">Commencer gratuitement</a>
                </div>
                
                <!-- Premium Plan -->
                <div class="pricing-card bg-white dark:bg-gray-800 rounded-xl p-8 shadow-sm reveal-right delay-300 border-2 border-indigo-500 dark:border-indigo-400 relative overflow-hidden">
                    <div class="absolute top-0 right-0 bg-indigo-500 dark:bg-indigo-600 px-4 py-1 text-sm font-medium text-white">Recommandé</div>
                    <h3 class="text-2xl font-bold text-gray-900 dark:text-white mb-2">Premium</h3>
                    <p class="text-gray-600 dark:text-gray-300 mb-6">Pour les utilisateurs sérieux</p>
                    <div class="text-4xl font-bold text-indigo-600 dark:text-indigo-400 mb-6">4,99€ <span class="text-base font-normal text-gray-500 dark:text-gray-400">/mois</span></div>
                    
                    <ul class="space-y-3 mb-8">
                        <li class="flex items-center text-gray-600 dark:text-gray-300">
                            <svg class="w-5 h-5 text-green-500 mr-2" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path></svg>
                            Habitudes illimitées
                        </li>
                        <li class="flex items-center text-gray-600 dark:text-gray-300">
                            <svg class="w-5 h-5 text-green-500 mr-2" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path></svg>
                            Statistiques avancées
                        </li>
                        <li class="flex items-center text-gray-600 dark:text-gray-300">
                            <svg class="w-5 h-5 text-green-500 mr-2" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path></svg>
                            Rappels personnalisés
                        </li>
                        <li class="flex items-center text-gray-600 dark:text-gray-300">
                            <svg class="w-5 h-5 text-green-500 mr-2" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path></svg>
                            Exportation des données
                        </li>
                        <li class="flex items-center text-gray-600 dark:text-gray-300">
                            <svg class="w-5 h-5 text-green-500 mr-2" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path></svg>
                            Support prioritaire
                        </li>
                    </ul>
                    
                    <a href="{{ route('register') }}?plan=premium" class="block w-full bg-indigo-600 hover:bg-indigo-700 text-white py-3 px-4 rounded-lg font-medium text-center transition-colors glow">Essayer 14 jours gratuits</a>
                </div>
            </div>
        </div>
    </section>

    <!-- CTA Section -->
    <section class="py-20 md:py-28 bg-gradient-to-r from-indigo-600 to-purple-600 text-white section-spacing relative overflow-hidden">
        <div class="particles-container"></div>
        
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
            <div class="grid md:grid-cols-2 gap-12 items-center">
                <div class="reveal-left">
                    <h2 class="text-3xl md:text-4xl font-bold mb-6">Prêt à transformer vos habitudes ?</h2>
                    <p class="text-xl mb-8 text-indigo-100">Rejoignez des milliers d'utilisateurs qui ont déjà changé leur vie grâce à Habit Tracker.</p>
                    <div class="flex flex-col sm:flex-row space-y-4 sm:space-y-0 sm:space-x-4">
                        <a href="{{ route('register') }}" class="bg-white text-indigo-600 hover:bg-indigo-50 px-6 py-3 rounded-lg font-medium text-center cta-button glow">Commencer gratuitement</a>
                    </div>
                </div>
                <div class="reveal-right">
                    <div class="card-3d">
                        <div class="card-3d-inner floating">
                            <img src="{{ asset('assets/images/devices-mockup.svg') }}" alt="Habit Tracker sur tous vos appareils" class="w-full max-w-md mx-auto">
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="bg-gray-50 dark:bg-gray-900 py-12 border-t border-gray-200 dark:border-gray-800">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid md:grid-cols-4 gap-8">
                <div>
                    <div class="flex items-center mb-4">
                        <span class="text-xl font-bold gradient-text">Habit Tracker</span>
                    </div>
                    <p class="text-gray-600 dark:text-gray-400 mb-4">Transformez vos habitudes, transformez votre vie.</p>
                    <div class="flex space-x-4">
                        <a href="#" class="text-gray-400 hover:text-indigo-600 dark:hover:text-indigo-400 transition-colors">
                            <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24"><path d="M22.675 0h-21.35c-.732 0-1.325.593-1.325 1.325v21.351c0 .731.593 1.324 1.325 1.324h11.495v-9.294h-3.128v-3.622h3.128v-2.671c0-3.1 1.893-4.788 4.659-4.788 1.325 0 2.463.099 2.795.143v3.24l-1.918.001c-1.504 0-1.795.715-1.795 1.763v2.313h3.587l-.467 3.622h-3.12v9.293h6.116c.73 0 1.323-.593 1.323-1.325v-21.35c0-.732-.593-1.325-1.325-1.325z"/></svg>
                        </a>
                        <a href="#" class="text-gray-400 hover:text-indigo-600 dark:hover:text-indigo-400 transition-colors">
                            <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24"><path d="M23.953 4.57a10 10 0 01-2.825.775 4.958 4.958 0 002.163-2.723c-.951.555-2.005.959-3.127 1.184a4.92 4.92 0 00-8.384 4.482C7.69 8.095 4.067 6.13 1.64 3.162a4.822 4.822 0 00-.666 2.475c0 1.71.87 3.213 2.188 4.096a4.904 4.904 0 01-2.228-.616v.06a4.923 4.923 0 003.946 4.827 4.996 4.996 0 01-2.212.085 4.936 4.936 0 004.604 3.417 9.867 9.867 0 01-6.102 2.105c-.39 0-.779-.023-1.17-.067a13.995 13.995 0 007.557 2.209c9.053 0 13.998-7.496 13.998-13.985 0-.21 0-.42-.015-.63A9.935 9.935 0 0024 4.59z"/></svg>
                        </a>
                        <a href="#" class="text-gray-400 hover:text-indigo-600 dark:hover:text-indigo-400 transition-colors">
                            <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24"><path d="M12 0C5.373 0 0 5.373 0 12s5.373 12 12 12 12-5.373 12-12S18.627 0 12 0zm4.441 16.892c-2.102.144-6.784.144-8.883 0C5.282 16.736 5.017 15.622 5 12c.017-3.629.285-4.736 2.558-4.892 2.099-.144 6.782-.144 8.883 0C18.718 7.264 18.982 8.378 19 12c-.018 3.629-.285 4.736-2.559 4.892zM10 9.658l4.917 2.338L10 14.342V9.658z"/></svg>
                        </a>
                    </div>
                </div>
                
                <div>
                    <h3 class="font-bold text-gray-900 dark:text-white mb-4">Liens rapides</h3>
                    <ul class="space-y-2">
                        <li><a href="#" class="text-gray-600 dark:text-gray-400 hover:text-indigo-600 dark:hover:text-indigo-400 transition-colors">Accueil</a></li>
                        <li><a href="#features" class="text-gray-600 dark:text-gray-400 hover:text-indigo-600 dark:hover:text-indigo-400 transition-colors">Fonctionnalités</a></li>
                        <li><a href="{{ route('login') }}" class="text-gray-600 dark:text-gray-400 hover:text-indigo-600 dark:hover:text-indigo-400 transition-colors">Connexion</a></li>
                        <li><a href="{{ route('register') }}" class="text-gray-600 dark:text-gray-400 hover:text-indigo-600 dark:hover:text-indigo-400 transition-colors">Inscription</a></li>
                    </ul>
                </div>
                
                <div>
                    <h3 class="font-bold text-gray-900 dark:text-white mb-4">Ressources</h3>
                    <ul class="space-y-2">
                        <li><a href="#" class="text-gray-600 dark:text-gray-400 hover:text-indigo-600 dark:hover:text-indigo-400 transition-colors">Blog</a></li>
                        <li><a href="#" class="text-gray-600 dark:text-gray-400 hover:text-indigo-600 dark:hover:text-indigo-400 transition-colors">Guide de démarrage</a></li>
                        <li><a href="#" class="text-gray-600 dark:text-gray-400 hover:text-indigo-600 dark:hover:text-indigo-400 transition-colors">FAQ</a></li>
                        <li><a href="#" class="text-gray-600 dark:text-gray-400 hover:text-indigo-600 dark:hover:text-indigo-400 transition-colors">Support</a></li>
                    </ul>
                </div>
                
                <div>
                    <h3 class="font-bold text-gray-900 dark:text-white mb-4">Légal</h3>
                    <ul class="space-y-2">
                        <li><a href="#" class="text-gray-600 dark:text-gray-400 hover:text-indigo-600 dark:hover:text-indigo-400 transition-colors">Conditions d'utilisation</a></li>
                        <li><a href="#" class="text-gray-600 dark:text-gray-400 hover:text-indigo-600 dark:hover:text-indigo-400 transition-colors">Politique de confidentialité</a></li>
                        <li><a href="#" class="text-gray-600 dark:text-gray-400 hover:text-indigo-600 dark:hover:text-indigo-400 transition-colors">Mentions légales</a></li>
                    </ul>
                </div>
            </div>
            
            <div class="border-t border-gray-200 dark:border-gray-800 mt-12 pt-8 text-center text-gray-500 dark:text-gray-400">
                <p>&copy; {{ date('Y') }} Habit Tracker. Tous droits réservés.</p>
            </div>
        </div>
    </footer>

    <!-- Scripts -->
    <script src="{{ asset('assets/js/particles.min.js') }}"></script>
    <script src="{{ asset('assets/js/modern-landing.js') }}"></script>
</body>
</html>