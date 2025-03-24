<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class HabitLog extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'habit_id',
        'date',
        'value',
        'notes',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'date' => 'date',
        'value' => 'float',
    ];

    /**
     * Get the habit that owns the log.
     */
    public function habit(): BelongsTo
    {
        return $this->belongsTo(Habit::class);
    }

    /**
     * Vérifie si l'objectif de l'habitude a été atteint
     */
    public function isTargetAchieved(): bool
    {
        return $this->value >= $this->habit->target_value;
    }

    /**
     * Calcule le pourcentage de réalisation
     */
    public function completionPercentage(): float
    {
        $targetValue = max(1, $this->habit->target_value);
        $percentage = min(100, ($this->value / $targetValue) * 100);
        
        return round($percentage, 1);
    }
}
