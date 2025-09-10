<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CalendarSettings extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'working_hours',
        'buffer_minutes',
        'min_advance_hours',
        'max_advance_days',
        'default_duration',
        'timezone',
        'email_reminders',
        'reminder_times',
        'public_booking_enabled',
        'public_booking_slug',
        'booking_policies',
        'google_sync_enabled',
        'auto_generate_meet',
    ];

    protected $casts = [
        'working_hours' => 'array',
        'reminder_times' => 'array',
        'email_reminders' => 'boolean',
        'public_booking_enabled' => 'boolean',
        'google_sync_enabled' => 'boolean',
        'auto_generate_meet' => 'boolean',
    ];

    /**
     * Relacionamento com usuário
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Obter configurações padrão
     */
    public static function getDefaults(): array
    {
        return [
            'working_hours' => [
                'monday' => ['start' => '09:00', 'end' => '18:00', 'enabled' => true],
                'tuesday' => ['start' => '09:00', 'end' => '18:00', 'enabled' => true],
                'wednesday' => ['start' => '09:00', 'end' => '18:00', 'enabled' => true],
                'thursday' => ['start' => '09:00', 'end' => '18:00', 'enabled' => true],
                'friday' => ['start' => '09:00', 'end' => '18:00', 'enabled' => true],
                'saturday' => ['start' => '09:00', 'end' => '13:00', 'enabled' => false],
                'sunday' => ['start' => '09:00', 'end' => '13:00', 'enabled' => false],
            ],
            'reminder_times' => [1440, 60], // 24h e 1h antes
            'buffer_minutes' => 10,
            'min_advance_hours' => 2,
            'max_advance_days' => 30,
            'default_duration' => 30,
            'timezone' => 'America/Fortaleza',
            'email_reminders' => true,
            'public_booking_enabled' => false,
            'google_sync_enabled' => true,
            'auto_generate_meet' => true,
        ];
    }

    /**
     * Obter configurações do usuário ou criar com padrões
     */
    public static function getForUser($userId)
    {
        $settings = static::where('user_id', $userId)->first();
        
        if (!$settings) {
            $defaults = static::getDefaults();
            $settings = static::create(array_merge($defaults, ['user_id' => $userId]));
        }
        
        return $settings;
    }

    /**
     * Verificar se um dia da semana está habilitado
     */
    public function isDayEnabled($dayOfWeek): bool
    {
        $workingHours = $this->working_hours ?? static::getDefaults()['working_hours'];
        $days = ['sunday', 'monday', 'tuesday', 'wednesday', 'thursday', 'friday', 'saturday'];
        $dayName = $days[$dayOfWeek] ?? 'monday';
        
        return $workingHours[$dayName]['enabled'] ?? false;
    }

    /**
     * Obter horários de trabalho de um dia específico
     */
    public function getDayWorkingHours($dayOfWeek): ?array
    {
        if (!$this->isDayEnabled($dayOfWeek)) {
            return null;
        }
        
        $workingHours = $this->working_hours ?? static::getDefaults()['working_hours'];
        $days = ['sunday', 'monday', 'tuesday', 'wednesday', 'thursday', 'friday', 'saturday'];
        $dayName = $days[$dayOfWeek] ?? 'monday';
        
        return $workingHours[$dayName] ?? null;
    }

    /**
     * Gerar slug único para agendamento público
     */
    public function generateUniqueSlug($baseSlug = null): string
    {
        if (!$baseSlug) {
            $baseSlug = strtolower(str_replace(' ', '-', $this->user->name ?? 'agenda'));
        }
        
        $slug = $baseSlug;
        $counter = 1;
        
        while (static::where('public_booking_slug', $slug)->where('id', '!=', $this->id)->exists()) {
            $slug = $baseSlug . '-' . $counter;
            $counter++;
        }
        
        return $slug;
    }
}