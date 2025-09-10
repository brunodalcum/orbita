<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>🔧 Debug Google Calendar - Orbita</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: #f8f9fa;
            padding: 20px;
            max-width: 1200px;
            margin: 0 auto;
        }
        .container {
            background: white;
            border-radius: 10px;
            padding: 30px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        .status-card {
            background: #e3f2fd;
            border-left: 4px solid #2196f3;
            padding: 15px;
            margin: 15px 0;
        }
        .error-card {
            background: #ffebee;
            border-left: 4px solid #f44336;
            padding: 15px;
            margin: 15px 0;
        }
        .success-card {
            background: #e8f5e8;
            border-left: 4px solid #4caf50;
            padding: 15px;
            margin: 15px 0;
        }
        .btn {
            display: inline-block;
            padding: 10px 20px;
            margin: 5px;
            background: #2196f3;
            color: white;
            text-decoration: none;
            border-radius: 5px;
            transition: all 0.3s;
        }
        .btn:hover {
            background: #1976d2;
        }
        .btn-success { background: #4caf50; }
        .btn-success:hover { background: #388e3c; }
        .btn-warning { background: #ff9800; }
        .btn-warning:hover { background: #f57c00; }
        .btn-danger { background: #f44336; }
        .btn-danger:hover { background: #d32f2f; }
        .code {
            background: #f5f5f5;
            padding: 10px;
            border-radius: 5px;
            font-family: monospace;
            margin: 10px 0;
        }
        .config-table {
            width: 100%;
            border-collapse: collapse;
            margin: 20px 0;
        }
        .config-table th, .config-table td {
            padding: 10px;
            border: 1px solid #ddd;
            text-align: left;
        }
        .config-table th {
            background: #f5f5f5;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>🔧 Debug Google Calendar Integration</h1>
        
        <div class="status-card">
            <h3>📋 Status Atual</h3>
            <div id="status-info">Carregando...</div>
        </div>

        <h2>🧪 Testes de Conexão</h2>
        
        <div>
            <a href="{{ route('google.status') }}" class="btn btn-success" target="_blank">📊 Verificar Status</a>
            <a href="{{ route('google.simple-test') }}" class="btn btn-success" target="_blank">🔧 Teste Simples</a>
            <a href="{{ route('google.test') }}" class="btn btn-warning" target="_blank">🧪 Testar Conexão</a>
            <a href="{{ route('google.auth') }}" class="btn">🔐 Conectar Google</a>
        </div>

        <h2>⚙️ Configuração</h2>
        
        <table class="config-table">
            <tr>
                <th>Configuração</th>
                <th>Valor</th>
                <th>Status</th>
            </tr>
            <tr>
                <td>GOOGLE_CLIENT_ID</td>
                <td>{{ config('google.client_id') ? '***configurado***' : 'NÃO CONFIGURADO' }}</td>
                <td>{{ config('google.client_id') ? '✅' : '❌' }}</td>
            </tr>
            <tr>
                <td>GOOGLE_CLIENT_SECRET</td>
                <td>{{ config('google.client_secret') ? '***configurado***' : 'NÃO CONFIGURADO' }}</td>
                <td>{{ config('google.client_secret') ? '✅' : '❌' }}</td>
            </tr>
            <tr>
                <td>GOOGLE_REDIRECT_URI</td>
                <td>{{ config('google.redirect_uri') }}</td>
                <td>✅</td>
            </tr>
            <tr>
                <td>GOOGLE_CALENDAR_ID</td>
                <td>{{ config('google.calendar.primary_calendar_id') }}</td>
                <td>✅</td>
            </tr>
        </table>

        @if(!config('google.client_id') || !config('google.client_secret'))
        <div class="error-card">
            <h3>❌ Credenciais não configuradas</h3>
            <p>Para usar a integração com Google Calendar, você precisa configurar as credenciais no arquivo <strong>.env</strong>:</p>
            <div class="code">
GOOGLE_CLIENT_ID=seu_client_id_aqui<br>
GOOGLE_CLIENT_SECRET=seu_client_secret_aqui<br>
GOOGLE_REDIRECT_URI=http://127.0.0.1:8000/google/callback
            </div>
            <p><strong>📖 Como obter as credenciais:</strong></p>
            <ol>
                <li>Acesse: <a href="https://console.cloud.google.com/" target="_blank">Google Cloud Console</a></li>
                <li>Crie um projeto ou selecione um existente</li>
                <li>Ative a <strong>Google Calendar API</strong></li>
                <li>Vá em <strong>APIs & Services > Credentials</strong></li>
                <li>Clique em <strong>Create Credentials > OAuth 2.0 Client IDs</strong></li>
                <li>Configure como <strong>Web application</strong></li>
                <li>Adicione a URL de redirect: <code>http://127.0.0.1:8000/google/callback</code></li>
                <li>Copie o Client ID e Client Secret para o .env</li>
            </ol>
        </div>
        @else
        <div class="success-card">
            <h3>✅ Credenciais configuradas</h3>
            <p>As credenciais do Google Calendar estão configuradas. Você pode testar a conexão usando os botões acima.</p>
        </div>
        @endif

        <h2>📋 Rotas Disponíveis</h2>
        <ul>
            <li><strong>GET</strong> <code>/google/auth</code> - Conectar com Google</li>
            <li><strong>GET</strong> <code>/google/callback</code> - Callback de autenticação</li>
            <li><strong>GET</strong> <code>/google/status</code> - Status da conexão (JSON)</li>
            <li><strong>GET</strong> <code>/google/test</code> - Testar conexão (JSON)</li>
            <li><strong>POST</strong> <code>/google/disconnect</code> - Desconectar</li>
        </ul>

        <h2>🔍 Logs</h2>
        <p>Para debug detalhado, verifique os logs em:</p>
        <div class="code">tail -f storage/logs/laravel.log | grep -i google</div>

        <div style="text-align: center; margin-top: 40px;">
            <a href="{{ route('dashboard.agenda') }}" class="btn">🏠 Voltar para Agenda</a>
        </div>
    </div>

    <script>
        // Verificar status automaticamente
        fetch('/google/status')
            .then(response => response.json())
            .then(data => {
                const statusDiv = document.getElementById('status-info');
                if (data.configured) {
                    if (data.connected) {
                        statusDiv.innerHTML = '<strong style="color: green;">✅ Conectado e funcionando!</strong>';
                    } else {
                        statusDiv.innerHTML = '<strong style="color: orange;">⚠️ Configurado mas não conectado</strong>';
                    }
                } else {
                    statusDiv.innerHTML = '<strong style="color: red;">❌ Não configurado</strong>';
                }
            })
            .catch(error => {
                document.getElementById('status-info').innerHTML = '<strong style="color: red;">❌ Erro ao verificar status</strong>';
            });
    </script>
</body>
</html>
