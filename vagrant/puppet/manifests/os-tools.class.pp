class os-tools {
    package { "git":
        ensure => latest,
        require => Exec['apt-update'],
    }

    package { "vim":
        ensure => latest,
        require => Exec['apt-update'],
    }

    package { "curl":
        ensure => present,
        require => Exec['apt-update'],
    }

    package { "nfs-common":
        ensure => present,
        require => Exec['apt-update'],
    }

    package { "make":
        ensure => latest,
        require => Exec['apt-update'],
    }

    package { "htop":
        ensure => latest,
        require => Exec['apt-update'],
    }

    package { "g++":
        ensure => present,
        require => Exec['apt-update'],
    }
}
