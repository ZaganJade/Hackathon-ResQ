## Context

ResQ is a disaster mitigation platform targeting Indonesian citizens. The system consolidates disaster information, AI assistance, and educational content into a single web application. This is a greenfield implementation using Laravel 12.

**Current State**: No existing system - building from scratch.

**Constraints**:
- Tech stack is FIXED: Laravel 12, PostgreSQL, Blade/Livewire
- AI response must complete in < 3 seconds
- Disaster data starts as dummy/mock data (real BMKG integration planned for future)
- Must handle traffic spikes during major disaster events
- Indonesian language support required for content and AI responses

**Stakeholders**:
- Indonesian citizens (end users)
- Content administrators (article/guide management)
- System operators (disaster monitoring)

## Goals / Non-Goals

**Goals:**
- Build scalable disaster mitigation platform with 7 core capabilities
- Implement AI Assist with Fireworks AI (Claude-Kimi router) for disaster Q&A
- Create interactive disaster map using Google Maps API
- Enable WhatsApp emergency notifications
- Provide educational content management (articles/guides)
- Support user profiles with persistent chat history
- Deliver admin dashboard for monitoring
- Ensure AI response time < 3 seconds
- Design for future BMKG API integration

**Non-Goals:**
- Real-time BMKG data integration (future phase)
- Mobile native apps (web-only for MVP)
- Multi-language support beyond Indonesian
- SMS/email notifications (WhatsApp only)
- Offline functionality
- User-to-user communication features

## Decisions

### 1. Service Layer Pattern
**Decision**: Use Controller → Service → Model → Repository pattern

**Rationale**:
- Separation of concerns: Controllers handle HTTP, Services handle business logic
- Testability: Services can be unit tested independently
- Reusability: Service methods can be called from controllers, commands, or jobs
- Consistency with Laravel best practices

**Implementation**:
- `app/Http/Controllers/` - Handle HTTP requests/responses
- `app/Services/` - Business logic orchestration
- `app/Models/` - Eloquent models with relationships
- `app/Repositories/` - Data access abstraction (optional, can use Eloquent directly for simplicity)

### 2. AI Assist Architecture
**Decision**: Implement async queue for AI requests with synchronous fallback

**Rationale**:
- Fireworks AI API latency can vary; queuing prevents timeout issues
- Fallback to synchronous for simple queries (< 3s requirement)
- Chat history stored in PostgreSQL for persistence

**Implementation**:
- `AIAssistService` handles Fireworks API communication
- `ChatlogModel` stores conversation history
- Queue worker for background processing when needed

### 3. Disaster Data Strategy
**Decision**: Start with seeded dummy data, design for future API integration

**Rationale**:
- No real BMKG API available yet
- Need to demonstrate functionality during development
- Schema must accommodate future real data sources

**Implementation**:
- `DisasterModel` with flexible schema (source, source_id, raw_data JSON field)
- Seeder creates realistic Indonesian disaster scenarios
- Adapter pattern for BMKG API integration

### 4. Map Rendering Approach
**Decision**: Server-side disaster data API + Client-side Google Maps rendering

**Rationale**:
- Reduces server load (client handles map rendering)
- Enables dynamic filtering without page reload
- Better user experience with interactive markers

**Implementation**:
- API endpoint: `GET /api/disasters` returns GeoJSON
- Frontend: Vanilla JS + Google Maps JS API
- Clustering for performance with many markers

### 5. WhatsApp Notification Design
**Decision**: HTTP-based WhatsApp Web API with queue-based sending

**Rationale**:
- Avoids WhatsApp Business API complexity and cost
- Queue ensures reliable delivery during high-volume periods
- Retry mechanism for failed sends

**Implementation**:
- `NotificationService` handles API calls
- `notification_logs` table tracks delivery status
- Laravel queue with Redis/Database driver

### 6. Database Schema Design
**Decision**: Normalize core entities, use JSON for flexible attributes

**Rationale**:
- PostgreSQL supports JSON well for extensibility
- Core relationships (users-chatlogs, disasters-guides) use foreign keys
- BMKG API data schema with flexibility for different disaster types

**Tables**:
- `users` - Core user accounts
- `chatlogs` - AI conversation history (user_id FK)
- `disasters` - Disaster events with location data
- `articles` - Educational news/articles
- `guides` - Mitigation guides (category-based)
- `notification_logs` - WhatsApp delivery tracking

### 7. Caching Strategy
**Decision**: Redis for session, cache, and queue; PostgreSQL for persistence

**Rationale**:
- Reduces database load during traffic spikes
- Session caching enables horizontal scaling
- Queue backend for reliable async processing

## Risks / Trade-offs

| Risk | Mitigation |
|------|------------|
| Fireworks AI latency exceeds 3s | Implement response streaming; show loading states; queue complex queries |
| WhatsApp API rate limits | Implement exponential backoff; queue messages; priority queuing for emergencies |
| Traffic spike during major disaster | Horizontal scaling with load balancer; CDN for static assets; database read replicas |
| Dummy data becomes stale | Build admin interface for manual updates; schedule regular data refresh job |
| Google Maps API costs | Implement caching for geocoding; limit map interactions; monitor usage |
| Security: WhatsApp number exposure | Store hashed numbers; implement opt-in consent; audit logs |

## Migration Plan

**Phase 1 - Foundation**:
1. Database migrations and seeders
2. Core Laravel structure (Controllers, Services, Models)
3. Authentication system

**Phase 2 - Core Features**:
4. AI Assist integration
5. Disaster map implementation
6. Content management (articles/guides)

**Phase 3 - Notifications**:
7. WhatsApp notification service
8. Alert triggers and scheduling

**Phase 4 - Polish**:
9. Dashboard and monitoring
10. Performance optimization
11. Security hardening

**Rollback Strategy**:
- Database migrations are reversible
- Feature flags for gradual rollout
- Blue-green deployment capability

## Open Questions

1. **WhatsApp API Provider**: Which specific WhatsApp Web API provider will be used? (Baileys, whatsapp-web.js, or commercial service?)
2. **Disaster Data Refresh Frequency**: How often should dummy data be refreshed to simulate realistic scenarios?
3. **AI System Prompt**: What specific system prompt should guide the AI for disaster-related responses in Indonesian?
4. **User Notification Consent**: Opt-in flow for WhatsApp notifications - required before implementation
