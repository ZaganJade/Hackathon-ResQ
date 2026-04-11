# ResQ - AI-Powered Disaster Management Platform

## Project Overview

ResQ adalah platform manajemen bencana berbasis AI yang menyediakan:
- Peta interaktif bencana real-time
- Notifikasi darurat via WhatsApp
- AI Assistant untuk informasi bencana
- Panduan mitigasi bencana
- Analisis risiko lokasi

## Superpowers Workflow

This project uses Superpowers - a complete software development workflow with composable skills.

### Available Skills

- **brainstorming** - Generate and refine ideas
- **dispatching-parallel-agents** - Run multiple agents simultaneously
- **executing-plans** - Execute implementation plans
- **finishing-a-development-branch** - Complete development branches
- **receiving-code-review** - Process code review feedback
- **requesting-code-review** - Request and manage code reviews
- **subagent-driven-development** - Subagent-driven development process
- **systematic-debugging** - Debug systematically
- **test-driven-development** - TDD workflow
- **using-git-worktrees** - Use git worktrees
- **using-superpowers** - Core superpowers usage
- **verification-before-completion** - Verify before completing
- **writing-plans** - Write implementation plans
- **writing-skills** - Create and refine skills

### Commands

Available commands in `/commands` directory:
- Use `/help` to see available commands

### Hooks

Hooks in `/hooks` directory handle:
- Pre/post command execution
- Error handling
- Session management

## Development Guidelines

### Before Writing Code

1. Understand the problem fully
2. Ask clarifying questions if needed
3. Consider user impact
4. Check existing solutions

### While Writing Code

1. Follow Laravel conventions
2. Use type hints and proper PHPDoc
3. Write tests for new features
4. Handle errors gracefully
5. Keep code DRY

### Before Completing

1. Verify the solution works
2. Check for edge cases
3. Ensure proper error handling
4. Confirm code follows project style
5. Get user confirmation

### Prohibited Actions

- Don't submit PRs without user review
- Don't add unnecessary dependencies
- Don't modify core skills without evaluation
- Don't make speculative changes

## Project Structure

```
resq/
├── app/
│   ├── Console/          # Artisan commands
│   ├── Http/
│   │   ├── Controllers/  # Web controllers
│   │   └── Middleware/   # HTTP middleware
│   ├── Models/           # Eloquent models
│   ├── Services/         # Business logic
│   └── Jobs/             # Queue jobs
├── config/               # Configuration files
├── database/
│   ├── migrations/       # DB migrations
│   └── seeders/          # Data seeders
├── resources/
│   └── views/            # Blade templates
├── routes/               # Route definitions
├── scheduler/            # Python scheduler
├── docs/                 # Documentation
└── tests/                # Test suites
```

## Key Technologies

- Laravel 12 (PHP 8.4)
- MySQL 8.0
- Redis (caching & queues)
- Python (scheduler & scrapers)
- Leaflet.js (maps)
- Alpine.js (frontend)

## API Endpoints

### Disasters
- `GET /api/disasters` - List disasters
- `GET /api/disasters/stats` - Disaster statistics
- `GET /api/disasters/{id}` - Disaster detail

### Location Analysis
- `POST /api/v1/location/analyze` - Analyze location risk

### AI Assist
- `POST /api/v1/ai/chat` - AI chat
- `GET /api/v1/ai/history` - Chat history

## Important Notes

- Always test in development before production
- Use transactions for database operations
- Cache expensive queries
- Handle external API failures gracefully
- Log errors for debugging
