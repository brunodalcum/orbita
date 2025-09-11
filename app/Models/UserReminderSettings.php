<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserReminderSettings extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'reminder_offsets',
        'email_enabled',
        'whatsapp_enabled',
        'sms_enabled',
        'quiet_start',
        'quiet_end',
        'respect_quiet_hours',
        'timezone',
        'language',
    ];

    protected $casts = [
        'reminder_offsets' => 'array',
        'email_enabled' => 'boolean',
        'whatsapp_enabled' => 'boolean',
        'sms_enabled' => 'boolean',
        'respect_quiet_hours' => 'boolean',
        'quiet_start' => 'datetime:H:i',
        'quiet_end' => 'datetime:H:i',
    ];

    /**
     * Relacionamento com usuário
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Obter configurações do usuário ou criar padrão
     */
    public static function getForUser($userId)
    {
        return self::firstOrCreate(
            ['user_id' => $userId],
            [
                'reminder_offsets' => [2880, 1440, 60], // 48h, 24h, 1h
                'email_enabled' => true,
                'whatsapp_enabled' => false,
                'sms_enabled' => false,
                'quiet_start' => '23:00',
                'quiet_end' => '07:00',
                'respect_quiet_hours' => false,
                'timezone' => 'America/Fortaleza',
                'language' => 'pt-BR',
            ]
        );
    }

    /**
     * Obter offsets de lembrete para o usuário
     */
    public function getReminderOffsets()
    {
        return $this->reminder_offsets ?? [2880, 1440, 60];
    }

    /**
     * Verificar se está no período silencioso
     */
    public function isQuietTime(?\Carbon\Carbon $dateTime = null)
    {
        if (!$this->respect_quiet_hours) {
            return false;
        }

        $dateTime = $dateTime ?? now($this->timezone);
        $time = $dateTime->format('H:i');
        
        $quietStart = $this->quiet_start->format('H:i');
        $quietEnd = $this->quiet_end->format('H:i');

        // Se o período silencioso cruza a meia-noite
        if ($quietStart > $quietEnd) {
            return $time >= $quietStart || $time <= $quietEnd;
        }

        return $time >= $quietStart && $time <= $quietEnd;
    }

    /**
     * Obter canais habilitados
     */
    public function getEnabledChannels()
    {
        $channels = [];

        if ($this->email_enabled) {
            $channels[] = 'email';
        }

        if ($this->whatsapp_enabled) {
            $channels[] = 'whatsapp';
        }

        if ($this->sms_enabled) {
            $channels[] = 'sms';
        }

        return $channels ?: ['email']; // Sempre ter pelo menos email
    }

    /**
     * Atualizar offsets de lembrete
     */
    public function updateReminderOffsets(array $offsets)
    {
        // Validar offsets (devem ser números positivos)
        $validOffsets = array_filter($offsets, function ($offset) {
            return is_numeric($offset) && $offset > 0;
        });

        // Ordenar em ordem decrescente (maior primeiro)
        rsort($validOffsets);

        $this->update(['reminder_offsets' => $validOffsets]);

        return $this;
    }

    /**
     * Obter configurações padrão do sistema
     */
    public static function getSystemDefaults()
    {
        return [
            'reminder_offsets' => [2880, 1440, 60], // 48h, 24h, 1h
            'email_enabled' => true,
            'whatsapp_enabled' => false,
            'sms_enabled' => false,
            'quiet_start' => '23:00',
            'quiet_end' => '07:00',
            'respect_quiet_hours' => false,
            'timezone' => 'America/Fortaleza',
            'language' => 'pt-BR',
        ];
    }

    /**
     * Obter labels amigáveis para offsets
     */
    public function getOffsetLabels()
    {
        return collect($this->getReminderOffsets())->map(function ($minutes) {
            if ($minutes >= 1440) {
                $days = intval($minutes / 1440);
                return $days === 1 ? '1 dia antes' : "{$days} dias antes";
            } elseif ($minutes >= 60) {
                $hours = intval($minutes / 60);
                return $hours === 1 ? '1 hora antes' : "{$hours} horas antes";
            } else {
                return "{$minutes} minutos antes";
            }
        })->toArray();
    }
}