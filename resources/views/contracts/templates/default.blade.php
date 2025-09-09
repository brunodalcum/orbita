<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contrato de Licenciamento - {{ $contratante['nome'] ?? 'N/A' }}</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'Times New Roman', Times, serif;
            font-size: 12px;
            line-height: 1.6;
            color: #000;
            margin: 0;
            padding: 0;
            background: white;
        }
        
        .document {
            max-width: 100%;
            margin: 0;
            padding: 25px;
            background: white;
        }
        
        .header {
            text-align: center;
            margin-bottom: 40px;
            padding-bottom: 20px;
            border-bottom: 2px solid #000;
        }
        
        .header h1 {
            font-size: 18px;
            font-weight: bold;
            margin: 0 0 15px 0;
            text-transform: uppercase;
            letter-spacing: 1px;
            color: #000;
        }
        
        .header .contract-info {
            margin-top: 15px;
        }
        
        .header .contract-info p {
            margin: 5px 0;
            font-size: 11px;
            font-weight: bold;
        }
        
        .section {
            margin-bottom: 30px;
            break-inside: avoid;
        }
        
        .section-title {
            font-size: 14px;
            font-weight: bold;
            text-transform: uppercase;
            margin-bottom: 18px;
            padding: 10px 0 8px 0;
            border-bottom: 1px solid #000;
            text-align: center;
            letter-spacing: 0.5px;
        }
        
        .parties {
            margin-bottom: 35px;
        }
        
        .party {
            margin-bottom: 20px;
            padding: 15px;
            border: 1px solid #ccc;
            background-color: #f8f8f8;
            break-inside: avoid;
        }
        
        .party-title {
            font-weight: bold;
            text-transform: uppercase;
            margin-bottom: 12px;
            font-size: 12px;
            color: #000;
            border-bottom: 1px solid #ddd;
            padding-bottom: 5px;
        }
        
        .party-info {
            margin: 6px 0;
            font-size: 11px;
            line-height: 1.5;
        }
        
        .party-info strong {
            font-weight: bold;
        }
        
        .clause {
            margin-bottom: 25px;
            text-align: justify;
            break-inside: avoid;
        }
        
        .clause-title {
            font-weight: bold;
            margin-bottom: 12px;
            font-size: 12px;
            text-transform: uppercase;
        }
        
        .clause p {
            margin-bottom: 12px;
            text-indent: 25px;
            text-align: justify;
            line-height: 1.6;
        }
        
        .subclauses {
            margin: 15px 0 15px 30px;
        }
        
        .subclause {
            margin-bottom: 8px;
            text-align: justify;
            font-size: 11px;
            line-height: 1.5;
        }
        
        .signatures {
            margin-top: 50px;
            page-break-inside: avoid;
            break-inside: avoid;
        }
        
        .signature-container {
            display: table;
            width: 100%;
            margin-top: 50px;
        }
        
        .signature-block {
            display: table-cell;
            width: 45%;
            text-align: center;
            vertical-align: top;
            padding: 0 10px;
        }
        
        .signature-line {
            border-top: 1px solid #000;
            width: 250px;
            margin: 0 auto 10px auto;
            height: 1px;
        }
        
        .signature-name {
            font-weight: bold;
            font-size: 11px;
            margin-bottom: 5px;
        }
        
        .signature-doc {
            font-size: 10px;
            margin-bottom: 5px;
        }
        
        .signature-role {
            font-size: 10px;
            font-weight: bold;
            text-transform: uppercase;
        }
        
        .footer {
            position: fixed;
            bottom: 20px;
            left: 25px;
            right: 25px;
            text-align: center;
            font-size: 9px;
            color: #666;
            border-top: 1px solid #ccc;
            padding-top: 10px;
        }
        
        @page {
            margin: 2cm;
            size: A4 portrait;
        }
        
        @media print {
            .document {
                margin: 0;
                padding: 0;
            }
            
            .signatures {
                page-break-inside: avoid;
            }
        }
        
        /* Quebras de página */
        .page-break {
            page-break-before: always;
        }
        
        .no-break {
            page-break-inside: avoid;
        }
        
        /* Melhorias de tipografia */
        strong {
            font-weight: bold;
        }
        
        em {
            font-style: italic;
        }
        
        /* Espaçamento consistente */
        .clause + .clause {
            margin-top: 20px;
        }
        
        /* Espaçamento extra para melhor separação visual */
        .section + .section {
            margin-top: 40px;
        }
    </style>
</head>
<body>
    <div class="document">
        <div class="header">
            <h1>Contrato de Licenciamento de Sistema de Pagamentos</h1>
            <div class="contract-info">
                <p>Contrato nº {{ str_pad($contrato['id'] ?? 0, 6, '0', STR_PAD_LEFT) }}</p>
                <p>Data: {{ $contrato['data'] ?? date('d/m/Y') }}</p>
            </div>
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
            <div class="party-info"><strong>{{ $contratante['nome'] ?? $licensee->razao_social ?? $licensee->nome_fantasia ?? 'Nome não informado' }}</strong></div>
            <div class="party-info">CNPJ/CPF: {{ $contratante['documento'] ?? ($licensee ? $licensee->cnpj_cpf : 'Documento não informado') }}</div>
            <div class="party-info">Endereço: {{ $contratante['endereco'] ?? ($licensee ? ($licensee->endereco . ', ' . $licensee->cidade . '/' . $licensee->estado) : 'Endereço não informado') }}</div>
            <div class="party-info">CONCEP: {{ $contratante['concep'] ?? ($licensee->concep ?? 'CONCEP não informado') }}</div>
            @if(!empty($contratante['email']) || !empty($licensee->email))
            <div class="party-info">E-mail: {{ $contratante['email'] ?? $licensee->email ?? 'E-mail não informado' }}</div>
            @endif
            @if(!empty($contratante['telefone']) || !empty($licensee->telefone))
            <div class="party-info">Telefone: {{ $contratante['telefone'] ?? $licensee->telefone ?? 'Telefone não informado' }}</div>
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

    <div class="section no-break">
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

    <div class="signatures no-break">
        <div class="section-title">Das Assinaturas</div>
        
        <p style="text-align: center; margin-bottom: 40px; font-style: italic; line-height: 1.6;">
            Por estarem justas e contratadas, as partes assinam o presente contrato em duas vias de igual teor e forma.
        </p>
        
        @if(isset($signature_info) && $signature_info)
            <!-- CONTRATO ASSINADO DIGITALMENTE -->
            <div class="digital-signature-section" style="border: 2px solid #10b981; border-radius: 10px; padding: 20px; background: #f0fdf4; margin-bottom: 30px;">
                <div style="text-align: center; font-weight: bold; color: #059669; margin-bottom: 20px; font-size: 18px;">
                    🔒 CONTRATO ASSINADO DIGITALMENTE
                </div>
                
                <div style="display: table; width: 100%; margin-bottom: 20px;">
                    <div style="display: table-row;">
                        <div style="display: table-cell; padding: 8px; border-bottom: 1px solid #d1fae5; font-weight: bold; width: 30%; color: #065f46;">Assinante:</div>
                        <div style="display: table-cell; padding: 8px; border-bottom: 1px solid #d1fae5;">{{ $signature_info['signer_name'] ?? 'N/A' }}</div>
                    </div>
                    <div style="display: table-row;">
                        <div style="display: table-cell; padding: 8px; border-bottom: 1px solid #d1fae5; font-weight: bold; width: 30%; color: #065f46;">Documento:</div>
                        <div style="display: table-cell; padding: 8px; border-bottom: 1px solid #d1fae5;">{{ $signature_info['signer_document'] ?? 'N/A' }}</div>
                    </div>
                    <div style="display: table-row;">
                        <div style="display: table-cell; padding: 8px; border-bottom: 1px solid #d1fae5; font-weight: bold; width: 30%; color: #065f46;">Data/Hora da Assinatura:</div>
                        <div style="display: table-cell; padding: 8px; border-bottom: 1px solid #d1fae5;">{{ $signature_info['signed_at'] ?? 'N/A' }}</div>
                    </div>
                    <div style="display: table-row;">
                        <div style="display: table-cell; padding: 8px; border-bottom: 1px solid #d1fae5; font-weight: bold; width: 30%; color: #065f46;">IP de Origem:</div>
                        <div style="display: table-cell; padding: 8px; border-bottom: 1px solid #d1fae5;">{{ $signature_info['signer_ip'] ?? 'N/A' }}</div>
                    </div>
                    <div style="display: table-row;">
                        <div style="display: table-cell; padding: 8px; font-weight: bold; width: 30%; color: #065f46;">Status:</div>
                        <div style="display: table-cell; padding: 8px;"><strong style="color: #059669;">✓ ASSINADO DIGITALMENTE</strong></div>
                    </div>
                </div>

                @if(isset($signature_image_path) && file_exists($signature_image_path))
                <div style="text-align: center; margin: 20px 0; padding: 20px; border: 1px dashed #10b981; background: #fff; border-radius: 8px;">
                    <div style="font-weight: bold; margin-bottom: 10px;">Assinatura Digital:</div>
                    <img src="file://{{ $signature_image_path }}" alt="Assinatura Digital" style="max-width: 300px; max-height: 100px;" />
                </div>
                @endif

                <div style="background: #065f46; color: white; padding: 15px; border-radius: 8px; margin-top: 20px; text-align: center;">
                    <div><strong>CERTIFICADO DE AUTENTICIDADE</strong></div>
                    <div>Este documento foi assinado digitalmente conforme a Lei 14.063/2020</div>
                    <div style="font-family: 'Courier New', monospace; font-size: 12px; word-break: break-all; margin-top: 10px; background: rgba(255,255,255,0.1); padding: 8px; border-radius: 4px;">
                        <strong>Hash de Integridade:</strong><br>
                        {{ $signature_info['signature_hash'] ?? 'N/A' }}
                    </div>
                </div>
            </div>
            
            <!-- Assinatura da Contratada -->
            <div class="signature-container">
                <div class="signature-block" style="width: 100%; text-align: center;">
                    <div class="signature-line"></div>
                    <div class="signature-name">{{ $contratada['nome'] ?? 'DSPAY TECNOLOGIA LTDA' }}</div>
                    <div class="signature-doc">CNPJ: {{ $contratada['cnpj'] ?? '00.000.000/0001-00' }}</div>
                    <div class="signature-role">CONTRATADA</div>
                </div>
            </div>
        @else
            <!-- CONTRATO PARA ASSINATURA -->
            <div class="signature-container">
                <div class="signature-block">
                    <div class="signature-line"></div>
                    <div class="signature-name">{{ $contratada['nome'] ?? 'DSPAY TECNOLOGIA LTDA' }}</div>
                    <div class="signature-doc">CNPJ: {{ $contratada['cnpj'] ?? '00.000.000/0001-00' }}</div>
                    <div class="signature-role">CONTRATADA</div>
                </div>
                
                <div class="signature-block">
                    <div class="signature-line"></div>
                    <div class="signature-name">{{ $contratante['nome'] ?? $licensee->razao_social ?? $licensee->nome_fantasia ?? 'Nome não informado' }}</div>
                    <div class="signature-doc">{{ $contratante['documento'] ?? ($licensee ? $licensee->cnpj_cpf : 'Documento não informado') }}</div>
                    <div class="signature-role">CONTRATANTE</div>
                </div>
            </div>
        @endif
    </div>

        <div class="footer">
            <p>Contrato nº {{ str_pad($contrato['id'] ?? 0, 6, '0', STR_PAD_LEFT) }} | Hash: {{ $contrato['hash'] ?? 'N/A' }} | Gerado em {{ $contrato['data'] ?? date('d/m/Y H:i') }}</p>
        </div>
    </div>
</body>
</html>
