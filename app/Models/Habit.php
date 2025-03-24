<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Habit extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'user_id',
        'name',
        'description',
        'type',
        'frequency',
        'target_value',
        'unit',
        'color',
        'is_active',
    ];

    /**
     * Constants for habit types
     */
    const TYPE_GENERAL = 'general';
    const TYPE_SLEEP = 'sleep';
    const TYPE_HYDRATION = 'hydration';

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'is_active' => 'boolean',
        'target_value' => 'float',
    ];

    /**
     * Récupère l'utilisateur propriétaire de l'habitude
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Récupère tous les logs associés à cette habitude
     */
    public function logs(): HasMany
    {
        return $this->hasMany(HabitLog::class);
    }

    /**
     * Récupère les logs pour la période en cours (jour, semaine, mois)
     */
    public function currentPeriodLogs()
    {
        $today = now();
        
        return match($this->frequency) {
            'daily' => $this->logs()->whereDate('date', $today),
            'weekly' => $this->logs()->whereBetween('date', [
                $today->startOfWeek()->format('Y-m-d'),
                $today->endOfWeek()->format('Y-m-d')
            ]),
            'monthly' => $this->logs()->whereBetween('date', [
                $today->startOfMonth()->format('Y-m-d'),
                $today->endOfMonth()->format('Y-m-d')
            ]),
            default => $this->logs()
        };
    }
}
