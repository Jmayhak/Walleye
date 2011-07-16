set :stages, %w(staging production development)
set :default_stage, "development"
require 'capistrano/ext/multistage'
