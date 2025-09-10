let currentAgendaId = null;

// Função para confirmar exclusão
function confirmDeleteAgenda(id, title) {
    currentAgendaId = id;
    document.getElementById('deleteAgendaTitle').textContent = title;
    showModal('deleteModal');
}

// Função para excluir agenda
function deleteAgenda() {
    if (!currentAgendaId) return;
    
    const btn = document.getElementById('confirmDeleteBtn');
    const originalText = btn.innerHTML;
    btn.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i>Excluindo...';
    btn.disabled = true;
    
    fetch(`/agenda/${currentAgendaId}`, {
        method: 'DELETE',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
            'Accept': 'application/json',
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            showToast('Reunião excluída com sucesso!', 'success');
            closeDeleteModal();
            location.reload();
        } else {
            showToast(data.message || 'Erro ao excluir reunião', 'error');
        }
    })
    .catch(error => {
        console.error('Erro:', error);
        showToast('Erro ao excluir reunião', 'error');
    })
    .finally(() => {
        btn.innerHTML = originalText;
        btn.disabled = false;
    });
}

// Função para ver detalhes
function viewAgendaDetails(id) {
    fetch(`/agenda/${id}`)
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            const agenda = data.agenda;
            const licenciado = data.licenciado;
            
            let participantesHtml = '';
            if (agenda.participantes && agenda.participantes.length > 0) {
                participantesHtml = agenda.participantes.map(email => 
                    `<span class="inline-block bg-blue-100 text-blue-800 px-3 py-1 rounded-full text-sm mr-2 mb-2">${email}</span>`
                ).join('');
            } else {
                participantesHtml = '<span class="text-gray-500 italic">Nenhum participante adicional</span>';
            }
            
            const detailsHtml = `
                <div class="bg-gradient-to-r from-blue-50 to-purple-50 p-6 rounded-xl border border-blue-200">
                    <h4 class="text-xl font-bold text-gray-900 mb-2">${agenda.titulo}</h4>
                    <p class="text-gray-600">${agenda.descricao || 'Sem descrição'}</p>
                </div>
                
                <div class="grid md:grid-cols-2 gap-6">
                    <div class="space-y-4">
                        <div class="bg-gray-50 p-4 rounded-xl">
                            <h5 class="font-semibold text-gray-900 mb-3 flex items-center">
                                <i class="fas fa-clock text-blue-600 mr-2"></i>
                                Data e Horário
                            </h5>
                            <div class="space-y-2 text-sm">
                                <div class="flex items-center">
                                    <span class="text-gray-600 w-16">Início:</span>
                                    <span class="font-medium">${formatDateTime(agenda.data_inicio)}</span>
                                </div>
                                <div class="flex items-center">
                                    <span class="text-gray-600 w-16">Fim:</span>
                                    <span class="font-medium">${formatDateTime(agenda.data_fim)}</span>
                                </div>
                            </div>
                        </div>
                        
                        <div class="bg-gray-50 p-4 rounded-xl">
                            <h5 class="font-semibold text-gray-900 mb-3 flex items-center">
                                <i class="fas fa-info-circle text-blue-600 mr-2"></i>
                                Informações
                            </h5>
                            <div class="space-y-2 text-sm">
                                <div class="flex items-center">
                                    <span class="text-gray-600 w-20">Tipo:</span>
                                    <span class="font-medium capitalize">${agenda.tipo_reuniao}</span>
                                </div>
                                <div class="flex items-center">
                                    <span class="text-gray-600 w-20">Status:</span>
                                    <span class="font-medium capitalize">${agenda.status.replace('_', ' ')}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="space-y-4">
                        ${licenciado ? `
                        <div class="bg-green-50 p-4 rounded-xl border border-green-200">
                            <h5 class="font-semibold text-gray-900 mb-3 flex items-center">
                                <i class="fas fa-user-tie text-green-600 mr-2"></i>
                                Licenciado
                            </h5>
                            <div class="space-y-2 text-sm">
                                <div><strong>${licenciado.razao_social}</strong></div>
                                <div class="text-gray-600">${licenciado.email}</div>
                                ${licenciado.nome_fantasia ? `<div class="text-gray-600">${licenciado.nome_fantasia}</div>` : ''}
                            </div>
                        </div>
                        ` : ''}
                        
                        ${agenda.meet_link || agenda.google_meet_link ? `
                        <div class="bg-blue-50 p-4 rounded-xl border border-blue-200">
                            <h5 class="font-semibold text-gray-900 mb-3 flex items-center">
                                <i class="fas fa-video text-blue-600 mr-2"></i>
                                Link da Reunião
                            </h5>
                            <a href="${agenda.meet_link || agenda.google_meet_link}" target="_blank" 
                               class="text-blue-600 hover:text-blue-800 text-sm break-all">
                                ${agenda.meet_link || agenda.google_meet_link}
                            </a>
                        </div>
                        ` : ''}
                    </div>
                </div>
                
                <div class="bg-gray-50 p-4 rounded-xl">
                    <h5 class="font-semibold text-gray-900 mb-3 flex items-center">
                        <i class="fas fa-users text-blue-600 mr-2"></i>
                        Participantes
                    </h5>
                    <div class="flex flex-wrap">
                        ${participantesHtml}
                    </div>
                </div>
                
                ${agenda.confirmacoes ? `
                <div class="bg-blue-50 p-4 rounded-xl border border-blue-200">
                    <h5 class="font-semibold text-gray-900 mb-3 flex items-center">
                        <i class="fas fa-check-circle text-blue-600 mr-2"></i>
                        Status das Confirmações
                    </h5>
                    <div class="grid grid-cols-3 gap-4 mb-4">
                        <div class="text-center p-3 bg-green-100 rounded-lg">
                            <div class="text-2xl font-bold text-green-600">${agenda.confirmacoes.confirmados || 0}</div>
                            <div class="text-sm text-green-700">Confirmados</div>
                        </div>
                        <div class="text-center p-3 bg-yellow-100 rounded-lg">
                            <div class="text-2xl font-bold text-yellow-600">${agenda.confirmacoes.pendentes || 0}</div>
                            <div class="text-sm text-yellow-700">Pendentes</div>
                        </div>
                        <div class="text-center p-3 bg-red-100 rounded-lg">
                            <div class="text-2xl font-bold text-red-600">${agenda.confirmacoes.recusados || 0}</div>
                            <div class="text-sm text-red-700">Recusados</div>
                        </div>
                    </div>
                    
                    ${agenda.detalhes_confirmacoes && agenda.detalhes_confirmacoes.length > 0 ? `
                    <div class="space-y-2">
                        <h6 class="font-medium text-gray-800 mb-2">Detalhes por Participante:</h6>
                        ${agenda.detalhes_confirmacoes.map(conf => `
                            <div class="flex items-center justify-between p-2 bg-white rounded border">
                                <span class="text-sm">${conf.email}</span>
                                <span class="px-2 py-1 rounded-full text-xs font-medium ${
                                    conf.status === 'confirmado' ? 'bg-green-100 text-green-800' :
                                    conf.status === 'recusado' ? 'bg-red-100 text-red-800' :
                                    'bg-yellow-100 text-yellow-800'
                                }">
                                    ${conf.status === 'confirmado' ? '✅ Confirmado' :
                                      conf.status === 'recusado' ? '❌ Recusado' :
                                      '⏰ Pendente'}
                                </span>
                            </div>
                        `).join('')}
                    </div>
                    ` : ''}
                </div>
                ` : ''}
            `;
            
            document.getElementById('agendaDetailsContent').innerHTML = detailsHtml;
            showModal('detailsModal');
        } else {
            showToast('Erro ao carregar detalhes', 'error');
        }
    })
    .catch(error => {
        console.error('Erro:', error);
        showToast('Erro ao carregar detalhes', 'error');
    });
}

// Função para editar agenda
function editAgenda(id) {
    fetch(`/agenda/${id}/edit`)
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            const agenda = data.agenda;
            
            // Buscar licenciados para o select
            fetch('/licenciados/api/list')
            .then(response => response.json())
            .then(licenciadosData => {
                const licenciados = licenciadosData.licenciados || [];
                
                let licenciadosOptions = '<option value="">Selecione um licenciado (opcional)</option>';
                licenciados.forEach(licenciado => {
                    const selected = agenda.licenciado_id == licenciado.id ? 'selected' : '';
                    licenciadosOptions += `<option value="${licenciado.id}" ${selected}>${licenciado.razao_social}</option>`;
                });
                
                const participantesText = agenda.participantes ? agenda.participantes.join(', ') : '';
                
                const editFormHtml = `
                    <div class="grid md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">
                                <i class="fas fa-heading text-blue-600 mr-2"></i>
                                Título *
                            </label>
                            <input type="text" name="titulo" value="${agenda.titulo}" required
                                   class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                        </div>
                        
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">
                                <i class="fas fa-user-tie text-blue-600 mr-2"></i>
                                Licenciado
                            </label>
                            <select name="licenciado_id" class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                                ${licenciadosOptions}
                            </select>
                        </div>
                        
                        <div class="md:col-span-2">
                            <label class="block text-sm font-semibold text-gray-700 mb-2">
                                <i class="fas fa-align-left text-blue-600 mr-2"></i>
                                Descrição
                            </label>
                            <textarea name="descricao" rows="3"
                                      class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent">${agenda.descricao || ''}</textarea>
                        </div>
                        
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">
                                <i class="fas fa-calendar text-blue-600 mr-2"></i>
                                Data e Hora de Início *
                            </label>
                            <input type="datetime-local" name="data_inicio" value="${formatDateTimeLocal(agenda.data_inicio)}" required
                                   class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                        </div>
                        
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">
                                <i class="fas fa-calendar-check text-blue-600 mr-2"></i>
                                Data e Hora de Fim *
                            </label>
                            <input type="datetime-local" name="data_fim" value="${formatDateTimeLocal(agenda.data_fim)}" required
                                   class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                        </div>
                        
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">
                                <i class="fas fa-video text-blue-600 mr-2"></i>
                                Tipo de Reunião *
                            </label>
                            <select name="tipo_reuniao" required
                                    class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                                <option value="online" ${agenda.tipo_reuniao === 'online' ? 'selected' : ''}>Online</option>
                                <option value="presencial" ${agenda.tipo_reuniao === 'presencial' ? 'selected' : ''}>Presencial</option>
                                <option value="hibrida" ${agenda.tipo_reuniao === 'hibrida' ? 'selected' : ''}>Híbrida</option>
                            </select>
                        </div>
                        
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">
                                <i class="fas fa-link text-blue-600 mr-2"></i>
                                Link da Reunião
                            </label>
                            <input type="url" name="meet_link" value="${agenda.meet_link || ''}"
                                   class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                   placeholder="https://meet.google.com/...">
                        </div>
                        
                        <div class="md:col-span-2">
                            <label class="block text-sm font-semibold text-gray-700 mb-2">
                                <i class="fas fa-users text-blue-600 mr-2"></i>
                                Participantes Adicionais
                            </label>
                            <textarea name="participantes" rows="3"
                                      class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                      placeholder="Digite os emails separados por vírgula">${participantesText}</textarea>
                            <p class="text-sm text-gray-500 mt-1">Separe múltiplos emails por vírgula</p>
                        </div>
                    </div>
                `;
                
                document.getElementById('editFormContent').innerHTML = editFormHtml;
                document.getElementById('editAgendaForm').action = `/agenda/${id}`;
                showModal('editModal');
            });
        } else {
            showToast('Erro ao carregar dados para edição', 'error');
        }
    })
    .catch(error => {
        console.error('Erro:', error);
        showToast('Erro ao carregar dados para edição', 'error');
    });
}

// Funções utilitárias
function showModal(modalId) {
    const modal = document.getElementById(modalId);
    const content = document.getElementById(modalId + 'Content');
    
    modal.classList.remove('hidden');
    modal.classList.add('flex');
    
    setTimeout(() => {
        content.classList.remove('scale-95', 'opacity-0');
        content.classList.add('scale-100', 'opacity-100');
    }, 10);
}

function closeDeleteModal() {
    hideModal('deleteModal');
    currentAgendaId = null;
}

function closeDetailsModal() {
    hideModal('detailsModal');
}

function closeEditModal() {
    hideModal('editModal');
}

function hideModal(modalId) {
    const modal = document.getElementById(modalId);
    const content = document.getElementById(modalId + 'Content');
    
    content.classList.remove('scale-100', 'opacity-100');
    content.classList.add('scale-95', 'opacity-0');
    
    setTimeout(() => {
        modal.classList.add('hidden');
        modal.classList.remove('flex');
    }, 300);
}

function formatDateTime(dateTimeString) {
    const date = new Date(dateTimeString);
    return date.toLocaleString('pt-BR', {
        day: '2-digit',
        month: '2-digit', 
        year: 'numeric',
        hour: '2-digit',
        minute: '2-digit'
    });
}

function formatDateTimeLocal(dateTimeString) {
    const date = new Date(dateTimeString);
    const year = date.getFullYear();
    const month = String(date.getMonth() + 1).padStart(2, '0');
    const day = String(date.getDate()).padStart(2, '0');
    const hours = String(date.getHours()).padStart(2, '0');
    const minutes = String(date.getMinutes()).padStart(2, '0');
    
    return `${year}-${month}-${day}T${hours}:${minutes}`;
}

function showToast(message, type = 'success') {
    // Criar toast
    const toast = document.createElement('div');
    toast.className = `fixed top-4 right-4 z-50 px-6 py-4 rounded-lg shadow-lg transform translate-x-full transition-transform duration-300 ${
        type === 'success' ? 'bg-green-600 text-white' : 'bg-red-600 text-white'
    }`;
    toast.innerHTML = `
        <div class="flex items-center">
            <i class="fas ${type === 'success' ? 'fa-check-circle' : 'fa-exclamation-circle'} mr-3"></i>
            ${message}
        </div>
    `;
    
    document.body.appendChild(toast);
    
    // Mostrar toast
    setTimeout(() => {
        toast.classList.remove('translate-x-full');
    }, 10);
    
    // Remover toast
    setTimeout(() => {
        toast.classList.add('translate-x-full');
        setTimeout(() => {
            document.body.removeChild(toast);
        }, 300);
    }, 3000);
}

// Event listeners
document.addEventListener('DOMContentLoaded', function() {
    // Submissão do formulário de edição
    const editForm = document.getElementById('editAgendaForm');
    if (editForm) {
        editForm.addEventListener('submit', function(e) {
            e.preventDefault();
            
            const formData = new FormData(this);
            const submitBtn = this.querySelector('button[type="submit"]');
            const originalText = submitBtn.innerHTML;
            
            submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i>Salvando...';
            submitBtn.disabled = true;
            
            fetch(this.action, {
                method: 'POST',
                body: formData,
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    'Accept': 'application/json',
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    showToast('Reunião atualizada com sucesso!', 'success');
                    closeEditModal();
                    location.reload();
                } else {
                    showToast(data.message || 'Erro ao atualizar reunião', 'error');
                }
            })
            .catch(error => {
                console.error('Erro:', error);
                showToast('Erro ao atualizar reunião', 'error');
            })
            .finally(() => {
                submitBtn.innerHTML = originalText;
                submitBtn.disabled = false;
            });
        });
    }

    // Fechar modais clicando fora
    document.addEventListener('click', function(e) {
        if (e.target.id === 'deleteModal') closeDeleteModal();
        if (e.target.id === 'detailsModal') closeDetailsModal();
        if (e.target.id === 'editModal') closeEditModal();
    });
});
