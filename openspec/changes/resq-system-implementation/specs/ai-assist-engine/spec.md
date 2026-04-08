## ADDED Requirements

### Requirement: User can send disaster-related questions to AI
The system SHALL provide an AI chat interface where users can ask disaster-related questions and receive contextual responses in Indonesian language.

#### Scenario: User asks about earthquake safety
- **WHEN** user types "What should I do during an earthquake?" in the chat input
- **THEN** the system SHALL send the message to Fireworks AI API with appropriate system prompt
- **AND** the system SHALL display the AI response within 3 seconds
- **AND** the conversation SHALL be saved to chatlogs table

#### Scenario: AI response exceeds timeout
- **WHEN** Fireworks AI API takes longer than 3 seconds to respond
- **THEN** the system SHALL display a loading indicator
- **AND** the system SHALL queue the request for background processing
- **AND** the user SHALL receive a notification when the response is ready

### Requirement: System maintains conversation context
The system SHALL maintain conversation history per user session, allowing contextual follow-up questions.

#### Scenario: Follow-up question with context
- **WHEN** user asks "What about aftershocks?" after asking about earthquakes
- **THEN** the AI SHALL understand the context from previous messages
- **AND** provide relevant guidance about earthquake aftershocks

### Requirement: AI responses are disaster-focused
The system SHALL ensure AI responses are relevant to disaster mitigation, emergency preparedness, and safety guidance.

#### Scenario: Off-topic question handling
- **WHEN** user asks "What's the weather today?"
- **THEN** the AI SHALL politely redirect to disaster-related topics
- **AND** suggest relevant disaster preparedness questions

### Requirement: Chat history persistence
The system SHALL save all user-AI conversations to enable historical review.

#### Scenario: User views chat history
- **WHEN** user navigates to chat history page
- **THEN** the system SHALL display all previous conversations
- **AND** conversations SHALL be sorted by timestamp (newest first)
- **AND** user SHALL be able to click a conversation to view full transcript
