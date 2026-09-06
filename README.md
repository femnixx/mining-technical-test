# MineOps - Mining Fleet Operations Platform

A self-hosted, multi-tenant mining fleet management platform built with Laravel, Inertia.js, and Vue 3. Optimized for self-hosted deployment on a single node with Tailscale for secure remote access and Grafana for observability.

## Architecture

```text
User Browser
    ↓
Tailscale Funnel (HTTPS / mTLS)
    ↓
Apache Web Server (Port 80)
    ↓
Laravel Application (PHP-FPM)
    ↓
SQLite Database
    ↓
Grafana Monitoring (observes metrics)
```

## Tech Stack

| Layer | Technology |
|-------|-----------|
| Reverse Proxy / Remote Access | Tailscale Funnel |
| Web Server | Apache 2.4 |
| Application | Laravel 13, PHP 8.4 |
| Frontend | Inertia.js, Vue 3, Tailwind CSS |
| Database | SQLite 3 |
| Container Runtime | Docker / Docker Compose |
| Monitoring | Grafana |
| CI/CD Ready | GitHub Actions compatible |

## Self-Hosting on a Laptop / Single Node

### Prerequisites

- Docker Engine 24+ and Docker Compose v2+
- Tailscale installed on the host machine
- (Optional) Grafana for metrics dashboards

### Deployment Steps

1. **Clone the repository**

   ```bash
   git clone https://github.com/yourusername/mining-technical-test.git
   cd mining-technical-test
   ```

2. **Configure Tailscale Funnel**

   ```bash
   sudo tailscale up
   sudo tailscale funnel --https=443 80
   ```

   This exposes the Apache web server via Tailscale's HTTPS funnel without opening any inbound ports on your network.

3. **Build and start the application**

   ```bash
   docker compose up -d --build
   ```

4. **Run database migrations**

   ```bash
   docker compose exec app php artisan migrate --force
   ```

5. **Access the application**

   Visit the Tailscale HTTPS URL assigned to your device. The first user to register becomes the admin and goes through the onboarding flow.

### Docker Compose Overview

The stack is designed for a single-node deployment:

- **app** - PHP 8.4 Apache container with Composer and Node.js 20 for asset builds
- **sqlite** - Embedded database file stored in the project directory
- **grafana** (optional) - Metrics dashboard

## Multi-Tenant Architecture

Each signup creates an isolated organization workspace:

```
Registration
    ↓
Onboarding: Organization Details
    ↓
Onboarding: Vehicles & Locations
    ↓
Onboarding: Operators
    ↓
Workspace Ready (Dashboard)
```

- Every user is scoped to an `organization_id`
- All fleet data (vehicles, operators, bookings, telemetry, maintenance) is tenant-isolated
- Lightweight middleware enforces onboarding completion

## Key Features

- **Multi-tenant onboarding** - Organization setup with isolated data per workspace
- **Fleet dispatch & telemetry** - Track heavy vehicles, operators, and shift dispatches
- **Booking approvals** - Multi-level approval workflow for vehicle reservations
- **Maintenance queue** - Issue tracking and service management
- **Analytics** - Fleet utilization and booking trend visualization
- **Theme toggle** - Dark and light mode with localStorage persistence
- **Responsive UI** - Works on mobile, tablet, and desktop

## Observability

- Application logs are captured via Docker container stdout
- SQLite database file can be mounted for external backup
- Health check endpoint available at `/health`
- Grafana included in the stack for dashboarding

### Grafana Setup

1. **Start Grafana with Docker Compose**

   ```bash
   docker compose up -d grafana
   ```

2. **Access Grafana**

   Open `http://localhost:3000` and log in with the default credentials:
   - Username: `admin`
   - Password: `admin`

3. **Add a data source**

   In Grafana, add a new data source pointing to your preferred backend:
   - **Loki** - for log aggregation from Docker containers
   - **Prometheus** - for metrics collection
   - **SQLite** - direct database queries (requires a SQLite plugin)

4. **Create dashboards**

   Import or build dashboards to visualize:
   - Application response times and error rates
   - Fleet booking volumes and trends
   - Database growth and query performance
   - Container resource usage (CPU, memory, network)

## Security

- Access restricted to Tailscale network (MagicDNS + ACLs)
- HTTPS terminated by Tailscale Funnel with automatic certificates
- Session-based authentication with Laravel's built-in security
- Role-based access control (Admin, Approver, Guest, Vendor)

## Troubleshooting

### Tailscale Funnel

If `sudo tailscale funnel https+insecure://localhost:8000` fails with `invalid hostname or IP address`, ensure:

1. Tailscale is running and the node is reachable:
   ```bash
   tailscale status
   ```

2. Use the correct syntax for your Tailscale version:
   ```bash
   # Modern syntax
   sudo tailscale funnel --https=443 80

   # If your app is on port 8000 instead
   sudo tailscale funnel --https=443 8000
   ```

3. Verify the local service is running before exposing it:
   ```bash
   curl -I http://localhost:80
   ```

4. Check firewall rules on the host:
   ```bash
   sudo iptables -L -n
   ```

5. If using a custom hostname, make sure it resolves within the Tailscale network:
   ```bash
   tailscale ping <node-name>
   ```

### Docker Build Failures

If the Docker image fails to build:

1. Clear build cache and rebuild:
   ```bash
   docker compose build --no-cache
   ```

2. Check Node.js and npm availability in the container:
   ```bash
   docker compose run --rm app node --version
   docker compose run --rm app npm --version
   ```

3. If `npm ci` fails due to network errors, the Dockerfile includes retry logic. Rebuild after confirming network connectivity.

## License

MIT
