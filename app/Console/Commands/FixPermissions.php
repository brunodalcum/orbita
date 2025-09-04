<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;

class FixPermissions extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'fix:permissions {--force : Force fix even if permissions seem correct}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Fix storage and cache permissions for production';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('🔧 Corrigindo permissões em produção...');

        // 1. Limpar caches
        $this->info('🧹 Limpando caches...');
        $this->call('cache:clear');
        $this->call('config:clear');
        $this->call('route:clear');
        $this->call('view:clear');

        // 2. Remover arquivos de cache problemáticos
        $this->info('🗑️ Removendo arquivos de cache...');
        $cachePaths = [
            storage_path('framework/cache'),
            storage_path('framework/sessions'),
            storage_path('framework/views'),
            storage_path('logs'),
            base_path('bootstrap/cache')
        ];

        foreach ($cachePaths as $path) {
            if (File::exists($path)) {
                File::deleteDirectory($path);
                File::makeDirectory($path, 0755, true);
                $this->line("   Limpo: {$path}");
            }
        }

        // 3. Definir permissões
        $this->info('🔐 Definindo permissões...');
        $this->setPermissions();

        // 4. Desabilitar cache de views
        $this->info('⚙️ Desabilitando cache de views...');
        $this->disableViewCache();

        // 5. Regenerar caches
        $this->info('🔄 Regenerando caches...');
        $this->call('config:cache');
        $this->call('route:cache');

        // 6. Testar permissões
        $this->info('🧪 Testando permissões...');
        $this->testPermissions();

        $this->info('🎉 Correção de permissões concluída!');
    }

    private function setPermissions()
    {
        $paths = [
            storage_path(),
            base_path('bootstrap/cache')
        ];

        foreach ($paths as $path) {
            if (File::exists($path)) {
                // Tentar diferentes métodos de permissão
                $this->setDirectoryPermissions($path);
            }
        }
    }

    private function setDirectoryPermissions($path)
    {
        // Método 1: chmod
        if (function_exists('chmod')) {
            $this->recursiveChmod($path, 0755);
        }

        // Método 2: chown (se disponível)
        if (function_exists('chown')) {
            $webUser = $this->getWebUser();
            if ($webUser) {
                $this->recursiveChown($path, $webUser);
            }
        }
    }

    private function recursiveChmod($path, $permissions)
    {
        if (is_dir($path)) {
            chmod($path, $permissions);
            $items = scandir($path);
            foreach ($items as $item) {
                if ($item != '.' && $item != '..') {
                    $this->recursiveChmod($path . '/' . $item, $permissions);
                }
            }
        } else {
            chmod($path, $permissions);
        }
    }

    private function recursiveChown($path, $owner)
    {
        if (is_dir($path)) {
            chown($path, $owner);
            $items = scandir($path);
            foreach ($items as $item) {
                if ($item != '.' && $item != '..') {
                    $this->recursiveChown($path . '/' . $item, $owner);
                }
            }
        } else {
            chown($path, $owner);
        }
    }

    private function getWebUser()
    {
        $possibleUsers = ['www-data', 'apache', 'nginx', 'httpd'];
        
        foreach ($possibleUsers as $user) {
            if (function_exists('posix_getpwnam') && posix_getpwnam($user)) {
                return $user;
            }
        }

        return null;
    }

    private function disableViewCache()
    {
        $envFile = base_path('.env');
        
        if (File::exists($envFile)) {
            $content = File::get($envFile);
            
            // Remover configurações existentes
            $content = preg_replace('/^VIEW_CACHE_ENABLED.*$/m', '', $content);
            $content = preg_replace('/^CACHE_DRIVER.*$/m', '', $content);
            $content = preg_replace('/^SESSION_DRIVER.*$/m', '', $content);
            
            // Adicionar novas configurações
            $content .= "\n# Configurações para resolver problemas de permissão\n";
            $content .= "VIEW_CACHE_ENABLED=false\n";
            $content .= "CACHE_DRIVER=array\n";
            $content .= "SESSION_DRIVER=array\n";
            
            File::put($envFile, $content);
            $this->line('   Arquivo .env atualizado');
        }
    }

    private function testPermissions()
    {
        $testFile = storage_path('framework/views/test_permissions.tmp');
        
        try {
            File::put($testFile, 'test');
            File::delete($testFile);
            $this->line('   ✅ Permissões de escrita funcionando');
        } catch (\Exception $e) {
            $this->error('   ❌ Problemas de permissão: ' . $e->getMessage());
        }
    }
}
