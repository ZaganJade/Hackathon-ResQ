# ResQ Kubernetes Deployment

## Prerequisites

- kubectl configured with kubeconfig.yaml
- Docker installed and logged in to Docker Hub
- Access to kanarazu-katsu namespace

## Deployment Summary

✅ **Laravel Application** - Deployed and running (2 replicas)
✅ **BMKG Scheduler** - Deployed as continuous-running pod (scrapes every 5 minutes)
✅ **Docker Registry Secret** - Created with zaganjade credentials
✅ **Webhook Secret** - Created for WhatsApp notifications

## Deployment Files

| File | Description |
|------|-------------|
| `laravel-deployment.yaml` | Laravel app Deployment, Service, and Ingress |
| `scheduler-deployment.yaml` | BMKG scheduler Deployment (runs continuously) |
| `all-resources.yaml` | Secrets and CronJob template (CronJob limited by RBAC) |

## Quick Commands

### Check Status
```bash
kubectl get deployments -n kanarazu-katsu --kubeconfig=kubeconfig.yaml
kubectl get pods -n kanarazu-katsu --kubeconfig=kubeconfig.yaml
```

### View Logs
```bash
# Laravel logs
kubectl logs -l app=laravel -n kanarazu-katsu --kubeconfig=kubeconfig.yaml

# Scheduler logs
kubectl logs -l app=scheduler -n kanarazu-katsu --kubeconfig=kubeconfig.yaml -f
```

### Restart Deployments
```bash
kubectl rollout restart deployment/laravel-app -n kanarazu-katsu --kubeconfig=kubeconfig.yaml
kubectl rollout restart deployment/bmkg-scheduler -n kanarazu-katsu --kubeconfig=kubeconfig.yaml
```

## Resources Created

1. **Secret `regcred`** - Docker Hub authentication (zaganjade)
2. **Secret `webhook-secret`** - API key for WhatsApp notifications
3. **Deployment `laravel-app`** - Laravel application (2 replicas)
4. **Deployment `bmkg-scheduler`** - BMKG scraper (runs continuously, checks every 5 min)
5. **Service `laravel-service`** - NodePort service on port 30007
6. **Ingress `laravel-ingress`** - Ingress for kanarazu-katsu.hackathon.sev-2.com

## Docker Images

- `zaganjade/laravel-hackathon:latest` - Laravel application
- `zaganjade/bmkg-scheduler:latest` - BMKG earthquake data scraper

## Notes

- The scheduler runs in continuous mode (not CronJob) due to RBAC limitations
- The scheduler checks BMKG for new earthquakes every 5 minutes
- Data is stored in PostgreSQL database at 103.185.52.138:1185
- Webhook notifications are sent for medium+ severity earthquakes
