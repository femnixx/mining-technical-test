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
- Grafana can be pointed at Loki, Prometheus, or logs exported from the app container
- Health check endpoint available at `/health`

## Security

- Access restricted to Tailscale network (MagicDNS + ACLs)
- HTTPS terminated by Tailscale Funnel with automatic certificates
- Session-based authentication with Laravel's built-in security
- Role-based access control (Admin, Approver, Guest, Vendor)

## License

MIT
