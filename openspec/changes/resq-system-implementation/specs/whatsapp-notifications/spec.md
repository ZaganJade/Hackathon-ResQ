## ADDED Requirements

### Requirement: System sends emergency alerts via WhatsApp
The system SHALL send disaster emergency alerts to subscribed users via WhatsApp Web API when new high-severity disasters are detected.

#### Scenario: New earthquake triggers alert
- **WHEN** a new high-severity earthquake is added to the system
- **THEN** the system SHALL identify users who opted into notifications
- **AND** the system SHALL send WhatsApp message with disaster details
- **AND** the message SHALL include location, magnitude, safety tips, and link to ResQ

#### Scenario: User receives notification
- **WHEN** user has opted in for WhatsApp notifications
- **AND** a relevant disaster occurs in their region
- **THEN** user SHALL receive WhatsApp message within 5 minutes
- **AND** the message SHALL be in Indonesian language

### Requirement: User notification preferences
The system SHALL allow users to opt-in or opt-out of WhatsApp notifications and select disaster types of interest.

#### Scenario: User subscribes to notifications
- **WHEN** user enters their WhatsApp number and clicks "Subscribe"
- **THEN** the system SHALL validate the phone number format
- **AND** the system SHALL save notification preferences
- **AND** the system SHALL send confirmation message via WhatsApp

#### Scenario: User unsubscribes
- **WHEN** user clicks "Unsubscribe" in notification settings
- **THEN** the system SHALL immediately stop sending notifications to that number
- **AND** the system SHALL send one final confirmation message

### Requirement: Notification delivery tracking
The system SHALL log all notification attempts with delivery status.

#### Scenario: Failed notification retry
- **WHEN** WhatsApp API returns error for a notification
- **THEN** the system SHALL log the failure with error code
- **AND** the system SHALL retry up to 3 times with exponential backoff
- **AND** final status SHALL be recorded in notification_logs table

### Requirement: Bulk notification support
The system SHALL support sending notifications to multiple users efficiently during major disasters.

#### Scenario: Mass notification during major event
- **WHEN** a catastrophic disaster occurs (magnitude > 7.0 earthquake)
- **THEN** the system SHALL queue notifications for all subscribed users
- **AND** the system SHALL process queue with rate limiting
- **AND** notifications SHALL be prioritized by user proximity to disaster
