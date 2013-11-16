# Describes the virtual machine required for the Build Security In notes 
# application, along with how to configure and provision the machine.
# 
# Author: Mark Johnman
# Date Last Modified: 17/11/13

# Vagrantfile API/syntax version. Don't touch unless you know what you're doing!
VAGRANTFILE_API_VERSION = "2"

Vagrant.configure(VAGRANTFILE_API_VERSION) do |config|
  # Every Vagrant virtual environment requires a box to build off of.
  config.vm.box = "precise32"

  # The url from where the 'config.vm.box' box will be fetched if it
  # doesn't already exist on the user's system.
  config.vm.box_url = "http://files.vagrantup.com/precise32.box"

  # Create a forwarded port mapping which allows access to a specific port
  # within the machine from a port on the host machine. In the scenario
  # below, accessing "localhost:8080" will access port 80 on the guest machine.
  config.vm.network "forwarded_port", guest: 80, host: 8080

  # Provision the guest VM using the given Ansible playbook.
  config.vm.provision "ansible" do |ansible|
    ansible.playbook = "provisioning/site.yml"
  end 	
end
