{{-- Componente de Card de Métrica --}}
<div class="bg-white rounded-lg shadow-sm border p-6">
    <div class="flex items-center">
        <div class="flex-shrink-0">
            <div class="w-12 h-12 bg-{{ $color }}-100 rounded-lg flex items-center justify-center">
                <i class="fas fa-{{ $icon }} text-{{ $color }}-600 text-xl"></i>
            </div>
        </div>
        <div class="ml-4 flex-1">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600">{{ $title }}</p>
                    <p class="text-2xl font-bold text-gray-900">{{ number_format($value) }}</p>
                </div>
                @if(isset($trend))
                    <div class="text-right">
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ str_starts_with($trend, '+') ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                            @if(str_starts_with($trend, '+'))
                                <i class="fas fa-arrow-up mr-1"></i>
                            @else
                                <i class="fas fa-arrow-down mr-1"></i>
                            @endif
                            {{ $trend }}
                        </span>
                        <p class="text-xs text-gray-500 mt-1">vs. mês anterior</p>
                    </div>
                @endif
            </div>
        </div>
    </div>
    
    @if(isset($description))
        <div class="mt-4 pt-4 border-t border-gray-200">
            <p class="text-sm text-gray-600">{{ $description }}</p>
        </div>
    @endif
</div>
