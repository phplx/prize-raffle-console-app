class apache {
    package { "apache2-mpm-prefork":
        ensure => present,
        require => Exec["apt-update"],
    }

    package { "libapache2-mod-php5":
        ensure => latest,
        require => Package["apache2-mpm-prefork"],
        notify => Service["apache2"],
    }

    service { "apache2":
        ensure => running,
        require => Package["apache2-mpm-prefork"],
        subscribe => File["mod_rewrite", "mod_actions"],
    }

    file { "mod_rewrite":
        path => "/etc/apache2/mods-enabled/rewrite.load",
        ensure => "link",
        target => "/etc/apache2/mods-available/rewrite.load",
        require => Package["apache2-mpm-prefork"],
    }

    file { "mod_actions":
        path => "/etc/apache2/mods-enabled/actions.load",
        ensure => "link",
        target => "/etc/apache2/mods-available/actions.load",
        require => Package["apache2-mpm-prefork"],
    }

    file { "mod_actions_conf":
        path => "/etc/apache2/mods-enabled/actions.conf",
        ensure => "link",
        target => "/etc/apache2/mods-available/actions.conf",
        require => Package["apache2-mpm-prefork"],
    }
}
