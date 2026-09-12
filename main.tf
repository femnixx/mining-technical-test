terraform { 
    required_providers { 
        tailscale = { 
            source = "tailscale/tailscale"
            version: "~> 0.16"
        }
    }
}

provider "tailscale" { 
    api_key = var.tailscale_api_key
    tailnet = var.tailnet_name
}

resource "tailscale_tailnet_key" "ci_auth_key" { 
    reusable        = true
    ephemeral       = true
    preauthorized   = true
    tags            = ["tag:ci"]
}