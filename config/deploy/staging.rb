# Application Naming
#
set :application, ""
set :scm, "git"
set :branch, "master"
set :user, ""
set :repository,  ""

# Application Deployment Location
#

set :deploy_to, ""
set :document_root, ""
# set :deploy_via, :remote_cache
set :copy_exclude, [".git", ".DS_Store", ".idea"]
set :use_sudo, false
set :ssh_options, {:forward_agent => true}
set :keep_releases, 10

role :app, ""
role :web, ""
role :db,  "", :primary => true

namespace :deploy do

        before "deploy:restart" do
            transaction do
                run "cd #{deploy_to}/#{current_dir} && phpunit --bootstrap ./tests/bootstrap.php ./tests"
            end
        end

		task :update do
			transaction do
				update_code
				symlink
				migrate
			end
		end

		task :finalize_update do
			transaction do
				run "chmod -R g+w #{releases_path}/#{release_name}"
			end
		end

		task :symlink do
			transaction do
				run "ln -nfs #{current_release} #{deploy_to}/#{current_dir}"
			end
		end

		task :migrate do
			transaction do
				run "cd #{deploy_to}/#{current_dir}/db && php main.php db:migrate ENV=staging"
			end
		end

		task :tweet do
		    transaction do
		        run "cd #{deploy_to}/#{current_dir}/hooks && php post-deploy.php http:// #{release_name}"
		    end
		end

		task :restart do
			transaction do
			    tweet
			end
		end

end
