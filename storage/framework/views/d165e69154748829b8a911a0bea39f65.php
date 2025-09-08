<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contrato Assinado - <?php echo e($contract->id ?? 'N/A'); ?></title>
    <style>
        body {
            font-family: 'DejaVu Sans', Arial, sans-serif;
            line-height: 1.6;
            color: #333;
            max-width: 800px;
            margin: 0 auto;
            padding: 20px;
            background: #fff;
        }
        .header {
            text-align: center;
            margin-bottom: 30px;
            border-bottom: 3px solid #2563eb;
            padding-bottom: 20px;
        }
        .company-logo {
            font-size: 24px;
            font-weight: bold;
            color: #2563eb;
            margin-bottom: 10px;
        }
        .contract-title {
            font-size: 20px;
            font-weight: bold;
            margin: 20px 0;
            text-transform: uppercase;
        }
        .contract-info {
            background: #f8f9fa;
            padding: 15px;
            border-radius: 8px;
            margin: 20px 0;
            border-left: 4px solid #2563eb;
        }
        .contract-info table {
            width: 100%;
            border-collapse: collapse;
        }
        .contract-info td {
            padding: 5px 10px;
            border-bottom: 1px solid #e9ecef;
        }
        .contract-info td:first-child {
            font-weight: bold;
            width: 30%;
        }
        .content {
            margin: 30px 0;
            text-align: justify;
        }
        .clause {
            margin: 20px 0;
        }
        .clause-title {
            font-weight: bold;
            margin: 15px 0 10px 0;
            color: #1f2937;
        }
        .signature-section {
            margin-top: 40px;
            border: 2px solid #10b981;
            border-radius: 10px;
            padding: 20px;
            background: #f0fdf4;
        }
        .signature-header {
            text-align: center;
            font-weight: bold;
            color: #059669;
            margin-bottom: 20px;
            font-size: 18px;
        }
        .signature-info {
            display: table;
            width: 100%;
            margin-bottom: 20px;
        }
        .signature-info .row {
            display: table-row;
        }
        .signature-info .cell {
            display: table-cell;
            padding: 8px;
            border-bottom: 1px solid #d1fae5;
        }
        .signature-info .cell:first-child {
            font-weight: bold;
            width: 30%;
            color: #065f46;
        }
        .signature-image {
            text-align: center;
            margin: 20px 0;
            padding: 20px;
            border: 1px dashed #10b981;
            background: #fff;
            border-radius: 8px;
        }
        .signature-image img {
            max-width: 300px;
            max-height: 100px;
        }
        .signature-validation {
            background: #065f46;
            color: white;
            padding: 15px;
            border-radius: 8px;
            margin-top: 20px;
            text-align: center;
        }
        .signature-validation .hash {
            font-family: 'Courier New', monospace;
            font-size: 12px;
            word-break: break-all;
            margin-top: 10px;
            background: rgba(255,255,255,0.1);
            padding: 8px;
            border-radius: 4px;
        }
        .footer {
            margin-top: 40px;
            text-align: center;
            font-size: 12px;
            color: #6b7280;
            border-top: 1px solid #e5e7eb;
            padding-top: 20px;
        }
        .page-break {
            page-break-before: always;
        }
        @media print {
            body { margin: 0; padding: 15px; }
            .page-break { page-break-before: always; }
        }
    </style>
</head>
<body>
    <!-- Header -->
    <div class="header">
        <div class="company-logo">DSPAY</div>
        <div>Soluções Completas em Meios de Pagamento</div>
        <div class="contract-title">Contrato de Licenciamento</div>
    </div>

    <!-- Contract Information -->
    <div class="contract-info">
        <table>
            <tr>
                <td>Número do Contrato:</td>
                <td>#<?php echo e(str_pad($contract->id ?? 0, 6, '0', STR_PAD_LEFT)); ?></td>
            </tr>
            <tr>
                <td>Data de Criação:</td>
                <td><?php echo e(isset($contract) ? $contract->created_at->format('d/m/Y H:i') : date('d/m/Y H:i')); ?></td>
            </tr>
            <tr>
                <td>Status:</td>
                <td><strong style="color: #059669;">✓ CONTRATO ASSINADO DIGITALMENTE</strong></td>
            </tr>
        </table>
    </div>

    <!-- Contract Content -->
    <div class="content">
        <div class="clause">
            <div class="clause-title">CONTRATANTE:</div>
            <p>
                <strong>DSPAY LTDA</strong>, pessoa jurídica de direito privado, inscrita no CNPJ sob o nº XX.XXX.XXX/0001-XX, 
                com sede na Rua Example, 123, São Paulo - SP, CEP 01234-567, neste ato representada por seus administradores.
            </p>
        </div>

        <div class="clause">
            <div class="clause-title">CONTRATADO (LICENCIADO):</div>
            <p>
                <strong><?php echo e($licenciado->name ?? 'Nome do Licenciado'); ?></strong>, 
                <?php echo e(strlen($licenciado->cnpj_cpf ?? '') == 14 ? 'pessoa jurídica' : 'pessoa física'); ?>, 
                portador do documento <?php echo e($licenciado->cnpj_cpf ?? 'N/A'); ?>, 
                com endereço <?php echo e($licenciado->endereco_completo ?? 'não informado'); ?>, 
                CEP <?php echo e($licenciado->cep ?? 'não informado'); ?>, 
                e-mail <?php echo e($licenciado->email ?? 'não informado'); ?>.
            </p>
        </div>

        <div class="clause">
            <div class="clause-title">CLÁUSULA 1ª - DO OBJETO</div>
            <p>
                O presente contrato tem por objeto o licenciamento de uso da plataforma DSPAY para processamento 
                de pagamentos eletrônicos, incluindo mas não limitado a: cartões de crédito, débito, PIX, 
                boletos bancários e demais meios de pagamento disponibilizados pela plataforma.
            </p>
        </div>

        <div class="clause">
            <div class="clause-title">CLÁUSULA 2ª - DAS OBRIGAÇÕES DO LICENCIADO</div>
            <p>São obrigações do CONTRATADO:</p>
            <ul>
                <li>Cumprir todas as normas e regulamentações estabelecidas pela DSPAY;</li>
                <li>Manter absoluto sigilo sobre as informações técnicas e comerciais;</li>
                <li>Utilizar a plataforma apenas para atividades lícitas e autorizadas;</li>
                <li>Efetuar os pagamentos das taxas conforme tabela vigente;</li>
                <li>Manter seus dados cadastrais sempre atualizados;</li>
                <li>Reportar imediatamente qualquer irregularidade ou suspeita de fraude.</li>
            </ul>
        </div>

        <div class="clause">
            <div class="clause-title">CLÁUSULA 3ª - DAS OBRIGAÇÕES DA CONTRATANTE</div>
            <p>São obrigações da CONTRATANTE:</p>
            <ul>
                <li>Disponibilizar a plataforma de pagamentos conforme especificações técnicas;</li>
                <li>Prestar suporte técnico durante horário comercial;</li>
                <li>Manter a segurança e integridade dos dados processados;</li>
                <li>Efetuar os repasses conforme cronograma estabelecido;</li>
                <li>Cumprir as normas do Banco Central e demais órgãos reguladores.</li>
            </ul>
        </div>

        <div class="clause">
            <div class="clause-title">CLÁUSULA 4ª - DA VIGÊNCIA</div>
            <p>
                Este contrato entra em vigor na data de sua assinatura digital e permanecerá válido por prazo 
                indeterminado, podendo ser rescindido por qualquer das partes mediante aviso prévio de 30 (trinta) dias.
            </p>
        </div>

        <div class="clause">
            <div class="clause-title">CLÁUSULA 5ª - DAS DISPOSIÇÕES GERAIS</div>
            <p>
                Este contrato é regido pelas leis brasileiras, especialmente pela Lei nº 14.063/2020 (Marco Legal 
                da Assinatura Eletrônica), Código Civil e demais legislações aplicáveis. Qualquer divergência será 
                resolvida no foro da comarca de São Paulo - SP.
            </p>
        </div>
    </div>

    <!-- Signature Section -->
    <div class="signature-section">
        <div class="signature-header">
            🔒 ASSINATURA DIGITAL VÁLIDA
        </div>

        <div class="signature-info">
            <div class="row">
                <div class="cell">Assinante:</div>
                <div class="cell"><?php echo e($signature_info['signer_name'] ?? 'N/A'); ?></div>
            </div>
            <div class="row">
                <div class="cell">Documento:</div>
                <div class="cell"><?php echo e($signature_info['signer_document'] ?? 'N/A'); ?></div>
            </div>
            <div class="row">
                <div class="cell">Data/Hora da Assinatura:</div>
                <div class="cell"><?php echo e($signature_info['signed_at'] ?? 'N/A'); ?></div>
            </div>
            <div class="row">
                <div class="cell">IP de Origem:</div>
                <div class="cell"><?php echo e($signature_info['signer_ip'] ?? 'N/A'); ?></div>
            </div>
            <div class="row">
                <div class="cell">Status:</div>
                <div class="cell"><strong style="color: #059669;">✓ ASSINADO DIGITALMENTE</strong></div>
            </div>
        </div>

        <?php if(isset($signature_image_path) && file_exists($signature_image_path)): ?>
        <div class="signature-image">
            <div style="font-weight: bold; margin-bottom: 10px;">Assinatura Digital:</div>
            <img src="<?php echo e($signature_image_path); ?>" alt="Assinatura Digital" />
        </div>
        <?php endif; ?>

        <div class="signature-validation">
            <div><strong>CERTIFICADO DE AUTENTICIDADE</strong></div>
            <div>Este documento foi assinado digitalmente conforme a Lei 14.063/2020</div>
            <div class="hash">
                <strong>Hash de Integridade:</strong><br>
                <?php echo e($signature_info['signature_hash'] ?? 'N/A'); ?>

            </div>
        </div>
    </div>

    <!-- Footer -->
    <div class="footer">
        <p>
            <strong>DSPAY LTDA</strong> - Soluções Completas em Meios de Pagamento<br>
            Este documento foi gerado automaticamente pelo sistema DSPAY<br>
            Data de geração: <?php echo e(date('d/m/Y H:i:s')); ?>

        </p>
        <p style="margin-top: 15px; font-size: 10px;">
            A assinatura digital deste contrato possui validade jurídica conforme Lei 14.063/2020 (Marco Legal da Assinatura Eletrônica), 
            MP 2.200-2/2001 (ICP-Brasil) e Código Civil Brasileiro Art. 219. 
            Este documento está protegido por criptografia SHA-256 e possui certificado de autenticidade.
        </p>
    </div>
</body>
</html>
<?php /**PATH /Applications/MAMP/htdocs/orbita/resources/views/contracts/templates/signed-default.blade.php ENDPATH**/ ?>