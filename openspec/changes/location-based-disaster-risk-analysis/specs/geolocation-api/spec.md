## ADDED Requirements

### Requirement: Quick zone status endpoint
The system SHALL provide lightweight API endpoint for quick zone status check.

#### Scenario: Get quick status
- **GIVEN** request to GET /api/v1/location/status?lat=-6.2088&lng=106.8456
- **WHEN** endpoint is called
- **THEN** response SHALL include status, label, color, risk_score, total_disasters
- **AND** response SHALL include trend and trend_change_percent
- **AND** response time SHALL be under 500ms

#### Scenario: Invalid coordinates
- **GIVEN** request with lat=999 (out of range)
- **WHEN** endpoint is called
- **THEN** response SHALL be 422 with error "Koordinat tidak valid"

### Requirement: Full zone analysis endpoint
The system SHALL provide detailed zone analysis via POST endpoint.

#### Scenario: Full analysis with custom radius
- **GIVEN** POST to /api/v1/location/analyze with body {"latitude": -6.2088, "longitude": 106.8456, "radius_km": 75}
- **WHEN** endpoint is called
- **THEN** response SHALL include zone status, location info, metrics
- **AND** response SHALL include disasters_by_type and disasters_by_severity
- **AND** response SHALL include most_recent_disaster with distance_km
- **AND** response SHALL include warning_message and recommendations
- **AND** response SHALL include nearby_disasters list with distances

#### Scenario: Analysis with missing coordinates
- **GIVEN** POST to /api/v1/location/analyze with body missing latitude
- **WHEN** endpoint is called
- **THEN** response SHALL be 422 with validation errors

### Requirement: Nearby disasters endpoint
The system SHALL provide endpoint to list disasters near coordinates.

#### Scenario: Get nearby disasters
- **GIVEN** request to GET /api/v1/location/nearby-disasters?lat=-6.2088&lng=106.8456&radius=50
- **WHEN** endpoint is called
- **THEN** response SHALL include total count
- **AND** disasters list SHALL include type, severity, location, coordinates, distance_km
- **AND** results SHALL be sorted by distance ascending

#### Scenario: Default radius used
- **GIVEN** request without radius parameter
- **WHEN** endpoint is called
- **THEN** default radius of 50km SHALL be used

### Requirement: Reverse geocode endpoint
The system SHALL provide endpoint to convert coordinates to human-readable address.

#### Scenario: Reverse geocode coordinates
- **GIVEN** request to GET /api/v1/location/reverse-geocode?lat=-6.2088&lng=106.8456
- **WHEN** endpoint is called
- **THEN** response SHALL include formatted address
- **AND** response SHALL include original coordinates

#### Scenario: Unknown location
- **GIVEN** request with coordinates in remote ocean area
- **WHEN** endpoint is called
- **THEN** response SHALL be 404 with error "Alamat tidak ditemukan"

### Requirement: Location-aware chat endpoint
The system SHALL provide authenticated endpoint for AI chat with location context.

#### Scenario: Chat with location (authenticated)
- **GIVEN** authenticated user with valid session
- **AND** POST to /api/v1/location/chat with {"message": "Help", "latitude": -6.2, "longitude": 106.8}
- **WHEN** endpoint is called
- **THEN** response SHALL include AI reply
- **AND** response SHALL include location_context with zone_status, zone_label, recommendations

#### Scenario: Chat without authentication
- **GIVEN** request without valid authentication
- **AND** POST to /api/v1/location/chat
- **WHEN** endpoint is called
- **THEN** response SHALL be 401 with error "Autentikasi diperlukan"

#### Scenario: Chat with missing message
- **GIVEN** authenticated request with latitude/longitude but no message
- **WHEN** endpoint is called
- **THEN** response SHALL be 422 with validation error
