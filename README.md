# 🔐 Secure Automated GitOps CI/CD Pipeline

> **Production-grade, containerized continuous deployment (CD) pipeline** leveraging a **Zero-Trust network architecture**. Automates application delivery from cloud-based CI/CD runners directly to isolated, private infrastructure without exposing management ports to the public internet.

![Status](https://img.shields.io/badge/status-production-brightgreen)
![License](https://img.shields.io/badge/license-MIT-blue)
![Architecture](https://img.shields.io/badge/architecture-zero--trust-red)

---

## 📋 Table of Contents

- [Architecture Overview](#-architecture-overview)
- [Technical Stack](#-technical-stack)
- [Pipeline Workflow](#-pipeline-workflow)
- [Quick Start](#-quick-start)
- [Configuration](#-configuration)
- [Secrets Management](#-secrets-management)
- [Engineering Lessons & Troubleshooting](#-engineering-lessons--troubleshooting)
- [Security Considerations](#-security-considerations)
- [Contributing](#-contributing)

---

## 🏗️ Architecture Overview

```
[ GitHub Actions Runner ]
│
▼ (Code Push to main)
┌────────────────────────────────────────────────────────┐
│             Tailscale Private Overlay Network          │
│  • Ephemeral client authentication                     │
│  • Fully closed inbound firewall (Port 22 disabled)    │
└─────────────────────────┬──────────────────────────────┘
                          │
                          ▼ (Secure SSH via Ed25519 Identity Key)
               [ Private Debian Target ]
                          │
                          ▼ (Automation Deployment Hook)
            [ Docker Compose Orchestration ]
              ┌───────────┴───────────┐
              ▼                       ▼
    [ App Container ]         [ Grafana Engine ]
```

### Key Principles

✅ **Zero-Trust Security** — No public SSH exposure; all connections tunnel through encrypted overlay network  
✅ **Ephemeral Authentication** — Automated node provisioning and cleanup; no persistent credentials stored  
✅ **Atomic Deployments** — GitOps-driven state synchronization ensures immutable, reproducible rollouts  
✅ **Self-Hosted Infrastructure** — Private Debian server maintains complete application lifecycle control  

---

## 🛠️ Technical Stack

| Category | Technology | Purpose |
|----------|-----------|---------|
| **CI/CD Engine** | [GitHub Actions](https://github.com/features/actions) | Event-driven pipeline execution & orchestration |
| **Zero-Trust Network** | [Tailscale](https://tailscale.com) | Ephemeral mesh VPN overlay (WireGuard-based) |
| **Access Control** | [OpenSSH (Ed25519)](https://man.openbsd.org/ssh-keygen) | Identity-based, cryptographically hardened authentication |
| **Containerization** | [Docker & Docker Compose](https://www.docker.com) | Multi-container application execution & state management |
| **OS Environment** | [Debian GNU/Linux](https://www.debian.org) | Hardened, self-hosted deployment target |

---

## 🔄 Pipeline Workflow

```mermaid
sequenceDiagram
    Developer->>GitHub: git push origin main
    GitHub->>GitHub Actions: Trigger workflow
    GitHub Actions->>Tailscale: Provision ephemeral node
    GitHub Actions->>Debian Target: SSH authenticate (Ed25519)
    GitHub Actions->>Debian Target: git fetch + git reset --hard
    GitHub Actions->>Docker Compose: Stop running stack
    Docker Compose->>Docker: Build image layers
    Docker Compose->>App Container: Deploy & start services
    App Container-->>Grafana: Stream metrics
```

### Step-by-Step Execution

1. **Code Ingestion** 🚀
   - A push event to the `main` branch triggers the GitHub Actions runner
   - Workflow initializes with runner context and secret injection

2. **Network Tunneling** 🌐
   - Runner provisions an ephemeral Tailscale node
   - Joins the private overlay network (tailnet)
   - Port 22 remains closed on the target; all traffic is encrypted via WireGuard

3. **Identity Handshake** 🔑
   - Runner opens SSH connection to private target host
   - Authentication via Ed25519 key (hardened elliptic-curve cryptography)
   - Repository credentials encrypted and validated

4. **State Synchronization** 📂
   - Automated deployment script performs **atomic sync**
   - `git fetch + git reset --hard origin/main` ensures exact state parity
   - No local drift; no untracked file conflicts

5. **Orchestration Lifecycle** 🐳
   - Docker Compose stops running stack
   - Builds updated image layers (caching optimized)
   - Spins up isolated application networks
   - Prunes dangling images to optimize disk usage

---

## 🚀 Quick Start

### Prerequisites

- [GitHub account](https://github.com/join) with repository access
- [Tailscale account](https://tailscale.com) (free tier supported)
- [Private Debian server](https://www.debian.org/download) with Docker & Docker Compose installed
- Ed25519 SSH key pair (pre-generated on target)

### 1️⃣ Initialize Tailscale on Target

```bash
# SSH into your Debian server
ssh user@debian-server

# Install Tailscale
curl -fsSL https://tailscale.com/install.sh | sh

# Start Tailscale daemon
sudo systemctl start tailscale
sudo systemctl enable tailscale

# Authenticate and obtain MagicDNS IP
sudo tailscale up --accept-routes

# Confirm connection and note your Tailscale IP (100.x.y.z)
tailscale ip -4
```

### 2️⃣ Generate Ed25519 SSH Key

```bash
# On target Debian server, create deployment user
sudo useradd -m -s /bin/bash debian-runner

# Generate Ed25519 key (no passphrase for automation)
sudo -u debian-runner ssh-keygen -t ed25519 -f /home/debian-runner/.ssh/id_ed25519 -N ""

# Display private key (will paste into GitHub Secret)
sudo cat /home/debian-runner/.ssh/id_ed25519

# Authorize public key for SSH access
sudo cp /home/debian-runner/.ssh/id_ed25519.pub /home/debian-runner/.ssh/authorized_keys
sudo chmod 600 /home/debian-runner/.ssh/authorized_keys
```

### 3️⃣ Configure GitHub Secrets

Navigate to your repository:  
**Settings** → **Secrets and variables** → **Actions**

Create the following secrets:

| Secret Name | Value | Example |
|-------------|-------|---------|
| `TS_OAUTH_CLIENT_ID` | Tailscale OAuth Client ID | `k1234567890abc...` |
| `TS_OAUTH_SECRET` | Tailscale OAuth Client Secret | `tskey_abc123xyz...` |
| `DEBIAN_HOST_IP` | Tailscale IP or MagicDNS hostname | `100.123.45.67` or `server.example.ts.net` |
| `DEBIAN_USER` | Target execution username | `debian-runner` |
| `DEBIAN_SSH_KEY` | Raw Ed25519 private key | `-----BEGIN OPENSSH PRIVATE KEY-----\n...` |

⚠️ **Important**: Ensure `DEBIAN_SSH_KEY` is stored as a literal string with proper line endings (use `cat key.pem \| base64` if needed).

### 4️⃣ Create GitHub Actions Workflow

Create `.github/workflows/deploy.yml`:

```yaml
name: Deploy to Production

on:
  push:
    branches:
      - main

jobs:
  deploy:
    runs-on: ubuntu-latest
    
    steps:
      - name: Checkout code
        uses: actions/checkout@v4
      
      - name: Set up Tailscale
        uses: tailscale/github-action@v2
        with:
          oauth-client-id: ${{ secrets.TS_OAUTH_CLIENT_ID }}
          oauth-secret: ${{ secrets.TS_OAUTH_SECRET }}
      
      - name: Configure SSH
        run: |
          mkdir -p ~/.ssh
          echo "${{ secrets.DEBIAN_SSH_KEY }}" > ~/.ssh/id_ed25519
          chmod 600 ~/.ssh/id_ed25519
          ssh-keyscan -H ${{ secrets.DEBIAN_HOST_IP }} >> ~/.ssh/known_hosts 2>/dev/null || true
      
      - name: Deploy via SSH
        run: |
          ssh -i ~/.ssh/id_ed25519 ${{ secrets.DEBIAN_USER }}@${{ secrets.DEBIAN_HOST_IP }} <<'EOF'
            set -e
            cd /opt/app
            git fetch origin
            git reset --hard origin/main
            export COMPOSE_PROJECT_NAME=prod
            docker compose down --remove-orphans
            docker compose up -d --build
            docker image prune -f
          EOF
```

### 5️⃣ Deploy!

Push to `main` and watch the GitHub Actions workflow execute:

```bash
git add .
git commit -m "chore: trigger deployment"
git push origin main
```

---

## ⚙️ Configuration

### Environment Variables

Export these in your deployment script or `docker-compose.yml`:

```bash
# Application Environment
export APP_ENV=production
export APP_PORT=8080
export LOG_LEVEL=info

# Docker Compose
export COMPOSE_PROJECT_NAME=prod
export COMPOSE_FILE=docker-compose.yml

# System Resources
export DOCKER_BUILDKIT=1
export BUILDKIT_PROGRESS=plain
```

### Docker Compose Template

```yaml
version: '3.9'

services:
  app:
    build:
      context: .
      dockerfile: Dockerfile
    container_name: app-prod
    restart: unless-stopped
    ports:
      - "8080:8080"
    environment:
      - APP_ENV=production
      - LOG_LEVEL=info
    volumes:
      - app-data:/app/data
    networks:
      - app-network
    healthcheck:
      test: ["CMD", "curl", "-f", "http://localhost:8080/health"]
      interval: 30s
      timeout: 10s
      retries: 3

  grafana:
    image: grafana/grafana:latest
    container_name: grafana-prod
    restart: unless-stopped
    ports:
      - "3000:3000"
    environment:
      - GF_SECURITY_ADMIN_PASSWORD=secure_password
    volumes:
      - grafana-storage:/var/lib/grafana
    networks:
      - app-network

volumes:
  app-data:
  grafana-storage:

networks:
  app-network:
    driver: bridge
```

---

## 🔐 Secrets Management

### Best Practices

```bash
# ✅ DO: Use GitHub Secrets for sensitive data
${{ secrets.DEBIAN_SSH_KEY }}
${{ secrets.TS_OAUTH_SECRET }}

# ❌ DON'T: Hardcode credentials in workflows
DEBIAN_SSH_KEY="-----BEGIN OPENSSH PRIVATE KEY-----"

# ✅ DO: Rotate SSH keys periodically
ssh-keygen -t ed25519 -f ~/.ssh/id_ed25519_new -N ""

# ✅ DO: Limit secret scope to specific environments
if: github.ref == 'refs/heads/main'
```

### Secret Rotation Checklist

- [ ] Generate new Ed25519 key pair
- [ ] Update target's `authorized_keys`
- [ ] Rotate GitHub Secret `DEBIAN_SSH_KEY`
- [ ] Test deployment with new credentials
- [ ] Archive old key in secure backup
- [ ] Document rotation timestamp

---

## 🛡️ Engineering Lessons & Troubleshooting

### ✅ Cryptographic Key Integrity

**Problem**: Intermittent SSH failures with "invalid format" errors  
**Root Cause**: Multiline secret truncation during CI/CD injection  
**Solution**: Migrated to strict Ed25519 key pairs formatted with exact line-endings

```bash
# Verify key format is valid
ssh-keygen -l -f ~/.ssh/id_ed25519

# Output should show: 256 SHA256:... (ED25519)
```

---

### ✅ Linux ACL & Permission Hardening

**Problem**: Persistent Git file lock blockages ("Status 1 file write failures")  
**Root Cause**: Non-root deployment user lacked write permissions  
**Solution**: Standardized ownership rules and strict permissions

```bash
# Apply ownership to deployment directory
sudo chown -R debian-runner:debian-runner /opt/app

# Set appropriate permissions
sudo chmod 755 /opt/app
sudo chmod 700 /opt/app/.git

# Verify no orphaned locks
find /opt/app -name "*.lock" -delete
```

---

### ✅ Declarative State Parity

**Problem**: `git pull` operations failed due to local state drift and untracked file conflicts  
**Root Cause**: Non-atomic git operations allowed intermediate inconsistent states  
**Solution**: Replaced with atomic `git reset --hard origin/main` workflow

```bash
# Before (❌ unreliable)
git pull origin main

# After (✅ atomic & immutable)
git fetch origin
git reset --hard origin/main
```

---

### ✅ Environment Context Injection

**Problem**: Variable scope loss across isolated SSH subshells  
**Root Cause**: SSH command inheritance didn't preserve export statements  
**Solution**: Explicitly export dynamic runtime variables before Docker invocation

```bash
# ❌ Variables lost in subshell
ssh user@host docker compose up -d --build

# ✅ Variables explicitly exported
ssh user@host <<'EOF'
  export COMPOSE_PROJECT_NAME=prod
  export APP_ENV=production
  docker compose up -d --build
EOF
```

---

## 🔐 Security Considerations

### Network Isolation

- **Tailscale Private Mesh**: All traffic encrypted with WireGuard; no public internet exposure
- **Firewall Rules**: Target Debian server has Port 22 closed to public; only Tailscale overlay accessible
- **Zero Inbound**: No exposed management ports; all connections originate from authenticated CI/CD runner

### Authentication

- **Ed25519 Elliptic Curve**: Modern cryptographic standard; more secure than RSA-2048
- **Ephemeral Tailscale Nodes**: Automatically cleaned up after workflow completion
- **No Password Authentication**: SSH key-based only; no brute-force attack surface

### Secret Storage

- **GitHub Secrets Encryption**: Encrypted at rest; decrypted only at workflow runtime
- **Minimal Exposure**: Secrets never logged or exposed in workflow output
- **Secret Scanning**: GitHub automatically detects and alerts on leaked credentials

### Audit & Monitoring

```bash
# View SSH login history
sudo journalctl -u ssh -n 50

# Monitor Docker activity
docker logs --tail 100 app-prod

# Tailscale network audit
tailscale status

# GitHub Actions audit logs
# Dashboard: Settings → Audit log
```

---

## 🐛 Common Issues & Fixes

### Issue: SSH Connection Timeout

```bash
# Check Tailscale connectivity
tailscale ping 100.x.y.z

# Verify DNS resolution
nslookup server.example.ts.net

# Test SSH connectivity
ssh -vvv debian-runner@100.x.y.z
```

### Issue: Docker Build Fails with Permission Denied

```bash
# Ensure docker socket ownership
sudo chown debian-runner:docker /var/run/docker.sock

# Add user to docker group
sudo usermod -aG docker debian-runner
```

### Issue: Git State Conflicts

```bash
# Hard reset remote state
git fetch origin
git reset --hard origin/main
git clean -fd

# Verify clean state
git status
```

### Issue: Secrets Truncated in Workflow

```bash
# Verify multiline secret format
cat ~/.ssh/id_ed25519 | wc -l

# Re-paste into GitHub with literal newlines
# Not base64 encoded; raw key content only
```

---

## 📊 Monitoring & Observability

### Health Checks

Add to your application container:

```dockerfile
HEALTHCHECK --interval=30s --timeout=10s --retries=3 \
  CMD curl -f http://localhost:8080/health || exit 1
```

### Logging

```bash
# View application logs
docker logs -f app-prod

# Tail GitHub Actions logs
# Dashboard: Actions → Workflow Run → Logs

# Monitor system metrics via Grafana
# http://debian-server:3000
```

---

## 🤝 Contributing

We welcome contributions! Please:

1. **Fork** the repository
2. **Create** a feature branch: `git checkout -b feature/your-feature`
3. **Commit** with descriptive messages: `git commit -m "feat: add feature"`
4. **Push** to your fork: `git push origin feature/your-feature`
5. **Open** a Pull Request with details

---

## 📝 License

This project is licensed under the **MIT License** — see the [LICENSE](LICENSE) file for details.

---

## 📞 Support & Community

- **Issues & Bugs**: [GitHub Issues](../../issues)
- **Discussions**: [GitHub Discussions](../../discussions)
- **Documentation**: [Full Wiki](../../wiki)
- **Security**: [Security Policy](SECURITY.md)

---

## 🙏 Acknowledgments

- [Tailscale](https://tailscale.com) — Zero-trust networking
- [GitHub Actions](https://github.com/features/actions) — CI/CD orchestration
- [Docker](https://www.docker.com) — Container runtime
- [Debian](https://www.debian.org) — Rock-solid OS foundation

---

<div align="center">

**Built with ❤️ for production-grade deployments**

[⬆ back to top](#-secure-automated-gitops-cicd-pipeline)

</div>
