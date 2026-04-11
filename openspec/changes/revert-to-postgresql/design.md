## Context

The ResQ platform currently has PostgreSQL database infrastructure running at 103.185.52.138:1185. Previous local development temporarily switched to MySQL for testing purposes. Now the platform needs to revert to PostgreSQL for the production Kubernetes deployment to ensure compatibility with the existing cloud database.

Current state:
- Dockerfile has MySQL extensions (`pdo_mysql`)
- Laravel config may have MySQL as default
- Python scheduler needs PostgreSQL adapter
- Kubernetes deployment already has PostgreSQL env vars configured

## Goals / Non-Goals

**Goals:**
- Update Laravel to use PostgreSQL (`DB_CONNECTION=pgsql`)
- Update Dockerfile to install PostgreSQL PHP extensions
- Ensure Python scheduler works with PostgreSQL
- Successfully deploy to Kubernetes

**Non-Goals:**
- Database schema migration (assume schema is compatible)
- Data migration (production data already in PostgreSQL)
- Add new features

## Decisions

**Decision 1**: Keep existing PostgreSQL credentials
- Rationale: Production database already configured with these credentials
- No alternatives needed

**Decision 2**: Use PDO PostgreSQL extension
- Rationale: Standard Laravel PostgreSQL driver
- Alternative: PostgreSQL native (rejected - PDO is Laravel standard)

## Risks / Trade-offs

[Risk] Connection string changes might break local development → Mitigation: Local .env can override with sqlite or MySQL

[Risk] Python scheduler compatibility with PostgreSQL → Mitigation: Verify psycopg2 or asyncpg is used