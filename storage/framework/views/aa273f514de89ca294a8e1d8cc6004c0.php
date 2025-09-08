<?php $__env->startSection('title', 'Suporte'); ?>
<?php $__env->startSection('subtitle', 'Central de ajuda e atendimento'); ?>

<?php $__env->startSection('content'); ?>
<div class="space-y-6">
    <!-- Contact Options -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <div class="card p-6 text-center hover:shadow-lg transition-shadow cursor-pointer">
            <div class="w-16 h-16 bg-blue-100 rounded-full flex items-center justify-center mx-auto mb-4">
                <i class="fas fa-comments text-blue-600 text-2xl"></i>
            </div>
            <h3 class="font-semibold text-gray-800 mb-2">Chat Online</h3>
            <p class="text-sm text-gray-600 mb-4">Fale conosco em tempo real</p>
            <button class="btn-primary w-full">Iniciar Chat</button>
        </div>

        <div class="card p-6 text-center hover:shadow-lg transition-shadow cursor-pointer">
            <div class="w-16 h-16 bg-green-100 rounded-full flex items-center justify-center mx-auto mb-4">
                <i class="fas fa-phone text-green-600 text-2xl"></i>
            </div>
            <h3 class="font-semibold text-gray-800 mb-2">Telefone</h3>
            <p class="text-sm text-gray-600 mb-4">(11) 3000-0000</p>
            <button class="btn-primary w-full">Ligar Agora</button>
        </div>

        <div class="card p-6 text-center hover:shadow-lg transition-shadow cursor-pointer">
            <div class="w-16 h-16 bg-purple-100 rounded-full flex items-center justify-center mx-auto mb-4">
                <i class="fas fa-envelope text-purple-600 text-2xl"></i>
            </div>
            <h3 class="font-semibold text-gray-800 mb-2">E-mail</h3>
            <p class="text-sm text-gray-600 mb-4">suporte@dspay.com.br</p>
            <button class="btn-primary w-full">Enviar E-mail</button>
        </div>
    </div>

    <!-- FAQ Section -->
    <div class="card p-6">
        <h3 class="text-xl font-semibold text-gray-800 mb-6">Perguntas Frequentes</h3>
        
        <div class="space-y-4">
            <div class="border border-gray-200 rounded-lg">
                <button class="w-full p-4 text-left flex items-center justify-between hover:bg-gray-50 faq-toggle">
                    <span class="font-medium text-gray-800">Como visualizar minhas comissões?</span>
                    <i class="fas fa-chevron-down text-gray-400"></i>
                </button>
                <div class="p-4 border-t border-gray-200 bg-gray-50 faq-content">
                    <p class="text-gray-600">
                        Você pode visualizar suas comissões acessando a seção "Comissões" no menu lateral. 
                        Lá você encontrará o detalhamento por modalidade, histórico de pagamentos e um simulador 
                        para calcular comissões futuras.
                    </p>
                </div>
            </div>

            <div class="border border-gray-200 rounded-lg">
                <button class="w-full p-4 text-left flex items-center justify-between hover:bg-gray-50 faq-toggle">
                    <span class="font-medium text-gray-800">Como adicionar um novo estabelecimento?</span>
                    <i class="fas fa-chevron-down text-gray-400"></i>
                </button>
                <div class="p-4 border-t border-gray-200 bg-gray-50 faq-content hidden">
                    <p class="text-gray-600">
                        Para adicionar um novo estabelecimento, vá até a seção "Estabelecimentos" e clique no 
                        botão "Novo Estabelecimento". Preencha as informações solicitadas e aguarde a aprovação 
                        da nossa equipe.
                    </p>
                </div>
            </div>

            <div class="border border-gray-200 rounded-lg">
                <button class="w-full p-4 text-left flex items-center justify-between hover:bg-gray-50 faq-toggle">
                    <span class="font-medium text-gray-800">Quando recebo minhas comissões?</span>
                    <i class="fas fa-chevron-down text-gray-400"></i>
                </button>
                <div class="p-4 border-t border-gray-200 bg-gray-50 faq-content hidden">
                    <p class="text-gray-600">
                        As comissões são pagas mensalmente, sempre no 5º dia útil do mês seguinte. 
                        Por exemplo, as comissões de março são pagas no 5º dia útil de abril.
                    </p>
                </div>
            </div>

            <div class="border border-gray-200 rounded-lg">
                <button class="w-full p-4 text-left flex items-center justify-between hover:bg-gray-50 faq-toggle">
                    <span class="font-medium text-gray-800">Como gerar relatórios?</span>
                    <i class="fas fa-chevron-down text-gray-400"></i>
                </button>
                <div class="p-4 border-t border-gray-200 bg-gray-50 faq-content hidden">
                    <p class="text-gray-600">
                        Na seção "Relatórios" você pode gerar relatórios personalizados ou usar os modelos 
                        pré-definidos. Escolha o período, formato (PDF, Excel ou CSV) e clique em "Gerar Relatório".
                    </p>
                </div>
            </div>

            <div class="border border-gray-200 rounded-lg">
                <button class="w-full p-4 text-left flex items-center justify-between hover:bg-gray-50 faq-toggle">
                    <span class="font-medium text-gray-800">Como alterar meus dados pessoais?</span>
                    <i class="fas fa-chevron-down text-gray-400"></i>
                </button>
                <div class="p-4 border-t border-gray-200 bg-gray-50 faq-content hidden">
                    <p class="text-gray-600">
                        Acesse a seção "Meu Perfil" para alterar suas informações pessoais, endereço e 
                        configurações de segurança. Não se esqueça de salvar as alterações.
                    </p>
                </div>
            </div>
        </div>
    </div>

    <!-- Support Form -->
    <div class="card p-6">
        <h3 class="text-xl font-semibold text-gray-800 mb-6">Abrir Chamado de Suporte</h3>
        
        <form class="space-y-6">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Categoria</label>
                    <select class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                        <option>Selecione uma categoria</option>
                        <option>Problemas com Vendas</option>
                        <option>Questões sobre Comissões</option>
                        <option>Estabelecimentos</option>
                        <option>Relatórios</option>
                        <option>Problemas Técnicos</option>
                        <option>Outros</option>
                    </select>
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Prioridade</label>
                    <select class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                        <option>Baixa</option>
                        <option>Média</option>
                        <option>Alta</option>
                        <option>Urgente</option>
                    </select>
                </div>
            </div>
            
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Assunto</label>
                <input type="text" placeholder="Descreva brevemente o problema" 
                       class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
            </div>
            
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Descrição Detalhada</label>
                <textarea rows="6" placeholder="Descreva o problema em detalhes..." 
                          class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"></textarea>
            </div>
            
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Anexos (opcional)</label>
                <div class="border-2 border-dashed border-gray-300 rounded-lg p-6 text-center">
                    <i class="fas fa-cloud-upload-alt text-gray-400 text-3xl mb-2"></i>
                    <p class="text-gray-600 mb-2">Clique para enviar arquivos ou arraste aqui</p>
                    <p class="text-sm text-gray-500">PNG, JPG, PDF até 10MB</p>
                    <input type="file" class="hidden" multiple accept=".png,.jpg,.jpeg,.pdf">
                </div>
            </div>
            
            <div class="flex justify-end">
                <button type="submit" class="btn-primary">
                    <i class="fas fa-paper-plane mr-2"></i>
                    Enviar Chamado
                </button>
            </div>
        </form>
    </div>

    <!-- My Tickets -->
    <div class="card">
        <div class="p-6 border-b border-gray-200">
            <h3 class="text-lg font-semibold text-gray-800">Meus Chamados</h3>
        </div>
        
        <div class="table-responsive">
            <table class="table">
                <thead>
                    <tr>
                        <th>Chamado</th>
                        <th>Assunto</th>
                        <th>Categoria</th>
                        <th>Status</th>
                        <th>Data</th>
                        <th>Ações</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>
                            <span class="font-mono text-sm text-blue-600">#001234</span>
                        </td>
                        <td>
                            <span class="font-medium">Problema com comissão de março</span>
                        </td>
                        <td>
                            <span class="px-2 py-1 bg-blue-100 text-blue-800 rounded-full text-xs">Comissões</span>
                        </td>
                        <td>
                            <span class="status-badge ativo">Resolvido</span>
                        </td>
                        <td><?php echo e(now()->subDays(2)->format('d/m/Y H:i')); ?></td>
                        <td>
                            <button class="text-blue-600 hover:text-blue-800">
                                <i class="fas fa-eye"></i>
                            </button>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <span class="font-mono text-sm text-blue-600">#001233</span>
                        </td>
                        <td>
                            <span class="font-medium">Dúvida sobre relatórios</span>
                        </td>
                        <td>
                            <span class="px-2 py-1 bg-purple-100 text-purple-800 rounded-full text-xs">Relatórios</span>
                        </td>
                        <td>
                            <span class="status-badge pendente">Em Andamento</span>
                        </td>
                        <td><?php echo e(now()->subDays(1)->format('d/m/Y H:i')); ?></td>
                        <td>
                            <button class="text-blue-600 hover:text-blue-800">
                                <i class="fas fa-eye"></i>
                            </button>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php $__env->startPush('scripts'); ?>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // FAQ Toggle
    const faqToggles = document.querySelectorAll('.faq-toggle');
    
    faqToggles.forEach(toggle => {
        toggle.addEventListener('click', function() {
            const content = this.nextElementSibling;
            const icon = this.querySelector('i');
            
            if (content.classList.contains('hidden')) {
                content.classList.remove('hidden');
                icon.classList.remove('fa-chevron-down');
                icon.classList.add('fa-chevron-up');
            } else {
                content.classList.add('hidden');
                icon.classList.remove('fa-chevron-up');
                icon.classList.add('fa-chevron-down');
            }
        });
    });

    // File Upload
    const fileInput = document.querySelector('input[type="file"]');
    const uploadArea = fileInput.parentElement;
    
    uploadArea.addEventListener('click', () => fileInput.click());
    
    uploadArea.addEventListener('dragover', (e) => {
        e.preventDefault();
        uploadArea.classList.add('border-blue-500', 'bg-blue-50');
    });
    
    uploadArea.addEventListener('dragleave', () => {
        uploadArea.classList.remove('border-blue-500', 'bg-blue-50');
    });
    
    uploadArea.addEventListener('drop', (e) => {
        e.preventDefault();
        uploadArea.classList.remove('border-blue-500', 'bg-blue-50');
        
        const files = e.dataTransfer.files;
        if (files.length > 0) {
            fileInput.files = files;
            // Handle file upload here
        }
    });
});
</script>
<?php $__env->stopPush(); ?>

<?php echo $__env->make('layouts.licenciado', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /Applications/MAMP/htdocs/orbita/resources/views/licenciado/suporte.blade.php ENDPATH**/ ?>