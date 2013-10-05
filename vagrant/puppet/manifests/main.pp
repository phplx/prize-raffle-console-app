import 'apt.class.pp'
import 'apache.class.pp'
import 'php.class.pp'
import 'os-tools.class.pp'

class groups {
  group { "puppet":
    ensure => present,
  }
}

include apt
include php5
include php54dotdeb
include os-tools
include apache
include groups
