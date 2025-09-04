<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\EmailModelo;
use App\Models\User;

class EmailModeloSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Buscar o primeiro usuário ou criar um
        $user = User::first();
        if (!$user) {
            $user = User::create([
                'name' => 'Admin',
                'email' => 'admin@example.com',
                'password' => bcrypt('password'),
            ]);
        }

        $modelos = [
            [
                'nome' => '🎯 Boas-vindas DSPay',
                'assunto' => 'Bem-vindo ao DSPay - Sua jornada começa aqui!',
                'conteudo' => '<h2>Olá {{nome}}!</h2>
<p>Seja bem-vindo ao DSPay! Estamos muito felizes em tê-lo conosco.</p>
<p>O DSPay é a solução completa para suas necessidades de pagamento e gestão financeira.</p>
<h3>O que você pode esperar:</h3>
<ul>
    <li>✅ Soluções de pagamento seguras</li>
    <li>✅ Suporte especializado</li>
    <li>✅ Tecnologia de ponta</li>
    <li>✅ Resultados comprovados</li>
</ul>
<p>Em breve entraremos em contato para apresentar nossas soluções personalizadas.</p>
<p>Atenciosamente,<br>Equipe DSPay</p>',
                'tipo' => 'lead',
                'user_id' => $user->id
            ],
            [
                'nome' => '🚀 Oportunidades de Negócio',
                'assunto' => 'Novas oportunidades para expandir seu negócio',
                'conteudo' => '<h2>Olá {{nome}}!</h2>
<p>Identificamos excelentes oportunidades para expandir seu negócio com o DSPay!</p>
<h3>Oportunidades disponíveis:</h3>
<ul>
    <li>💳 Novos produtos de pagamento</li>
    <li>📱 Soluções mobile</li>
    <li>🔒 Segurança avançada</li>
    <li>📊 Relatórios detalhados</li>
</ul>
<p>Agende uma reunião conosco para discutir como podemos ajudar!</p>
<p>Atenciosamente,<br>Equipe DSPay</p>',
                'tipo' => 'licenciado',
                'user_id' => $user->id
            ],
            [
                'nome' => '💡 Dicas e Insights',
                'assunto' => 'Dicas exclusivas para otimizar seus pagamentos',
                'conteudo' => '<h2>Olá {{nome}}!</h2>
<p>Compartilhamos com você dicas exclusivas para otimizar seus pagamentos:</p>
<h3>Dicas da semana:</h3>
<ul>
    <li>📈 Como aumentar a conversão de pagamentos</li>
    <li>🔐 Melhores práticas de segurança</li>
    <li>📱 Otimização para dispositivos móveis</li>
    <li>📊 Análise de métricas importantes</li>
</ul>
<p>Fique atento às próximas dicas!</p>
<p>Atenciosamente,<br>Equipe DSPay</p>',
                'tipo' => 'geral',
                'user_id' => $user->id
            ]
        ];

        foreach ($modelos as $modelo) {
            EmailModelo::create($modelo);
        }
    }
}
