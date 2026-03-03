<?php

namespace Deployer;

require 'recipe/laravel.php';

// Configuration
set('application', 'YT-DLP Dashboard');
set('ssh_type', 'native');
set('ssh_multiplexing', true);
set('default_timeout', 6000);
set('writable_mode', 'chmod');
set('writable_chmod_mode', '0775');
set('writable_use_sudo', true);

set('repository', 'https://github.com/denniskrol/yt-dlp-dashboard.git');

// Servers
host('yt-dlp-dashboard.lan')
    ->setRemoteUser('root')
    ->setIdentityFile('~/.ssh/id_rsa')
    ->setForwardAgent(true)
    ->set('branch', 'master')
    ->set('deploy_path', '/var/www/html');

// Tasks
desc('Restart artisan serve service');
task('php:restart', function () {
    run('supervisorctl restart laravel-serve');
});

task('npm:install', function () {
    run('npm ci --prefix /var/www/html/release');
    run('npm run build --prefix /var/www/html/release');
    run('rm -rf /var/www/html/release/node_modules');
});


after('deploy:failed', 'deploy:unlock');
before('deploy:symlink', 'npm:install');
after('deploy:symlink', 'php:restart');
after('deploy:symlink', 'artisan:queue:restart');
