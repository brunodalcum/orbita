<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Permission;
use App\Models\Role;

class PermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $permissions = [
            // Dashboard
            ['name' => 'dashboard.view', 'display_name' => 'Visualizar Dashboard', 'module' => 'dashboard', 'action' => 'view'],
            
            // Licenciados
            ['name' => 'licenciados.view', 'display_name' => 'Visualizar Licenciados', 'module' => 'licenciados', 'action' => 'view'],
            ['name' => 'licenciados.create', 'display_name' => 'Criar Licenciados', 'module' => 'licenciados', 'action' => 'create'],
            ['name' => 'licenciados.update', 'display_name' => 'Editar Licenciados', 'module' => 'licenciados', 'action' => 'update'],
            ['name' => 'licenciados.delete', 'display_name' => 'Excluir Licenciados', 'module' => 'licenciados', 'action' => 'delete'],
            ['name' => 'licenciados.manage', 'display_name' => 'Gerenciar Licenciados', 'module' => 'licenciados', 'action' => 'manage'],
            ['name' => 'licenciados.approve', 'display_name' => 'Aprovar Licenciados', 'module' => 'licenciados', 'action' => 'approve'],
            
            // Contratos
            ['name' => 'contratos.view', 'display_name' => 'Visualizar Contratos', 'module' => 'contratos', 'action' => 'view'],
            ['name' => 'contratos.create', 'display_name' => 'Criar Contratos', 'module' => 'contratos', 'action' => 'create'],
            ['name' => 'contratos.update', 'display_name' => 'Editar Contratos', 'module' => 'contratos', 'action' => 'update'],
            ['name' => 'contratos.delete', 'display_name' => 'Excluir Contratos', 'module' => 'contratos', 'action' => 'delete'],
            ['name' => 'contratos.manage', 'display_name' => 'Gerenciar Contratos', 'module' => 'contratos', 'action' => 'manage'],
            ['name' => 'contratos.approve', 'display_name' => 'Aprovar Documentos', 'module' => 'contratos', 'action' => 'approve'],
            ['name' => 'contratos.send', 'display_name' => 'Enviar Contratos', 'module' => 'contratos', 'action' => 'send'],
            
            // Operações
            ['name' => 'operacoes.view', 'display_name' => 'Visualizar Operações', 'module' => 'operacoes', 'action' => 'view'],
            ['name' => 'operacoes.create', 'display_name' => 'Criar Operações', 'module' => 'operacoes', 'action' => 'create'],
            ['name' => 'operacoes.update', 'display_name' => 'Editar Operações', 'module' => 'operacoes', 'action' => 'update'],
            ['name' => 'operacoes.delete', 'display_name' => 'Excluir Operações', 'module' => 'operacoes', 'action' => 'delete'],
            ['name' => 'operacoes.manage', 'display_name' => 'Gerenciar Operações', 'module' => 'operacoes', 'action' => 'manage'],
            
            // Planos
            ['name' => 'planos.view', 'display_name' => 'Visualizar Planos', 'module' => 'planos', 'action' => 'view'],
            ['name' => 'planos.create', 'display_name' => 'Criar Planos', 'module' => 'planos', 'action' => 'create'],
            ['name' => 'planos.update', 'display_name' => 'Editar Planos', 'module' => 'planos', 'action' => 'update'],
            ['name' => 'planos.delete', 'display_name' => 'Excluir Planos', 'module' => 'planos', 'action' => 'delete'],
            ['name' => 'planos.manage', 'display_name' => 'Gerenciar Planos', 'module' => 'planos', 'action' => 'manage'],
            
            // Adquirentes
            ['name' => 'adquirentes.view', 'display_name' => 'Visualizar Adquirentes', 'module' => 'adquirentes', 'action' => 'view'],
            ['name' => 'adquirentes.create', 'display_name' => 'Criar Adquirentes', 'module' => 'adquirentes', 'action' => 'create'],
            ['name' => 'adquirentes.update', 'display_name' => 'Editar Adquirentes', 'module' => 'adquirentes', 'action' => 'update'],
            ['name' => 'adquirentes.delete', 'display_name' => 'Excluir Adquirentes', 'module' => 'adquirentes', 'action' => 'delete'],
            ['name' => 'adquirentes.manage', 'display_name' => 'Gerenciar Adquirentes', 'module' => 'adquirentes', 'action' => 'manage'],
            
            // Agenda
            ['name' => 'agenda.view', 'display_name' => 'Visualizar Agenda', 'module' => 'agenda', 'action' => 'view'],
            ['name' => 'agenda.create', 'display_name' => 'Criar Compromissos', 'module' => 'agenda', 'action' => 'create'],
            ['name' => 'agenda.update', 'display_name' => 'Editar Compromissos', 'module' => 'agenda', 'action' => 'update'],
            ['name' => 'agenda.delete', 'display_name' => 'Excluir Compromissos', 'module' => 'agenda', 'action' => 'delete'],
            ['name' => 'agenda.manage', 'display_name' => 'Gerenciar Agenda', 'module' => 'agenda', 'action' => 'manage'],
            
            // Leads
            ['name' => 'leads.view', 'display_name' => 'Visualizar Leads', 'module' => 'leads', 'action' => 'view'],
            ['name' => 'leads.create', 'display_name' => 'Criar Leads', 'module' => 'leads', 'action' => 'create'],
            ['name' => 'leads.update', 'display_name' => 'Editar Leads', 'module' => 'leads', 'action' => 'update'],
            ['name' => 'leads.delete', 'display_name' => 'Excluir Leads', 'module' => 'leads', 'action' => 'delete'],
            ['name' => 'leads.manage', 'display_name' => 'Gerenciar Leads', 'module' => 'leads', 'action' => 'manage'],
            
            // Marketing
            ['name' => 'marketing.view', 'display_name' => 'Visualizar Marketing', 'module' => 'marketing', 'action' => 'view'],
            ['name' => 'marketing.create', 'display_name' => 'Criar Campanhas', 'module' => 'marketing', 'action' => 'create'],
            ['name' => 'marketing.update', 'display_name' => 'Editar Campanhas', 'module' => 'marketing', 'action' => 'update'],
            ['name' => 'marketing.delete', 'display_name' => 'Excluir Campanhas', 'module' => 'marketing', 'action' => 'delete'],
            ['name' => 'marketing.manage', 'display_name' => 'Gerenciar Marketing', 'module' => 'marketing', 'action' => 'manage'],
            ['name' => 'marketing.send', 'display_name' => 'Enviar Campanhas', 'module' => 'marketing', 'action' => 'send'],
            
            // Configurações
            ['name' => 'configuracoes.view', 'display_name' => 'Visualizar Configurações', 'module' => 'configuracoes', 'action' => 'view'],
            ['name' => 'configuracoes.update', 'display_name' => 'Editar Configurações', 'module' => 'configuracoes', 'action' => 'update'],
            ['name' => 'configuracoes.manage', 'display_name' => 'Gerenciar Configurações', 'module' => 'configuracoes', 'action' => 'manage'],
            
            // Usuários e Roles
            ['name' => 'usuarios.view', 'display_name' => 'Visualizar Usuários', 'module' => 'usuarios', 'action' => 'view'],
            ['name' => 'usuarios.create', 'display_name' => 'Criar Usuários', 'module' => 'usuarios', 'action' => 'create'],
            ['name' => 'usuarios.update', 'display_name' => 'Editar Usuários', 'module' => 'usuarios', 'action' => 'update'],
            ['name' => 'usuarios.delete', 'display_name' => 'Excluir Usuários', 'module' => 'usuarios', 'action' => 'delete'],
            ['name' => 'usuarios.manage', 'display_name' => 'Gerenciar Usuários', 'module' => 'usuarios', 'action' => 'manage'],
            
            // Permissões do Sistema
            ['name' => 'permissoes.view', 'display_name' => 'Visualizar Permissões', 'module' => 'permissoes', 'action' => 'view'],
            ['name' => 'permissoes.create', 'display_name' => 'Criar Permissões', 'module' => 'permissoes', 'action' => 'create'],
            ['name' => 'permissoes.update', 'display_name' => 'Editar Permissões', 'module' => 'permissoes', 'action' => 'update'],
            ['name' => 'permissoes.delete', 'display_name' => 'Excluir Permissões', 'module' => 'permissoes', 'action' => 'delete'],
            ['name' => 'permissoes.manage', 'display_name' => 'Gerenciar Permissões', 'module' => 'permissoes', 'action' => 'manage'],
            
            // Estabelecimentos (ECs)
            ['name' => 'estabelecimentos.view', 'display_name' => 'Visualizar Estabelecimentos', 'module' => 'estabelecimentos', 'action' => 'view'],
            ['name' => 'estabelecimentos.create', 'display_name' => 'Criar Estabelecimentos', 'module' => 'estabelecimentos', 'action' => 'create'],
            ['name' => 'estabelecimentos.update', 'display_name' => 'Editar Estabelecimentos', 'module' => 'estabelecimentos', 'action' => 'update'],
            ['name' => 'estabelecimentos.delete', 'display_name' => 'Excluir Estabelecimentos', 'module' => 'estabelecimentos', 'action' => 'delete'],
            ['name' => 'estabelecimentos.manage', 'display_name' => 'Gerenciar Estabelecimentos', 'module' => 'estabelecimentos', 'action' => 'manage'],
            
            // Relatórios
            ['name' => 'relatorios.view', 'display_name' => 'Visualizar Relatórios', 'module' => 'relatorios', 'action' => 'view'],
            ['name' => 'relatorios.export', 'display_name' => 'Exportar Relatórios', 'module' => 'relatorios', 'action' => 'export'],
        ];

        foreach ($permissions as $permission) {
            Permission::updateOrCreate(
                ['name' => $permission['name']],
                array_merge($permission, ['is_active' => true])
            );
        }

        // Atribuir permissões aos roles
        $this->assignPermissionsToRoles();
    }

    private function assignPermissionsToRoles()
    {
        // Super Admin - Acesso total
        $superAdmin = Role::where('name', 'super_admin')->first();
        if ($superAdmin) {
            $superAdmin->permissions()->sync(Permission::pluck('id'));
        }

        // Admin - Acesso avançado (exceto gerenciamento de usuários e configurações críticas)
        $admin = Role::where('name', 'admin')->first();
        if ($admin) {
            $adminPermissions = Permission::whereNotIn('name', [
                'usuarios.create', 'usuarios.update', 'usuarios.delete', 'usuarios.manage',
                'permissoes.create', 'permissoes.update', 'permissoes.delete', 'permissoes.manage',
                'configuracoes.manage'
            ])->pluck('id');
            $admin->permissions()->sync($adminPermissions);
        }

        // Funcionário - Acesso limitado
        $funcionario = Role::where('name', 'funcionario')->first();
        if ($funcionario) {
            $funcionarioPermissions = Permission::whereIn('name', [
                'dashboard.view',
                'licenciados.view', 'licenciados.create', 'licenciados.update',
                'operacoes.view',
                'planos.view',
                'adquirentes.view',
                'agenda.view', 'agenda.create', 'agenda.update',
                'leads.view', 'leads.create', 'leads.update',
                'estabelecimentos.view', 'estabelecimentos.create', 'estabelecimentos.update',
                'marketing.view', 'marketing.create', 'marketing.update',
                'relatorios.view'
            ])->pluck('id');
            $funcionario->permissions()->sync($funcionarioPermissions);
        }

        // Licenciado - Acesso restrito ao seu escopo
        $licenciado = Role::where('name', 'licenciado')->first();
        if ($licenciado) {
            $licenciadoPermissions = Permission::whereIn('name', [
                'dashboard.view',
                'licenciados.view', // Apenas seus próprios dados
                'operacoes.view',
                'planos.view',
                'agenda.view', 'agenda.create', 'agenda.update',
                'leads.view', 'leads.create', 'leads.update',
                'relatorios.view'
            ])->pluck('id');
            $licenciado->permissions()->sync($licenciadoPermissions);
        }
    }
}
