class php5 {

    package { "php5-cli":
        ensure => latest,
        require => Exec["apt-update"],
    }

    package { "php5-dev":
        ensure => latest,
        require => Exec["apt-update"],
    }

    package { "php5-xdebug":
        ensure => latest,
        require => Package["libapache2-mod-php5"],
        notify => Service["apache2"],
    }

    package { "php5-intl":
        ensure => latest,
        require => Package["libapache2-mod-php5"],
    }

    package { "php5-sqlite":
        ensure => latest,
        require => Package["libapache2-mod-php5"],
    }

    file { "php-timezone.ini":
        path => "/etc/php5/cli/conf.d/30-timezone.ini",
        ensure => file,
        source => '/vagrant/vagrant/puppet/resources/timezone.ini',
        require => Package["php5-cli"],
        notify => Service["apache2"],
    }

    file { "xdebug-conf.ini":
        path => "/etc/php5/cli/conf.d/40-xdebug.ini",
        ensure => file,
        source => '/vagrant/vagrant/puppet/resources/xdebug-conf.ini',
        require => Package["php5-cli"],
        notify => Service["apache2"],
    }
}

class php54dotdeb {
    file { "dotdeb.list":
        path => "/etc/apt/sources.list.d/dotdeb.list",
        ensure => file,
        owner => "root",
        group => "root",
        content => "deb http://ftp.ch.debian.org/debian squeeze main contrib non-free\ndeb http://packages.dotdeb.org squeeze all\ndeb-src http://packages.dotdeb.org squeeze all\ndeb http://packages.dotdeb.org squeeze-php54 all\ndeb-src http://packages.dotdeb.org squeeze-php54 all",
        notify => Exec["dotDebKeys"],
    }

    exec { "dotDebKeys":
        command => "wget -q -O - http://www.dotdeb.org/dotdeb.gpg | sudo apt-key add -",
        path => ["/bin", "/usr/bin"],
        notify => Exec["apt-update"],
        unless => "apt-key list | grep dotdeb",
    }

    package { "phpapi-20090626":
        ensure => purged,
    }

    package { "php-apc":
        ensure => purged,
    }


    package { "php-pear":
        ensure => present,
        require => Exec["apt-update"],
    }
}
