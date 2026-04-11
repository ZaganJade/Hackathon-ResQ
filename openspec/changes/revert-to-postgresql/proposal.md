## Why

The platform was temporarily configured to use MySQL for local development, but needs to revert to PostgreSQL for production Kubernetes deployment to align with the existing cloud database infrastructure and maintain data consistency.

## What Changes

- **BREAKING**: Update Laravel database configuration from MySQL to PostgreSQL
- **BREAKING**: Update Python scheduler database configuration from MySQL to PostgreSQL  
- **BREAKING**: Update Dockerfile to install PostgreSQL extensions instead of MySQL
- Update Kubernetes deployment environment variables for PostgreSQL
- Rebuild and redeploy Docker image with PostgreSQL support

## Capabilities

### New Capabilities
- None

### Modified Capabilities
- None

## Impact

- Database driver: `mysql` → `pgsql`
- PHP extensions: `pdo_mysql` → `pdo_pgsql`
- Python scheduler: `psycopg2` driver configuration
- Kubernetes deployment env vars updated
- Requires database migration validation