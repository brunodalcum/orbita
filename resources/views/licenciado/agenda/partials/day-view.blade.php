<!-- Visualização do Dia -->
<div class="glass-effect rounded-3xl shadow-xl border border-white/20 overflow-hidden">
    
    <!-- Header do Dia -->
    <div class="bg-gradient-to-r from-blue-50 to-indigo-50 p-6 border-b border-gray-200">
        <div class="flex items-center justify-between">
            <div>
                <h2 class="text-2xl font-bold text-gray-900">{{ $date->locale('pt_BR')->isoFormat('dddd') }}</h2>
                <p class="text-gray-600">{{ $date->locale('pt_BR')->isoFormat('D [de] MMMM [de] YYYY') }}</p>
            </div>
            <div class="text-right">
                <div class="text-3xl font-bold text-blue-600">{{ $agendas->count() }}</div>
                <div class="text-sm text-gray-600">compromissos</div>
            </div>
        </div>
    </div>
    
    <!-- Timeline do Dia -->
    <div class="p-6">
        @if($agendas->count() > 0)
            <div class="space-y-4">
                @php
                    $currentHour = null;
                @endphp
                
                @foreach($agendas as $agenda)
                    @php
                        $startTime = \Carbon\Carbon::parse($agenda->data_inicio);
                        $endTime = \Carbon\Carbon::parse($agenda->data_fim);
                        $hour = $startTime->format('H:00');
                        
                        $statusColors = [
                            'confirmado' => 'from-green-400 to-emerald-500',
                            'aprovada' => 'from-green-400 to-emerald-500',
                            'pendente' => 'from-yellow-400 to-orange-500',
                            'cancelado' => 'from-red-400 to-rose-500',
                            'recusada' => 'from-red-400 to-rose-500',
                            'automatica' => 'from-blue-400 to-indigo-500'
                        ];
                        
                        $statusColor = $statusColors[$agenda->status_aprovacao] ?? 'from-gray-400 to-gray-500';
                    @endphp
                    
                    <!-- Separador de Hora -->
                    @if($currentHour !== $hour)
                        @if($currentHour !== null)
                            <div class="border-t border-gray-200 my-6"></div>
                        @endif
                        <div class="flex items-center mb-4">
                            <div class="w-16 h-16 bg-gradient-to-br from-gray-100 to-gray-200 rounded-2xl flex items-center justify-center mr-4">
                                <span class="text-lg font-bold text-gray-700">{{ $startTime->format('H:i') }}</span>
                            </div>
                            <div class="flex-1 h-px bg-gray-200"></div>
                        </div>
                        @php $currentHour = $hour; @endphp
                    @endif
                    
                    <!-- Card do Compromisso -->
                    <div class="group relative ml-20">
                        <div class="hover-scale bg-white rounded-2xl shadow-lg border border-gray-200 p-6 cursor-pointer hover:shadow-xl transition-all duration-300"
                             onclick="showEventDetails({{ json_encode($agenda) }})">
                            
                            <!-- Status Indicator -->
                            <div class="absolute -left-4 top-6 w-4 h-4 bg-gradient-to-br {{ $statusColor }} rounded-full shadow-lg"></div>
                            
                            <!-- Conteúdo -->
                            <div class="flex items-start justify-between">
                                <div class="flex-1">
                                    <div class="flex items-center mb-2">
                                        <h3 class="text-lg font-semibold text-gray-900 mr-3">{{ $agenda->titulo }}</h3>
                                        @if($agenda->tipo_reuniao === 'online')
                                            <i class="fas fa-video text-blue-500"></i>
                                        @elseif($agenda->tipo_reuniao === 'presencial')
                                            <i class="fas fa-map-marker-alt text-green-500"></i>
                                        @else
                                            <i class="fas fa-users text-purple-500"></i>
                                        @endif
                                    </div>
                                    
                                    <div class="flex items-center text-sm text-gray-600 mb-2">
                                        <i class="fas fa-clock mr-2"></i>
                                        <span>{{ $startTime->format('H:i') }} - {{ $endTime->format('H:i') }}</span>
                                        <span class="mx-2">•</span>
                                        <span>{{ $startTime->diffInMinutes($endTime) }} min</span>
                                    </div>
                                    
                                    @if($agenda->descricao)
                                        <p class="text-gray-600 text-sm line-clamp-2">{{ $agenda->descricao }}</p>
                                    @endif
                                </div>
                                
                                <!-- Status Badge -->
                                <div class="ml-4">
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium
                                        {{ $agenda->status_aprovacao === 'confirmado' || $agenda->status_aprovacao === 'aprovada' ? 'bg-green-100 text-green-800' : 
                                           ($agenda->status_aprovacao === 'pendente' ? 'bg-yellow-100 text-yellow-800' : 
                                           ($agenda->status_aprovacao === 'cancelado' || $agenda->status_aprovacao === 'recusada' ? 'bg-red-100 text-red-800' : 'bg-blue-100 text-blue-800')) }}">
                                        @if($agenda->status_aprovacao === 'confirmado' || $agenda->status_aprovacao === 'aprovada')
                                            <i class="fas fa-check mr-1"></i>Confirmado
                                        @elseif($agenda->status_aprovacao === 'pendente')
                                            <i class="fas fa-clock mr-1"></i>Pendente
                                        @elseif($agenda->status_aprovacao === 'cancelado' || $agenda->status_aprovacao === 'recusada')
                                            <i class="fas fa-times mr-1"></i>Cancelado
                                        @else
                                            <i class="fas fa-calendar mr-1"></i>Automático
                                        @endif
                                    </span>
                                </div>
                            </div>
                            
                            <!-- Ações Rápidas (aparecem no hover) -->
                            <div class="absolute top-4 right-4 opacity-0 group-hover:opacity-100 transition-opacity duration-200">
                                <div class="flex space-x-2">
                                    <button onclick="event.stopPropagation(); editEvent({{ $agenda->id }})" 
                                            class="w-8 h-8 bg-blue-500 hover:bg-blue-600 text-white rounded-full flex items-center justify-center transition-colors duration-200"
                                            title="Editar">
                                        <i class="fas fa-edit text-xs"></i>
                                    </button>
                                    <button onclick="event.stopPropagation(); deleteEvent({{ $agenda->id }})" 
                                            class="w-8 h-8 bg-red-500 hover:bg-red-600 text-white rounded-full flex items-center justify-center transition-colors duration-200"
                                            title="Excluir">
                                        <i class="fas fa-trash text-xs"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <!-- Estado Vazio -->
            <div class="text-center py-16">
                <div class="w-24 h-24 bg-gradient-to-br from-gray-100 to-gray-200 rounded-full flex items-center justify-center mx-auto mb-6">
                    <i class="fas fa-calendar-day text-gray-400 text-3xl"></i>
                </div>
                <h3 class="text-xl font-semibold text-gray-900 mb-2">Nenhum compromisso hoje</h3>
                <p class="text-gray-600 mb-6">Você não tem compromissos agendados para este dia.</p>
                <a href="{{ route('licenciado.agenda.create') }}" 
                   class="inline-flex items-center px-6 py-3 bg-gradient-to-r from-blue-500 to-indigo-600 text-white font-semibold rounded-2xl shadow-lg hover:shadow-xl transform hover:scale-105 transition-all duration-300">
                    <i class="fas fa-plus mr-2"></i>
                    Agendar Compromisso
                </a>
            </div>
        @endif
    </div>
</div>
