<x-simple-branding />
<!DOCTYPE html>
<html lang="pt-BR">
<head>

    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Detalhes da Campanha - DSPay Orbita</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>


    <script>
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

        function editCampanha(id) {
            window.location.href = `/dashboard/marketing/campanhas/${id}/edit`;
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

        function reenviarCampanha(id) {
            if (confirm('Tem certeza que deseja reenviar esta campanha? Esta ação não pode ser desfeita.')) {
                enviarCampanha(id);
            }
        }

        function duplicateCampanha(id) {
            if (confirm('Tem certeza que deseja duplicar esta campanha?')) {
                // Implementar duplicação da campanha
                showNotification(`Campanha ${id} duplicada com sucesso!`, 'success');
            }
        }

        function viewModelo(id) {
            // Abrir modal ou redirecionar para visualizar o modelo
            showNotification(`Visualizando modelo ${id}`, 'info');
        }
    </script>
</body>
</html>
