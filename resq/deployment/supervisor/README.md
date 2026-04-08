# ResQ Queue Worker Supervisor Setup

## Installation

1. Install Supervisor:
```bash
# Ubuntu/Debian
sudo apt-get install supervisor

# CentOS/RHEL
sudo yum install supervisor
```

2. Copy the configuration file:
```bash
sudo cp deployment/supervisor/resq-worker.conf /etc/supervisor/conf.d/
```

3. Update the configuration paths to match your installation directory.

4. Reload and start:
```bash
sudo supervisorctl reread
sudo supervisorctl update
sudo supervisorctl start resq-worker:*
```

## Commands

```bash
# Check status
sudo supervisorctl status

# Restart workers
sudo supervisorctl restart resq-worker:*

# Stop workers
sudo supervisorctl stop resq-worker:*

# View logs
sudo tail -f /var/www/resq/storage/logs/worker.log
```

## Configuration

- **numprocs=2**: Runs 2 worker processes (adjust based on server capacity)
- **queue=default,notifications,ai-chat**: Processes jobs in priority order
- **tries=3**: Failed jobs retry 3 times before going to failed_jobs table
- **max-time=3600**: Workers restart after 1 hour to prevent memory leaks
