<x-simple-branding />
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Gerenciar Licenciado - dspay</title>
    <link rel="icon" type="image/png" href="{{ asset('images/dspay-logo.png') }}">
    <link rel="shortcut icon" type="image/png" href="{{ asset('images/dspay-logo.png') }}">
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="{{ asset('app.css') }}" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <!-- Branding Dinâmico -->
    


    <script>
        const licenciadoId = {{ $licenciado->id }};

        // Função para alterar status
        async function alterarStatus(id, status) {
            const statusLabels = {
                'aprovado': 'Aprovar',
                'recusado': 'Reprovar',
                'em_analise': 'Revisar'
            };

            if (!confirm(`Tem certeza que deseja ${statusLabels[status]} este licenciado?`)) {
                return;
            }

            try {
                const response = await fetch(`/dashboard/licenciados/${id}/status`, {
                    method: 'PATCH',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    },
                    body: JSON.stringify({ status })
                });

                const result = await response.json();

                if (result.success) {
                    alert('Status alterado com sucesso!');
                    location.reload();
                } else {
                    alert('Erro ao alterar status: ' + (result.message || 'Erro desconhecido'));
                }
            } catch (error) {
                console.error('Erro:', error);
                alert('Erro de conexão');
            }
        }

        // Funções do modal de documento
        function openDocumentModal() {
            document.getElementById('documentModal').classList.add('show');
        }

        function closeDocumentModal() {
            document.getElementById('documentModal').classList.remove('show');
            document.getElementById('documentForm').reset();
        }

        // Funções do modal de follow-up
        function openFollowUpModal() {
            document.getElementById('followUpModal').classList.add('show');
        }

        function closeFollowUpModal() {
            document.getElementById('followUpModal').classList.remove('show');
            document.getElementById('followUpForm').reset();
        }

        // Event listeners
        document.addEventListener('DOMContentLoaded', function() {
            // Form de documento
            document.getElementById('documentForm').addEventListener('submit', async function(e) {
                e.preventDefault();
                
                const formData = new FormData(this);
                formData.append('licenciado_id', licenciadoId);
                
                try {
                    const response = await fetch(`/dashboard/licenciados/${licenciadoId}/document`, {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                        },
                        body: formData
                    });

                    const result = await response.json();

                    if (result.success) {
                        alert('Documento enviado com sucesso!');
                        closeDocumentModal();
                        location.reload();
                    } else {
                        alert('Erro ao enviar documento: ' + (result.message || 'Erro desconhecido'));
                    }
                } catch (error) {
                    console.error('Erro:', error);
                    alert('Erro de conexão');
                }
            });

            // Form de follow-up
            document.getElementById('followUpForm').addEventListener('submit', async function(e) {
                e.preventDefault();
                
                const formData = new FormData(this);
                
                try {
                    const response = await fetch(`/dashboard/licenciados/${licenciadoId}/followup`, {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                        },
                        body: formData
                    });

                    const result = await response.json();

                    if (result.success) {
                        alert('Follow-up adicionado com sucesso!');
                        closeFollowUpModal();
                        location.reload();
                    } else {
                        alert('Erro ao adicionar follow-up: ' + (result.message || 'Erro desconhecido'));
                    }
                } catch (error) {
                    console.error('Erro:', error);
                    alert('Erro de conexão');
                }
            });

            // Fechar modais ao clicar fora
            document.getElementById('documentModal').addEventListener('click', function(e) {
                if (e.target === this) {
                    closeDocumentModal();
                }
            });

            document.getElementById('followUpModal').addEventListener('click', function(e) {
                if (e.target === this) {
                    closeFollowUpModal();
                }
            });
        });
    </script>
            </main>
        </div>
    </div>
</body>
</html>
