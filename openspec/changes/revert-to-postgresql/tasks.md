## 1. Laravel Configuration

- [ ] 1.1 Verify Laravel database config uses pgsql driver
- [ ] 1.2 Update .env.example to show pgsql as default

## 2. Dockerfile Update

- [ ] 2.1 Verify Dockerfile installs pdo_pgsql extension
- [ ] 2.2 Remove any MySQL-specific configurations if present

## 3. Python Scheduler Update

- [ ] 3.1 Check scheduler database connection uses PostgreSQL
- [ ] 3.2 Update requirements.txt if needed for psycopg2

## 4. Kubernetes Deployment

- [ ] 4.1 Verify deployment.yaml has DB_CONNECTION=pgsql
- [ ] 4.2 Ensure imagePullSecrets is configured

## 5. Build and Deploy

- [ ] 5.1 Build new Docker image with PostgreSQL extensions
- [ ] 5.2 Push image to Docker Hub
- [ ] 5.3 Deploy to Kubernetes
- [ ] 5.4 Verify pods are running
- [ ] 5.5 Test website HTTPS and database connection