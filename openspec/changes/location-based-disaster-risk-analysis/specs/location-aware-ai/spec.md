## ADDED Requirements

### Requirement: Chat with location context
The system SHALL support AI chat with optional location parameters to provide personalized disaster warnings and recommendations.

#### Scenario: Chat in danger zone
- **GIVEN** user is in danger zone (10+ time-proximate disasters nearby)
- **AND** user sends chat message "Apa yang harus saya lakukan?"
- **WHEN** AI processes the message with location context
- **THEN** AI response SHALL include zone status warning
- **AND** AI response SHALL prioritize evacuation and safety information
- **AND** location context SHALL be persisted in chat metadata

#### Scenario: Chat in warning zone
- **GIVEN** user is in warning zone (5-9 time-proximate disasters nearby)
- **AND** user sends chat message "Apakah aman di sini?"
- **WHEN** AI processes the message with location context
- **THEN** AI response SHALL include warning zone notification
- **AND** AI response SHALL include preparedness recommendations
- **AND** AI response SHALL advise continuous monitoring

#### Scenario: Chat in safe zone
- **GIVEN** user is in safe zone (<5 time-proximate disasters nearby)
- **AND** user sends chat message "Bagaimana cuaca di sini?"
- **WHEN** AI processes the message with location context
- **THEN** AI response SHALL acknowledge safe zone status
- **AND** AI response SHALL provide general preparedness education

#### Scenario: Chat without location
- **GIVEN** user sends chat message without providing location coordinates
- **WHEN** AI processes the message
- **THEN** AI SHALL respond with general disaster information
- **AND** response SHALL not include location-specific warnings

### Requirement: Enhance system prompt with location context
The system SHALL dynamically enhance AI system prompt with current zone status and nearby disaster information.

#### Scenario: Enhanced prompt for danger zone
- **GIVEN** user is in danger zone
- **WHEN** system builds messages for AI API
- **THEN** system prompt SHALL include "USER BERADA DI ZONA BERBAHAYA"
- **AND** system prompt SHALL include total disaster count in area
- **AND** system prompt SHALL instruct AI to prioritize safety and evacuation

#### Scenario: Enhanced prompt includes disaster types
- **GIVEN** disasters in area include earthquakes and floods
- **WHEN** system builds messages for AI API
- **THEN** system prompt SHALL list "earthquake" and "flood" as disaster types in area
- **AND** system prompt SHALL include type-specific recommendations

### Requirement: Prioritize mitigation for prevalent disaster types
The system SHALL instruct AI to prioritize mitigation guidance for disaster types that frequently occur in the user's area.

#### Scenario: AI prioritizes earthquake mitigation
- **GIVEN** user is in danger zone with 8 earthquakes and 2 floods in cluster
- **AND** user asks "Apa yang harus saya siapkan?"
- **WHEN** AI processes with location context
- **THEN** AI response SHALL prioritize earthquake preparedness (struktur bangunan, titik aman)
- **AND** AI response SHALL mention flood preparation as secondary concern

#### Scenario: AI prioritizes flood mitigation
- **GIVEN** user is in danger zone with 6 floods and 4 earthquakes in cluster
- **AND** user asks "Bagaimana cara melindungi rumah saya?"
- **WHEN** AI processes with location context
- **THEN** AI response SHALL prioritize flood protection (dokumen ke tempat tinggi, pelampung)
- **AND** AI response SHALL mention earthquake safety sebagai pelengkap

#### Scenario: AI focuses on top 3 disaster types
- **GIVEN** disasters in area include 5 earthquakes, 4 floods, 3 landslides, and 2 fires
- **WHEN** system builds AI prompt
- **THEN** system prompt SHALL explicitly list top 3 disaster types: earthquake, flood, landslide
- **AND** system prompt SHALL instruct "ANDA WAJIB memprioritaskan mitigasi untuk bencana-bencana tersebut"
- **AND** system prompt SHALL include specific guidance untuk masing-masing tipe bencana

### Requirement: Persist location in chat history
The system SHALL persist user location coordinates and zone status in chat log metadata.

#### Scenario: Save chat with location
- **GIVEN** user sends message with lat=-6.2088, lng=106.8456
- **WHEN** message and response are saved
- **THEN** chatlog metadata SHALL include coordinates
- **AND** chatlog metadata SHALL include zone_status
- **AND** chatlog metadata SHALL include zone analysis metrics

### Requirement: Auto-warning on first danger zone detection
The system SHALL automatically display warning message when chat opens in danger zone.

#### Scenario: First open in danger zone
- **GIVEN** user opens chat for first time
- **AND** user location is in danger zone
- **WHEN** chat component initializes
- **THEN** system SHALL automatically add warning message to chat history
- **AND** warning SHALL include zone label and disaster count
