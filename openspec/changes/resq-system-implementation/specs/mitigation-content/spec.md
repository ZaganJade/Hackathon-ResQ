## ADDED Requirements

### Requirement: System manages educational articles
The system SHALL provide article management for disaster-related news and educational content.

#### Scenario: Admin creates article
- **WHEN** admin fills article form with title, content, category, and image
- **THEN** the system SHALL validate all required fields
- **AND** the system SHALL save the article with published status
- **AND** the article SHALL be immediately visible to users

#### Scenario: User views article list
- **WHEN** user navigates to Articles section
- **THEN** the system SHALL display paginated list of published articles
- **AND** articles SHALL be sorted by publish date (newest first)
- **AND** user SHALL see article title, excerpt, thumbnail, and date

#### Scenario: User reads full article
- **WHEN** user clicks on an article from the list
- **THEN** the system SHALL display full article content
- **AND** the system SHALL track article view count
- **AND** related articles SHALL be displayed at bottom

### Requirement: System organizes mitigation guides
The system SHALL organize educational guides by disaster type for easy access to mitigation procedures.

#### Scenario: User browses guides by category
- **WHEN** user navigates to Mitigation Guides section
- **THEN** the system SHALL display disaster categories (Earthquake, Flood, Volcano, etc.)
- **AND** clicking a category SHALL show relevant guides

#### Scenario: User views guide details
- **WHEN** user selects a specific guide (e.g., "Earthquake Safety at Home")
- **THEN** the system SHALL display step-by-step instructions
- **AND** the guide SHALL include preparation, during-event, and post-event sections
- **AND** the guide SHALL support rich content (images, videos)

### Requirement: Content search functionality
The system SHALL provide search across articles and guides.

#### Scenario: User searches for flood information
- **WHEN** user types "flood preparation" in search box
- **THEN** the system SHALL return matching articles and guides
- **AND** results SHALL be ranked by relevance
- **AND** user SHALL see content type indicator (Article vs Guide)

### Requirement: Content management permissions
The system SHALL restrict content creation and editing to authorized administrators.

#### Scenario: Non-admin attempts to create article
- **WHEN** non-admin user attempts to access article creation page
- **THEN** the system SHALL redirect to login or show 403 error
- **AND** no article SHALL be created
