# -*- mode: ruby -*-
# vi: set ft=ruby :

Vagrant.configure("2") do |config|
    config.vm.box = "squeeze-64-4-2-12"
    #config.vm.boot_mode = :gui
    config.vm.box_url = "https://dl.dropboxusercontent.com/u/13054557/vagrant_boxes/debian-squeeze.box"

    config.vm.network :private_network, ip: "10.0.5.29"

    # Enable NFS
    # config.vm.synced_folder ".", "/vagrant", id: "vagrant-root", :nfs => true

    config.vm.provider :virtualbox do |vb|
    	vb.customize [
            "modifyvm", :id,
            '--chipset', 'ich9',
            '--natdnsproxy1', 'on',
            '--natdnshostresolver1', 'on'
            ]
    end
    
    config.vm.provision :puppet do |puppet|
        puppet.manifests_path = "vagrant/puppet/manifests"
        puppet.manifest_file  = "main.pp"
        puppet.options        = [
                                  '--verbose',
                                  #'--debug',
                                ]
    end
end
