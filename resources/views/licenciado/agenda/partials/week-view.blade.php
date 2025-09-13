<!-- Visualização da Semana -->
<div class="glass-effect rounded-3xl shadow-xl border border-white/20 overflow-hidden">
    
    <!-- Header da Semana -->
    <div class="bg-gradient-to-r from-blue-50 to-indigo-50 p-6 border-b border-gray-200">
        <div class="flex items-center justify-between">
            <div>
                <h2 class="text-2xl font-bold text-gray-900">Visualização Semanal</h2>
                <p class="text-gray-600">{{ $startOfWeek->format('d/m') }} a {{ $endOfWeek->format('d/m/Y') }}</p>
            </div>
            <div class="text-right">
                <div class="text-3xl font-bold text-blue-600">{{ $agendas->count() }}</div>
                <div class="text-sm text-gray-600">compromissos</div>
            </div>
        </div>
    </div>
    
    <!-- Grid da Semana -->
    <div class="p-6">
        <div class="grid grid-cols-7 gap-4">
            @php
                $currentWeekDay = $startOfWeek->copy();
                $daysOfWeek = ['Dom', 'Seg', 'Ter', 'Qua', 'Qui', 'Sex', 'Sáb'];
            @endphp
            
            @for($i = 0; $i < 7; $i++)
                @php
                    $dayAgendas = $agendas->filter(function($agenda) use ($currentWeekDay) {
                        return \Carbon\Carbon::parse($agenda->data_inicio)->format('Y-m-d') === $currentWeekDay->format('Y-m-d');
                    });
                    $isToday = $currentWeekDay->isToday();
                    $isWeekend = $currentWeekDay->isWeekend();
                @endphp
                
                <!-- Coluna do Dia -->
                <div class="bg-white rounded-2xl shadow-lg border border-gray-200 overflow-hidden {{ $isToday ? 'ring-2 ring-blue-500 ring-opacity-50' : '' }}">
                    
                    <!-- Cabeçalho do Dia -->
                    <div class="p-4 border-b border-gray-200 {{ $isWeekend ? 'bg-gradient-to-r from-blue-50 to-indigo-50' : 'bg-gray-50' }}">
                        <div class="text-center">
                            <div class="text-xs font-medium text-gray-500 uppercase tracking-wider mb-1">
                                {{ $daysOfWeek[$i] }}
                            </div>
                            <div class="relative">
                                @if($isToday)
                                    <div class="w-8 h-8 bg-gradient-to-r from-blue-500 to-indigo-600 rounded-full flex items-center justify-center mx-auto shadow-lg">
                                        <span class="text-sm font-bold text-white">{{ $currentWeekDay->day }}</span>
                                    </div>
                                @else
                                    <div class="w-8 h-8 flex items-center justify-center mx-auto">
                                        <span class="text-lg font-semibold {{ $isWeekend ? 'text-blue-600' : 'text-gray-900' }}">{{ $currentWeekDay->day }}</span>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                    
                    <!-- Compromissos do Dia -->
                    <div class="p-3 min-h-[300px]">
                        @if($dayAgendas->count() > 0)
                            <div class="space-y-2">
                                @foreach($dayAgendas->take(4) as $agenda)
                                    @php
                                        $startTime = \Carbon\Carbon::parse($agenda->data_inicio);
                                        $statusColors = [
                                            'confirmado' => 'from-green-400 to-emerald-500 border-green-200',
                                            'aprovada' => 'from-green-400 to-emerald-500 border-green-200',
                                            'pendente' => 'from-yellow-400 to-orange-500 border-yellow-200',
                                            'cancelado' => 'from-red-400 to-rose-500 border-red-200',
                                            'recusada' => 'from-red-400 to-rose-500 border-red-200',
                                            'automatica' => 'from-blue-400 to-indigo-500 border-blue-200'
                                        ];
                                        $statusColor = $statusColors[$agenda->status_aprovacao] ?? 'from-gray-400 to-gray-500 border-gray-200';
                                    @endphp
                                    
                                    <div class="group relative bg-gradient-to-r {{ $statusColor }} rounded-xl p-3 cursor-pointer hover:shadow-lg transition-all duration-200 transform hover:scale-105"
                                         onclick="showEventDetails({{ json_encode($agenda) }})">
                                        
                                        <!-- Horário -->
                                        <div class="text-xs font-bold text-white mb-1">
                                            {{ $startTime->format('H:i') }}
                                        </div>
                                        
                                        <!-- Título -->
                                        <div class="text-sm font-semibold text-white line-clamp-2 mb-1">
                                            {{ $agenda->titulo }}
                                        </div>
                                        
                                        <!-- Tipo de Reunião -->
                                        <div class="flex items-center justify-between">
                                            <div class="text-xs text-white opacity-80">
                                                @if($agenda->tipo_reuniao === 'online')
                                                    <i class="fas fa-video mr-1"></i>Online
                                                @elseif($agenda->tipo_reuniao === 'presencial')
                                                    <i class="fas fa-map-marker-alt mr-1"></i>Presencial
                                                @else
                                                    <i class="fas fa-users mr-1"></i>Híbrida
                                                @endif
                                            </div>
                                            
                                            <!-- Ações Rápidas -->
                                            <div class="opacity-0 group-hover:opacity-100 transition-opacity duration-200 flex space-x-1">
                                                <button onclick="event.stopPropagation(); editEvent({{ $agenda->id }})" 
                                                        class="w-6 h-6 bg-white bg-opacity-20 hover:bg-opacity-30 rounded-full flex items-center justify-center transition-all duration-200"
                                                        title="Editar">
                                                    <i class="fas fa-edit text-xs text-white"></i>
                                                </button>
                                                <button onclick="event.stopPropagation(); deleteEvent({{ $agenda->id }})" 
                                                        class="w-6 h-6 bg-white bg-opacity-20 hover:bg-opacity-30 rounded-full flex items-center justify-center transition-all duration-200"
                                                        title="Excluir">
                                                    <i class="fas fa-trash text-xs text-white"></i>
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                                
                                @if($dayAgendas->count() > 4)
                                    <div class="text-center py-2">
                                        <span class="text-xs text-gray-500 bg-gray-100 px-2 py-1 rounded-full">
                                            +{{ $dayAgendas->count() - 4 }} mais
                                        </span>
                                    </div>
                                @endif
                            </div>
                        @else
                            <!-- Dia Vazio -->
                            <div class="flex items-center justify-center h-full">
                                <div class="text-center">
                                    <div class="w-12 h-12 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-2">
                                        <i class="fas fa-calendar text-gray-400"></i>
                                    </div>
                                    <p class="text-xs text-gray-500">Sem compromissos</p>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
                
                @php $currentWeekDay->addDay(); @endphp
            @endfor
        </div>
        
        <!-- Resumo da Semana -->
        @if($agendas->count() > 0)
            <div class="mt-8 bg-gradient-to-r from-blue-50 to-indigo-50 rounded-2xl p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Resumo da Semana</h3>
                <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                    @php
                        $totalAgendas = $agendas->count();
                        $pendentes = $agendas->where('status_aprovacao', 'pendente')->count();
                        $aprovadas = $agendas->whereIn('status_aprovacao', ['aprovada', 'confirmado'])->count();
                        $online = $agendas->where('tipo_reuniao', 'online')->count();
                    @endphp
                    
                    <div class="text-center">
                        <div class="text-2xl font-bold text-blue-600">{{ $totalAgendas }}</div>
                        <div class="text-sm text-gray-600">Total</div>
                    </div>
                    <div class="text-center">
                        <div class="text-2xl font-bold text-green-600">{{ $aprovadas }}</div>
                        <div class="text-sm text-gray-600">Confirmados</div>
                    </div>
                    <div class="text-center">
                        <div class="text-2xl font-bold text-yellow-600">{{ $pendentes }}</div>
                        <div class="text-sm text-gray-600">Pendentes</div>
                    </div>
                    <div class="text-center">
                        <div class="text-2xl font-bold text-purple-600">{{ $online }}</div>
                        <div class="text-sm text-gray-600">Online</div>
                    </div>
                </div>
            </div>
        @endif
    </div>
</div>
