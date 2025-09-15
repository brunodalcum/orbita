{{-- Mini visualização da árvore hierárquica --}}
<div class="hierarchy-tree-mini">
    <div class="flex flex-col items-center space-y-4">
        @if($context['type'] === 'super_admin')
            <!-- Super Admin View -->
            <div class="flex flex-col items-center">
                <div class="w-12 h-12 bg-red-100 rounded-full flex items-center justify-center border-2 border-red-300">
                    <i class="fas fa-crown text-red-600"></i>
                </div>
                <span class="text-xs text-gray-600 mt-1">Super Admin</span>
            </div>
            
            <div class="w-px h-4 bg-gray-300"></div>
            
            <div class="flex space-x-8">
                <div class="flex flex-col items-center">
                    <div class="w-10 h-10 bg-blue-100 rounded-full flex items-center justify-center border-2 border-blue-300">
                        <i class="fas fa-building text-blue-600 text-sm"></i>
                    </div>
                    <span class="text-xs text-gray-600 mt-1">Operações</span>
                    <span class="text-xs font-medium text-blue-600">{{ $metrics['operacoes'] ?? 0 }}</span>
                </div>
                
                <div class="flex flex-col items-center">
                    <div class="w-10 h-10 bg-green-100 rounded-full flex items-center justify-center border-2 border-green-300">
                        <i class="fas fa-tag text-green-600 text-sm"></i>
                    </div>
                    <span class="text-xs text-gray-600 mt-1">White Labels</span>
                    <span class="text-xs font-medium text-green-600">{{ $metrics['white_labels'] ?? 0 }}</span>
                </div>
            </div>
            
        @elseif($context['type'] === 'licenciado')
            <!-- Licenciado View -->
            <div class="flex flex-col items-center">
                <div class="w-12 h-12 bg-blue-100 rounded-full flex items-center justify-center border-2 border-blue-300">
                    <i class="fas fa-user text-blue-600"></i>
                </div>
                <span class="text-xs text-gray-600 mt-1">{{ $user->name }}</span>
                <span class="text-xs text-blue-600 font-medium">{{ ucfirst(str_replace('_', ' ', $user->node_type)) }}</span>
            </div>
            
            @if(($metrics['direct_children'] ?? 0) > 0)
                <div class="w-px h-4 bg-gray-300"></div>
                
                <div class="flex space-x-4">
                    @for($i = 0; $i < min(3, $metrics['direct_children']); $i++)
                        <div class="flex flex-col items-center">
                            <div class="w-8 h-8 bg-green-100 rounded-full flex items-center justify-center border border-green-300">
                                <i class="fas fa-user text-green-600 text-xs"></i>
                            </div>
                        </div>
                    @endfor
                    
                    @if(($metrics['direct_children'] ?? 0) > 3)
                        <div class="flex flex-col items-center">
                            <div class="w-8 h-8 bg-gray-100 rounded-full flex items-center justify-center border border-gray-300">
                                <span class="text-xs text-gray-600">+{{ $metrics['direct_children'] - 3 }}</span>
                            </div>
                        </div>
                    @endif
                </div>
            @endif
            
        @else
            <!-- Default View -->
            <div class="flex flex-col items-center">
                <div class="w-12 h-12 bg-gray-100 rounded-full flex items-center justify-center border-2 border-gray-300">
                    <i class="fas fa-user text-gray-600"></i>
                </div>
                <span class="text-xs text-gray-600 mt-1">{{ $user->name }}</span>
            </div>
        @endif
    </div>
    
    <!-- Legenda -->
    <div class="mt-6 pt-4 border-t border-gray-200">
        <div class="flex justify-center space-x-6 text-xs text-gray-500">
            <div class="flex items-center">
                <div class="w-3 h-3 bg-blue-100 rounded-full mr-2"></div>
                <span>Operação</span>
            </div>
            <div class="flex items-center">
                <div class="w-3 h-3 bg-green-100 rounded-full mr-2"></div>
                <span>White Label</span>
            </div>
            <div class="flex items-center">
                <div class="w-3 h-3 bg-yellow-100 rounded-full mr-2"></div>
                <span>Licenciado</span>
            </div>
        </div>
    </div>
</div>

<style>
.hierarchy-tree-mini {
    min-height: 200px;
    display: flex;
    flex-direction: column;
    justify-content: center;
}
</style>
