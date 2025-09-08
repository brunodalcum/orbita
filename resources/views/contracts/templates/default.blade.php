<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contrato de Licenciamento - {{ $contratante['nome'] ?? 'N/A' }}</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 12px;
            line-height: 1.6;
            color: #333;
            margin: 0;
            padding: 20px;
        }
        .header {
            text-align: center;
            margin-bottom: 30px;
            border-bottom: 2px solid #333;
            padding-bottom: 20px;
        }
        .header h1 {
            font-size: 18px;
            font-weight: bold;
            margin: 0 0 10px 0;
            text-transform: uppercase;
        }
        .header p {
            margin: 5px 0;
            font-size: 11px;
        }
        .section {
            margin-bottom: 25px;
        }
        .section-title {
            font-size: 14px;
            font-weight: bold;
            text-transform: uppercase;
            margin-bottom: 15px;
            border-bottom: 1px solid #ccc;
            padding-bottom: 5px;
        }
        .parties {
            margin-bottom: 20px;
        }
        .party {
            margin-bottom: 15px;
            padding: 10px;
            background-color: #f9f9f9;
            border-left: 3px solid #333;
        }
        .party-title {
            font-weight: bold;
            text-transform: uppercase;
            margin-bottom: 8px;
        }
        .party-info {
            margin: 3px 0;
        }
        .clause {
            margin-bottom: 15px;
            text-align: justify;
        }
        .clause-title {
            font-weight: bold;
            margin-bottom: 8px;
        }
        .subclauses {
            margin-left: 20px;
        }
        .subclause {
            margin-bottom: 8px;
        }
        .signatures {
            margin-top: 50px;
            page-break-inside: avoid;
        }
        .signature-block {
            margin-top: 40px;
            text-align: center;
        }
        .signature-line {
            border-top: 1px solid #333;
            width: 300px;
            margin: 0 auto 5px auto;
        }
        .footer {
            position: fixed;
            bottom: 20px;
            left: 20px;
            right: 20px;
            text-align: center;
            font-size: 10px;
            color: #666;
            border-top: 1px solid #ccc;
            padding-top: 10px;
        }
        @page {
            margin: 2cm;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>Contrato de Licenciamento de Sistema de Pagamentos</h1>
        <p>Contrato nº {{ $contrato['id'] ?? 'N/A' }}</p>
        <p>Data: {{ $contrato['data'] ?? date('d/m/Y') }}</p>
    </div>

    <div class="section parties">
        <div class="section-title">Das Partes Contratantes</div>
        
        <div class="party">
            <div class="party-title">Contratada:</div>
            <div class="party-info"><strong>{{ $contratada['nome'] ?? 'DSPAY TECNOLOGIA LTDA' }}</strong></div>
            <div class="party-info">CNPJ: {{ $contratada['cnpj'] ?? '00.000.000/0001-00' }}</div>
            <div class="party-info">Endereço: {{ $contratada['endereco'] ?? 'Endereço não informado' }}, {{ $contratada['cidade'] ?? 'Cidade' }}/{{ $contratada['uf'] ?? 'UF' }}</div>
            <div class="party-info">CEP: {{ $contratada['cep'] ?? '00000-000' }}</div>
        </div>

        <div class="party">
            <div class="party-title">Contratante (Licenciado):</div>
            <div class="party-info"><strong>{{NOME}}</strong></div>
            <div class="party-info">CNPJ/CPF: {{CNPJ}}</div>
            <div class="party-info">Endereço: {{ENDERECO}}</div>
            <div class="party-info">CONCEP: {{CONCEP}}</div>
            @if(!empty($contratante['email']))
            <div class="party-info">E-mail: {{ $contratante['email'] }}</div>
            @endif
            @if(!empty($contratante['telefone']))
            <div class="party-info">Telefone: {{ $contratante['telefone'] }}</div>
            @endif
        </div>

        @if(!empty($representante['nome']))
        <div class="party">
            <div class="party-title">Representante Legal do Contratante:</div>
            <div class="party-info"><strong>{{ $representante['nome'] }}</strong></div>
            <div class="party-info">CPF: {{ $representante['cpf'] ?? 'CPF não informado' }}</div>
            <div class="party-info">E-mail: {{ $representante['email'] ?? 'E-mail não informado' }}</div>
            <div class="party-info">Telefone: {{ $representante['telefone'] ?? 'Telefone não informado' }}</div>
        </div>
        @endif
    </div>

    <div class="section">
        <div class="section-title">Do Objeto</div>
        <div class="clause">
            <div class="clause-title">Cláusula 1ª - DO OBJETO</div>
            <p>O presente contrato tem por objeto o licenciamento de uso do sistema de pagamentos eletrônicos desenvolvido e mantido pela CONTRATADA, incluindo todos os módulos, funcionalidades e serviços correlatos, para utilização pelo CONTRATANTE em suas atividades comerciais.</p>
        </div>
    </div>

    <div class="section">
        <div class="section-title">Das Obrigações</div>
        
        <div class="clause">
            <div class="clause-title">Cláusula 2ª - DAS OBRIGAÇÕES DA CONTRATADA</div>
            <div class="subclauses">
                <div class="subclause">a) Disponibilizar o sistema de pagamentos em funcionamento;</div>
                <div class="subclause">b) Prestar suporte técnico durante o horário comercial;</div>
                <div class="subclause">c) Manter a segurança e integridade dos dados;</div>
                <div class="subclause">d) Realizar atualizações e melhorias no sistema;</div>
                <div class="subclause">e) Fornecer relatórios de transações e comissões.</div>
            </div>
        </div>

        <div class="clause">
            <div class="clause-title">Cláusula 3ª - DAS OBRIGAÇÕES DO CONTRATANTE</div>
            <div class="subclauses">
                <div class="subclause">a) Utilizar o sistema de acordo com os termos deste contrato;</div>
                <div class="subclause">b) Manter seus dados cadastrais atualizados;</div>
                <div class="subclause">c) Cumprir as normas de segurança estabelecidas;</div>
                <div class="subclause">d) Comunicar imediatamente qualquer irregularidade;</div>
                <div class="subclause">e) Pagar as taxas e comissões devidas conforme tabela vigente.</div>
            </div>
        </div>
    </div>

    <div class="section">
        <div class="section-title">Das Condições Financeiras</div>
        
        <div class="clause">
            <div class="clause-title">Cláusula 4ª - DAS TAXAS E COMISSÕES</div>
            <p>O CONTRATANTE pagará as taxas e comissões conforme tabela de preços vigente, disponibilizada no sistema e atualizada periodicamente pela CONTRATADA.</p>
            <div class="subclauses">
                <div class="subclause">§1º - As taxas incidem sobre o valor bruto de cada transação;</div>
                <div class="subclause">§2º - O desconto das taxas é realizado automaticamente no momento do repasse;</div>
                <div class="subclause">§3º - Os repasses são realizados conforme modalidade D+0, D+1 ou D+30, conforme contratado.</div>
            </div>
        </div>
    </div>

    <div class="section">
        <div class="section-title">Da Vigência</div>
        
        <div class="clause">
            <div class="clause-title">Cláusula 5ª - DA VIGÊNCIA E RESCISÃO</div>
            <p>Este contrato entra em vigor na data de sua assinatura e permanecerá válido por prazo indeterminado, podendo ser rescindido por qualquer das partes mediante aviso prévio de 30 (trinta) dias.</p>
            <div class="subclauses">
                <div class="subclause">§1º - A rescisão não afeta as obrigações já assumidas;</div>
                <div class="subclause">§2º - Em caso de inadimplência, o contrato pode ser rescindido imediatamente;</div>
                <div class="subclause">§3º - Após a rescisão, o acesso ao sistema será suspenso em até 24 horas.</div>
            </div>
        </div>
    </div>

    <div class="section">
        <div class="section-title">Das Disposições Gerais</div>
        
        <div class="clause">
            <div class="clause-title">Cláusula 6ª - DO FORO</div>
            <p>Fica eleito o foro da comarca de {{ $contratada['cidade'] ?? 'São Paulo' }}/{{ $contratada['uf'] ?? 'SP' }} para dirimir quaisquer controvérsias oriundas deste contrato, renunciando as partes a qualquer outro, por mais privilegiado que seja.</p>
        </div>

        <div class="clause">
            <div class="clause-title">Cláusula 7ª - DAS DISPOSIÇÕES FINAIS</div>
            <p>Este contrato representa o acordo integral entre as partes, revogando todos os acordos anteriores. Qualquer alteração deve ser feita por escrito e assinada por ambas as partes.</p>
        </div>
    </div>

    <div class="signatures">
        <div class="section-title">Das Assinaturas</div>
        
        <p>Por estarem justas e contratadas, as partes assinam o presente contrato em duas vias de igual teor e forma.</p>
        
        <div style="display: flex; justify-content: space-between; margin-top: 60px;">
            <div class="signature-block" style="width: 45%;">
                <div class="signature-line"></div>
                <div><strong>{{ $contratada['nome'] ?? 'DSPAY TECNOLOGIA LTDA' }}</strong></div>
                <div>CONTRATADA</div>
            </div>
            
            <div class="signature-block" style="width: 45%;">
                <div class="signature-line"></div>
                <div><strong>{{NOME}}</strong></div>
                <div>{{CNPJ}}</div>
                <div>CONTRATANTE</div>
            </div>
        </div>
    </div>

    <div class="footer">
        <p>Contrato nº {{ $contrato['id'] ?? 'N/A' }} | Hash: {{ $contrato['hash'] ?? 'N/A' }} | Gerado em {{ $contrato['data'] ?? date('d/m/Y H:i') }}</p>
    </div>
</body>
</html>
