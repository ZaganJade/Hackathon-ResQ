## ADDED Requirements

### Requirement: Support optional location parameters in chat
The AI Assist service SHALL support optional latitude and longitude parameters to provide location-aware responses.

#### Scenario: Chat with location parameters
- **GIVEN** valid message with latitude=-6.2088 and longitude=106.8456
- **WHEN** chat endpoint receives request
- **THEN** system SHALL detect zone status at coordinates
- **AND** AI SHALL be called with location-enhanced system prompt
- **AND** response SHALL include location_context with zone details

#### Scenario: Chat without location (backward compatibility)
- **GIVEN** message without latitude/longitude parameters
- **WHEN** chat endpoint receives request
- **THEN** system SHALL process chat normally without location context
- **AND** response SHALL NOT include location_context
- **AND** existing chat functionality SHALL remain unchanged

### Requirement: Extend AIAssistService with chatWithLocation method
The AIAssistService SHALL have a dedicated method for location-aware chat.

#### Scenario: Call chatWithLocation
- **GIVEN** message "Apa yang harus saya lakukan?", user_id=123, lat=-6.2, lng=106.8
- **WHEN** chatWithLocation is called
- **THEN** location risk analysis SHALL be performed
- **AND** AI SHALL receive enhanced prompt with zone context
- **AND** chat logs SHALL be saved with location metadata

### Requirement: Backward compatible existing chat method
The existing chat method SHALL continue to work without location parameters.

#### Scenario: Existing chat without location
- **GIVEN** existing code calling chat(message, userId, conversationId)
- **WHEN** chat is called
- **THEN** no error SHALL occur
- **AND** functionality SHALL be identical to before change
