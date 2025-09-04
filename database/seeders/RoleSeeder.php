<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Role;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $roles = [
            [
                'name' => 'super_admin',
                'display_name' => 'Super Admin',
                'description' => 'Dono do sistema, comando total da operação. Pode criar novos administradores, configurar políticas globais, definir regras de segurança, gerenciar todos os licenciados e controlar absolutamente todos os módulos.',
                'level' => 1,
                'is_active' => true,
            ],
            [
                'name' => 'admin',
                'display_name' => 'Admin',
                'description' => 'Administra o dia a dia da plataforma. Tem acesso avançado, mas abaixo do Super Admin. Pode gerenciar funcionários, acompanhar relatórios, aprovar licenciados, definir permissões internas e cuidar do fluxo da operação.',
                'level' => 2,
                'is_active' => true,
            ],
            [
                'name' => 'funcionario',
                'display_name' => 'Funcionário',
                'description' => 'Usuário que atua em áreas específicas: suporte, comercial, financeiro, onboarding. Tem acesso limitado ao que precisa para executar sua função.',
                'level' => 3,
                'is_active' => true,
            ],
            [
                'name' => 'licenciado',
                'display_name' => 'Licenciado',
                'description' => 'Parceiro externo que utiliza a plataforma para gerenciar sua rede e clientes. Pode cadastrar estabelecimentos, acompanhar vendas, acessar relatórios de sua carteira e interagir com sua própria equipe.',
                'level' => 4,
                'is_active' => true,
            ],
        ];

        foreach ($roles as $role) {
            Role::updateOrCreate(
                ['name' => $role['name']],
                $role
            );
        }
    }
}
