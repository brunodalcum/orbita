# GUIA DE MIGRAÇÃO - SISTEMA UNIFICADO DE BRANDING

## Classes Disponíveis

### Botões
- `.brand-btn` - Classe base para todos os botões
- `.brand-btn-primary` - Botão primário (cor principal do branding)
- `.brand-btn-secondary` - Botão secundário (outline)
- `.brand-btn-accent` - Botão de destaque (cor de accent)

### Cards
- `.brand-card` - Card básico com sombra e bordas arredondadas
- `.brand-card-header` - Cabeçalho do card com gradiente
- `.brand-card-body` - Corpo do card
- `.brand-card-footer` - Rodapé do card

### Estatísticas
- `.brand-stat-card` - Card de estatística com gradiente
- `.stat-value` - Valor numérico grande
- `.stat-label` - Label da estatística
- `.stat-icon` - Ícone da estatística

### Formulários
- `.brand-input` - Input padrão com foco personalizado
- `.brand-select` - Select padrão
- `.brand-textarea` - Textarea padrão

### Badges
- `.brand-badge` - Badge básico
- `.brand-badge-primary` - Badge com cor primária
- `.brand-badge-accent` - Badge com cor de accent
- `.brand-badge-secondary` - Badge com cor secundária

### Tabelas
- `.brand-table` - Tabela com estilo unificado
- `.brand-table th` - Cabeçalho da tabela
- `.brand-table td` - Célula da tabela

### Navegação
- `.brand-sidebar` - Sidebar com gradiente
- `.brand-nav` - Navegação horizontal
- `.brand-nav-item` - Item de navegação

### Alertas
- `.brand-alert` - Alerta básico
- `.brand-alert-info` - Alerta informativo
- `.brand-alert-success` - Alerta de sucesso
- `.brand-alert-warning` - Alerta de aviso
- `.brand-alert-error` - Alerta de erro

### Utilitários
- `.brand-text-primary` - Texto com cor primária
- `.brand-text-secondary` - Texto com cor secundária
- `.brand-text-accent` - Texto com cor de accent
- `.brand-bg-primary` - Background com cor primária
- `.brand-bg-secondary` - Background com cor secundária
- `.brand-bg-accent` - Background com cor de accent

## Variáveis CSS Disponíveis

```css
:root {
    --brand-primary: /* Cor primária do branding */
    --brand-secondary: /* Cor secundária */
    --brand-accent: /* Cor de destaque */
    --brand-text: /* Cor do texto */
    --brand-background: /* Cor de fundo */
    --brand-primary-light: /* Versão clara da cor primária */
    --brand-primary-dark: /* Versão escura da cor primária */
    --brand-primary-text: /* Cor do texto em backgrounds primários */
    --brand-gradient-primary: /* Gradiente primário */
    --brand-gradient-accent: /* Gradiente de destaque */
}
```

## Exemplos de Uso

### Botão Primário
```html
<button class="brand-btn brand-btn-primary">
    Salvar
</button>
```

### Card de Estatística
```html
<div class="brand-stat-card">
    <div class="flex items-center justify-between">
        <div>
            <p class="stat-label">Total de Usuários</p>
            <p class="stat-value">1,234</p>
        </div>
        <div class="stat-icon">
            <i class="fas fa-users"></i>
        </div>
    </div>
</div>
```

### Input com Label
```html
<div class="mb-4">
    <label class="block text-sm font-medium mb-2">Nome</label>
    <input type="text" class="brand-input" placeholder="Digite o nome">
</div>
```

### Badge
```html
<span class="brand-badge brand-badge-primary">Ativo</span>
```

## Migração de Classes Antigas

| Classe Antiga | Nova Classe |
|---------------|-------------|
| `bg-blue-500` | `brand-btn brand-btn-primary` |
| `bg-white shadow` | `brand-card` |
| `border border-gray-300` | `brand-input` |
| `bg-blue-100 text-blue-800` | `brand-badge brand-badge-primary` |
| `table` | `brand-table` |

## Componente Blade

Para usar o sistema, inclua o componente no layout:

```blade
<x-unified-branding />
```

Este componente:
- Carrega as variáveis CSS dinâmicas baseadas no branding do usuário
- Atualiza automaticamente as cores quando o branding é alterado
- Mantém compatibilidade com classes Tailwind existentes
