
<x-simple-branding />
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Modelos de E-mail - DSPay Orbita</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>



    <script>
        function openAddModeloModal() {
            document.getElementById('modalTitle').textContent = 'Novo Modelo';
            document.getElementById('modeloForm').reset();
            document.getElementById('modelo_id').value = '';
            document.getElementById('modeloModal').classList.remove('hidden');
        }

        function closeModeloModal() {
            document.getElementById('modeloModal').classList.add('hidden');
        }

        function editModelo(id) {
            // Implementar edição do modelo
            alert(`Editar modelo ${id} será implementado aqui!`);
        }

        function deleteModelo(id) {
            if (confirm('Tem certeza que deseja excluir este modelo?')) {
                // Implementar exclusão do modelo
                alert(`Excluir modelo ${id} será implementado aqui!`);
            }
        }

        function previewModelo(id) {
            // Implementar preview do modelo
            alert(`Preview do modelo ${id} será implementado aqui!`);
        }

        function useModelo(id) {
            // Implementar uso do modelo
            alert(`Usar modelo ${id} será implementado aqui!`);
        }

        function saveModelo() {
            const form = document.getElementById('modeloForm');
            const formData = new FormData(form);
            
            fetch('{{ route("dashboard.marketing.modelos.store") }}', {
                method: 'POST',
                body: formData,
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    alert(data.message);
                    closeModeloModal();
                    location.reload();
                } else {
                    alert('Erro: ' + data.message);
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Erro ao salvar modelo');
            });
        }

        // Close modal when clicking outside
        window.onclick = function(event) {
            const modal = document.getElementById('modeloModal');
            if (event.target === modal) {
                closeModeloModal();
            }
        }
    </script>
</body>
</html>

