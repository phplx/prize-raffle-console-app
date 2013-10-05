class apt {
    exec { "apt-update":
        command => "sudo apt-get update",
        path => ["/bin", "/usr/bin"],
    }
}
