#!/bin/bash

# ResQ Queue Worker Script
# Usage: ./scripts/queue-worker.sh [start|stop|restart|status]

APP_DIR="$(cd "$(dirname "${BASH_SOURCE[0]}")/.." && pwd)"
LOG_FILE="$APP_DIR/storage/logs/queue-worker.log"
PID_FILE="$APP_DIR/storage/queue-worker.pid"

case "${1:-start}" in
    start)
        echo "Starting ResQ queue worker..."
        cd "$APP_DIR"
        nohup php artisan queue:work redis --sleep=3 --tries=3 --queue=default,notifications,ai-chat > "$LOG_FILE" 2>&1 &
        echo $! > "$PID_FILE"
        echo "Queue worker started with PID: $(cat "$PID_FILE")"
        echo "Logs: $LOG_FILE"
        ;;
    stop)
        if [ -f "$PID_FILE" ]; then
            PID=$(cat "$PID_FILE")
            echo "Stopping queue worker (PID: $PID)..."
            kill "$PID" 2>/dev/null || true
            rm "$PID_FILE"
            echo "Queue worker stopped."
        else
            echo "No queue worker PID file found."
        fi
        ;;
    restart)
        $0 stop
        sleep 2
        $0 start
        ;;
    status)
        if [ -f "$PID_FILE" ] && kill -0 "$(cat "$PID_FILE")" 2>/dev/null; then
            echo "Queue worker is running (PID: $(cat "$PID_FILE"))"
            echo "Recent logs:"
            tail -n 20 "$LOG_FILE"
        else
            echo "Queue worker is not running."
        fi
        ;;
    *)
        echo "Usage: $0 {start|stop|restart|status}"
        exit 1
        ;;
esac
