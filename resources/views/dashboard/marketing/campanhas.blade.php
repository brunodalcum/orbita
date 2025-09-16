
<x-simple-branding />
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Campanhas - DSPay Orbita</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>



    <script>
        let campanhaEditando = null;

        // Sistema de notificações
        function showNotification(message, type = 'info', duration = 5000) {
            const container = document.getElementById('notificationContainer');
            const notification = document.createElement('div');
            notification.className = `notification ${type}`;
            
            const icon = document.createElement('i');
            switch (type) {
                case 'success':
                    icon.className = 'fas fa-check-circle text-lg';
                    break;
                case 'error':
                    icon.className = 'fas fa-exclamation-circle text-lg';
                    break;
                case 'warning':
                    icon.className = 'fas fa-exclamation-triangle text-lg';
                    break;
                default:
                    icon.className = 'fas fa-info-circle text-lg';
            }
            
            const text = document.createElement('span');
            text.textContent = message;
            
            const closeBtn = document.createElement('button');
            closeBtn.className = 'notification-close ml-auto';
            closeBtn.innerHTML = '<i class="fas fa-times"></i>';
            closeBtn.onclick = () => notification.remove();
            
            notification.appendChild(icon);
            notification.appendChild(text);
            notification.appendChild(closeBtn);
            
            container.appendChild(notification);
            
            // Auto-remove após duração
            setTimeout(() => {
                if (notification.parentNode) {
                    notification.remove();
                }
            }, duration);
        }

        function openAddCampanhaModal() {
            document.getElementById('modalTitle').textContent = 'Nova Campanha';
            document.getElementById('campanhaForm').reset();
            document.getElementById('campanha_id').value = '';
            campanhaEditando = null;
            document.getElementById('campanhaModal').classList.remove('hidden');
        }

        function testarEnvioEmail() {
            document.getElementById('testeEmailForm').reset();
            document.getElementById('testeEmailModal').classList.remove('hidden');
        }

        function closeTesteEmailModal() {
            document.getElementById('testeEmailModal').classList.add('hidden');
        }

        function closeCampanhaModal() {
            document.getElementById('campanhaModal').classList.add('hidden');
            campanhaEditando = null;
        }

        function editCampanha(id) {
            // Buscar dados da campanha via AJAX
            fetch(`/dashboard/marketing/campanhas/${id}`)
                .then(response => {
                    if (!response.ok) {
                        throw new Error('Erro na requisição');
                    }
                    return response.json();
                })
                .then(data => {
                    if (data.success) {
                        const campanha = data.campanha;
                        document.getElementById('modalTitle').textContent = 'Editar Campanha';
                        document.getElementById('campanha_id').value = campanha.id;
                        document.getElementById('nome').value = campanha.nome;
                        document.getElementById('descricao').value = campanha.descricao || '';
                        document.getElementById('tipo').value = campanha.tipo;
                        document.getElementById('modelo_id').value = campanha.modelo_id;
                        document.getElementById('agendamento').value = campanha.data_inicio ? campanha.data_inicio.slice(0, 16) : '';
                        
                        // Limpar checkboxes anteriores
                        document.querySelectorAll('input[name="segmentacao[]"]').forEach(cb => cb.checked = false);
                        
                        // Marcar segmentação atual
                        if (campanha.segmentacao) {
                            campanha.segmentacao.forEach(segmento => {
                                const checkbox = document.querySelector(`input[name="segmentacao[]"][value="${segmento}"]`);
                                if (checkbox) checkbox.checked = true;
                            });
                        }
                        
                        campanhaEditando = campanha.id;
                        document.getElementById('campanhaModal').classList.remove('hidden');
                    } else {
                        showNotification(data.message || 'Erro ao carregar dados da campanha', 'error');
                    }
                })
                .catch(error => {
                    console.error('Erro:', error);
                    showNotification('Erro ao carregar dados da campanha', 'error');
                });
        }

        function pauseCampanha(id) {
            if (confirm('Tem certeza que deseja pausar esta campanha?')) {
                changeStatus(id, 'pausada');
            }
        }

        function resumeCampanha(id) {
            if (confirm('Tem certeza que deseja retomar esta campanha?')) {
                changeStatus(id, 'ativa');
            }
        }

        function activateCampanha(id) {
            if (confirm('Tem certeza que deseja ativar esta campanha?')) {
                changeStatus(id, 'ativa');
            }
        }

        function changeStatus(id, status) {
            fetch(`/dashboard/marketing/campanhas/${id}/status`, {
                method: 'PATCH',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: JSON.stringify({ status: status })
            })
            .then(response => {
                if (!response.ok) {
                    throw new Error('Erro na requisição');
                }
                return response.json();
            })
            .then(data => {
                if (data.success) {
                    showNotification(data.message, 'success');
                    setTimeout(() => location.reload(), 1500);
                } else {
                    showNotification(data.message || 'Erro ao alterar status', 'error');
                }
            })
            .catch(error => {
                console.error('Erro:', error);
                showNotification('Erro ao alterar status da campanha', 'error');
            });
        }

        function viewCampanha(id) {
            // Redirecionar para a página de detalhes da campanha
            window.location.href = `/dashboard/marketing/campanhas/${id}/detalhes`;
        }

        function duplicateCampanha(id) {
            if (confirm('Tem certeza que deseja duplicar esta campanha?')) {
                // Implementar duplicação da campanha
                showNotification(`Campanha ${id} duplicada com sucesso!`, 'success');
            }
        }

        function enviarCampanha(id) {
            if (confirm('Tem certeza que deseja enviar esta campanha? Esta ação não pode ser desfeita.')) {
                fetch(`/dashboard/marketing/campanhas/${id}/enviar`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    }
                })
                .then(response => {
                    if (!response.ok) {
                        throw new Error('Erro na requisição');
                    }
                    return response.json();
                })
                .then(data => {
                    if (data.success) {
                        showNotification(data.message, 'success');
                        setTimeout(() => location.reload(), 2000);
                    } else {
                        showNotification(data.message || 'Erro ao enviar campanha', 'error');
                    }
                })
                .catch(error => {
                    console.error('Erro:', error);
                    showNotification('Erro ao enviar campanha', 'error');
                });
            }
        }

        function saveCampanha() {
            const form = document.getElementById('campanhaForm');
            const formData = new FormData(form);
            
            // Adicionar segmentação
            const segmentacao = [];
            document.querySelectorAll('input[name="segmentacao[]"]:checked').forEach(cb => {
                segmentacao.push(cb.value);
            });
            formData.append('segmentacao', JSON.stringify(segmentacao));
            
            const url = campanhaEditando 
                ? `/dashboard/marketing/campanhas/${campanhaEditando}`
                : '/dashboard/marketing/campanhas';
            
            const method = campanhaEditando ? 'PUT' : 'POST';
            
            // Adicionar _method para PUT
            if (campanhaEditando) {
                formData.append('_method', 'PUT');
            }
            
            fetch(url, {
                method: method,
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: formData
            })
            .then(response => {
                if (!response.ok) {
                    throw new Error('Erro na requisição');
                }
                return response.json();
            })
            .then(data => {
                if (data.success) {
                    showNotification(data.message, 'success');
                    closeCampanhaModal();
                    setTimeout(() => location.reload(), 1500);
                } else {
                    showNotification(data.message || 'Erro ao salvar campanha', 'error');
                }
            })
            .catch(error => {
                console.error('Erro:', error);
                showNotification('Erro ao salvar campanha. Verifique os dados e tente novamente.', 'error');
            });
        }

        function enviarEmailTeste() {
            const form = document.getElementById('testeEmailForm');
            const formData = new FormData(form);
            
            // Validar campos
            const email = formData.get('email_teste');
            const modelo = formData.get('modelo_teste');
            
            if (!email || !modelo) {
                showNotification('Por favor, preencha todos os campos obrigatórios.', 'warning');
                return;
            }
            
            // Mostrar loading
            const btn = document.querySelector('button[onclick="enviarEmailTeste()"]');
            const originalText = btn.innerHTML;
            btn.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i>Enviando...';
            btn.disabled = true;
            
            fetch('/dashboard/marketing/testar-email', {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: formData
            })
            .then(response => {
                if (!response.ok) {
                    throw new Error('Erro na requisição');
                }
                return response.json();
            })
            .then(data => {
                if (data.success) {
                    showNotification(data.message, 'success');
                    closeTesteEmailModal();
                } else {
                    showNotification(data.message || 'Erro ao enviar e-mail de teste', 'error');
                }
            })
            .catch(error => {
                console.error('Erro:', error);
                showNotification('Erro ao enviar e-mail de teste. Verifique a conexão e tente novamente.', 'error');
            })
            .finally(() => {
                // Restaurar botão
                btn.innerHTML = originalText;
                btn.disabled = false;
            });
        }

        // Close modal when clicking outside
        window.onclick = function(event) {
            const modal = document.getElementById('campanhaModal');
            const testeModal = document.getElementById('testeEmailModal');
            if (event.target === modal) {
                closeCampanhaModal();
            }
            if (event.target === testeModal) {
                closeTesteEmailModal();
            }
        }
    </script>
</body>
</html>

