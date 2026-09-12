terraform { 
    required_providers { 
        docker = { 
            source = "kreuzwerker/docker"
            version = "~> 3.0.1"
        }
    }
}

resource "null_resource" "docker_compose" { 
    provisioner "local-exec" { 
        command = "docker compose -f ${path.module}/docker-compose.yml up -d --build"
    }

    provisioner "local-exec" { 
        when = "destroy"
        command = "docker compose -f ${path.module}/docker-compose.yml down"
    }
}


