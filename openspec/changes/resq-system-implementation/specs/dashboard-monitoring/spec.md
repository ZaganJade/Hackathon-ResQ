## ADDED Requirements

### Requirement: System provides admin dashboard
The system SHALL provide a centralized dashboard for administrators to monitor system status and manage content.

#### Scenario: Admin views dashboard
- **WHEN** admin logs in and navigates to Dashboard
- **THEN** the system SHALL display summary statistics:
  - Total active disasters
  - Total users registered
  - Total chat conversations today
  - Total articles and guides
- **AND** the system SHALL display recent activity feed

### Requirement: Dashboard shows disaster overview
The system SHALL provide a visual summary of current disaster situations.

#### Scenario: Disaster statistics widget
- **WHEN** admin views dashboard
- **THEN** the system SHALL display disasters grouped by type (pie/bar chart)
- **AND** the system SHALL show severity distribution
- **AND** clicking a statistic SHALL navigate to relevant detail page

#### Scenario: Real-time disaster map preview
- **WHEN** admin views dashboard
- **THEN** the system SHALL display compact map with recent disasters
- **AND** the map SHALL auto-refresh every 5 minutes
- **AND** admin SHALL be able to click through to full map

### Requirement: Dashboard shows system health
The system SHALL display key system health metrics on the dashboard.

#### Scenario: System health monitoring
- **WHEN** admin views dashboard
- **THEN** the system SHALL show:
  - API response times (AI, WhatsApp, Maps)
  - Queue status (pending notifications)
  - Database connection status
  - Recent error log entries (last 5)

### Requirement: Dashboard provides quick actions
The system SHALL provide quick action buttons for common administrative tasks.

#### Scenario: Quick action buttons
- **WHEN** admin views dashboard
- **THEN** the system SHALL provide buttons for:
  - Add new disaster
  - Create new article
  - Send test notification
  - View system logs
- **AND** clicking buttons SHALL navigate to respective pages

### Requirement: Dashboard shows notification statistics
The system SHALL display WhatsApp notification delivery statistics.

#### Scenario: Notification analytics
- **WHEN** admin views dashboard
- **THEN** the system SHALL show:
  - Notifications sent today
  - Delivery success rate
  - Failed deliveries count
  - Average delivery time
- **AND** admin SHALL be able to view detailed logs
