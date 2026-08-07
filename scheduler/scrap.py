#!/usr/bin/env python3
"""
BMKG Earthquake Data Scraper
Scrapes real-time earthquake data from https://www.bmkg.go.id/gempabumi/gempabumi-realtime
and returns results as JSON with properly parsed data types.
"""

import requests
import json
import re
import os
import pymysql
from bs4 import BeautifulSoup
from datetime import datetime
from typing import Dict, Any, List, Optional
from dotenv import load_dotenv
import asyncio

# Load environment variables
load_dotenv('../resq/.env')

class BMKGEarthquakeScraper:
    """Scraper for BMKG earthquake real-time data"""
    
    BASE_URL = "https://www.bmkg.go.id/gempabumi/gempabumi-realtime"
    
    # Magnitude severity classification thresholds
    MAGNITUDE_SEVERITY_MAP = {
        (0, 2.5): 'low',        # Vicinity - Usually not felt
        (2.5, 5.5): 'low',      # Minor/Light - Often felt, minor damage
        (5.5, 6.1): 'medium',   # Moderate - Slight damage
        (6.1, 7.0): 'high',     # Strong - Lot of damage in populated areas
        (7.0, 8.0): 'high',     # Major - Serious damage
        (8.0, float('inf')): 'critical'  # Great - Destroy communities
    }
    
    def __init__(self, timeout: int = 10):
        """
        Initialize the scraper
        
        Args:
            timeout: Request timeout in seconds
        """
        self.timeout = timeout
        self.session = requests.Session()
        self.session.headers.update({
            'User-Agent': 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36'
        })
        
    def fetch_page(self) -> Optional[str]:
        """
        Fetch the BMKG earthquake page
        
        Returns:
            HTML content of the page or None if failed
        """
        try:
            # print(f"Fetching: {self.BASE_URL}")
            response = self.session.get(self.BASE_URL, timeout=self.timeout)
            response.raise_for_status()
            # print(f"Status code: {response.status_code}")
            with open('debug_bmkg_page.html', 'w', encoding='utf-8') as f:
                f.write(response.text)
            return response.text
        except requests.RequestException as e:
            print(f"Error fetching page: {e}")
            return None
    
    @staticmethod
    def parse_magnitude(value: str) -> Optional[float]:
        """
        Parse magnitude value (e.g., "5.6" to 5.6)
        
        Args:
            value: String value of magnitude
            
        Returns:
            Float value or None
        """
        try:
            match = re.search(r'(\d+\.?\d*)', value.strip())
            return float(match.group(1)) if match else None
        except (ValueError, AttributeError):
            return None
    
    @staticmethod
    def parse_depth(value: str) -> Optional[float]:
        """
        Parse depth value (e.g., "3.4 km" to 3.4)
        
        Args:
            value: String value with unit (e.g., "10 km", "123 Km")
            
        Returns:
            Float value in km or None
        """
        try:
            match = re.search(r'(\d+\.?\d*)\s*(?:km|Km)', value.strip())
            return float(match.group(1)) if match else None
        except (ValueError, AttributeError):
            return None
    
    @staticmethod
    def parse_coordinates(latitude: str, longitude: str) -> Dict[str, Optional[float]]:
        """
        Parse latitude and longitude values
        
        Args:
            latitude: Latitude string (e.g., "1.50 LS")
            longitude: Longitude string (e.g., "101.56 BT")
            
        Returns:
            Dictionary with parsed latitude and longitude
        """
        coords = {'latitude': None, 'longitude': None}
        
        try:
            # Parse latitude
            lat_match = re.search(r'(\d+\.?\d*)', latitude.strip())
            if lat_match:
                lat_value = float(lat_match.group(1))
                # Check for South (LS) or North (LU)
                if 'LS' in latitude or 'ls' in latitude:
                    lat_value = -lat_value
                coords['latitude'] = lat_value
            
            # Parse longitude
            lon_match = re.search(r'(\d+\.?\d*)', longitude.strip())
            if lon_match:
                lon_value = float(lon_match.group(1))
                # Check for West (BB) or East (BT)
                if 'BB' in longitude or 'bb' in longitude:
                    lon_value = -lon_value
                coords['longitude'] = lon_value
        except (ValueError, AttributeError):
            pass
        
        return coords
    
    @staticmethod
    def parse_datetime(date_str: str, time_str: str) -> Optional[str]:
        """
        Parse date and time strings to ISO format datetime
        
        Args:
            date_str: Date string (e.g., "09-04-2026")
            time_str: Time string (e.g., "01:17:34")
            
        Returns:
            ISO format datetime string or None
        """
        try:
            datetime_str = f"{date_str} {time_str}"
            dt = datetime.strptime(datetime_str, "%d-%m-%Y %H:%M:%S")
            return dt.isoformat()
        except ValueError:
            return None
    def parse_table(self, html: str) -> List[Dict[str, Any]]:
      """
      Parse the earthquake table from HTML
      
      Args:
          html: HTML content of the page
          
      Returns:
          List of earthquake data dictionaries
      """
      soup = BeautifulSoup(html, 'html.parser')
      earthquakes = []
      
      # Find the table containing earthquake data
      table = soup.find('table')
      
      if not table:
          print("Warning: Could not find earthquake data table")
          return earthquakes
      
      # Get table rows from tbody
      tbody = table.find('tbody')
      if tbody:
          rows = tbody.find_all('tr')
      else:
          rows = table.find_all('tr')[1:]  # Skip header row if no tbody
      
      for row in rows:
          cols = row.find_all('td')
          if len(cols) < 7:  # Minimum required columns
              continue
          
          try:
              # Extract text from cells
              # Column structure: [0] index, [1] datetime, [2] magnitude, [3] depth, [4] coordinates, [5] location, [6] status, [7] button
              
              # Skip if first column is empty or incomplete rows
              index_text = cols[0].get_text(strip=True)
              if not index_text or not index_text.isdigit():
                  continue
              
              # Parse datetime from column 1 (has <br> separating date and time)
              datetime_cell = cols[1]
              datetime_text = datetime_cell.get_text(strip=True)  # This will join with space after <br>
              # Format: "09 Apr 202612:03:41 WIB" - need to add space between year and time
              datetime_match = re.search(r'(\d{2})\s+(\w+)\s+(\d{4})(\d{2}):(\d{2}):(\d{2})', datetime_text)
              
              if not datetime_match:
                  continue
              
              day, month_str, year, hour, minute, second = datetime_match.groups()
              time_str = f"{hour}:{minute}:{second}"
              
              # Map month names to numbers
              months = {
                  'jan': '01', 'january': '01', 'januari': '01',
                  'feb': '02', 'february': '02', 'februari': '02',
                  'mar': '03', 'march': '03', 'maret': '03',
                  'apr': '04', 'april': '04',
                  'may': '05', 'mei': '05',
                  'jun': '06', 'june': '06', 'juni': '06',
                  'jul': '07', 'july': '07', 'juli': '07',
                  'aug': '08', 'august': '08', 'agustus': '08',
                  'sep': '09', 'september': '09',
                  'oct': '10', 'october': '10', 'oktober': '10',
                  'nov': '11', 'november': '11',
                  'dec': '12', 'december': '12', 'desember': '12'
              }
              month_num = months.get(month_str.lower(), '01')
              date_str = f"{day}-{month_num}-{year}"
              
              # Parse magnitude from column 2 (inside span tag, uses comma for decimal)
              magnitude_cell = cols[2]
              magnitude_text = magnitude_cell.get_text(strip=True)
              magnitude_str = magnitude_text.replace(',', '.')  # Convert comma to dot
              magnitude = self.parse_magnitude(magnitude_str)
              
              # Parse depth from column 3
              depth_str = cols[3].get_text(strip=True)
              depth = self.parse_depth(depth_str)
              
              # Parse coordinates from column 4 (format: "3,55 LS-131,51 BT")
              coordinates_str = cols[4].get_text(strip=True)
              coords = self.parse_coordinates_combined(coordinates_str)
              
              # Get location from column 5
              location_cell = cols[5]
              location_str = location_cell.get_text(strip=True)
              
              # Get status from column 6 (often "–")
              status_str = cols[6].get_text(strip=True) if len(cols) > 6 else "–"
              
              # Parse datetime
              datetime_iso = self.parse_datetime(date_str, time_str) if date_str and time_str else None
              
              earthquake = {
                  'datetime': datetime_iso,
                  'time': time_str,
                  'date': date_str,
                  'latitude': coords['latitude'],
                  'longitude': coords['longitude'],
                  'latitude_raw': coords['latitude_raw'],
                  'longitude_raw': coords['longitude_raw'],
                  'depth_km': depth,
                  # 'depth_raw': depth_str,
                  'magnitude': magnitude,
                  # 'magnitude_raw': magnitude_str,
                  'location': location_str,
                  'status': status_str if status_str != '–' else 'Automatic'
              }
              
              # Only add if we have meaningful data with valid coordinates
              if coords['latitude'] is not None and coords['longitude'] is not None and any([depth, magnitude]):
                  earthquakes.append(earthquake)
          
          except (IndexError, ValueError, AttributeError) as e:
              print(f"Warning: Error parsing row: {e}")
              continue
      
      return earthquakes
    
    @staticmethod
    def parse_coordinates_combined(coord_string: str) -> Dict[str, Any]:
        """
        Parse combined coordinates string (format: "3,55 LS-131,51 BT")
        
        Args:
            coord_string: Combined coordinates string
            
        Returns:
            Dictionary with parsed latitude and longitude
        """
        coords = {
            'latitude': None,
            'longitude': None,
            'latitude_raw': '',
            'longitude_raw': ''
        }
        
        try:
            # Split by dash to separate latitude and longitude
            # Format: "latitude_value DIRECTION-longitude_value DIRECTION"
            parts = coord_string.split('-')
            if len(parts) < 2:
                return coords
            
            lat_part = parts[0].strip()  # e.g., "3,55 LS"
            lon_part = parts[1].strip()  # e.g., "131,51 BT"
            
            coords['latitude_raw'] = lat_part
            coords['longitude_raw'] = lon_part
            
            # Parse latitude
            lat_match = re.search(r'([\d,]+)\s+(LS|LU|LS|LU)', lat_part, re.IGNORECASE)
            if lat_match:
                lat_value = float(lat_match.group(1).replace(',', '.'))
                # LS = South (negative), LU = North (positive)
                if lat_match.group(2).upper() in ['LS']:
                    lat_value = -lat_value
                coords['latitude'] = lat_value
              # Parse longitude
            lon_match = re.search(r'([\d,]+)\s+(BT|BB|BT|BB)', lon_part, re.IGNORECASE)
            if lon_match:
                lon_value = float(lon_match.group(1).replace(',', '.'))
                # BB = West (negative), BT = East (positive)
                if lon_match.group(2).upper() in ['BB']:
                    lon_value = -lon_value
                coords['longitude'] = lon_value
        
        except (ValueError, AttributeError):
            pass
        
        return coords
    
    @staticmethod
    def classify_severity(magnitude: Optional[float]) -> str:
        """
        Classify earthquake severity based on magnitude
        
        Args:
            magnitude: Earthquake magnitude value
            
        Returns:
            Severity level: 'low', 'medium', 'high', or 'critical'
        """
        if magnitude is None:
            return 'low'
        
        for (min_mag, max_mag), severity in BMKGEarthquakeScraper.MAGNITUDE_SEVERITY_MAP.items():
            if min_mag <= magnitude < max_mag:
                return severity
        
        return 'critical'
    
    def send_webhook_notification(self, earthquake: Dict[str, Any], webhook_url: str, api_key: str) -> bool:
        """
        Send earthquake notification to webhook endpoint

        Args:
            earthquake: Earthquake data dictionary
            webhook_url: URL of the webhook endpoint
            api_key: API key for webhook authentication

        Returns:
            True if successful, False otherwise
        """
        try:
            severity = self.classify_severity(earthquake.get('magnitude'))
            location = earthquake.get('location', 'Unknown Location')
            magnitude = earthquake.get('magnitude', 0)

            # Skip notification for low severity (only send for medium, high, critical)
            if severity == 'low':
                print(f"Skipping notification for severity '{severity}' (magnitude {magnitude}) - below threshold")
                return True

            print(f"Sending notification for severity '{severity}' (magnitude {magnitude})")

            # Format lokasi yang readable (bukan koordinat mentah)
            loc_display = location if location and not location.replace('.', '').replace(',', '').replace('-', '').isdigit() else "Lokasi tidak diketahui"

            payload = {
                "message": f"Gempa magnitude {magnitude} terdeteksi di {loc_display}",
                "disaster_type": "earthquake",
                "location": loc_display,
                "severity": severity,
            }

            headers = {
                "X-API-Key": api_key,
                "Content-Type": "application/json"
            }

            response = requests.post(webhook_url, json=payload, headers=headers, timeout=10)

            if response.status_code in [200, 201]:
                print(f"Webhook notification sent successfully for earthquake at {location} (severity: {severity})")
                return True
            else:
                print(f"Webhook notification failed with status {response.status_code}: {response.text}")
                return False

        except Exception as e:
            print(f"Error sending webhook notification: {e}")
            return False
    
    def scrape(self) -> Dict[str, Any]:
        """
        Main scraping method
        
        Returns:
            Dictionary containing scraped earthquake data and metadata
        """
        html = self.fetch_page()
        
        if not html:
            return {
                'success': False,
                'error': 'Failed to fetch page',
                'data': []
            }
        
        earthquakes = self.parse_table(html)
        
        return {
            'success': True,
            'source': self.BASE_URL,
            'timestamp': datetime.now().isoformat(),
            'count': len(earthquakes),
            'data': earthquakes
        }

def store_earthquake_to_database(earthquake: Dict[str, Any]) -> bool:
    """
    Store earthquake data to PostgreSQL database
    
    Args:
        earthquake: Earthquake data dictionary
        
    Returns:
        True if successful, False otherwise
    """
    try:
        # Get database connection details from environment
        db_host = os.getenv('DB_HOST')
        db_port = int(os.getenv('DB_PORT', 5432))
        db_name = os.getenv('DB_DATABASE')
        db_user = os.getenv('DB_USERNAME')
        db_password = os.getenv('DB_PASSWORD')
        
        # Connect to database
        conn = pymysql.connect(
            host=db_host,
            port=db_port,
            database=db_name,
            user=db_user,
            password=db_password
        )
        
        cursor = conn.cursor()
        
        # Prepare data for insertion
        severity = BMKGEarthquakeScraper.classify_severity(earthquake.get('magnitude'))
        
        # Build the raw_data JSON
        raw_data = {
            'magnitude': earthquake.get('magnitude'),
            'magnitude_raw': earthquake.get('magnitude_raw'),
            'depth_km': earthquake.get('depth_km'),
            'depth_raw': earthquake.get('depth_raw'),
            'latitude_raw': earthquake.get('latitude_raw'),
            'longitude_raw': earthquake.get('longitude_raw'),
            'datetime': earthquake.get('datetime'),
            'date': earthquake.get('date'),
            'time': earthquake.get('time'),
            'status': earthquake.get('status'),
        }
        
        # Insert into disasters table
        insert_query = """
        INSERT INTO disasters (
            type, 
            location, 
            latitude, 
            longitude, 
            severity, 
            status, 
            source, 
            raw_data,
            created_at,
            updated_at
        ) VALUES (%s, %s, %s, %s, %s, %s, %s, %s, %s, %s)
        """
        
        cursor.execute(insert_query, (
            'earthquake',  # type
            earthquake.get('location', 'Unknown'),  # location
            earthquake.get('latitude'),  # latitude
            earthquake.get('longitude'),  # longitude
            severity,  # severity
            'active',  # status
            'bmkg_api',  # source
            json.dumps(raw_data),  # raw_data as JSON
            datetime.now().isoformat(),  # created_at
            datetime.now().isoformat(),  # updated_at
        ))
        
        conn.commit()

        disaster_id = cursor.lastrowid
        
        print(f"Earthquake data stored in database with ID: {disaster_id}")
        cursor.close()
        conn.close()
        
        return True
        
    except pymysql.Error as e:
        print(f"Database error: {e}")
        return False
    except Exception as e:
        print(f"Error storing earthquake data: {e}")
        return False


scraper = BMKGEarthquakeScraper()

def schedule():
    """Main entry point"""
    # print("Starting BMKG Earthquake Scraper...")
    
    result = scraper.scrape()
    
    # Check if scraping was successful
    if not result.get('success') or not result.get('data'):
        print("Failed to fetch earthquake data")
        return result
    
    try:
        with open('prev_data.json', 'r', encoding='utf-8') as f:
            prev_data = json.load(f)
    except FileNotFoundError:
        prev_data = None
    
    current_earthquake = result['data'][0]
    
    if prev_data is None:
        print("No previous data found, initializing data...")
        with open('prev_data.json', 'w', encoding='utf-8') as f:
            json.dump(current_earthquake, f, indent=2, ensure_ascii=False)
    else:
        print("Checking...")
        # Check if there's new earthquake data
        is_new_data = (
            prev_data.get("datetime") != current_earthquake.get("datetime") or
            prev_data.get("time") != current_earthquake.get("time") or
            prev_data.get("date") != current_earthquake.get("date") or
            prev_data.get("latitude") != current_earthquake.get("latitude") or
            prev_data.get("longitude") != current_earthquake.get("longitude") or
            prev_data.get("latitude_raw") != current_earthquake.get("latitude_raw") or
            prev_data.get("longitude_raw") != current_earthquake.get("longitude_raw") or
            prev_data.get("depth_km") != current_earthquake.get("depth_km") or
            # prev_data.get("depth_raw") != current_earthquake.get("depth_raw") or
            prev_data.get("magnitude") != current_earthquake.get("magnitude") or
            # prev_data.get("magnitude_raw") != current_earthquake.get("magnitude_raw") or
            prev_data.get("location") != current_earthquake.get("location") or
            prev_data.get("status") != current_earthquake.get("status")
        )
        
        if is_new_data:
            print("New earthquake data detected!")
            
            # Store to database
            db_stored = store_earthquake_to_database(current_earthquake)
            
            if db_stored:
                # Send webhook notification
                webhook_url = os.getenv('WEBHOOK_URL', '/api/v1/webhook/whatsapp/broadcast')
                webhook_key = os.getenv('WEBHOOK_API_KEY', 'your_webhook_key')
                
                scraper.send_webhook_notification(
                    current_earthquake,
                    webhook_url,
                    webhook_key
                )
            
            # Update previous data cache
            with open('prev_data.json', 'w', encoding='utf-8') as f:
                json.dump(current_earthquake, f, indent=2, ensure_ascii=False)
            
            print("Data cache updated.")
        else:
            print("No new earthquake data detected.")

async def main():
    schedule()
    while True:
        await asyncio.sleep(60) # 1 min interval
        schedule()

if __name__ == '__main__':
    asyncio.run(main())

