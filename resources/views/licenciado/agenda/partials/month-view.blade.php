<!-- Visualização do Mês -->
<div class="glass-effect rounded-3xl shadow-xl border border-white/20 overflow-hidden">
    
    <!-- Header do Mês -->
    <div class="bg-gradient-to-r from-blue-50 to-indigo-50 p-6 border-b border-gray-200">
        <div class="flex items-center justify-between">
            <div>
                <h2 class="text-2xl font-bold text-gray-900">{{ $date->locale('pt_BR')->isoFormat('MMMM [de] YYYY') }}</h2>
                <p class="text-gray-600">Visualização mensal completa</p>
            </div>
            <div class="text-right">
                <div class="text-3xl font-bold text-blue-600">{{ $agendas->count() }}</div>
                <div class="text-sm text-gray-600">compromissos</div>
            </div>
        </div>
    </div>
    
    <!-- Cabeçalho dos Dias da Semana -->
    <div class="grid grid-cols-7 bg-gray-50 border-b border-gray-200">
        @foreach(['Domingo', 'Segunda', 'Terça', 'Quarta', 'Quinta', 'Sexta', 'Sábado'] as $index => $dia)
            <div class="p-4 text-center border-r border-gray-200 last:border-r-0 {{ $index == 0 || $index == 6 ? 'bg-blue-50' : '' }}">
                <div class="text-xs font-medium text-gray-500 uppercase tracking-wider mb-1">
                    {{ substr($dia, 0, 3) }}
                </div>
                <div class="text-sm font-bold text-gray-800">
                    {{ $dia }}
                </div>
            </div>
        @endforeach
    </div>
    
    <!-- Grid do Calendário -->
    <div class="grid grid-cols-7">
        @php
            $startOfMonth = $date->copy()->startOfMonth();
            $endOfMonth = $date->copy()->endOfMonth();
            $startOfCalendar = $startOfMonth->copy()->startOfWeek();
            $endOfCalendar = $endOfMonth->copy()->endOfWeek();
            $currentDate = $startOfCalendar->copy();
        @endphp
        
        @while($currentDate <= $endOfCalendar)
            @php
                $isCurrentMonth = $currentDate->month === $date->month;
                $isToday = $currentDate->isToday();
                $isWeekend = $currentDate->isWeekend();
                $dayAgendas = $agendas->filter(function($agenda) use ($currentDate) {
                    return \Carbon\Carbon::parse($agenda->data_inicio)->format('Y-m-d') === $currentDate->format('Y-m-d');
                });
            @endphp
            
            <div class="group relative min-h-[120px] p-3 border-r border-b border-gray-100 last:border-r-0 transition-all duration-300 hover:bg-gradient-to-br hover:from-blue-50 hover:to-indigo-50 hover:shadow-lg cursor-pointer
                {{ !$isCurrentMonth ? 'bg-gray-50/50' : '' }}
                {{ $isToday ? 'bg-gradient-to-br from-blue-100 to-indigo-100 ring-2 ring-blue-300 ring-opacity-50' : '' }}
                {{ $isWeekend && $isCurrentMonth ? 'bg-gradient-to-br from-blue-50 to-indigo-50' : '' }}"
                 onclick="goToDay('{{ $currentDate->format('Y-m-d') }}')">
                
                <!-- Número do Dia -->
                <div class="flex items-center justify-between mb-2">
                    <div class="relative">
                        @if($isToday)
                            <div class="absolute -inset-1 bg-gradient-to-r from-blue-600 to-indigo-600 rounded-full animate-pulse opacity-20"></div>
                            <span class="relative inline-flex items-center justify-center w-7 h-7 bg-gradient-to-r from-blue-600 to-indigo-600 text-white text-sm font-bold rounded-full shadow-lg">
                                {{ $currentDate->day }}
                            </span>
                        @else
                            <span class="inline-flex items-center justify-center w-7 h-7 text-sm font-semibold rounded-full transition-all duration-200
                                {{ !$isCurrentMonth ? 'text-gray-400' : 'text-gray-700 group-hover:bg-blue-100 group-hover:text-blue-700' }}">
                                {{ $currentDate->day }}
                            </span>
                        @endif
                    </div>
                    
                    @if($dayAgendas->count() > 0)
                        <div class="flex items-center space-x-1">
                            <div class="w-2 h-2 bg-gradient-to-r from-blue-500 to-indigo-500 rounded-full animate-pulse"></div>
                            <span class="text-xs font-medium text-blue-600">{{ $dayAgendas->count() }}</span>
                        </div>
                    @endif
                </div>
                
                <!-- Compromissos do Dia -->
                <div class="space-y-1">
                    @foreach($dayAgendas->take(3) as $agenda)
                        @php
                            $startTime = \Carbon\Carbon::parse($agenda->data_inicio);
                            $statusColors = [
                                'confirmado' => 'bg-green-100 border-green-300 text-green-800',
                                'aprovada' => 'bg-green-100 border-green-300 text-green-800',
                                'pendente' => 'bg-yellow-100 border-yellow-300 text-yellow-800',
                                'cancelado' => 'bg-red-100 border-red-300 text-red-800',
                                'recusada' => 'bg-red-100 border-red-300 text-red-800',
                                'automatica' => 'bg-blue-100 border-blue-300 text-blue-800'
                            ];
                            $statusColor = $statusColors[$agenda->status_aprovacao] ?? 'bg-gray-100 border-gray-300 text-gray-800';
                        @endphp
                        
                        <div class="group/event relative p-2 rounded-lg border cursor-pointer {{ $statusColor }} hover:shadow-md transition-all duration-200 transform hover:scale-105"
                             onclick="event.stopPropagation(); showEventDetails({{ json_encode($agenda) }})"
                             title="{{ $agenda->titulo }} - {{ $startTime->format('H:i') }}">
                            
                            <!-- Indicador de Status -->
                            <div class="absolute -left-1 top-2 w-2 h-2 rounded-full
                                {{ $agenda->status_aprovacao === 'confirmado' || $agenda->status_aprovacao === 'aprovada' ? 'bg-green-500' : 
                                   ($agenda->status_aprovacao === 'pendente' ? 'bg-yellow-500' : 
                                   ($agenda->status_aprovacao === 'cancelado' || $agenda->status_aprovacao === 'recusada' ? 'bg-red-500' : 'bg-blue-500')) }}">
                            </div>
                            
                            <!-- Horário -->
                            <div class="flex items-center justify-between mb-1">
                                <span class="text-xs font-bold">{{ $startTime->format('H:i') }}</span>
                                @if($agenda->tipo_reuniao === 'online')
                                    <i class="fas fa-video text-xs opacity-70"></i>
                                @elseif($agenda->tipo_reuniao === 'presencial')
                                    <i class="fas fa-map-marker-alt text-xs opacity-70"></i>
                                @else
                                    <i class="fas fa-users text-xs opacity-70"></i>
                                @endif
                            </div>
                            
                            <!-- Título -->
                            <div class="text-xs font-semibold truncate">{{ $agenda->titulo }}</div>
                            
                            <!-- Ações Rápidas (aparecem no hover) -->
                            <div class="absolute top-1 right-1 opacity-0 group-hover/event:opacity-100 transition-opacity duration-200">
                                <div class="flex space-x-1">
                                    <button onclick="event.stopPropagation(); editEvent({{ $agenda->id }})" 
                                            class="w-5 h-5 bg-blue-500 hover:bg-blue-600 text-white rounded-full flex items-center justify-center transition-colors duration-200"
                                            title="Editar">
                                        <i class="fas fa-edit text-xs"></i>
                                    </button>
                                    <button onclick="event.stopPropagation(); deleteEvent({{ $agenda->id }})" 
                                            class="w-5 h-5 bg-red-500 hover:bg-red-600 text-white rounded-full flex items-center justify-center transition-colors duration-200"
                                            title="Excluir">
                                        <i class="fas fa-trash text-xs"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                    @endforeach
                    
                    @if($dayAgendas->count() > 3)
                        <div class="text-center">
                            <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-blue-100 text-blue-700 border border-blue-200">
                                <i class="fas fa-plus mr-1"></i>
                                {{ $dayAgendas->count() - 3 }} mais
                            </span>
                        </div>
                    @endif
                </div>
            </div>
            
            @php $currentDate->addDay(); @endphp
        @endwhile
    </div>
    
    <!-- Estatísticas do Mês -->
    @if($agendas->count() > 0)
        <div class="p-6 bg-gradient-to-r from-gray-50 to-blue-50 border-t border-gray-200">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">Estatísticas do Mês</h3>
            <div class="grid grid-cols-2 md:grid-cols-5 gap-4">
                @php
                    $totalAgendas = $agendas->count();
                    $pendentes = $agendas->where('status_aprovacao', 'pendente')->count();
                    $aprovadas = $agendas->whereIn('status_aprovacao', ['aprovada', 'confirmado'])->count();
                    $online = $agendas->where('tipo_reuniao', 'online')->count();
                    $presencial = $agendas->where('tipo_reuniao', 'presencial')->count();
                @endphp
                
                <div class="text-center bg-white rounded-2xl p-4 shadow-sm border border-gray-200">
                    <div class="text-2xl font-bold text-blue-600">{{ $totalAgendas }}</div>
                    <div class="text-sm text-gray-600">Total</div>
                </div>
                <div class="text-center bg-white rounded-2xl p-4 shadow-sm border border-gray-200">
                    <div class="text-2xl font-bold text-green-600">{{ $aprovadas }}</div>
                    <div class="text-sm text-gray-600">Confirmados</div>
                </div>
                <div class="text-center bg-white rounded-2xl p-4 shadow-sm border border-gray-200">
                    <div class="text-2xl font-bold text-yellow-600">{{ $pendentes }}</div>
                    <div class="text-sm text-gray-600">Pendentes</div>
                </div>
                <div class="text-center bg-white rounded-2xl p-4 shadow-sm border border-gray-200">
                    <div class="text-2xl font-bold text-purple-600">{{ $online }}</div>
                    <div class="text-sm text-gray-600">Online</div>
                </div>
                <div class="text-center bg-white rounded-2xl p-4 shadow-sm border border-gray-200">
                    <div class="text-2xl font-bold text-green-600">{{ $presencial }}</div>
                    <div class="text-sm text-gray-600">Presencial</div>
                </div>
            </div>
        </div>
    @endif
</div>

<script>
// Função para ir para visualização do dia
function goToDay(date) {
    window.location.href = `{{ route('licenciado.agenda.calendar') }}?view=day&date=${date}`;
}
</script>
