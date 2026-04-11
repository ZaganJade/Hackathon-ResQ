FROM python:3.11-slim

WORKDIR /app

# Install system dependencies
RUN apt-get update && apt-get install -y \
    gcc \
    libpq-dev \
    && rm -rf /var/lib/apt/lists/*

# Copy requirements and install Python dependencies
COPY requirements.txt .
RUN pip install --no-cache-dir -r requirements.txt

# Copy application code
COPY scrap.py .

# Create directory for prev_data.json
RUN mkdir -p /data && chmod 777 /data

# Set environment variables (will be overridden by CronJob)
ENV DB_HOST=localhost \
    DB_PORT=5432 \
    DB_DATABASE=resq \
    DB_USERNAME=postgres \
    DB_PASSWORD=secret \
    WEBHOOK_URL=http://localhost/api/v1/webhook/whatsapp/broadcast \
    WEBHOOK_API_KEY=your_webhook_key \
    PREV_DATA_PATH=/data/prev_data.json \
    PYTHONUNBUFFERED=1

# Run the scraper once (CronJob will handle scheduling)
CMD ["python", "scrap.py"]
