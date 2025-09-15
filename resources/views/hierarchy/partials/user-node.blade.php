@php
    $colors = [
        'licenciado_l1' => ['bg' => 'bg-yellow-500', 'border' => 'border-yellow-200', 'bg-light' => 'bg-yellow-50', 'text' => 'text-yellow-800', 'bg-badge' => 'bg-yellow-100'],
        'licenciado_l2' => ['bg' => 'bg-orange-500', 'border' => 'border-orange-200', 'bg-light' => 'bg-orange-50', 'text' => 'text-orange-800', 'bg-badge' => 'bg-orange-100'],
        'licenciado_l3' => ['bg' => 'bg-red-500', 'border' => 'border-red-200', 'bg-light' => 'bg-red-50', 'text' => 'text-red-800', 'bg-badge' => 'bg-red-100'],
        'operacao' => ['bg' => 'bg-blue-500', 'border' => 'border-blue-200', 'bg-light' => 'bg-blue-50', 'text' => 'text-blue-800', 'bg-badge' => 'bg-blue-100'],
        'white_label' => ['bg' => 'bg-green-500', 'border' => 'border-green-200', 'bg-light' => 'bg-green-50', 'text' => 'text-green-800', 'bg-badge' => 'bg-green-100'],
    ];
    
    $nodeColors = $colors[$user->node_type] ?? $colors['licenciado_l1'];
    $hasChildren = $user->children()->count() > 0;
    $isRoot = $isRoot ?? false;
@endphp

<div x-data="{ userExpanded: {{ $hasChildren ? 'true' : 'false' }} }" class="{{ $isRoot ? 'border-2 border-indigo-300' : '' }}">
    <div class="flex items-center p-2 {{ $nodeColors['bg-light'] }} rounded border {{ $nodeColors['border'] }} {{ $hasChildren ? 'cursor-pointer' : '' }}" 
         @if($hasChildren) @click="userExpanded = !userExpanded" @endif>
        
        @if($hasChildren)
        <div class="flex-shrink-0">
            <svg class="w-3 h-3 text-gray-400 transition-transform" :class="{ 'rotate-90': userExpanded }" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
            </svg>
        </div>
        @else
        <div class="w-3 h-3 flex-shrink-0"></div>
        @endif
        
        <div class="flex-shrink-0 ml-2">
            <div class="w-5 h-5 {{ $nodeColors['bg'] }} rounded-full flex items-center justify-center">
                <span class="text-white text-xs font-medium">{{ substr($user->name, 0, 1) }}</span>
            </div>
        </div>
        
        <div class="ml-2 flex-1">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-900">{{ $user->name }}</p>
                    <p class="text-xs text-gray-500">{{ $user->email }}</p>
                </div>
                <div class="flex items-center space-x-2">
                    @if(!$user->is_active)
                    <span class="inline-flex items-center px-1.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                        Inativo
                    </span>
                    @endif
                    
                    <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium {{ $nodeColors['bg-badge'] }} {{ $nodeColors['text'] }}">
                        {{ ucfirst(str_replace('_', ' ', $user->node_type)) }}
                    </span>
                    
                    @if($user->hierarchy_level)
                    <span class="inline-flex items-center px-1.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                        N{{ $user->hierarchy_level }}
                    </span>
                    @endif
                </div>
            </div>
            
            <!-- Informações adicionais -->
            <div class="mt-1 flex items-center space-x-4 text-xs text-gray-500">
                @if($user->whiteLabel)
                <span>WL: {{ $user->whiteLabel->display_name }}</span>
                @endif
                
                @if($user->orbitaOperacao)
                <span>Op: {{ $user->orbitaOperacao->display_name }}</span>
                @endif
                
                @if($user->children()->count() > 0)
                <span>{{ $user->children()->count() }} filho(s)</span>
                @endif
                
                @if($user->getAllDescendants()->count() > 0)
                <span>{{ $user->getAllDescendants()->count() }} descendente(s)</span>
                @endif
            </div>
        </div>
        
        <!-- Ações -->
        <div class="flex-shrink-0 ml-2">
            <div class="flex items-center space-x-1">
                @if($user->canHaveChildren())
                <button type="button" class="p-1 text-gray-400 hover:text-gray-600" title="Criar filho">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                    </svg>
                </button>
                @endif
                
                <button type="button" class="p-1 text-gray-400 hover:text-gray-600" title="Ver detalhes">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </button>
                
                @if(Auth::user()->canImpersonate($user))
                <button type="button" class="p-1 text-gray-400 hover:text-gray-600" title="Impersonar">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4"/>
                    </svg>
                </button>
                @endif
            </div>
        </div>
    </div>
    
    @if($hasChildren)
    <div x-show="userExpanded" x-transition class="ml-6 mt-1 space-y-1">
        @foreach($user->children as $child)
            @include('hierarchy.partials.user-node', ['user' => $child, 'level' => $child->node_type])
        @endforeach
    </div>
    @endif
</div>
