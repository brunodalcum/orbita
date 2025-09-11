<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lembrete de Reunião - Órbita</title>
    <style>
        /* Reset e base */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif;
            line-height: 1.6;
            color: #333333;
            background-color: #f8fafc;
        }

        .container {
            max-width: 600px;
            margin: 0 auto;
            background-color: #ffffff;
            border-radius: 12px;
            overflow: hidden;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }

        /* Header */
        .header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 30px 20px;
            text-align: center;
        }

        .header h1 {
            font-size: 24px;
            font-weight: 600;
            margin-bottom: 8px;
        }

        .header p {
            font-size: 16px;
            opacity: 0.9;
        }

        /* Content */
        .content {
            padding: 40px 30px;
        }

        .greeting {
            font-size: 18px;
            color: #2d3748;
            margin-bottom: 20px;
        }

        .event-card {
            background: #f7fafc;
            border: 2px solid #e2e8f0;
            border-radius: 12px;
            padding: 25px;
            margin: 25px 0;
        }

        .event-title {
            font-size: 20px;
            font-weight: 600;
            color: #2d3748;
            margin-bottom: 15px;
        }

        .event-details {
            display: table;
            width: 100%;
        }

        .detail-row {
            display: table-row;
            margin-bottom: 12px;
        }

        .detail-label {
            display: table-cell;
            font-weight: 600;
            color: #4a5568;
            padding: 8px 15px 8px 0;
            vertical-align: top;
            width: 120px;
        }

        .detail-value {
            display: table-cell;
            color: #2d3748;
            padding: 8px 0;
            vertical-align: top;
        }

        .timezone {
            font-size: 14px;
            color: #718096;
            font-style: italic;
        }

        /* Botões */
        .button-container {
            text-align: center;
            margin: 30px 0;
        }

        .btn {
            display: inline-block;
            padding: 14px 28px;
            font-size: 16px;
            font-weight: 600;
            text-decoration: none;
            border-radius: 8px;
            margin: 8px;
            transition: all 0.3s ease;
        }

        .btn-primary {
            background: linear-gradient(135deg, #4299e1 0%, #3182ce 100%);
            color: white;
            box-shadow: 0 4px 6px rgba(66, 153, 225, 0.3);
        }

        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 12px rgba(66, 153, 225, 0.4);
        }

        .btn-secondary {
            background: #edf2f7;
            color: #4a5568;
            border: 2px solid #e2e8f0;
        }

        .btn-secondary:hover {
            background: #e2e8f0;
            border-color: #cbd5e0;
        }

        /* Descrição */
        .description {
            background: #fffbf0;
            border-left: 4px solid #f6ad55;
            padding: 20px;
            margin: 20px 0;
            border-radius: 0 8px 8px 0;
        }

        .description h4 {
            color: #c05621;
            margin-bottom: 10px;
            font-size: 16px;
        }

        .description p {
            color: #744210;
            line-height: 1.6;
        }

        /* Footer */
        .footer {
            background: #f7fafc;
            padding: 30px;
            text-align: center;
            border-top: 1px solid #e2e8f0;
        }

        .footer-links {
            margin-bottom: 20px;
        }

        .footer-links a {
            color: #4299e1;
            text-decoration: none;
            margin: 0 15px;
            font-size: 14px;
        }

        .footer-links a:hover {
            text-decoration: underline;
        }

        .footer-text {
            color: #718096;
            font-size: 14px;
            line-height: 1.5;
        }

        .logo {
            font-weight: 700;
            color: #2d3748;
        }

        /* Responsivo */
        @media (max-width: 600px) {
            .container {
                margin: 0;
                border-radius: 0;
            }

            .content {
                padding: 30px 20px;
            }

            .event-card {
                padding: 20px;
            }

            .detail-label,
            .detail-value {
                display: block;
                width: 100%;
                padding: 4px 0;
            }

            .detail-label {
                font-weight: 600;
                margin-top: 12px;
            }

            .btn {
                display: block;
                margin: 10px 0;
            }
        }

        /* Ícones */
        .icon {
            display: inline-block;
            width: 20px;
            height: 20px;
            margin-right: 8px;
            vertical-align: middle;
        }

        /* Urgência */
        .urgent {
            border-color: #f56565 !important;
            background: #fed7d7 !important;
        }

        .urgent .event-title {
            color: #c53030;
        }

        /* Status indicator */
        .time-indicator {
            display: inline-block;
            padding: 4px 12px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .time-indicator.soon {
            background: #fed7d7;
            color: #c53030;
        }

        .time-indicator.today {
            background: #fef5e7;
            color: #c05621;
        }

        .time-indicator.upcoming {
            background: #e6fffa;
            color: #319795;
        }
    </style>
</head>
<body>
    <div class="container">
        <!-- Header -->
        <div class="header">
            <h1>🔔 Lembrete de Reunião</h1>
            <p>Sua reunião está chegando {{ $time_until_event }}</p>
        </div>

        <!-- Content -->
        <div class="content">
            <!-- Greeting -->
            <div class="greeting">
                Olá, <strong>{{ $participant_name }}</strong>!
            </div>

            <!-- Event Card -->
            <div class="event-card {{ str_contains($time_until_event, 'menos de 1 hora') ? 'urgent' : '' }}">
                <div class="event-title">
                    📅 {{ $event_title }}
                </div>

                <!-- Time Indicator -->
                <div style="margin-bottom: 20px;">
                    <span class="time-indicator {{ str_contains($time_until_event, 'menos de 1 hora') ? 'soon' : (str_contains($time_until_event, 'hora') && !str_contains($time_until_event, 'dia') ? 'today' : 'upcoming') }}">
                        {{ $time_until_event }}
                    </span>
                </div>

                <!-- Event Details -->
                <div class="event-details">
                    <div class="detail-row">
                        <div class="detail-label">📅 Quando:</div>
                        <div class="detail-value">
                            {{ $event_start_formatted }}
                            <div class="timezone">({{ $event_timezone }})</div>
                        </div>
                    </div>

                    <div class="detail-row">
                        <div class="detail-label">⏰ Horário:</div>
                        <div class="detail-value">{{ $event_time_formatted }}</div>
                    </div>

                    <div class="detail-row">
                        <div class="detail-label">👤 Com:</div>
                        <div class="detail-value">
                            {{ $host_name }}
                            <div style="font-size: 14px; color: #718096;">{{ $host_email }}</div>
                        </div>
                    </div>

                    @if($event_meet_link)
                    <div class="detail-row">
                        <div class="detail-label">🔗 Link:</div>
                        <div class="detail-value">
                            <a href="{{ $event_meet_link }}" style="color: #4299e1; text-decoration: none;">
                                Google Meet
                            </a>
                        </div>
                    </div>
                    @endif
                </div>
            </div>

            <!-- Description -->
            @if($event_description)
            <div class="description">
                <h4>📝 Observações:</h4>
                <p>{{ $event_description }}</p>
            </div>
            @endif

            <!-- Action Buttons -->
            <div class="button-container">
                @if($event_meet_link)
                <a href="{{ $event_meet_link }}" class="btn btn-primary">
                    🎥 Entrar no Google Meet
                </a>
                @endif

                <a href="{{ $calendar_link }}" class="btn btn-secondary">
                    📅 Ver na Agenda
                </a>
            </div>

            <!-- Quick Info -->
            <div style="background: #f0fff4; border: 1px solid #9ae6b4; border-radius: 8px; padding: 20px; margin: 25px 0; text-align: center;">
                <div style="font-size: 16px; color: #276749; margin-bottom: 8px;">
                    <strong>⚡ Acesso Rápido</strong>
                </div>
                <div style="font-size: 14px; color: #2f855a;">
                    @if($event_meet_link)
                        Clique no botão acima para entrar diretamente na reunião
                    @else
                        Verifique os detalhes da reunião na sua agenda
                    @endif
                </div>
            </div>

            <!-- Reschedule Option -->
            <div style="text-align: center; margin: 20px 0; padding: 15px; background: #f7fafc; border-radius: 8px;">
                <div style="font-size: 14px; color: #4a5568;">
                    Precisa remarcar? 
                    <a href="{{ $reschedule_link }}" style="color: #4299e1; text-decoration: none;">
                        Clique aqui
                    </a>
                </div>
            </div>
        </div>

        <!-- Footer -->
        <div class="footer">
            <div class="footer-links">
                <a href="{{ $calendar_link }}">Ver Agenda</a>
                <a href="{{ $reschedule_link }}">Remarcar</a>
                <a href="mailto:{{ $host_email }}">Contatar Host</a>
            </div>

            <div class="footer-text">
                <div class="logo">Órbita</div>
                <div>Sistema de Gestão de Reuniões</div>
                <div style="margin-top: 10px; font-size: 12px;">
                    Este é um lembrete automático. Você está recebendo porque participa desta reunião.
                </div>
            </div>
        </div>
    </div>
</body>
</html>
