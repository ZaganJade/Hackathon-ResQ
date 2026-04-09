## ADDED Requirements

### Requirement: Store multiple user locations
The system SHALL allow users to save multiple named locations (e.g., home, office, dorm).

#### Scenario: Create new location
- **GIVEN** authenticated user with name "John", coordinates (-6.2, 106.8), address "Jakarta"
- **WHEN** user saves location with name "Rumah"
- **THEN** location SHALL be persisted to user_locations table
- **AND** location SHALL be associated with user
- **AND** success response SHALL be returned

#### Scenario: Set default location
- **GIVEN** user has multiple saved locations
- **WHEN** user sets location "Rumah" as default
- **THEN** location SHALL have is_default=true
- **AND** all other user locations SHALL have is_default=false

#### Scenario: Prevent multiple defaults
- **GIVEN** user has location A with is_default=true
- **WHEN** user sets location B as default
- **THEN** location A SHALL automatically have is_default=false
- **AND** location B SHALL have is_default=true

### Requirement: Configure notification preferences per location
The system SHALL allow enabling/disabling notifications and setting radius per location.

#### Scenario: Enable notifications for location
- **GIVEN** saved location with notifications_enabled=false
- **WHEN** user enables notifications
- **THEN** notifications_enabled SHALL be true
- **AND** system SHALL include this location in risk monitoring

#### Scenario: Set custom notification radius
- **GIVEN** saved location with default radius 50km
- **WHEN** user sets notification_radius_km to 100
- **THEN** monitoring for this location SHALL use 100km radius
- **AND** zone analysis SHALL use 100km radius

### Requirement: Retrieve user locations
The system SHALL support querying saved locations for a user.

#### Scenario: Get all user locations
- **GIVEN** user has 3 saved locations
- **WHEN** system queries user locations
- **THEN** all 3 locations SHALL be returned
- **AND** locations SHALL include coordinates, name, address, and preferences

#### Scenario: Get default location
- **GIVEN** user has default location set
- **WHEN** system queries default location
- **THEN** only location with is_default=true SHALL be returned

### Requirement: Update and delete locations
The system SHALL support updating and deleting saved locations.

#### Scenario: Update location coordinates
- **GIVEN** saved location with coordinates (-6.2, 106.8)
- **WHEN** user updates coordinates to (-6.3, 106.9)
- **THEN** coordinates SHALL be updated in database
- **AND** updated_at timestamp SHALL be refreshed

#### Scenario: Delete location
- **GIVEN** user has saved location
- **WHEN** user deletes the location
- **THEN** location SHALL be removed from database
- **AND** associated notification logs SHALL remain (orphaned allowed)
