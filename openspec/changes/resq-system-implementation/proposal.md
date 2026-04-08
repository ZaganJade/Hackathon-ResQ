## Why

Indonesia faces frequent natural disasters (earthquakes, floods, volcanic eruptions) that require rapid response and public awareness. Currently, disaster information is fragmented across multiple sources (BMKG, BNPB, social media), making it difficult for citizens to access real-time alerts, mitigation guidance, and AI-powered assistance during emergencies. ResQ aims to consolidate disaster mitigation tools into a single, accessible web platform with AI assistance for the Indonesian population.

## What Changes

- **New Web Platform**: Build ResQ - a disaster mitigation web application using Laravel 12
- **AI Assist Feature**: Integrate Fireworks AI API (Claude-Kimi router) to provide disaster-related Q&A and guidance
- **Disaster Map**: Implement Google Maps integration with disaster markers and location-based alerts
- **WhatsApp Notifications**: Integrate WhatsApp Web API for emergency alert notifications
- **Educational Content**: Article and guide management system for disaster mitigation education
- **User Management**: User profiles with chat history persistence
- **Dashboard**: Admin dashboard for disaster monitoring and content management

## Capabilities

### New Capabilities

- `ai-assist-engine`: AI-powered chat assistance for disaster-related queries using Fireworks AI
- `disaster-map`: Interactive Google Maps with disaster location markers and geospatial data
- `whatsapp-notifications`: Emergency alert notification system via WhatsApp Web API
- `mitigation-content`: Article and educational guide management for disaster preparedness
- `user-chat-history`: Persistent chat logging and conversation history for users
- `disaster-management`: Backend system for managing disaster data (initially dummy data, extensible for BMKG integration)
- `dashboard-monitoring`: Admin dashboard for system monitoring and disaster overview

### Modified Capabilities

- None (this is a greenfield implementation)

## Impact

**Backend**: New Laravel 12 application with Service Layer pattern (Controller → Service → Model → DB)

**Frontend**: Blade/Livewire templates with Google Maps JavaScript API integration

**External APIs**:
- Fireworks AI API (LLM inference for AI Assist)
- Google Maps JavaScript API (map rendering and geocoding)
- WhatsApp Web API (notification delivery)

**Database**: PostgreSQL with tables: users, chatlogs, disasters, articles, guides, notification_logs

**Deployment**: Kubernetes-based deployment for horizontal scaling during disaster events (scalability requirement)

**Performance**: AI response time < 3 seconds

**Future Integrations**: System architecture prepared for BMKG API integration (official government weather/climate data source)
