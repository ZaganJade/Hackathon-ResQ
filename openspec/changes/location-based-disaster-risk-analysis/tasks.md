## 1. Database Setup

- [ ] 1.1 Create migration `create_user_locations_table` with fields: user_id, name, latitude, longitude, address, is_default, notifications_enabled, notification_radius_km, timestamps
- [ ] 1.2 Add indexes for user_id, is_default, and lat/lng columns
- [ ] 1.3 Create `UserLocation` model with casts and relationships
- [ ] 1.4 Add `locations()` and `defaultLocation()` relations to User model
- [ ] 1.5 Implement booted() method untuk auto-set default location
- [ ] 1.6 Run migration and verify table structure

## 2. LocationRiskService Implementation

- [ ] 2.1 Create `app/Services/LocationRiskService.php` skeleton
- [ ] 2.2 Implement `getNearbyActiveDisasters()` dengan Haversine query
- [ ] 2.3 Implement `calculateTimeClusters()` dengan sliding window algorithm
- [ ] 2.4 Implement `determineStatus()` dengan threshold logic (10/5/5)
- [ ] 2.5 Implement `getStatusLabel()` dan `getStatusColor()` helpers
- [ ] 2.6 Implement `analyzeZoneStatus()` full analysis method
- [ ] 2.7 Implement `quickZoneStatus()` lightweight method
- [ ] 2.8 Implement `getRiskTrend()` untuk trend analysis
- [ ] 2.9 Implement `generateWarningMessage()` dengan pesan peringatan sesuai zona
- [ ] 2.10 Implement `getRecommendations()` dengan general recommendations per status
- [ ] 2.11 Perkaya `$typeSpecificRecommendations` dengan detail mitigasi lengkap untuk: earthquake, flood, tsunami, volcanic_eruption, landslide, fire, tornado, drought, epidemic
- [ ] 2.12 Add constants: STATUS_DANGER, STATUS_WARNING, STATUS_SAFE
- [ ] 2.12 Test service dengan dummy data

## 3. AIAssistService Integration

- [ ] 3.1 Inject LocationRiskService ke AIAssistService constructor
- [ ] 3.2 Create `chatWithLocation()` method signature
- [ ] 3.3 Implement `buildLocationAwarePrompt()` dengan konteks zona dan instruksi status-specific
- [ ] 3.4 Tambah "=== PRIORITAS MITIGASI ===" section dengan top 3 disaster types
- [ ] 3.5 Implement specific guidance untuk setiap tipe bencana (earthquake, flood, tsunami, dll)
- [ ] 3.6 Implement `buildLocationAwareMessages()` untuk API payload
- [ ] 3.7 Implement `getLocationPrefix()` untuk user message
- [ ] 3.6 Implement `saveChatWithLocation()` dengan metadata lokasi
- [ ] 3.7 Update existing `chat()` untuk detect lat/lng parameter
- [ ] 3.8 Test backward compatibility chat tanpa lokasi
- [ ] 3.9 Test chatWithLocation dengan berbagai zona status

## 4. API Controllers

- [ ] 4.1 Create `LocationRiskController` skeleton
- [ ] 4.2 Implement `analyze()` endpoint dengan validasi lat/lng
- [ ] 4.3 Implement `quickStatus()` endpoint untuk lightweight check
- [ ] 4.4 Implement `chat()` endpoint untuk location-aware AI chat
- [ ] 4.5 Implement `nearbyDisasters()` endpoint
- [ ] 4.6 Implement `reverseGeocode()` endpoint
- [ ] 4.7 Add `calculateDistance()` helper method
- [ ] 4.8 Test semua endpoints dengan Postman

## 5. API Routes

- [ ] 5.1 Add location routes prefix di `routes/api.php`
- [ ] 5.2 Register GET /api/v1/location/status route
- [ ] 5.3 Register POST /api/v1/location/analyze route
- [ ] 5.4 Register GET /api/v1/location/nearby-disasters route
- [ ] 5.5 Register GET /api/v1/location/reverse-geocode route
- [ ] 5.6 Register POST /api/v1/location/chat route dengan auth middleware
- [ ] 5.7 Update `AIAssistController::chat()` untuk support lat/lng
- [ ] 5.8 Test routes dengan curl/Postman

## 6. Frontend Components - Zone Status Widget

- [ ] 6.1 Create `resources/views/components/zone-status-widget.blade.php`
- [ ] 6.2 Implement Alpine.js data `zoneStatusWidget()`
- [ ] 6.3 Implement `requestLocation()` dengan geolocation API
- [ ] 6.4 Implement `fetchZoneAnalysis()` untuk call API
- [ ] 6.5 Design UI untuk loading/requesting/denied/active states
- [ ] 6.6 Implement status badge dengan color indicator
- [ ] 6.7 Implement warning message display untuk danger/warning
- [ ] 6.8 Implement metrics grid (total disasters, max cluster)
- [ ] 6.9 Implement trend display dengan change percent
- [ ] 6.10 Implement recommendations list
- [ ] 6.11 Implement nearby disasters list
- [ ] 6.12 Add refresh button functionality
- [ ] 6.13 Test widget di dashboard

## 7. Frontend Components - AI Chatbot Geolocation

- [ ] 7.1 Update `ai-chatbot.blade.php` Alpine.js data
- [ ] 7.2 Rename data dari `chatbot` ke `chatbotWithLocation`
- [ ] 7.3 Add location properties: latitude, longitude, locationStatus
- [ ] 7.4 Add zone properties: zoneStatus, zoneLabel, zoneColor, nearbyDisasters
- [ ] 7.5 Implement `requestLocation()` di init lifecycle
- [ ] 7.6 Implement `getCurrentPosition()` dengan Promise wrapper
- [ ] 7.7 Implement `fetchZoneStatus()` untuk check zona
- [ ] 7.8 Implement `showZoneWarning()` untuk auto-warning danger zone
- [ ] 7.9 Update chat header dengan zone status indicator dot
- [ ] 7.10 Add zone status banner untuk danger/warning
- [ ] 7.11 Update `sendMessage()` untuk include lat/lng di request
- [ ] 7.12 Handle location error states (denied, unavailable, timeout)
- [ ] 7.13 Test chat dengan berbagai lokasi

## 8. Dashboard Integration

- [ ] 8.1 Import zone-status-widget component di dashboard.blade.php
- [ ] 8.2 Tambah section widget setelah dynamic alert bar
- [ ] 8.3 Verify widget renders correctly
- [ ] 8.4 Test responsive layout

## 9. Console Command & Scheduling

- [ ] 9.1 Create `CheckLocationRiskCommand` class skeleton
- [ ] 9.2 Implement `handle()` untuk iterasi users dengan locations
- [ ] 9.3 Implement `sendRiskNotification()` untuk WhatsApp
- [ ] 9.4 Add command signature dengan options --user dan --notify
- [ ] 9.5 Register command di `routes/console.php`
- [ ] 9.6 Add scheduled task every 30 minutes
- [ ] 9.7 Add daily scheduled task at 08:00 dengan --notify
- [ ] 9.8 Test command dengan `php artisan resq:check-location-risk`

## 10. Testing & Verification

- [ ] 10.1 Test LocationRiskService dengan various cluster scenarios
- [ ] 10.2 Test API endpoints dengan valid dan invalid coordinates
- [ ] 10.3 Test frontend geolocation permission scenarios
- [ ] 10.4 Test AI chat dengan danger/warning/safe zones
- [ ] 10.5 Test console command dengan --notify flag
- [ ] 10.6 Verify backward compatibility existing chat tanpa lokasi
- [ ] 10.7 Test multiple saved locations per user
- [ ] 10.8 Test notification dengan default radius dan custom radius
- [ ] 10.9 Verify CSP headers untuk geolocation permission policy
- [ ] 10.10 End-to-end test: dashboard → allow location → see zone status

## 11. Documentation

- [ ] 11.1 Update API documentation dengan new endpoints
- [ ] 11.2 Document location-based features di README
- [ ] 11.3 Add inline comments untuk complex clustering algorithm
- [ ] 11.4 Document parameter thresholds (10/5/5, 30 days, 50km)
- [ ] 11.5 Create user guide untuk fitur status zona
