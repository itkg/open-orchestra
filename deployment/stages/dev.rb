server 'open_orchestra_backoffice_dev', roles: %w{web app db env}
set :repo_url, 'git@github.com:open-orchestra/open-orchestra.git'
set :update_dir, 'update-vendor-back-inte'
set :git_project_dir, 'open-orchestra'
set :deploy_to, '/var/www/openorchestra'
set :branch, 'master'
set :application, 'OpenOrchestra'
