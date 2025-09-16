<!DOCTYPE html>
<html lang="pt-BR">
<head>
<x-dynamic-branding />
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Agenda - dspay</title>
    <link rel="icon" type="image/png" href="{{ asset('images/dspay-logo.png') }}">
    <link rel="shortcut icon" type="image/png" href="{{ asset('images/dspay-logo.png') }}">
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="{{ asset('app.css') }}" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <script>
        // Definir funções globais imediatamente
        window.openAddAgendaModal = function() {
            console.log('Função openAddAgendaModal chamada');
            
            const modal = document.getElementById('agendaModal');
            const modalTitle = document.getElementById('modalTitle');
            const agendaForm = document.getElementById('agendaForm');
            const agendaId = document.getElementById('agendaId');
            
            modalTitle.textContent = 'Nova Reunião';
            agendaForm.reset();
            agendaId.value = '';
            loadLicenciados();
            modal.style.display = 'block';
            console.log('Modal aberto com sucesso');
            
            // Debug: verificar se os botões estão presentes
            const saveButton = modal.querySelector('button[type="submit"]');
            const cancelButton = modal.querySelector('button[type="button"]');
            console.log('Botão Salvar encontrado:', saveButton);
            console.log('Botão Cancelar encontrado:', cancelButton);
            
            if (saveButton) {
                console.log('Texto do botão Salvar:', saveButton.textContent);
                console.log('Classes do botão Salvar:', saveButton.className);
            }
        }

        window.closeAgendaModal = function() {
            document.getElementById('agendaModal').style.display = 'none';
        }

        window.closeViewAgendaModal = function() {
            document.getElementById('viewAgendaModal').style.display = 'none';
        }

        window.closeConfirmModal = function() {
            document.getElementById('confirmModal').style.display = 'none';
        }

        window.viewAgenda = function(id) {
            fetch(`/agenda/${id}`)
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        const agenda = data.agenda;
                        const details = `
                            <div class="space-y-4">
                                <div>
                                    <h4 class="font-semibold text-gray-800">${agenda.titulo}</h4>
                                    ${agenda.descricao ? `<p class="text-gray-600 mt-2">${agenda.descricao}</p>` : ''}
                                </div>
                                
                                <div class="grid grid-cols-2 gap-4">
                                    <div>
                                        <span class="text-sm font-medium text-gray-600">Início:</span>
                                        <p class="text-gray-800">${new Date(agenda.data_inicio).toLocaleString('pt-BR')}</p>
                                    </div>
                                    <div>
                                        <span class="text-sm font-medium text-gray-600">Fim:</span>
                                        <p class="text-gray-800">${new Date(agenda.data_fim).toLocaleString('pt-BR')}</p>
                                    </div>
                                </div>
                                
                                <div>
                                    <span class="text-sm font-medium text-gray-600">Tipo:</span>
                                    <p class="text-gray-800">${agenda.tipo_reuniao.charAt(0).toUpperCase() + agenda.tipo_reuniao.slice(1)}</p>
                                </div>
                                
                                <div>
                                    <span class="text-sm font-medium text-gray-600">Status:</span>
                                    <span class="status-badge status-${agenda.status}">${agenda.status.replace('_', ' ').charAt(0).toUpperCase() + agenda.status.replace('_', ' ').slice(1)}</span>
                                </div>
                                
                                ${agenda.participantes && agenda.participantes.length > 0 ? `
                                    <div>
                                        <span class="text-sm font-medium text-gray-600">Participantes:</span>
                                        <ul class="text-gray-800 mt-1">
                                            ${agenda.participantes.map(p => `<li>• ${p}</li>`).join('')}
                                        </ul>
                                    </div>
                                    
                                    <!-- Status das Confirmações -->
                                    <div class="mt-4">
                                        <span class="text-sm font-medium text-gray-600">Status das Confirmações:</span>
                                        <div class="mt-2 space-y-2">
                                            ${agenda.detalhes_confirmacoes ? agenda.detalhes_confirmacoes.map(conf => `
                                                <div class="flex items-center justify-between bg-gray-50 rounded-lg p-3">
                                                    <div class="flex items-center">
                                                        <span class="text-sm text-gray-800">${conf.email}</span>
                                                    </div>
                                                    <div class="flex items-center space-x-2">
                                                        ${conf.status === 'confirmado' ? `
                                                            <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                                                <i class="fas fa-check mr-1"></i>Confirmado
                                                            </span>
                                                        ` : conf.status === 'pendente' ? `
                                                            <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                                                                <i class="fas fa-clock mr-1"></i>Pendente
                                                            </span>
                                                        ` : `
                                                            <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                                                <i class="fas fa-times mr-1"></i>Recusado
                                                            </span>
                                                        `}
                                                        ${conf.confirmado_em ? `
                                                            <span class="text-xs text-gray-500">
                                                                ${new Date(conf.confirmado_em).toLocaleString('pt-BR')}
                                                            </span>
                                                        ` : ''}
                                                    </div>
                                                </div>
                                            `).join('') : ''}
                                        </div>
                                    </div>
                                ` : ''}
                                
                                ${agenda.google_meet_link ? `
                                    <div>
                                        <a href="${agenda.google_meet_link}" target="_blank" class="meet-link">
                                            <i class="fas fa-video mr-2"></i>
                                            Entrar no Google Meet
                                        </a>
                                    </div>
                                ` : ''}
                            </div>
                        `;
                        
                        document.getElementById('agendaDetails').innerHTML = details;
                        document.getElementById('viewAgendaModal').style.display = 'block';
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    showToast('Erro ao carregar reunião', 'error');
                });
        }

        window.editAgenda = function(id) {
            fetch(`/agenda/${id}/edit`)
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        const agenda = data.agenda;
                        document.getElementById('modalTitle').textContent = 'Editar Reunião';
                        document.getElementById('agendaId').value = agenda.id;
                        document.getElementById('titulo').value = agenda.titulo;
                        document.getElementById('descricao').value = agenda.descricao || '';
                        document.getElementById('data_inicio').value = new Date(agenda.data_inicio).toISOString().slice(0, 16);
                        document.getElementById('data_fim').value = new Date(agenda.data_fim).toISOString().slice(0, 16);
                        document.getElementById('tipo_reuniao').value = agenda.tipo_reuniao;
                        document.getElementById('participantes').value = agenda.participantes ? agenda.participantes.join(', ') : '';
                        
                        loadLicenciados();
                        document.getElementById('licenciado_id').value = '';
                        
                        document.getElementById('agendaModal').style.display = 'block';
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    showToast('Erro ao carregar dados da reunião', 'error');
                });
        }

        window.deleteAgenda = function(id) {
            currentAgendaId = id;
            currentAction = 'delete';
            document.getElementById('confirmMessage').textContent = 'Tem certeza que deseja excluir esta reunião?';
            document.getElementById('confirmButton').textContent = 'Excluir';
            document.getElementById('confirmButton').className = 'btn-danger';
            document.getElementById('confirmModal').style.display = 'block';
        }

        window.toggleStatus = function(id, status) {
            currentAgendaId = id;
            currentAction = 'toggleStatus';
            const statusText = status === 'em_andamento' ? 'iniciar' : 'concluir';
            document.getElementById('confirmMessage').textContent = `Tem certeza que deseja ${statusText} esta reunião?`;
            document.getElementById('confirmButton').textContent = statusText.charAt(0).toUpperCase() + statusText.slice(1);
            document.getElementById('confirmButton').className = status === 'em_andamento' ? 'btn-success' : 'btn-warning';
            document.getElementById('confirmModal').style.display = 'block';
        }

        window.loadAgendaForDate = function(date) {
            fetch(`/agenda/data/${date}`)
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        updateAgendaList(data.agendas);
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                });
        }

        window.updateAgendaList = function(agendas) {
            const agendaList = document.getElementById('agendaList');
            
            if (agendas.length === 0) {
                agendaList.innerHTML = `
                    <div class="empty-state">
                        <i class="fas fa-calendar-times"></i>
                        <h3 class="text-lg font-medium text-gray-900 mb-2">Nenhuma reunião agendada</h3>
                        <p class="text-gray-500 mb-4">Você não tem reuniões agendadas para este dia.</p>
                        <button onclick="openAddAgendaModal()" class="btn-primary">
                            <i class="fas fa-plus mr-2"></i>
                            Agendar Primeira Reunião
                        </button>
                    </div>
                `;
                return;
            }

            agendaList.innerHTML = agendas.map(agenda => `
                <div class="agenda-card ${agenda.tipo_reuniao}">
                    <div class="flex justify-between items-start mb-6">
                        <div class="flex-1">
                            <div class="flex items-center gap-3 mb-3">
                                <div class="w-3 h-3 rounded-full ${agenda.tipo_reuniao === 'online' ? 'bg-green-500' : (agenda.tipo_reuniao === 'presencial' ? 'bg-orange-500' : 'bg-purple-500')} animate-pulse"></div>
                                <h3 class="text-xl font-bold text-gray-800">${agenda.titulo}</h3>
                            </div>
                            ${agenda.descricao ? `<p class="text-gray-600 mb-4 text-base leading-relaxed">${agenda.descricao}</p>` : ''}
                            
                            <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-4">
                                <div class="flex items-center p-3 bg-blue-50 rounded-lg">
                                    <i class="fas fa-clock text-blue-600 mr-3 text-lg"></i>
                                    <div>
                                        <p class="text-sm text-blue-600 font-medium">Horário</p>
                                        <p class="text-blue-800 font-semibold">${new Date(agenda.data_inicio).toLocaleTimeString('pt-BR', {hour: '2-digit', minute: '2-digit'})} - ${new Date(agenda.data_fim).toLocaleTimeString('pt-BR', {hour: '2-digit', minute: '2-digit'})}</p>
                                    </div>
                                </div>
                                
                                <div class="flex items-center p-3 ${agenda.tipo_reuniao === 'online' ? 'bg-green-50' : (agenda.tipo_reuniao === 'presencial' ? 'bg-orange-50' : 'bg-purple-50')} rounded-lg">
                                    <i class="fas fa-${agenda.tipo_reuniao === 'online' ? 'video' : (agenda.tipo_reuniao === 'presencial' ? 'map-marker-alt' : 'users')} ${agenda.tipo_reuniao === 'online' ? 'text-green-600' : (agenda.tipo_reuniao === 'presencial' ? 'text-orange-600' : 'text-purple-600')} mr-3 text-lg"></i>
                                    <div>
                                        <p class="text-sm ${agenda.tipo_reuniao === 'online' ? 'text-green-600' : (agenda.tipo_reuniao === 'presencial' ? 'text-orange-600' : 'text-purple-600')} font-medium">Tipo</p>
                                        <p class="${agenda.tipo_reuniao === 'online' ? 'text-green-800' : (agenda.tipo_reuniao === 'presencial' ? 'text-orange-800' : 'text-purple-800')} font-semibold">${agenda.tipo_reuniao.charAt(0).toUpperCase() + agenda.tipo_reuniao.slice(1)}</p>
                                    </div>
                                </div>
                                
                                ${agenda.participantes && agenda.participantes.length > 0 ? `
                                    <div class="flex items-center p-3 bg-indigo-50 rounded-lg">
                                        <i class="fas fa-users text-indigo-600 mr-3 text-lg"></i>
                                        <div>
                                            <p class="text-sm text-indigo-600 font-medium">Participantes</p>
                                            <p class="text-indigo-800 font-semibold">${agenda.participantes.length} pessoa(s)</p>
                                        </div>
                                    </div>
                                ` : ''}
                            </div>
                            
                            <!-- Status das Confirmações -->
                            ${agenda.participantes && agenda.participantes.length > 0 ? `
                                <div class="bg-gradient-to-r from-gray-50 to-gray-100 rounded-xl p-4 border border-gray-200">
                                    <h5 class="text-sm font-semibold text-gray-700 mb-3 flex items-center">
                                        <i class="fas fa-clipboard-check mr-2 text-gray-600"></i>
                                        Status dos Participantes
                                    </h5>
                                    <div class="flex flex-wrap gap-3">
                                        ${agenda.confirmacoes && agenda.confirmacoes.confirmados > 0 ? `
                                            <span class="inline-flex items-center px-3 py-2 rounded-full text-xs font-semibold bg-green-100 text-green-800 shadow-sm">
                                                <i class="fas fa-check-circle mr-2"></i>${agenda.confirmacoes.confirmados} Confirmados
                                            </span>
                                        ` : ''}
                                        
                                        ${agenda.confirmacoes && agenda.confirmacoes.pendentes > 0 ? `
                                            <span class="inline-flex items-center px-3 py-2 rounded-full text-xs font-semibold bg-yellow-100 text-yellow-800 shadow-sm">
                                                <i class="fas fa-clock mr-2"></i>${agenda.confirmacoes.pendentes} Pendentes
                                            </span>
                                        ` : ''}
                                        
                                        ${agenda.confirmacoes && agenda.confirmacoes.recusados > 0 ? `
                                            <span class="inline-flex items-center px-3 py-2 rounded-full text-xs font-semibold bg-red-100 text-red-800 shadow-sm">
                                                <i class="fas fa-times-circle mr-2"></i>${agenda.confirmacoes.recusados} Recusados
                                            </span>
                                        ` : ''}
                                    </div>
                                </div>
                            ` : ''}
                        </div>
                        <div class="flex items-center space-x-2 ml-4">
                            <span class="status-badge status-${agenda.status}">${agenda.status.replace('_', ' ').charAt(0).toUpperCase() + agenda.status.replace('_', ' ').slice(1)}</span>
                        </div>
                    </div>
                    
                    ${agenda.google_meet_link ? `
                        <div class="mb-6">
                            <a href="${agenda.google_meet_link}" target="_blank" class="meet-link">
                                <i class="fas fa-video mr-2"></i>
                                Entrar no Google Meet
                            </a>
                        </div>
                    ` : ''}

                    <div class="flex justify-end space-x-3 pt-4 border-t border-gray-100">
                        <button onclick="viewAgenda(${agenda.id})" class="btn-secondary">
                            <i class="fas fa-eye"></i>
                            Ver
                        </button>
                        <button onclick="editAgenda(${agenda.id})" class="btn-primary">
                            <i class="fas fa-edit"></i>
                            Editar
                        </button>
                        ${agenda.status === 'agendada' ? `
                            <button onclick="toggleStatus(${agenda.id}, 'em_andamento')" class="btn-success">
                                <i class="fas fa-play"></i>
                                Iniciar
                            </button>
                        ` : agenda.status === 'em_andamento' ? `
                            <button onclick="toggleStatus(${agenda.id}, 'concluida')" class="btn-warning">
                                <i class="fas fa-check"></i>
                                Concluir
                            </button>
                        ` : ''}
                        <button onclick="deleteAgenda(${agenda.id})" class="btn-danger">
                            <i class="fas fa-trash"></i>
                            Excluir
                        </button>
                    </div>
                </div>
            `).join('');
        }

        window.loadLicenciados = function() {
            console.log('Carregando licenciados...');
            fetch('/agenda/licenciados/list', {
                method: 'GET',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    'Content-Type': 'application/json'
                }
            })
            .then(response => {
                console.log('Response status:', response.status);
                return response.json();
            })
            .then(data => {
                console.log('Dados recebidos:', data);
                if (data.success) {
                    const licenciadoSelect = document.getElementById('licenciado_id');
                    if (licenciadoSelect) {
                        console.log('Select encontrado, carregando opções...');
                        const currentValue = licenciadoSelect.value;
                        licenciadoSelect.innerHTML = '<option value="">Selecione um licenciado...</option>';
                        data.licenciados.forEach(licenciado => {
                            const option = document.createElement('option');
                            option.value = licenciado.id;
                            option.textContent = licenciado.nome_fantasia || licenciado.razao_social;
                            licenciadoSelect.appendChild(option);
                        });
                        console.log('Licenciados carregados:', data.licenciados.length);
                        if (currentValue) {
                            licenciadoSelect.value = currentValue;
                        }
                    } else {
                        console.error('Elemento licenciado_id não encontrado');
                    }
                } else {
                    console.error('Erro na resposta:', data.message);
                }
            })
            .catch(error => {
                console.error('Erro ao carregar licenciados:', error);
            });
        }

        window.loadLicenciadoEmail = function(licenciadoId) {
            console.log('Carregando email do licenciado:', licenciadoId);
            fetch(`/agenda/licenciados/${licenciadoId}`, {
                method: 'GET',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    'Content-Type': 'application/json'
                }
            })
            .then(response => {
                console.log('Response status:', response.status);
                return response.json();
            })
            .then(data => {
                console.log('Dados do licenciado:', data);
                if (data.success && data.licenciado.email) {
                    const participantesField = document.getElementById('participantes');
                    const currentEmails = participantesField.value.trim();
                    
                    if (currentEmails) {
                        const emails = currentEmails.split(',').map(email => email.trim());
                        if (!emails.includes(data.licenciado.email)) {
                            participantesField.value = currentEmails + ', ' + data.licenciado.email;
                            console.log('Email adicionado à lista existente');
                        } else {
                            console.log('Email já existe na lista');
                        }
                    } else {
                        participantesField.value = data.licenciado.email;
                        console.log('Primeiro email adicionado');
                    }
                } else {
                    console.error('Licenciado não encontrado ou sem email');
                }
            })
            .catch(error => {
                console.error('Erro ao carregar email do licenciado:', error);
            });
        }

        window.showToast = function(message, type) {
            const toast = document.getElementById('toast');
            toast.textContent = message;
            toast.className = `toast ${type}`;
            toast.classList.add('show');
            
            setTimeout(() => {
                toast.classList.remove('show');
            }, 4000);
        }

        window.saveAgenda = function() {
            const form = document.getElementById('agendaForm');
            const formData = new FormData(form);
            const agendaId = document.getElementById('agendaId').value;
            
            const participantes = document.getElementById('participantes').value
                .split(',')
                .map(email => email.trim())
                .filter(email => email);
            formData.set('participantes', JSON.stringify(participantes));

            // Debug: log dos dados sendo enviados
            console.log('Dados sendo enviados:');
            for (let [key, value] of formData.entries()) {
                console.log(key + ': ' + value);
            }

            const url = agendaId ? `/agenda/${agendaId}` : '/agenda';
            const method = agendaId ? 'PUT' : 'POST';

            fetch(url, {
                method: method,
                body: formData,
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                }
            })
            .then(response => {
                console.log('Response status:', response.status);
                return response.json();
            })
            .then(data => {
                console.log('Response data:', data);
                if (data.success) {
                    showToast(data.message, 'success');
                    closeAgendaModal();
                    loadAgendaForDate(document.getElementById('datePicker').value);
                } else {
                    showToast(data.message, 'error');
                    if (data.errors) {
                        console.error('Validation errors:', data.errors);
                    }
                }
            })
            .catch(error => {
                console.error('Error:', error);
                showToast('Erro ao salvar reunião', 'error');
            });
        }

        // Variáveis globais
        let currentAgendaId = null;
        let currentAction = null;
    </script>
    
    <style>
        body {
            font-family: 'Inter', sans-serif;
        }
        .sidebar-link:hover {
            background: rgba(255, 255, 255, 0.1);
            transform: translateX(5px);
        }
        .sidebar-link.active {
            background: rgba(255, 255, 255, 0.2);
            border-left: 4px solid white;
        }
        .modal {
            display: none;
            position: fixed;
            z-index: 9999;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            background: linear-gradient(135deg, rgba(0, 0, 0, 0.7) 0%, rgba(0, 0, 0, 0.5) 100%);
            backdrop-filter: blur(10px);
            animation: modalFadeIn 0.3s ease-out;
        }
        @keyframes modalFadeIn {
            from {
                opacity: 0;
                backdrop-filter: blur(0px);
            }
            to {
                opacity: 1;
                backdrop-filter: blur(10px);
            }
        }
        .modal-content {
            background: linear-gradient(135deg, #ffffff 0%, #f8fafc 100%);
            margin: 3% auto;
            padding: 0;
            border-radius: 24px;
            width: 90%;
            max-width: 700px;
            max-height: 90vh;
            overflow-y: auto;
            box-shadow: 0 25px 80px rgba(0, 0, 0, 0.25), 0 0 0 1px rgba(255, 255, 255, 0.1);
            border: 1px solid rgba(255, 255, 255, 0.2);
            animation: modalSlideIn 0.4s cubic-bezier(0.4, 0, 0.2, 1);
            position: relative;
            overflow: hidden;
            backdrop-filter: blur(20px);
            min-height: 600px;
        }
        
        /* CSS específico para o modal de agenda */
        #agendaModal .modal-content {
            min-height: 700px !important;
            overflow-y: auto !important;
            overflow-x: hidden !important;
            position: relative !important;
            max-height: 90vh !important;
        }
        
        /* Garantir que o modal tenha altura suficiente para o footer */
        #agendaModal {
            min-height: 100vh !important;
        }
        
        /* Garantir que o modal tenha altura suficiente */
        #agendaModal {
            min-height: 100vh !important;
        }
        
        /* Estilizar a barra de rolagem do modal */
        #agendaModal .modal-content::-webkit-scrollbar {
            width: 8px;
        }
        
        #agendaModal .modal-content::-webkit-scrollbar-track {
            background: #f1f5f9;
            border-radius: 4px;
        }
        
        #agendaModal .modal-content::-webkit-scrollbar-thumb {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border-radius: 4px;
        }
        
        #agendaModal .modal-content::-webkit-scrollbar-thumb:hover {
            background: linear-gradient(135deg, #5a67d8 0%, #6b46c1 100%);
        }
        
        /* Garantir que o conteúdo do modal seja visível */
        #agendaModal * {
            visibility: visible !important;
            opacity: 1 !important;
        }
        
        /* CSS ADICIONAL PARA GARANTIR VISIBILIDADE DOS BOTÕES */
        #agendaModal .flex.justify-end {
            display: flex !important;
            visibility: visible !important;
            opacity: 1 !important;
            position: relative !important;
            z-index: 9999 !important;
            margin-top: 32px !important;
            padding: 24px 32px !important;
            border-top: 2px solid #e2e8f0 !important;
            background: linear-gradient(135deg, #f8fafc 0%, #f1f5f9 100%) !important;
            border-radius: 0 0 24px 24px !important;
            box-shadow: inset 0 1px 0 rgba(255, 255, 255, 0.8), inset 0 -1px 0 rgba(0, 0, 0, 0.05) !important;
            overflow: visible !important;
        }
        
        /* FORÇAR VISIBILIDADE DOS BOTÕES DO MODAL DE AGENDA */
        #agendaModal button[type="submit"],
        #agendaModal button[type="button"] {
            display: inline-flex !important;
            visibility: visible !important;
            opacity: 1 !important;
            position: relative !important;
            z-index: 10000 !important;
            margin: 0 !important;
            padding: 16px 32px !important;
            border-radius: 16px !important;
            font-weight: 600 !important;
            font-size: 16px !important;
            cursor: pointer !important;
            min-width: 140px !important;
            height: auto !important;
            border: none !important;
            transition: all 0.3s ease !important;
        }
        
        #agendaModal button[type="submit"] {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%) !important;
            color: white !important;
            box-shadow: 0 4px 15px rgba(102, 126, 234, 0.3) !important;
        }
        
        #agendaModal button[type="button"] {
            background: linear-gradient(135deg, #6b7280 0%, #4b5563 100%) !important;
            color: white !important;
            box-shadow: 0 4px 15px rgba(107, 114, 128, 0.3) !important;
        }
        
        /* Hover effects para os botões do modal de agenda */
        #agendaModal button[type="submit"]:hover {
            transform: translateY(-2px) !important;
            box-shadow: 0 8px 25px rgba(102, 126, 234, 0.4) !important;
        }
        
        #agendaModal button[type="button"]:hover {
            transform: translateY(-2px) !important;
            box-shadow: 0 8px 25px rgba(107, 114, 128, 0.4) !important;
            background: linear-gradient(135deg, #4b5563 0%, var(--secondary-color) 100%) !important;
        }
        @keyframes modalSlideIn {
            from {
                opacity: 0;
                transform: translateY(-50px) scale(0.95);
            }
            to {
                opacity: 1;
                transform: translateY(0) scale(1);
            }
        }
        .modal-content::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 6px;
            background: linear-gradient(90deg, #667eea 0%, #764ba2 100%);
        }
        
        .modal-content::after {
            content: '';
            position: absolute;
            top: 20px;
            right: 20px;
            width: 60px;
            height: 60px;
            background: radial-gradient(circle, rgba(102, 126, 234, 0.1) 0%, transparent 70%);
            border-radius: 50%;
            animation: floatParticle 4s ease-in-out infinite;
        }
        
        @keyframes floatParticle {
            0%, 100% { transform: translateY(0px) scale(1); opacity: 0.7; }
            50% { transform: translateY(-10px) scale(1.1); opacity: 1; }
        }
        .modal-header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 28px 32px;
            border-radius: 24px 24px 0 0;
            display: flex;
            justify-content: space-between;
            align-items: center;
            position: relative;
            overflow: hidden;
            box-shadow: inset 0 1px 0 rgba(255, 255, 255, 0.1);
        }
        .modal-header::before {
            content: '';
            position: absolute;
            top: -50%;
            right: -50%;
            width: 200%;
            height: 200%;
            background: radial-gradient(circle, rgba(255, 255, 255, 0.1) 0%, transparent 70%);
            animation: modalFloat 8s ease-in-out infinite;
        }
        @keyframes modalFloat {
            0%, 100% { transform: translateY(0px) rotate(0deg); }
            50% { transform: translateY(-15px) rotate(180deg); }
        }
        .modal-header h3 {
            font-size: 24px;
            font-weight: 700;
            position: relative;
            z-index: 1;
        }
        .modal-body {
            padding: 40px 32px;
            min-height: 500px;
            background: linear-gradient(135deg, #ffffff 0%, #f8fafc 100%);
            position: relative;
            display: flex !important;
            flex-direction: column !important;
        }
        
        /* CSS específico para o modal de agenda */
        #agendaModal .modal-body {
            min-height: 500px !important;
            padding-bottom: 32px !important;
            margin-bottom: 0 !important;
        }
        
        /* CSS para o footer do modal com os botões */
        .modal-footer {
            background: linear-gradient(135deg, #f8fafc 0%, #f1f5f9 100%);
            padding: 24px 32px;
            border-top: 2px solid #e2e8f0;
            display: flex !important;
            justify-content: flex-end !important;
            align-items: center !important;
            gap: 16px;
            border-radius: 0 0 24px 24px;
            box-shadow: inset 0 1px 0 rgba(255, 255, 255, 0.8), inset 0 -1px 0 rgba(0, 0, 0, 0.05);
            position: relative;
            z-index: 1000;
        }
        
        /* Garantir que os botões do footer sejam sempre visíveis */
        .modal-footer button {
            display: inline-flex !important;
            visibility: visible !important;
            opacity: 1 !important;
            position: relative !important;
            z-index: 1001 !important;
            margin: 0 !important;
            padding: 16px 32px !important;
            border-radius: 16px !important;
            font-weight: 600 !important;
            font-size: 16px !important;
            cursor: pointer !important;
            min-width: 140px !important;
            height: auto !important;
            border: none !important;
            transition: all 0.3s ease !important;
            text-decoration: none !important;
            align-items: center !important;
            justify-content: center !important;
        }
        
        .modal-footer .btn-primary {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%) !important;
            color: white !important;
            box-shadow: 0 4px 15px rgba(102, 126, 234, 0.3) !important;
        }
        
        .modal-footer .btn-secondary {
            background: linear-gradient(135deg, #6b7280 0%, #4b5563 100%) !important;
            color: white !important;
            box-shadow: 0 4px 15px rgba(107, 114, 128, 0.3) !important;
        }
        
        .modal-footer .btn-primary:hover {
            transform: translateY(-2px) !important;
            box-shadow: 0 8px 25px rgba(102, 126, 234, 0.4) !important;
        }
        
        .modal-footer .btn-secondary:hover {
            transform: translateY(-2px) !important;
            box-shadow: 0 8px 25px rgba(107, 114, 128, 0.4) !important;
            background: linear-gradient(135deg, #4b5563 0%, var(--secondary-color) 100%) !important;
        }
        
        .modal-body form {
            display: flex !important;
            flex-direction: column !important;
            height: 100% !important;
            justify-content: flex-start !important;
            position: relative !important;
            min-height: 400px !important;
        }
        
        /* CSS específico para o formulário do modal de agenda */
        #agendaModal form {
            min-height: 400px !important;
        }
        
        .modal-body::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background-image: 
                radial-gradient(circle at 20% 80%, rgba(102, 126, 234, 0.03) 0%, transparent 50%),
                radial-gradient(circle at 80% 20%, rgba(118, 75, 162, 0.03) 0%, transparent 50%),
                radial-gradient(circle at 40% 40%, rgba(16, 185, 129, 0.02) 0%, transparent 50%);
            pointer-events: none;
        }
        .close {
            color: white;
            font-size: 32px;
            font-weight: 300;
            cursor: pointer;
            transition: all 0.3s ease;
            width: 40px;
            height: 40px;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 50%;
            background: rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(10px);
            position: relative;
            z-index: 1;
        }
        .close:hover {
            background: rgba(255, 255, 255, 0.2);
            transform: rotate(90deg) scale(1.1);
        }
        .btn-primary {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border: none;
            color: white;
            padding: 16px 32px;
            border-radius: 16px;
            cursor: pointer;
            transition: all 0.3s ease;
            display: inline-flex !important;
            align-items: center;
            justify-content: center;
            text-decoration: none;
            font-weight: 600;
            font-size: 16px;
            box-shadow: 0 4px 15px rgba(102, 126, 234, 0.3), inset 0 1px 0 rgba(255, 255, 255, 0.2);
            position: relative;
            overflow: hidden;
            min-width: 140px;
        }
        .btn-primary::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.3), transparent);
            transition: left 0.6s ease;
            filter: blur(1px);
        }
        .btn-primary:hover::before {
            left: 100%;
        }
        .btn-primary:hover {
            transform: translateY(-3px);
            box-shadow: 0 12px 35px rgba(102, 126, 234, 0.4);
        }
        
        .btn-primary:active {
            transform: translateY(-1px);
            box-shadow: 0 6px 20px rgba(102, 126, 234, 0.3);
        }
        
        .btn-primary.loading {
            position: relative;
            overflow: hidden;
        }
        
        .btn-primary.loading::after {
            content: '';
            position: absolute;
            top: 50%;
            left: 50%;
            width: 20px;
            height: 20px;
            margin: -10px 0 0 -10px;
            border: 2px solid transparent;
            border-top: 2px solid white;
            border-radius: 50%;
            animation: spin 1s linear infinite;
        }
        
        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }
        .btn-secondary {
            background: linear-gradient(135deg, #6b7280 0%, #4b5563 100%);
            border: none;
            color: white;
            padding: 16px 32px;
            border-radius: 16px;
            cursor: pointer;
            transition: all 0.3s ease;
            display: inline-flex !important;
            align-items: center;
            justify-content: center;
            text-decoration: none;
            font-weight: 600;
            font-size: 16px;
            box-shadow: 0 4px 15px rgba(107, 114, 128, 0.3), inset 0 1px 0 rgba(255, 255, 255, 0.2);
            min-width: 140px;
        }
        .btn-secondary:hover {
            transform: translateY(-3px);
            box-shadow: 0 12px 35px rgba(107, 114, 128, 0.4);
            background: linear-gradient(135deg, #4b5563 0%, var(--secondary-color) 100%);
        }
        .btn-danger {
            background: #ef4444;
            border: none;
            color: white;
            padding: 8px 16px;
            border-radius: 6px;
            cursor: pointer;
            transition: all 0.3s ease;
        }
        .btn-danger:hover {
            background: #dc2626;
        }
        .btn-success {
            background: #10b981;
            border: none;
            color: white;
            padding: 8px 16px;
            border-radius: 6px;
            cursor: pointer;
            transition: all 0.3s ease;
        }
        .btn-success:hover {
            background: var(--accent-color);
        }
        .btn-warning {
            background: #f59e0b;
            border: none;
            color: white;
            padding: 8px 16px;
            border-radius: 6px;
            cursor: pointer;
            transition: all 0.3s ease;
        }
        .btn-warning:hover {
            background: #d97706;
        }
        .form-group {
            margin-bottom: 28px;
            position: relative;
        }
        .form-group label {
            display: block;
            margin-bottom: 12px;
            font-weight: 600;
            color: #1f2937;
            font-size: 15px;
            position: relative;
            text-shadow: 0 1px 0 rgba(255, 255, 255, 0.8);
        }
        .form-group label::after {
            content: '';
            position: absolute;
            bottom: -4px;
            left: 0;
            width: 0;
            height: 2px;
            background: linear-gradient(90deg, #667eea 0%, #764ba2 100%);
            transition: width 0.3s ease;
        }
        .form-group:focus-within label::after {
            width: 100%;
        }
        .form-control {
            width: 100%;
            padding: 16px 20px;
            border: 2px solid #e5e7eb;
            border-radius: 16px;
            font-size: 15px;
            font-weight: 500;
            transition: all 0.3s ease;
            background: linear-gradient(135deg, #ffffff 0%, #f9fafb 100%);
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.05), inset 0 1px 0 rgba(255, 255, 255, 0.8);
        }
        .form-control:focus {
            outline: none;
            border-color: #667eea;
            box-shadow: 0 0 0 4px rgba(102, 126, 234, 0.1), 0 8px 25px rgba(102, 126, 234, 0.15);
            transform: translateY(-2px);
            background: #ffffff;
            position: relative;
        }
        
        .form-control:focus::after {
            content: '';
            position: absolute;
            top: -2px;
            left: -2px;
            right: -2px;
            bottom: -2px;
            background: linear-gradient(45deg, #667eea, #764ba2, #667eea);
            border-radius: 18px;
            z-index: -1;
            animation: borderGlow 2s ease-in-out infinite;
        }
        
        @keyframes borderGlow {
            0%, 100% { opacity: 0.5; }
            50% { opacity: 1; }
        }
        .form-control:hover {
            border-color: #d1d5db;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
            background: linear-gradient(135deg, #ffffff 0%, #f0f9ff 100%);
            transform: translateY(-1px);
        }
        .form-control::placeholder {
            color: #9ca3af;
            font-weight: 400;
        }
        .agenda-card {
            background: linear-gradient(135deg, #ffffff 0%, #f8fafc 100%);
            border-radius: 16px;
            padding: 24px;
            margin-bottom: 20px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
            border: 1px solid #e2e8f0;
            transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
            position: relative;
            overflow: hidden;
        }
        .agenda-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 4px;
            background: linear-gradient(90deg, #667eea 0%, #764ba2 100%);
            transform: scaleX(0);
            transition: transform 0.4s ease;
        }
        .agenda-card:hover::before {
            transform: scaleX(1);
        }
        .agenda-card:hover {
            transform: translateY(-4px);
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.12);
            border-color: #cbd5e1;
        }
        .agenda-card.online {
            border-left: 4px solid #10b981;
        }
        .agenda-card.online::before {
            background: linear-gradient(90deg, #10b981 0%, var(--accent-color) 100%);
        }
        .agenda-card.presencial {
            border-left: 4px solid #f59e0b;
        }
        .agenda-card.presencial::before {
            background: linear-gradient(90deg, #f59e0b 0%, #d97706 100%);
        }
        .agenda-card.hibrida {
            border-left: 4px solid #8b5cf6;
        }
        .agenda-card.hibrida::before {
            background: linear-gradient(90deg, #8b5cf6 0%, #7c3aed 100%);
        }
        .status-badge {
            padding: 6px 16px;
            border-radius: 25px;
            font-size: 11px;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
            position: relative;
            overflow: hidden;
        }
        .status-badge::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: linear-gradient(135deg, rgba(255, 255, 255, 0.2) 0%, rgba(255, 255, 255, 0) 100%);
            opacity: 0;
            transition: opacity 0.3s ease;
        }
        .status-badge:hover::before {
            opacity: 1;
        }
        .status-agendada {
            background: linear-gradient(135deg, #3b82f6 0%, #1d4ed8 100%);
            color: white;
        }
        .status-em_andamento {
            background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%);
            color: white;
        }
        .status-concluida {
            background: linear-gradient(135deg, #10b981 0%, var(--accent-color) 100%);
            color: white;
        }
        .status-cancelada {
            background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%);
            color: white;
        }
        .toast {
            position: fixed;
            top: 20px;
            right: 20px;
            z-index: 9999;
            padding: 16px 24px;
            border-radius: 8px;
            color: white;
            font-weight: 500;
            transform: translateX(400px);
            transition: transform 0.3s ease;
        }
        .toast.show {
            transform: translateX(0);
        }
        .toast.success {
            background: linear-gradient(135deg, #10b981 0%, var(--accent-color) 100%);
        }
        .toast.error {
            background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%);
        }
        .calendar-header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 28px;
            border-radius: 20px;
            margin-bottom: 32px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            box-shadow: 0 10px 30px rgba(102, 126, 234, 0.3);
            position: relative;
            overflow: hidden;
        }
        .calendar-header::before {
            content: '';
            position: absolute;
            top: -50%;
            right: -50%;
            width: 200%;
            height: 200%;
            background: radial-gradient(circle, rgba(255, 255, 255, 0.1) 0%, transparent 70%);
            animation: float 6s ease-in-out infinite;
        }
        @keyframes float {
            0%, 100% { transform: translateY(0px) rotate(0deg); }
            50% { transform: translateY(-20px) rotate(180deg); }
        }
        
        /* Animações para os cards */
        @keyframes slideInUp {
            from {
                opacity: 0;
                transform: translateY(30px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
        
        .agenda-card {
            animation: slideInUp 0.6s ease-out;
        }
        
        .agenda-card:nth-child(1) { animation-delay: 0.1s; }
        .agenda-card:nth-child(2) { animation-delay: 0.2s; }
        .agenda-card:nth-child(3) { animation-delay: 0.3s; }
        .agenda-card:nth-child(4) { animation-delay: 0.4s; }
        .agenda-card:nth-child(5) { animation-delay: 0.5s; }
        
        /* Hover effects para os ícones */
        .agenda-card .fas {
            transition: transform 0.3s ease;
        }
        
        .agenda-card:hover .fas {
            transform: scale(1.1);
        }
        
        /* Efeito de shimmer para o link do Meet */
        @keyframes shimmer {
            0% { background-position: -200% 0; }
            100% { background-position: 200% 0; }
        }
        
        .meet-link:hover {
            background: linear-gradient(90deg, #10b981, var(--accent-color), #10b981);
            background-size: 200% 100%;
            animation: shimmer 2s infinite;
        }
        .date-picker {
            background: rgba(255, 255, 255, 0.15);
            border: 2px solid rgba(255, 255, 255, 0.3);
            color: white;
            padding: 12px 16px;
            border-radius: 12px;
            font-weight: 500;
            transition: all 0.3s ease;
            backdrop-filter: blur(10px);
        }
        .date-picker:focus {
            outline: none;
            background: rgba(255, 255, 255, 0.25);
            border-color: rgba(255, 255, 255, 0.6);
            box-shadow: 0 0 0 4px rgba(255, 255, 255, 0.1);
        }
        .date-picker::-webkit-calendar-picker-indicator {
            filter: invert(1);
            cursor: pointer;
        }
        .meet-link {
            display: inline-flex;
            align-items: center;
            background: linear-gradient(135deg, #10b981 0%, var(--accent-color) 100%);
            color: white;
            text-decoration: none;
            font-weight: 600;
            padding: 10px 20px;
            border-radius: 25px;
            box-shadow: 0 4px 15px rgba(16, 185, 129, 0.3);
            transition: all 0.3s ease;
            position: relative;
            overflow: hidden;
        }
        .meet-link::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.2), transparent);
            transition: left 0.5s ease;
        }
        .meet-link:hover::before {
            left: 100%;
        }
        .meet-link:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(16, 185, 129, 0.4);
            text-decoration: none;
        }
        .empty-state {
            text-align: center;
            padding: 80px 20px;
            color: #6b7280;
            background: linear-gradient(135deg, #f8fafc 0%, #f1f5f9 100%);
            border-radius: 20px;
            border: 2px dashed #cbd5e1;
            margin: 40px 0;
        }
        .empty-state i {
            font-size: 64px;
            margin-bottom: 24px;
            background: linear-gradient(135deg, #cbd5e1 0%, #94a3b8 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            display: block;
        }
        .empty-state h3 {
            font-size: 24px;
            font-weight: 700;
            margin-bottom: 12px;
            color: #475569;
        }
        .empty-state p {
            font-size: 16px;
            line-height: 1.6;
            margin-bottom: 32px;
            color: #64748b;
        }
        
        /* Garantir que os botões do modal sejam sempre visíveis */
        .modal .btn-primary,
        .modal .btn-secondary {
            position: relative;
            z-index: 1;
            visibility: visible !important;
            opacity: 1 !important;
            display: inline-flex !important;
        }
        
        /* Forçar visibilidade dos botões específicos */
        .modal button[type="submit"].btn-primary {
            display: inline-flex !important;
            visibility: visible !important;
            opacity: 1 !important;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%) !important;
            color: white !important;
            border: none !important;
            padding: 16px 32px !important;
            border-radius: 16px !important;
            font-weight: 600 !important;
            font-size: 16px !important;
            cursor: pointer !important;
            min-width: 140px !important;
            height: auto !important;
            box-shadow: 0 4px 15px rgba(102, 126, 234, 0.3) !important;
            position: relative !important;
            z-index: 1000 !important;
        }
        
        .modal button[type="button"].btn-secondary {
            display: inline-flex !important;
            visibility: visible !important;
            opacity: 1 !important;
            background: linear-gradient(135deg, #6b7280 0%, #4b5563 100%) !important;
            color: white !important;
            border: none !important;
            padding: 16px 32px !important;
            border-radius: 16px !important;
            font-weight: 600 !important;
            font-size: 16px !important;
            cursor: pointer !important;
            min-width: 140px !important;
            height: auto !important;
            box-shadow: 0 4px 15px rgba(107, 114, 128, 0.3) !important;
            position: relative !important;
            z-index: 1000 !important;
        }
        
        /* Garantir que o formulário seja completo */
        .modal form {
            display: block !important;
            width: 100% !important;
            visibility: visible !important;
            opacity: 1 !important;
        }
        
        /* Garantir que todos os elementos do formulário sejam visíveis */
        .modal form * {
            visibility: visible !important;
            opacity: 1 !important;
        }
        
        /* Garantir que o container dos botões seja visível */
        .modal form .flex.justify-end {
            display: flex !important;
            visibility: visible !important;
            opacity: 1 !important;
            position: relative !important;
            z-index: 999 !important;
            background: linear-gradient(135deg, #f8fafc 0%, #f1f5f9 100%) !important;
            margin: 32px -32px -40px -32px !important;
            padding: 32px !important;
            border-top: 1px solid #e2e8f0 !important;
            border-radius: 0 0 24px 24px !important;
        }
        
        /* Garantir que os botões do formulário sejam visíveis */
        .modal form .flex {
            display: flex !important;
            visibility: visible !important;
            opacity: 1 !important;
        }
        
        /* CSS ESPECÍFICO PARA O MODAL DE AGENDA - GARANTIR VISIBILIDADE DOS BOTÕES */
        #agendaModal {
            z-index: 10000 !important;
        }
        
        #agendaModal .modal-content {
            min-height: 600px !important;
            overflow-y: auto !important;
            overflow-x: hidden !important;
            max-height: 90vh !important;
        }
        
        #agendaModal .modal-body {
            min-height: 500px !important;
            padding-bottom: 32px !important;
            overflow: visible !important;
        }
        
        #agendaModal form {
            min-height: 400px !important;
            position: relative !important;
        }
        
        /* GARANTIR QUE OS BOTÕES SEJAM SEMPRE VISÍVEIS */
        #agendaModal .flex.justify-end {
            display: flex !important;
            visibility: visible !important;
            opacity: 1 !important;
            position: relative !important;
            z-index: 9999 !important;
            margin-top: 32px !important;
            padding: 24px 0 !important;
            border-top: 2px solid #e2e8f0 !important;
            background: linear-gradient(135deg, #f8fafc 0%, #f1f5f9 100%) !important;
            border-radius: 0 0 16px 16px !important;
            box-shadow: inset 0 1px 0 rgba(255, 255, 255, 0.8), inset 0 -1px 0 rgba(0, 0, 0, 0.05) !important;
            overflow: visible !important;
        }
        
        /* FORÇAR VISIBILIDADE DOS BOTÕES */
        #agendaModal button[type="submit"],
        #agendaModal button[type="button"] {
            display: inline-flex !important;
            visibility: visible !important;
            opacity: 1 !important;
            position: relative !important;
            z-index: 10000 !important;
            margin: 0 !important;
            padding: 16px 32px !important;
            border-radius: 16px !important;
            font-weight: 600 !important;
            font-size: 16px !important;
            cursor: pointer !important;
            min-width: 140px !important;
            height: auto !important;
            border: none !important;
            transition: all 0.3s ease !important;
        }
        
        #agendaModal button[type="submit"] {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%) !important;
            color: white !important;
            box-shadow: 0 4px 15px rgba(102, 126, 234, 0.3) !important;
        }
        
        #agendaModal button[type="button"] {
            background: linear-gradient(135deg, #6b7280 0%, #4b5563 100%) !important;
            color: white !important;
            box-shadow: 0 4px 15px rgba(107, 114, 128, 0.3) !important;
        }
        
        /* Hover effects para os botões do modal de agenda */
        #agendaModal button[type="submit"]:hover {
            transform: translateY(-2px) !important;
            box-shadow: 0 8px 25px rgba(102, 126, 234, 0.4) !important;
        }
        
        #agendaModal button[type="button"]:hover {
            transform: translateY(-2px) !important;
            box-shadow: 0 8px 25px rgba(107, 114, 128, 0.4) !important;
            background: linear-gradient(135deg, #4b5563 0%, var(--secondary-color) 100%) !important;
        }
        
        /* Garantir que os botões sejam sempre visíveis mesmo com JavaScript */
        #agendaModal button {
            display: inline-flex !important;
            visibility: visible !important;
            opacity: 1 !important;
        }

        /* Estilos específicos para o modal de agenda */
        .modal .grid {
            gap: 24px;
        }
        
        .modal .form-group:last-child {
            margin-bottom: 0;
        }
        
        .modal .form-group small {
            display: block;
            margin-top: 8px;
            color: #6b7280;
            font-size: 13px;
            font-weight: 500;
            padding: 8px 12px;
            background: linear-gradient(135deg, #f3f4f6 0%, #e5e7eb 100%);
            border-radius: 8px;
            border-left: 3px solid #667eea;
        }
        
        /* Melhorar o select */
        .modal select.form-control {
            background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 20 20'%3e%3cpath stroke='%236b7280' stroke-linecap='round' stroke-linejoin='round' stroke-width='1.5' d='m6 8 4 4 4-4'/%3e%3c/svg%3e");
            background-position: right 12px center;
            background-repeat: no-repeat;
            background-size: 16px;
            padding-right: 48px;
            appearance: none;
            cursor: pointer;
        }
        
        .modal select.form-control:hover {
            background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 20 20'%3e%3cpath stroke='%23667eea' stroke-linecap='round' stroke-linejoin='round' stroke-width='1.5' d='m6 8 4 4 4-4'/%3e%3c/svg%3e");
        }
        
        /* Melhorar o textarea */
        .modal textarea.form-control {
            resize: vertical;
            min-height: 100px;
            line-height: 1.6;
        }
        
        /* Melhorar o input datetime-local */
        .modal input[type="datetime-local"].form-control {
            cursor: pointer;
        }
        
        .modal input[type="datetime-local"].form-control::-webkit-calendar-picker-indicator {
            filter: invert(0.5);
            cursor: pointer;
            transition: filter 0.3s ease;
        }
        
        .modal input[type="datetime-local"].form-control:hover::-webkit-calendar-picker-indicator {
            filter: invert(0.7);
        }
        
        /* Container dos botões do formulário */
        .modal .flex.justify-end {
            background: linear-gradient(135deg, #f8fafc 0%, #f1f5f9 100%);
            margin: 32px -32px -40px -32px;
            padding: 24px 32px;
            border-top: 1px solid #e2e8f0;
            gap: 16px;
            display: flex !important;
            visibility: visible !important;
            opacity: 1 !important;
            position: relative !important;
            z-index: 1000 !important;
            box-shadow: inset 0 1px 0 rgba(255, 255, 255, 0.8), inset 0 -1px 0 rgba(0, 0, 0, 0.05);
            min-height: 80px;
            backdrop-filter: blur(10px);
            border-radius: 0 0 24px 24px;
        }
        
        .modal .flex.justify-end::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 1px;
            background: linear-gradient(90deg, transparent, rgba(102, 126, 234, 0.3), transparent);
        }
        
        /* Garantir que os botões sejam sempre visíveis */
        .modal .flex.justify-end button {
            display: inline-flex !important;
            visibility: visible !important;
            opacity: 1 !important;
            position: relative;
            z-index: 11;
            min-width: 140px;
            height: auto;
            padding: 16px 32px;
            background: var(--button-bg, linear-gradient(135deg, #667eea 0%, #764ba2 100%)) !important;
            color: white !important;
            border: none !important;
            border-radius: 16px !important;
            font-weight: 600 !important;
            font-size: 16px !important;
            cursor: pointer !important;
            transition: all 0.3s ease !important;
        }
        
        /* Garantir que os botões específicos sejam visíveis */
        .modal button[type="submit"] {
            display: inline-flex !important;
            visibility: visible !important;
            opacity: 1 !important;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%) !important;
            color: white !important;
            border: none !important;
            padding: 16px 32px !important;
            border-radius: 16px !important;
            font-weight: 600 !important;
            font-size: 16px !important;
            cursor: pointer !important;
            min-width: 140px !important;
            height: auto !important;
            box-shadow: 0 4px 15px rgba(102, 126, 234, 0.3) !important;
            transition: all 0.3s ease !important;
            position: relative !important;
            z-index: 1000 !important;
        }
        
        .modal button[type="submit"]:hover {
            transform: translateY(-2px) !important;
            box-shadow: 0 8px 25px rgba(102, 126, 234, 0.4) !important;
        }
        
        .modal button[type="button"] {
            display: inline-flex !important;
            visibility: visible !important;
            opacity: 1 !important;
            background: linear-gradient(135deg, #6b7280 0%, #4b5563 100%) !important;
            color: white !important;
            border: none !important;
            padding: 16px 32px !important;
            border-radius: 16px !important;
            font-weight: 600 !important;
            font-size: 16px !important;
            cursor: pointer !important;
            min-width: 140px !important;
            height: auto !important;
            box-shadow: 0 4px 15px rgba(107, 114, 128, 0.3) !important;
            transition: all 0.3s ease !important;
            position: relative !important;
            z-index: 1000 !important;
        }
        
        .modal button[type="button"]:hover {
            transform: translateY(-2px) !important;
            box-shadow: 0 8px 25px rgba(107, 114, 128, 0.4) !important;
            background: linear-gradient(135deg, #4b5563 0%, var(--secondary-color) 100%) !important;
        }
        
        /* Responsividade do modal */
        @media (max-width: 768px) {
            .modal-content {
                margin: 5% auto;
                width: 95%;
                max-width: none;
            }
            
            .modal-header {
                padding: 24px 20px;
            }
            
            .modal-body {
                padding: 30px 20px;
            }
            
            .modal .flex.justify-end {
                margin: 0;
                padding: 24px 20px;
                flex-direction: column;
                position: relative !important;
            }
            
            .modal .btn-primary,
            .modal .btn-secondary {
                width: 100%;
                justify-content: center;
                margin-bottom: 10px;
            }
        }
        
        /* Responsividade específica para o modal de agenda */
        @media (max-width: 768px) {
            #agendaModal .modal-content {
                min-height: 600px !important;
            }
            
            #agendaModal .modal-body {
                min-height: 500px !important;
                padding-bottom: 0 !important;
            }
            
            #agendaModal form .flex.justify-end {
                padding: 24px 20px !important;
                margin-top: 24px !important;
            }
            
            #agendaModal button[type="submit"],
            #agendaModal button[type="button"] {
                width: 100% !important;
                justify-content: center !important;
                margin-bottom: 10px !important;
            }
            
            /* Responsividade para o footer do modal */
            .modal-footer {
                padding: 20px !important;
                flex-direction: column !important;
                gap: 12px !important;
            }
            
            .modal-footer button {
                width: 100% !important;
                justify-content: center !important;
                margin-bottom: 8px !important;
            }
        }
        
        /* Efeitos de foco e hover para os campos */
        .modal .form-control:focus + label::after,
        .modal .form-control:focus ~ label::after {
            width: 100%;
        }
        
        /* Estilo para campos obrigatórios */
        .modal .form-group label[for*="titulo"],
        .modal .form-group label[for*="data_inicio"],
        .modal .form-group label[for*="data_fim"],
        .modal .form-group label[for*="tipo_reuniao"] {
            position: relative;
        }
        
        .modal .form-group label[for*="titulo"]::before,
        .modal .form-group label[for*="data_inicio"]::before,
        .modal .form-group label[for*="data_fim"]::before,
        .modal .form-group label[for*="tipo_reuniao"]::before {
            content: '*';
            color: #ef4444;
            margin-right: 4px;
            font-weight: 700;
            animation: pulseRequired 2s ease-in-out infinite;
        }
        
        @keyframes pulseRequired {
            0%, 100% { transform: scale(1); opacity: 1; }
            50% { transform: scale(1.1); opacity: 0.8; }
        }
        
        /* Animação para os campos quando aparecem */
        .modal .form-group {
            animation: formFieldSlideIn 0.5s ease-out;
            animation-fill-mode: both;
        }
        
        .modal .form-group:nth-child(1) { animation-delay: 0.1s; }
        .modal .form-group:nth-child(2) { animation-delay: 0.2s; }
        .modal .form-group:nth-child(3) { animation-delay: 0.3s; }
        .modal .form-group:nth-child(4) { animation-delay: 0.4s; }
        .modal .form-group:nth-child(5) { animation-delay: 0.5s; }
        .modal .form-group:nth-child(6) { animation-delay: 0.6s; }
        .modal .form-group:nth-child(7) { animation-delay: 0.7s; }
        
        @keyframes formFieldSlideIn {
            from {
                opacity: 0;
                transform: translateX(-20px);
            }
            to {
                opacity: 1;
                transform: translateX(0);
            }
        }
        
        /* Melhorar a aparência dos placeholders */
        .modal .form-control::placeholder {
            transition: all 0.3s ease;
        }
        
        .modal .form-control:focus::placeholder {
            opacity: 0.7;
            transform: translateX(5px);
        }
        
        /* Destaque especial para o campo de título */
        .modal .form-group:first-child .form-control {
            border-width: 3px;
            background: linear-gradient(135deg, #ffffff 0%, #fef3c7 100%);
        }
        
        .modal .form-group:first-child .form-control:focus {
            background: linear-gradient(135deg, #ffffff 0%, #fde68a 100%);
            border-color: #f59e0b;
        }
        
        /* Destaque para o campo de participantes */
        .modal .form-group:last-child .form-control {
            background: linear-gradient(135deg, #ffffff 0%, #ecfdf5 100%);
            border-color: #10b981;
        }
        
        .modal .form-group:last-child .form-control:focus {
            background: linear-gradient(135deg, #ffffff 0%, #d1fae5 100%);
            border-color: var(--accent-color);
        }
        
        /* Destaque para os campos de data */
        .modal input[type="datetime-local"].form-control {
            background: linear-gradient(135deg, #ffffff 0%, #f3f4f6 100%);
            border-color: #8b5cf6;
        }
        
        .modal input[type="datetime-local"].form-control:focus {
            background: linear-gradient(135deg, #ffffff 0%, #ede9fe 100%);
            border-color: #7c3aed;
        }
        
        /* Destaque para o campo de tipo de reunião */
        .modal select#tipo_reuniao.form-control {
            background: linear-gradient(135deg, #ffffff 0%, #fef3c7 100%);
            border-color: #f59e0b;
        }
        
        .modal select#tipo_reuniao.form-control:focus {
            background: linear-gradient(135deg, #ffffff 0%, #fde68a 100%);
            border-color: #d97706;
        }
        
        /* Destaque para o campo de licenciado */
        .modal select#licenciado_id.form-control {
            background: linear-gradient(135deg, #ffffff 0%, #fff7ed 100%);
            border-color: #ea580c;
        }
        
        .modal select#licenciado_id.form-control:focus {
            background: linear-gradient(135deg, #ffffff 0%, #fed7aa 100%);
            border-color: #c2410c;
        }
        
        /* Destaque para o campo de descrição */
        .modal textarea#descricao.form-control {
            background: linear-gradient(135deg, #ffffff 0%, #f0fdf4 100%);
            border-color: #16a34a;
        }
        
        .modal textarea#descricao.form-control:focus {
            background: linear-gradient(135deg, #ffffff 0%, #dcfce7 100%);
            border-color: #15803d;
        }

        /* Melhorar os botões de ação dos cards */
        .agenda-card .btn-primary,
        .agenda-card .btn-secondary,
        .agenda-card .btn-success,
        .agenda-card .btn-warning,
        .agenda-card .btn-danger {
            padding: 8px 16px;
            border-radius: 12px;
            font-weight: 600;
            font-size: 13px;
            transition: all 0.3s ease;
            border: none;
            cursor: pointer;
            display: inline-flex;
            align-items: center;
            gap: 6px;
            text-decoration: none;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
        }
        
        .agenda-card .btn-primary {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
        }
        
        .agenda-card .btn-secondary {
            background: linear-gradient(135deg, #6b7280 0%, #4b5563 100%);
            color: white;
        }
        
        .agenda-card .btn-success {
            background: linear-gradient(135deg, #10b981 0%, var(--accent-color) 100%);
            color: white;
        }
        
        .agenda-card .btn-warning {
            background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%);
            color: white;
        }
        
        .agenda-card .btn-danger {
            background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%);
            color: white;
        }
        
        .agenda-card .btn-primary:hover,
        .agenda-card .btn-secondary:hover,
        .agenda-card .btn-success:hover,
        .agenda-card .btn-warning:hover,
        .agenda-card .btn-danger:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.2);
        }
        
        /* Melhorar os badges de confirmação */
        .agenda-card .inline-flex {
            padding: 6px 12px;
            border-radius: 20px;
            font-size: 11px;
            font-weight: 600;
            display: inline-flex;
            align-items: center;
            gap: 4px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
        }
        
        .agenda-card .bg-green-100 {
            background: linear-gradient(135deg, #d1fae5 0%, #a7f3d0 100%) !important;
            color: #065f46 !important;
        }
        
        .agenda-card .bg-yellow-100 {
            background: linear-gradient(135deg, #fef3c7 0%, #fde68a 100%) !important;
            color: #92400e !important;
        }
        
        .agenda-card .bg-red-100 {
            background: linear-gradient(135deg, #fee2e2 0%, #fecaca 100%) !important;
            color: #991b1b !important;
        }
        
        /* CSS de debug para garantir visibilidade dos botões */
        .modal button[type="submit"],
        .modal button[type="button"] {
            display: inline-flex !important;
            visibility: visible !important;
            opacity: 1 !important;
            position: relative !important;
            z-index: 9999 !important;
            background: var(--button-bg, linear-gradient(135deg, #667eea 0%, #764ba2 100%)) !important;
            color: white !important;
            border: none !important;
            padding: 16px 32px !important;
            border-radius: 16px !important;
            font-weight: 600 !important;
            font-size: 16px !important;
            cursor: pointer !important;
            min-width: 140px !important;
            height: auto !important;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.2) !important;
            margin: 0 !important;
        }
        
        /* CSS específico para os botões do modal de agenda */
        #agendaModal button[type="submit"],
        #agendaModal button[type="button"] {
            display: inline-flex !important;
            visibility: visible !important;
            opacity: 1 !important;
            position: relative !important;
            z-index: 9999 !important;
            margin: 0 !important;
            padding: 16px 32px !important;
            border-radius: 16px !important;
            font-weight: 600 !important;
            font-size: 16px !important;
            cursor: pointer !important;
            min-width: 140px !important;
            height: auto !important;
            border: none !important;
        }
        
        #agendaModal button[type="submit"] {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%) !important;
            color: white !important;
            box-shadow: 0 4px 15px rgba(102, 126, 234, 0.3) !important;
        }
        
        #agendaModal button[type="button"] {
            background: linear-gradient(135deg, #6b7280 0%, #4b5563 100%) !important;
            color: white !important;
            box-shadow: 0 4px 15px rgba(107, 114, 128, 0.3) !important;
        }
        
        /* Garantir que o container dos botões seja visível */
        #agendaModal .flex.justify-end {
            display: flex !important;
            visibility: visible !important;
            opacity: 1 !important;
            position: relative !important;
            z-index: 999 !important;
            margin-top: 32px !important;
            padding: 24px 0 !important;
            border-top: 1px solid #e2e8f0 !important;
            background: linear-gradient(135deg, #f8fafc 0%, #f1f5f9 100%) !important;
            border-radius: 0 0 16px 16px !important;
        }
        
        /* Garantir que os botões do formulário sejam sempre visíveis */
        .modal form .flex.justify-end {
            display: flex !important;
            visibility: visible !important;
            opacity: 1 !important;
            position: relative !important;
            z-index: 999 !important;
            margin-top: 32px !important;
            padding: 24px 0 !important;
            border-top: 1px solid #e2e8f0 !important;
            background: linear-gradient(135deg, #f8fafc 0%, #f1f5f9 100%) !important;
            border-radius: 0 0 16px 16px !important;
        }
        
        /* CSS específico para os botões do modal de agenda */
        #agendaModal form .flex.justify-end {
            display: flex !important;
            visibility: visible !important;
            opacity: 1 !important;
            position: relative !important;
            z-index: 9999 !important;
            margin-top: 32px !important;
            padding: 32px !important;
            border-top: 2px solid #e2e8f0 !important;
            background: linear-gradient(135deg, #f8fafc 0%, #f1f5f9 100%) !important;
            border-radius: 0 0 24px 24px !important;
            box-shadow: inset 0 1px 0 rgba(255, 255, 255, 0.8), inset 0 -1px 0 rgba(0, 0, 0, 0.05) !important;
        }
        
        .modal button[type="submit"] {
            --button-bg: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        }
        
        .modal button[type="button"] {
            --button-bg: linear-gradient(135deg, #6b7280 0%, #4b5563 100%);
        }
        
        /* CSS ADICIONAL PARA GARANTIR VISIBILIDADE DOS BOTÕES */
        .modal .flex.justify-end {
            display: flex !important;
            visibility: visible !important;
            opacity: 1 !important;
            position: relative !important;
            z-index: 999 !important;
            margin-top: 32px !important;
            padding: 24px 0 !important;
            border-top: 1px solid #e2e8f0 !important;
            background: linear-gradient(135deg, #f8fafc 0%, #f1f5f9 100%) !important;
            border-radius: 0 0 16px 16px !important;
            box-shadow: inset 0 1px 0 rgba(255, 255, 255, 0.8), inset 0 -1px 0 rgba(0, 0, 0, 0.05) !important;
        }
        
        /* FORÇAR VISIBILIDADE DOS BOTÕES */
        .modal button[type="submit"],
        .modal button[type="button"] {
            display: inline-flex !important;
            visibility: visible !important;
            opacity: 1 !important;
            position: relative !important;
            z-index: 1000 !important;
            margin: 0 !important;
            padding: 16px 32px !important;
            border-radius: 16px !important;
            font-weight: 600 !important;
            font-size: 16px !important;
            cursor: pointer !important;
            min-width: 140px !important;
            height: auto !important;
            border: none !important;
            transition: all 0.3s ease !important;
        }
        
        .modal button[type="submit"] {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%) !important;
            color: white !important;
            box-shadow: 0 4px 15px rgba(102, 126, 234, 0.3) !important;
        }
        
        .modal button[type="button"] {
            background: linear-gradient(135deg, #6b7280 0%, #4b5563 100%) !important;
            color: white !important;
            box-shadow: 0 4px 15px rgba(107, 114, 128, 0.3) !important;
        }
        
        /* Hover effects */
        .modal button[type="submit"]:hover {
            transform: translateY(-2px) !important;
            box-shadow: 0 8px 25px rgba(102, 126, 234, 0.4) !important;
        }
        
        .modal button[type="button"]:hover {
            transform: translateY(-2px) !important;
            box-shadow: 0 8px 25px rgba(107, 114, 128, 0.4) !important;
            background: linear-gradient(135deg, #4b5563 0%, var(--secondary-color) 100%) !important;
        }
    </style>
</head>
<body class="bg-gray-50">
    <div class="flex h-screen">
        <!-- Sidebar Dinâmico -->
        <x-dynamic-sidebar />

        <!-- Main Content -->
        <div class="flex-1 flex flex-col overflow-hidden">
            <!-- Header -->
            <header class="bg-white shadow-sm border-b">
                <div class="flex items-center justify-between px-6 py-4">
                    <div>
                        <h1 class="text-2xl font-bold text-gray-800">Agenda</h1>
                        <p class="text-gray-600">Gerencie suas reuniões e compromissos</p>
                    </div>
                    <div class="flex items-center space-x-4">
                        <button class="p-2 text-gray-400 hover:text-gray-600">
                            <i class="fas fa-bell"></i>
                        </button>
                        <form method="POST" action="{{ route('logout.custom') }}" class="inline">
                            @csrf
                            <button type="submit" class="p-2 text-gray-400 hover:text-gray-600">
                                <i class="fas fa-sign-out-alt"></i>
                            </button>
                        </form>
                    </div>
                </div>
            </header>

            <!-- Content -->
            <main class="flex-1 overflow-y-auto p-6">
                <!-- Calendar Header -->
                <div class="calendar-header">
                    <div>
                        <h2 class="text-xl font-bold">Agenda do Dia</h2>
                        <p class="text-blue-100">Seus compromissos e reuniões</p>
                    </div>
                    <div class="flex items-center space-x-4">
                        <input type="date" id="datePicker" class="date-picker" value="{{ $dataAtual }}">
                        <button onclick="openAddAgendaModal()" class="btn-primary" id="novaReuniaoBtn">
                            <i class="fas fa-plus mr-2"></i>
                            Nova Reunião
                        </button>
                    </div>
                </div>

                <!-- Agenda List -->
                <div id="agendaList">
                    @forelse($agendas as $agenda)
                        <div class="agenda-card {{ $agenda->tipo_reuniao }}">
                            <div class="flex justify-between items-start mb-6">
                                <div class="flex-1">
                                    <div class="flex items-center gap-3 mb-3">
                                        <div class="w-3 h-3 rounded-full {{ $agenda->tipo_reuniao === 'online' ? 'bg-green-500' : ($agenda->tipo_reuniao === 'presencial' ? 'bg-orange-500' : 'bg-purple-500') }} animate-pulse"></div>
                                        <h3 class="text-xl font-bold text-gray-800">{{ $agenda->titulo }}</h3>
                                    </div>
                                    @if($agenda->descricao)
                                        <p class="text-gray-600 mb-4 text-base leading-relaxed">{{ $agenda->descricao }}</p>
                                    @endif
                                    
                                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-4">
                                        <div class="flex items-center p-3 bg-blue-50 rounded-lg">
                                            <i class="fas fa-clock text-blue-600 mr-3 text-lg"></i>
                                            <div>
                                                <p class="text-sm text-blue-600 font-medium">Horário</p>
                                                <p class="text-blue-800 font-semibold">{{ $agenda->data_inicio->format('H:i') }} - {{ $agenda->data_fim->format('H:i') }}</p>
                                            </div>
                                        </div>
                                        
                                        <div class="flex items-center p-3 {{ $agenda->tipo_reuniao === 'online' ? 'bg-green-50' : ($agenda->tipo_reuniao === 'presencial' ? 'bg-orange-50' : 'bg-purple-50') }} rounded-lg">
                                            <i class="fas fa-{{ $agenda->tipo_reuniao === 'online' ? 'video' : ($agenda->tipo_reuniao === 'presencial' ? 'map-marker-alt' : 'users') }} {{ $agenda->tipo_reuniao === 'online' ? 'text-green-600' : ($agenda->tipo_reuniao === 'presencial' ? 'text-orange-600' : 'text-purple-600') }} mr-3 text-lg"></i>
                                            <div>
                                                <p class="text-sm {{ $agenda->tipo_reuniao === 'online' ? 'text-green-600' : ($agenda->tipo_reuniao === 'presencial' ? 'text-orange-600' : 'text-purple-600') }} font-medium">Tipo</p>
                                                <p class="{{ $agenda->tipo_reuniao === 'online' ? 'text-green-800' : ($agenda->tipo_reuniao === 'presencial' ? 'text-orange-800' : 'text-purple-800') }} font-semibold">{{ ucfirst($agenda->tipo_reuniao) }}</p>
                                            </div>
                                        </div>
                                        
                                        @if($agenda->participantes && count($agenda->participantes) > 0)
                                            <div class="flex items-center p-3 bg-indigo-50 rounded-lg">
                                                <i class="fas fa-users text-indigo-600 mr-3 text-lg"></i>
                                                <div>
                                                    <p class="text-sm text-indigo-600 font-medium">Participantes</p>
                                                    <p class="text-indigo-800 font-semibold">{{ count($agenda->participantes) }} pessoa(s)</p>
                                                </div>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                                <div class="flex items-center space-x-2 ml-4">
                                    <span class="status-badge status-{{ $agenda->status }}">{{ ucfirst(str_replace('_', ' ', $agenda->status)) }}</span>
                                </div>
                            </div>
                            
                            @if($agenda->google_meet_link)
                                <div class="mb-6">
                                    <a href="{{ $agenda->google_meet_link }}" target="_blank" class="meet-link">
                                        <i class="fas fa-video mr-2"></i>
                                        Entrar no Google Meet
                                    </a>
                                </div>
                            @endif

                            <div class="flex justify-end space-x-3 pt-4 border-t border-gray-100">
                                <button onclick="viewAgenda({{ $agenda->id }})" class="btn-secondary">
                                    <i class="fas fa-eye"></i>
                                    Ver
                                </button>
                                <button onclick="editAgenda({{ $agenda->id }})" class="btn-primary">
                                    <i class="fas fa-edit"></i>
                                    Editar
                                </button>
                                @if($agenda->status === 'agendada')
                                    <button onclick="toggleStatus({{ $agenda->id }}, 'em_andamento')" class="btn-success">
                                        <i class="fas fa-play"></i>
                                        Iniciar
                                    </button>
                                @elseif($agenda->status === 'em_andamento')
                                    <button onclick="toggleStatus({{ $agenda->id }}, 'concluida')" class="btn-warning">
                                        <i class="fas fa-check"></i>
                                        Concluir
                                    </button>
                                @endif
                                <button onclick="deleteAgenda({{ $agenda->id }})" class="btn-danger">
                                    <i class="fas fa-trash"></i>
                                    Excluir
                                </button>
                            </div>
                        </div>
                    @empty
                        <div class="empty-state">
                            <i class="fas fa-calendar-times"></i>
                            <h3 class="text-lg font-medium text-gray-900 mb-2">Nenhuma reunião agendada</h3>
                            <p class="text-gray-500 mb-4">Você não tem reuniões agendadas para este dia.</p>
                            <button onclick="openAddAgendaModal()" class="btn-primary" id="primeiraReuniaoBtn">
                                <i class="fas fa-plus mr-2"></i>
                                Agendar Primeira Reunião
                            </button>
                        </div>
                    @endforelse
                </div>
            </main>
        </div>
    </div>

    <!-- Modal Adicionar/Editar Reunião -->
    <div id="agendaModal" class="modal">
        <div class="modal-content">
            <div class="modal-header">
                <h3 id="modalTitle">Nova Reunião</h3>
                <span class="close" onclick="closeAgendaModal()">&times;</span>
            </div>
            <div class="modal-body">
                <form id="agendaForm">
                    <input type="hidden" id="agendaId" name="agendaId">
                    
                    <div class="form-group">
                        <label for="titulo">
                            <i class="fas fa-heading mr-2 text-blue-600"></i>
                            Título da Reunião *
                        </label>
                        <input type="text" id="titulo" name="titulo" required class="form-control" placeholder="Ex: Reunião com cliente">
                    </div>
                    
                    <div class="form-group">
                        <label for="descricao">
                            <i class="fas fa-align-left mr-2 text-green-600"></i>
                            Descrição
                        </label>
                        <textarea id="descricao" name="descricao" class="form-control" rows="4" placeholder="Descreva os detalhes da reunião..."></textarea>
                    </div>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="form-group">
                            <label for="data_inicio">
                                <i class="fas fa-calendar-plus mr-2 text-purple-600"></i>
                                Data e Hora de Início *
                            </label>
                            <input type="datetime-local" id="data_inicio" name="data_inicio" required class="form-control">
                        </div>
                        
                        <div class="form-group">
                            <label for="data_fim">
                                <i class="fas fa-calendar-check mr-2 text-red-600"></i>
                                Data e Hora de Fim *
                            </label>
                            <input type="datetime-local" id="data_fim" name="data_fim" required class="form-control">
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <label for="tipo_reuniao">
                            <i class="fas fa-video mr-2 text-indigo-600"></i>
                            Tipo de Reunião *
                        </label>
                        <select id="tipo_reuniao" name="tipo_reuniao" required class="form-control">
                            <option value="">Selecione o tipo de reunião...</option>
                            <option value="online">🌐 Online (Google Meet)</option>
                            <option value="presencial">🏢 Presencial</option>
                            <option value="hibrida">🔄 Híbrida (Online + Presencial)</option>
                        </select>
                    </div>
                    
                    <div class="form-group">
                        <label for="licenciado_id">
                            <i class="fas fa-user-tie mr-2 text-orange-600"></i>
                            Licenciado
                        </label>
                        <select id="licenciado_id" name="licenciado_id" class="form-control">
                            <option value="">Selecione um licenciado...</option>
                        </select>
                    </div>
                    
                    <div class="form-group">
                        <label for="participantes">
                            <i class="fas fa-users mr-2 text-teal-600"></i>
                            Participantes
                        </label>
                        <input type="text" id="participantes" name="participantes" class="form-control" placeholder="email1@exemplo.com, email2@exemplo.com">
                        <small>
                            <i class="fas fa-info-circle mr-1"></i>
                            O e-mail do licenciado selecionado será adicionado automaticamente
                        </small>
                    </div>
                </form>
            </div>
            
            <!-- Botões do Modal - FORA DO FORMULÁRIO -->
            <div class="modal-footer">
                <button type="button" onclick="closeAgendaModal()" class="btn-secondary">
                    <i class="fas fa-times mr-2"></i>
                    Cancelar
                </button>
                <button type="button" onclick="saveAgenda()" class="btn-primary">
                    <i class="fas fa-save mr-2"></i>
                    Salvar Reunião
                </button>
            </div>
        </div>
    </div>

    <!-- Modal Visualizar Reunião -->
    <div id="viewAgendaModal" class="modal">
        <div class="modal-content">
            <div class="modal-header">
                <h3>Detalhes da Reunião</h3>
                <span class="close" onclick="closeViewAgendaModal()">&times;</span>
            </div>
            <div class="modal-body">
                <div id="agendaDetails">
                    <!-- Conteúdo será preenchido via JavaScript -->
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Confirmação -->
    <div id="confirmModal" class="modal">
        <div class="modal-content" style="max-width: 400px;">
            <div class="modal-header">
                <h3>Confirmar Ação</h3>
                <span class="close" onclick="closeConfirmModal()">&times;</span>
            </div>
            <div class="modal-body">
                <p id="confirmMessage">Tem certeza que deseja realizar esta ação?</p>
                <div class="flex justify-end space-x-4 mt-6">
                    <button onclick="closeConfirmModal()" class="btn-secondary">Cancelar</button>
                    <button id="confirmButton" class="btn-danger">Confirmar</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Toast Notification -->
    <div id="toast" class="toast"></div>

    <script>
        // Event listeners quando o DOM estiver carregado
        document.addEventListener('DOMContentLoaded', function() {
            // Removido o event listener do form já que os botões estão fora
            // Os botões agora chamam saveAgenda() diretamente via onclick

            // Date picker change
            const datePicker = document.getElementById('datePicker');
            if (datePicker) {
                datePicker.addEventListener('change', function() {
                    loadAgendaForDate(this.value);
                });
            }

            // Licenciado select change
            const licenciadoSelect = document.getElementById('licenciado_id');
            if (licenciadoSelect) {
                licenciadoSelect.addEventListener('change', function() {
                    if (this.value) {
                        loadLicenciadoEmail(this.value);
                    } else {
                        const participantesField = document.getElementById('participantes');
                        participantesField.value = '';
                    }
                });
            }

            // Confirm action
            const confirmButton = document.getElementById('confirmButton');
            if (confirmButton) {
                confirmButton.addEventListener('click', function() {
                    if (currentAction === 'delete') {
                        fetch(`/agenda/${currentAgendaId}`, {
                            method: 'DELETE',
                            headers: {
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                            }
                        })
                        .then(response => response.json())
                        .then(data => {
                            if (data.success) {
                                showToast(data.message, 'success');
                                closeConfirmModal();
                                loadAgendaForDate(document.getElementById('datePicker').value);
                            } else {
                                showToast(data.message, 'error');
                            }
                        })
                        .catch(error => {
                            console.error('Error:', error);
                            showToast('Erro ao excluir reunião', 'error');
                        });
                    } else if (currentAction === 'toggleStatus') {
                        const status = document.getElementById('confirmButton').textContent.toLowerCase() === 'iniciar' ? 'em_andamento' : 'concluida';
                        fetch(`/agenda/${currentAgendaId}/toggle-status`, {
                            method: 'PATCH',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                            },
                            body: JSON.stringify({ status: status })
                        })
                        .then(response => response.json())
                        .then(data => {
                            if (data.success) {
                                showToast(data.message, 'success');
                                closeConfirmModal();
                                loadAgendaForDate(document.getElementById('datePicker').value);
                            } else {
                                showToast(data.message, 'error');
                            }
                        })
                        .catch(error => {
                            console.error('Error:', error);
                            showToast('Erro ao alterar status da reunião', 'error');
                        });
                    }
                });
            }

            // Close modals when clicking outside
            window.onclick = function(event) {
                const agendaModal = document.getElementById('agendaModal');
                const viewModal = document.getElementById('viewAgendaModal');
                const confirmModal = document.getElementById('confirmModal');
                
                if (event.target === agendaModal) {
                    closeAgendaModal();
                }
                if (event.target === viewModal) {
                    closeViewAgendaModal();
                }
                if (event.target === confirmModal) {
                    closeConfirmModal();
                }
            }
        });
    </script>
</body>
</html>
