## ADDED Requirements

### Requirement: System stores disaster event data
The system SHALL maintain a database of disaster events with location, type, severity, and status information.

#### Scenario: Admin adds new disaster event
- **WHEN** admin fills disaster creation form with type, location, coordinates, severity, description
- **THEN** the system SHALL validate all required fields
- **AND** the system SHALL geocode location if coordinates not provided
- **AND** the disaster SHALL be saved to disasters table with status "active"

#### Scenario: Disaster data includes source tracking
- **WHEN** disaster is created (manual or future API)
- **THEN** the system SHALL record data source (e.g., "manual", "bmkg_api")
- **AND** the system SHALL store external source ID if applicable
- **AND** the system SHALL store raw data in JSON field for future reference

### Requirement: Disaster status lifecycle
The system SHALL support disaster status workflow: active → monitoring → resolved.

#### Scenario: Disaster status updated
- **WHEN** admin changes disaster status from "active" to "resolved"
- **THEN** the system SHALL update status with timestamp
- **AND** the system SHALL optionally require resolution notes
- **AND** resolved disasters SHALL remain visible on map with different indicator

#### Scenario: Automatic status transitions
- **WHEN** a disaster has been "active" for more than 7 days
- **THEN** the system SHALL notify admin to review status
- **AND** suggest status change to "monitoring" if appropriate

### Requirement: Disaster data extensibility
The system SHALL design disaster schema to accommodate future data sources.

#### Scenario: BMKG integration preparation
- **WHEN** BMKG API becomes available
- **THEN** the disaster schema SHALL accommodate BMKG-specific fields
- **AND** existing disasters SHALL not require migration
- **AND** new adapter service SHALL be implementable without schema changes

### Requirement: Disaster severity classification
The system SHALL classify disasters by severity levels affecting display and notification priority.

#### Scenario: High severity earthquake added
- **WHEN** earthquake with magnitude > 6.0 is added
- **THEN** the system SHALL automatically classify as "high" severity
- **AND** the system SHALL trigger high-priority notifications
- **AND** the map SHALL display with red marker

#### Scenario: Manual severity override
- **WHEN** admin manually sets severity level
- **THEN** the system SHALL override automatic classification
- **AND** admin SHALL provide reason for override
- **AND** override reason SHALL be logged
