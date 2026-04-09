## ADDED Requirements

### Requirement: Analyze zone status based on disaster clustering
The system SHALL analyze zone status (danger/warning/safe) by calculating clusters of disasters within time proximity windows.

#### Scenario: Danger zone detected
- **GIVEN** 10 or more disasters occurred within 30 days of each other within 50km radius
- **WHEN** system analyzes zone at the center coordinates
- **THEN** status SHALL be "danger" with red color indicator
- **AND** warning message SHALL include total disaster count and recommendation to evacuate

#### Scenario: Warning zone detected
- **GIVEN** 5-9 disasters occurred within 30 days of each other within 50km radius
- **WHEN** system analyzes zone at the center coordinates
- **THEN** status SHALL be "warning" with amber color indicator
- **AND** warning message SHALL advise vigilance and preparedness

#### Scenario: Safe zone detected
- **GIVEN** fewer than 5 disasters within time proximity within 50km radius
- **WHEN** system analyzes zone at the center coordinates
- **THEN** status SHALL be "safe" with green color indicator
- **AND** message SHALL confirm minimal disaster activity

### Requirement: Calculate time-based disaster clusters
The system SHALL calculate clusters using sliding window approach where disasters within 30 days of each other are considered time-proximate.

#### Scenario: Cluster calculation with sliding window
- **GIVEN** disasters at dates: Jan 1, Jan 15, Feb 1, Feb 20, Mar 15 (30-day window)
- **WHEN** system calculates clusters
- **THEN** cluster sizes SHALL be [2, 2, 1] (Jan cluster, Feb cluster, Mar single)
- **AND** max_cluster_size SHALL be 2

#### Scenario: Single large cluster
- **GIVEN** 12 disasters all within 30 days of each other
- **WHEN** system calculates clusters
- **THEN** single cluster with size 12 SHALL be returned
- **AND** max_cluster_size SHALL be 12

### Requirement: Calculate risk trend over time
The system SHALL calculate trend by comparing disaster counts in current vs previous time periods.

#### Scenario: Increasing trend detected
- **GIVEN** current period has 150% or more disasters than previous period
- **WHEN** system calculates trend
- **THEN** trend SHALL be "increasing"
- **AND** change_percent SHALL reflect the increase

#### Scenario: Decreasing trend detected
- **GIVEN** current period has 50% or fewer disasters than previous period
- **WHEN** system calculates trend
- **THEN** trend SHALL be "decreasing"
- **AND** change_percent SHALL reflect the decrease

#### Scenario: Stable trend detected
- **GIVEN** disaster count change between periods is within 50-150%
- **WHEN** system calculates trend
- **THEN** trend SHALL be "stable"

### Requirement: Generate zone-specific recommendations
The system SHALL provide recommendations based on zone status and disaster types in the area.

#### Scenario: Danger zone recommendations
- **GIVEN** zone status is "danger"
- **WHEN** system generates recommendations
- **THEN** recommendations SHALL include evacuation procedures and emergency kit preparation
- **AND** type-specific advice for detected disaster types SHALL be included

#### Scenario: Warning zone recommendations
- **GIVEN** zone status is "warning"
- **WHEN** system generates recommendations
- **THEN** recommendations SHALL include monitoring advice and family preparedness planning
- **AND** type-specific advice for detected disaster types SHALL be included

### Requirement: Query nearby disasters with distance calculation
The system SHALL retrieve active disasters within specified radius and calculate distance from center point.

#### Scenario: Query disasters within radius
- **GIVEN** user coordinates at (-6.2088, 106.8456) with 50km radius
- **WHEN** system queries nearby disasters
- **THEN** disasters within 50km SHALL be returned
- **AND** each disaster SHALL include distance_km from user location
- **AND** results SHALL be sorted by distance ascending

### Requirement: Support configurable analysis parameters
The system SHALL support configurable radius and time proximity parameters.

#### Scenario: Custom radius analysis
- **GIVEN** user requests analysis with radius_km of 100
- **WHEN** system performs zone analysis
- **THEN** disasters within 100km SHALL be included in calculation
- **AND** default radius of 50km SHALL be overridden

#### Scenario: Custom time proximity
- **GIVEN** system configured with time_proximity_days of 14
- **WHEN** system calculates clusters
- **THEN** disasters within 14 days SHALL be considered time-proximate
