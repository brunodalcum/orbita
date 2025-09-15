<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\OrbitaOperacao;
use App\Models\WhiteLabel;
use App\Models\NodeBranding;
use App\Models\Role;
use Illuminate\Support\Facades\Hash;

class HierarchySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $this->command->info('🚀 Criando hierarquia White Label da Órbita...');

        // 1. Criar Super Admin
        $superAdmin = $this->createSuperAdmin();
        $this->command->info('✅ Super Admin criado: ' . $superAdmin->email);

        // 2. Criar Operação A (com White Label)
        $operacaoA = $this->createOperacao('Operação Alpha', 'alpha.orbita.com');
        $this->command->info('✅ Operação A criada: ' . $operacaoA->name);

        // 3. Criar White Label da Operação A
        $whiteLabelA = $this->createWhiteLabel($operacaoA, 'WL Alpha Premium', 'wl-alpha.orbita.com');
        $this->command->info('✅ White Label A criado: ' . $whiteLabelA->name);

        // 4. Criar Operação B (sem White Label - direto)
        $operacaoB = $this->createOperacao('Operação Beta', 'beta.orbita.com');
        $this->command->info('✅ Operação B criada: ' . $operacaoB->name);

        // 5. Criar usuários da hierarquia - Árvore A (com WL)
        $this->createTreeA($operacaoA, $whiteLabelA);
        
        // 6. Criar usuários da hierarquia - Árvore B (sem WL)
        $this->createTreeB($operacaoB);

        // 7. Criar branding para demonstrar herança
        $this->createBrandingExamples($operacaoA, $whiteLabelA, $operacaoB);

        $this->command->info('🎉 Hierarquia White Label criada com sucesso!');
        $this->command->info('');
        $this->command->info('📊 Estrutura criada:');
        $this->command->info('├── Super Admin');
        $this->command->info('├── Operação A → WL Alpha → L1 → L2 → L3');
        $this->command->info('└── Operação B → L1 → L2 → L3');
    }

    private function createSuperAdmin(): User
    {
        $superAdminRole = Role::where('name', 'super_admin')->first();
        
        return User::create([
            'name' => 'Super Admin Órbita',
            'email' => 'superadmin@orbita.com',
            'password' => Hash::make('password'),
            'role_id' => $superAdminRole->id,
            'node_type' => 'super_admin',
            'hierarchy_level' => 1,
            'hierarchy_path' => '1',
            'is_active' => true,
        ]);
    }

    private function createOperacao(string $name, string $subdomain): OrbitaOperacao
    {
        return OrbitaOperacao::create([
            'name' => strtolower(str_replace(' ', '_', $name)),
            'display_name' => $name,
            'description' => 'Operação de exemplo para demonstrar a hierarquia White Label',
            'subdomain' => $subdomain,
            'branding' => [
                'primary_color' => '#3B82F6',
                'secondary_color' => '#1E40AF',
                'accent_color' => '#F59E0B'
            ],
            'modules' => [
                'pix' => ['enabled' => true, 'settings' => []],
                'bolepix' => ['enabled' => true, 'settings' => []],
                'gateway' => ['enabled' => false, 'settings' => []],
                'gerenpix' => ['enabled' => true, 'settings' => []]
            ],
            'settings' => [
                'max_white_labels' => 10,
                'max_licenciados_per_wl' => 100
            ],
            'is_active' => true
        ]);
    }

    private function createWhiteLabel(OrbitaOperacao $operacao, string $name, string $subdomain): WhiteLabel
    {
        return WhiteLabel::create([
            'operacao_id' => $operacao->id,
            'name' => strtolower(str_replace(' ', '_', $name)),
            'display_name' => $name,
            'description' => 'White Label de exemplo para demonstrar a hierarquia',
            'subdomain' => $subdomain,
            'branding' => [
                'primary_color' => '#10B981', // Verde para diferenciar
                'logo_url' => '/images/wl-logo.png'
            ],
            'modules' => [
                'gateway' => ['enabled' => true, 'settings' => []] // Habilita módulo que estava desabilitado na operação
            ],
            'settings' => [
                'max_licenciados' => 50,
                'commission_rate' => 2.5
            ],
            'is_active' => true
        ]);
    }

    private function createTreeA(OrbitaOperacao $operacao, WhiteLabel $whiteLabel): void
    {
        $adminRole = Role::where('name', 'admin')->first();
        $licenciadoRole = Role::where('name', 'licenciado')->first();

        // Operação A → WL Alpha → L1 → L2 → L3
        $l1 = User::create([
            'name' => 'Licenciado L1 Alpha',
            'email' => 'l1.alpha@exemplo.com',
            'password' => Hash::make('password'),
            'role_id' => $licenciadoRole->id,
            'node_type' => 'licenciado_l1',
            'hierarchy_level' => 3, // Super Admin(1) → Operação(2) → WL(2.5) → L1(3)
            'operacao_id' => $operacao->id,
            'white_label_id' => $whiteLabel->id,
            'modules' => [
                'pix' => ['enabled' => true, 'settings' => ['daily_limit' => 10000]]
            ],
            'is_active' => true,
        ]);
        $l1->update(['hierarchy_path' => "1/{$operacao->id}/{$whiteLabel->id}/{$l1->id}"]);

        $l2 = User::create([
            'name' => 'Licenciado L2 Alpha',
            'email' => 'l2.alpha@exemplo.com',
            'password' => Hash::make('password'),
            'role_id' => $licenciadoRole->id,
            'parent_id' => $l1->id,
            'node_type' => 'licenciado_l2',
            'hierarchy_level' => 4,
            'operacao_id' => $operacao->id,
            'white_label_id' => $whiteLabel->id,
            'is_active' => true,
        ]);
        $l2->update(['hierarchy_path' => "{$l1->hierarchy_path}/{$l2->id}"]);

        $l3 = User::create([
            'name' => 'Licenciado L3 Alpha',
            'email' => 'l3.alpha@exemplo.com',
            'password' => Hash::make('password'),
            'role_id' => $licenciadoRole->id,
            'parent_id' => $l2->id,
            'node_type' => 'licenciado_l3',
            'hierarchy_level' => 5,
            'operacao_id' => $operacao->id,
            'white_label_id' => $whiteLabel->id,
            'is_active' => true,
        ]);
        $l3->update(['hierarchy_path' => "{$l2->hierarchy_path}/{$l3->id}"]);

        $this->command->info("   ├── L1: {$l1->email}");
        $this->command->info("   ├── L2: {$l2->email}");
        $this->command->info("   └── L3: {$l3->email}");
    }

    private function createTreeB(OrbitaOperacao $operacao): void
    {
        $licenciadoRole = Role::where('name', 'licenciado')->first();

        // Operação B → L1 → L2 → L3 (sem White Label)
        $l1 = User::create([
            'name' => 'Licenciado L1 Beta',
            'email' => 'l1.beta@exemplo.com',
            'password' => Hash::make('password'),
            'role_id' => $licenciadoRole->id,
            'node_type' => 'licenciado_l1',
            'hierarchy_level' => 3, // Super Admin(1) → Operação(2) → L1(3)
            'operacao_id' => $operacao->id,
            'white_label_id' => null, // Sem White Label
            'is_active' => true,
        ]);
        $l1->update(['hierarchy_path' => "1/{$operacao->id}/{$l1->id}"]);

        $l2 = User::create([
            'name' => 'Licenciado L2 Beta',
            'email' => 'l2.beta@exemplo.com',
            'password' => Hash::make('password'),
            'role_id' => $licenciadoRole->id,
            'parent_id' => $l1->id,
            'node_type' => 'licenciado_l2',
            'hierarchy_level' => 4,
            'operacao_id' => $operacao->id,
            'white_label_id' => null,
            'is_active' => true,
        ]);
        $l2->update(['hierarchy_path' => "{$l1->hierarchy_path}/{$l2->id}"]);

        $l3 = User::create([
            'name' => 'Licenciado L3 Beta',
            'email' => 'l3.beta@exemplo.com',
            'password' => Hash::make('password'),
            'role_id' => $licenciadoRole->id,
            'parent_id' => $l2->id,
            'node_type' => 'licenciado_l3',
            'hierarchy_level' => 5,
            'operacao_id' => $operacao->id,
            'white_label_id' => null,
            'is_active' => true,
        ]);
        $l3->update(['hierarchy_path' => "{$l2->hierarchy_path}/{$l3->id}"]);

        $this->command->info("   ├── L1: {$l1->email}");
        $this->command->info("   ├── L2: {$l2->email}");
        $this->command->info("   └── L3: {$l3->email}");
    }

    private function createBrandingExamples(OrbitaOperacao $operacaoA, WhiteLabel $whiteLabelA, OrbitaOperacao $operacaoB): void
    {
        // Branding da Operação A
        NodeBranding::create([
            'node_type' => 'operacao',
            'node_id' => $operacaoA->id,
            'logo_url' => '/images/operacao-a-logo.png',
            'primary_color' => '#3B82F6',
            'secondary_color' => '#1E40AF',
            'accent_color' => '#F59E0B',
            'font_family' => 'Inter, sans-serif',
            'inherit_from_parent' => false
        ]);

        // Branding do White Label A (herda da Operação A mas customiza algumas cores)
        NodeBranding::create([
            'node_type' => 'white_label',
            'node_id' => $whiteLabelA->id,
            'logo_url' => '/images/wl-alpha-logo.png',
            'primary_color' => '#10B981', // Verde customizado
            'inherit_from_parent' => true // Herda outras configurações
        ]);

        // Branding da Operação B
        NodeBranding::create([
            'node_type' => 'operacao',
            'node_id' => $operacaoB->id,
            'logo_url' => '/images/operacao-b-logo.png',
            'primary_color' => '#EF4444', // Vermelho
            'secondary_color' => '#DC2626',
            'accent_color' => '#F97316',
            'font_family' => 'Roboto, sans-serif',
            'inherit_from_parent' => false
        ]);

        $this->command->info('✅ Branding de exemplo criado para demonstrar herança');
    }
}