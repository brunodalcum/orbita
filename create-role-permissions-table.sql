-- Script SQL para criar manualmente a tabela role_permissions
-- Execute este SQL diretamente no MySQL se os scripts PHP não funcionarem

-- 1. Criar a tabela role_permissions
CREATE TABLE IF NOT EXISTS `role_permissions` (
    `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
    `role_id` bigint(20) unsigned NOT NULL,
    `permission_id` bigint(20) unsigned NOT NULL,
    `created_at` timestamp NULL DEFAULT NULL,
    `updated_at` timestamp NULL DEFAULT NULL,
    PRIMARY KEY (`id`),
    UNIQUE KEY `role_permissions_role_id_permission_id_unique` (`role_id`,`permission_id`),
    KEY `role_permissions_role_id_foreign` (`role_id`),
    KEY `role_permissions_permission_id_foreign` (`permission_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 2. Inserir todas as permissões para o Super Admin (role_id = 1)
INSERT IGNORE INTO `role_permissions` (`role_id`, `permission_id`, `created_at`, `updated_at`)
SELECT 1, p.id, NOW(), NOW()
FROM `permissions` p
WHERE p.is_active = 1;

-- 3. Verificar se foi criado corretamente
SELECT 
    'role_permissions' as tabela,
    COUNT(*) as total_registros
FROM `role_permissions`;

-- 4. Testar a query que estava falhando
SELECT EXISTS(
    SELECT * FROM `permissions` 
    INNER JOIN `role_permissions` ON `permissions`.`id` = `role_permissions`.`permission_id` 
    WHERE `role_permissions`.`role_id` = 1 AND `permissions`.`name` = 'dashboard.view'
) as `dashboard_permission_exists`;
