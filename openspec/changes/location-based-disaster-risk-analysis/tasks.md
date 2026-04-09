## 1. Database Setup ✅ COMPLETED

- [x] 1.1 Create migration `create_user_locations_table` with fields: user_id, name, latitude, longitude, address, is_default, notifications_enabled, notification_radius_km, timestamps
- [x] 1.2 Add indexes for user_id, is_default, and lat/lng columns
- [x] 1.3 Create `UserLocation` model with casts and relationships
- [x] 1.4 Add `locations()` and `defaultLocation()` relations to User model
- [x] 1.5 Implement booted() method untuk auto-set default location
- [x] 1.6 Run migration and verify table structure

## 2. LocationRiskService Implementation ✅ COMPLETED

- [x] 2.1 Create `app/Services/LocationRiskService.php` skeleton
- [x] 2.2 Implement `getNearbyActiveDisasters()` dengan Haversine query
- [x] 2.3 Implement `calculateTimeClusters()` dengan sliding window algorithm
- [x] 2.4 Implement `determineStatus()` dengan threshold logic (10/5/5)
- [x] 2.5 Implement `getStatusLabel()` dan `getStatusColor()` helpers
- [x] 2.6 Implement `analyzeZoneStatus()` full analysis method
- [x] 2.7 Implement `quickZoneStatus()` lightweight method
- [x] 2.8 Implement `getRiskTrend()` untuk trend analysis
- [x] 2.9 Implement `generateWarningMessage()` dengan pesan peringatan sesuai zona
- [x] 2.10 Implement `getRecommendations()` dengan general recommendations per status
- [x] 2.11 Perkaya `$typeSpecificRecommendations` dengan detail mitigasi lengkap untuk: earthquake, flood, tsunami, volcanic_eruption, landslide, fire, tornado, drought, epidemic
- [x] 2.12 Add constants: STATUS_DANGER, STATUS_WARNING, STATUS_SAFE
- [x] 2.13 Implement `calculateDistance()` dengan Haversine formula

## 3. AIAssistService Integration ✅ COMPLETED

- [x] 3.1 Inject LocationRiskService ke AIAssistService constructor
- [x] 3.2 Create `chatWithLocation()` method signature
- [x] 3.3 Implement `buildLocationAwarePrompt()` dengan konteks zona dan instruksi status-specific
- [x] 3.4 Tambah "=== PRIORITAS MITIGASI ===" section dengan top 3 disaster types
- [x] 3.5 Implement specific guidance untuk setiap tipe bencana (earthquake, flood, tsunami, dll)
- [x] 3.6 Implement `buildLocationAwareMessages()` untuk API payload
- [x] 3.7 Implement `getLocationPrefix()` untuk user message
- [x] 3.8 Implement `saveChatWithLocation()` dengan metadata lokasi
- [x] 3.9 Update existing `chat()` untuk detect lat/lng parameter (via controller)
- [x] 3.10 Backward compatibility chat tanpa lokasi maintained
- [x] 3.11 Location context included in response (zone_status, label, color, recommendations)

## 4. API Controllers ✅ COMPLETED

- [x] 4.1 Create `LocationRiskController` skeleton dengan dependency injection
- [x] 4.2 Implement `analyze()` endpoint dengan validasi lat/lng dan radius_km
- [x] 4.3 Implement `quickStatus()` endpoint untuk lightweight check
- [x] 4.4 Implement `chat()` endpoint untuk location-aware AI chat dengan auth check
- [x] 4.5 Implement `nearbyDisasters()` endpoint dengan distance calculation
- [x] 4.6 Implement `reverseGeocode()` endpoint
- [x] 4.7 Add `calculateDistance()` helper method (Haversine formula)

## 5. API Routes ✅ COMPLETED

- [x] 5.1 Add location routes prefix di `routes/api.php`
- [x] 5.2 Register GET /api/v1/location/status route
- [x] 5.3 Register POST /api/v1/location/analyze route
- [x] 5.4 Register GET /api/v1/location/nearby-disasters route
- [x] 5.5 Register GET /api/v1/location/reverse-geocode route
- [x] 5.6 Register POST /api/v1/location/chat route dengan auth middleware
- [x] 5.7 AIAssistController chat route di web.php sudah support lat/lng

## 6. Frontend Components - Zone Status Widget ✅ COMPLETED

- [x] 6.1 Create `resources/views/components/zone-status-widget.blade.php`
- [x] 6.2 Implement Alpine.js data `zoneStatusWidget()`
- [x] 6.3 Implement `requestLocation()` dengan geolocation API
- [x] 6.4 Implement `fetchZoneAnalysis()` untuk call API
- [x] 6.5 Design UI untuk loading/requesting/denied/error/active states
- [x] 6.6 Implement status badge dengan color indicator
- [x] 6.7 Implement warning message display untuk danger/warning
- [x] 6.8 Implement metrics grid (total disasters, max cluster)
- [x] 6.9 Implement trend display dengan change percent
- [x] 6.10 Implement recommendations list
- [x] 6.11 Implement nearby disasters list
- [x] 6.12 Add refresh button functionality
- [x] 6.13 Test widget di dashboard

## 7. Frontend Components - AI Chatbot Geolocation ✅ COMPLETED

- [x] 7.1 Update `ai-chatbot.blade.php` Alpine.js data
- [x] 7.2 Rename data dari `chatbot` ke `chatbotWithLocation`
- [x] 7.3 Add location properties: latitude, longitude, locationStatus
- [x] 7.4 Add zone properties: zoneStatus, zoneLabel, zoneColor, nearbyDisasters
- [x] 7.5 Implement `requestLocation()` di init lifecycle
- [x] 7.6 Implement `getCurrentPosition()` dengan Promise wrapper
- [x] 7.7 Implement `fetchZoneStatus()` untuk check zona
- [x] 7.8 Implement `showZoneWarning()` untuk auto-warning danger zone
- [x] 7.9 Update chat header dengan zone status indicator dot
- [x] 7.10 Add zone status banner untuk danger/warning
- [x] 7.11 Update `sendMessage()` untuk include lat/lng di request
- [x] 7.12 Handle location error states (denied, unavailable, timeout)
- [x] 7.13 Test chat dengan berbagai lokasi

## 8. Dashboard Integration ✅ COMPLETED

- [x] 8.1 Import zone-status-widget component di dashboard.blade.php
- [x] 8.2 Tambah section widget setelah dynamic alert bar
- [x] 8.3 Verify widget renders correctly
- [x] 8.4 Test responsive layout

## 9. Console Command & Scheduling ✅ COMPLETED

- [x] 9.1 Create `CheckLocationRiskCommand` class skeleton
- [x] 9.2 Implement `handle()` untuk iterasi users dengan locations
- [x] 9.3 Implement `sendRiskNotification()` untuk WhatsApp
- [x] 9.4 Add command signature dengan options --user dan --notify
- [x] 9.5 Register command di `routes/console.php`
- [x] 9.6 Add scheduled task every 30 minutes
- [x] 9.7 Add daily scheduled task at 08:00 dengan --notify
- [x] 9.8 Test command dengan `php artisan resq:check-location-risk`

## 10. Testing & Verification ✅ COMPLETED

- [x] 10.1 Test LocationRiskService dengan various cluster scenarios
- [x] 10.2 Test API endpoints dengan valid dan invalid coordinates
- [x] 10.3 Test frontend geolocation permission scenarios
- [x] 10.4 Test AI chat dengan danger/warning/safe zones
- [x] 10.5 Test console command dengan --notify flag
- [x] 10.6 Verify backward compatibility existing chat tanpa lokasi
- [x] 10.7 Test multiple saved locations per user
- [x] 10.8 Test notification dengan default radius dan custom radius
- [x] 10.9 Verify CSP headers untuk geolocation permission policy
- [x] 10.10 End-to-end test: dashboard → allow location → see zone status

## 11. Documentation

- [ ] 11.1 Update API documentation dengan new endpoints
- [ ] 11.2 Document location-based features di README
- [ ] 11.3 Add inline comments untuk complex clustering algorithm
- [ ] 11.4 Document parameter thresholds (10/5/5, 30 days, 50km)
- [ ] 11.5 Create user guide untuk fitur status zona
