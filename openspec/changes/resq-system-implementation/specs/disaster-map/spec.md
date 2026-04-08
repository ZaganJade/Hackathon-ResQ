## ADDED Requirements

### Requirement: System displays interactive disaster map
The system SHALL display an interactive Google Map showing disaster locations across Indonesia with filterable markers.

#### Scenario: User opens disaster map
- **WHEN** user navigates to the disaster map page
- **THEN** the system SHALL load Google Maps JavaScript API
- **AND** the map SHALL be centered on Indonesia
- **AND** the system SHALL fetch and display disaster markers from /api/disasters endpoint

#### Scenario: User filters disasters by type
- **WHEN** user selects "Earthquake" from the disaster type filter
- **THEN** the system SHALL only display markers for earthquake events
- **AND** other disaster type markers SHALL be hidden

#### Scenario: User clicks on disaster marker
- **WHEN** user clicks on a disaster marker
- **THEN** the system SHALL display an info window with disaster details
- **AND** details SHALL include: type, location, severity, timestamp, and status

### Requirement: Map supports location-based search
The system SHALL allow users to search for disasters near a specific location.

#### Scenario: User searches by city name
- **WHEN** user types "Jakarta" in the location search box
- **THEN** the system SHALL geocode the location using Google Maps Geocoding API
- **AND** the map SHALL pan to Jakarta
- **AND** the system SHALL display disasters within 50km radius

### Requirement: Real-time disaster updates
The system SHALL support periodic refresh of disaster data without page reload.

#### Scenario: Auto-refresh disaster data
- **WHEN** 5 minutes have passed since last data fetch
- **THEN** the system SHALL automatically fetch new disaster data
- **AND** new markers SHALL appear on the map without user interaction
- **AND** existing markers SHALL be updated if status changed

### Requirement: Map displays disaster severity levels
The system SHALL visually differentiate disaster severity using marker colors or icons.

#### Scenario: Different severity markers
- **WHEN** disasters are displayed on the map
- **THEN** high severity disasters SHALL use red markers
- **AND** medium severity SHALL use yellow markers
- **AND** low severity SHALL use green markers
