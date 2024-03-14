#include <ESP8266WiFi.h>
#include <ESP8266HTTPClient.h>

// const int trigPin = 12;
// const int echoPin = 14;

// #define SOUND_VELOCITY 0.034
// long duration, distance, distance_avg = 0;
// int counter = 0;

// const char WIFI_SSID[] = "Nokia 6.1";
// const char WIFI_PASSWORD[] = "ivo123456"; 

// String HOST_NAME   = "http://192.168.43.10:8000"; 
// String PATH_NAME   = "/post";
// String queryString = "temperature=26&humidity=70";

// void setup() {
//   Serial.begin(115200);
//   pinMode(trigPin, OUTPUT);
//   pinMode(echoPin, INPUT);
//   // WiFi.begin(WIFI_SSID, WIFI_PASSWORD);
//   // Serial.println("Connecting");
//   // while (WiFi.status() != WL_CONNECTED) {
//   //   delay(500);
//   //   Serial.print(".");
//   // }
//   // Serial.println("");
//   // Serial.print("Connected to WiFi network with IP Address: ");
//   // Serial.println(WiFi.localIP());
// }

// void loop() {

//   //trig a measurement
//   digitalWrite(trigPin, LOW);
//   delayMicroseconds(2);
//   digitalWrite(trigPin, HIGH);
//   delayMicroseconds(10);
//   digitalWrite(trigPin, LOW);
  
//   duration = pulseIn(echoPin, HIGH);

//   distance = duration * 0.034 / 2;
//   // if(distance_avg == 0){
//   //   distance_avg = distance;

//   // }else{
//   //   distance_avg = 0.9 * distance_avg + 0.1 * distance;
//   // }
//   // WiFiClient client;
//   // HTTPClient http;

//   // http.begin(client, HOST_NAME + PATH_NAME + "?" + queryString);
//   // http.addHeader("Content-Type", "application/x-www-form-urlencoded");

  
//   //int httpCode = http.GET();
//   // if (httpCode > 0) {

//   //   if (httpCode == HTTP_CODE_OK) {
//   //     String payload = http.getString();
//   //     //Serial.println(payload);
//   //   } else {
//   //     Serial.printf("[HTTP] POST... code: %d\n", httpCode);
//   //   }
//   // } else {
//   //   Serial.printf("[HTTP] POST... failed, error: %s\n", http.errorToString(httpCode).c_str());
//   // }


// // if (distance <= 5  && distance >= 200) {
// //     counter++;
// //     Serial.println("Person detected!");
// //     Serial.print("Current count: ");
// //     Serial.println(counter);
// //   }
//   if(distance <= 500 && distance >= 7){
//     Serial.print("Distance: ");
//     Serial.println(distance);
//   }
//  delay(50);
// }

const int trigPin = 12;
const int echoPin = 14;
float duration, distance;
unsigned long lastDetectionTime = 0; // Time of the last detection
const unsigned long detectionTimeout = 1000; // Timeout in milliseconds to end tracking
bool isTracking = false; // Are we currently tracking?
float initialDistance, finalDistance; // To determine direction
unsigned long initialTime, finalTime; // To calculate speed
int peopleCount = 0; // Count of people detected

const char WIFI_SSID[] = "Nokia 6.1";
const char WIFI_PASSWORD[] = "ivo123456"; 

String HOST_NAME   = "http://192.168.43.10:8000"; 
String PATH_NAME   = "/post";
String queryString = "temperature=26&humidity=70";


void displayPeopleCount();

void setup() {
  pinMode(trigPin, OUTPUT);
  pinMode(echoPin, INPUT);
  Serial.begin(115200);
  // WiFi.begin(WIFI_SSID, WIFI_PASSWORD);
  // Serial.println("Connecting");
  // while (WiFi.status() != WL_CONNECTED) {
  //   delay(500);
  //   Serial.print(".");
  // }
  // Serial.println("");
  // Serial.print("Connected to WiFi network with IP Address: ");
  // Serial.println(WiFi.localIP());


}

void loop() {
  digitalWrite(trigPin, LOW);
  delayMicroseconds(2);
  digitalWrite(trigPin, HIGH);
  delayMicroseconds(10);
  digitalWrite(trigPin, LOW);
  duration = pulseIn(echoPin, HIGH);
  distance = (duration*.0343)/2;




  if(distance <= 500 && distance >= 7){
    if (!isTracking) {
      // Start tracking a new event
      isTracking = true;
      initialDistance = distance;
      initialTime = millis();
    } else {
      // Update the final distance and time as we're still within an event
      finalDistance = distance;
      finalTime = millis();
    }
    lastDetectionTime = millis(); // Update the last detection time
  }

  // Check if the tracking should be concluded
  if (isTracking && (millis() - lastDetectionTime > detectionTimeout)) {
    // Calculate speed and direction based on initial and final values
    float timeDiff = (finalTime - initialTime) / 1000.0; // Time difference in seconds
    float distanceDiff = finalDistance - initialDistance; // Distance difference in cm
    float speed = abs(distanceDiff) / timeDiff; // Speed in cm/s
    String direction = distanceDiff < 0 ? "Approaching" : "Leaving";

    // Output the findings
    Serial.print("Person Counted. Speed: ");
    Serial.print(speed);
    Serial.print(" cm/s, Direction: ");
    Serial.println(direction);
    
    peopleCount++; // Increment people count

    // Reset tracking flags
    isTracking = false;
    // WiFiClient client;
    // HTTPClient http;
    
    // http.begin(client, HOST_NAME + PATH_NAME + "?" + queryString);
    // http.addHeader("Content-Type", "application/x-www-form-urlencoded");

    
    // int httpCode = http.GET();
    // if (httpCode > 0) {

    //   if (httpCode == HTTP_CODE_OK) {
    //     String payload = http.getString();
    //     //Serial.println(payload);
    //   } else {
    //     Serial.printf("[HTTP] POST... code: %d\n", httpCode);
    //   }
    // } else {
    //   Serial.printf("[HTTP] POST... failed, error: %s\n", http.errorToString(httpCode).c_str());
    // }

  }
  

  delay(50); // Small delay to prevent spamming



}

void displayPeopleCount() {
  Serial.print("Total people counted: ");
  Serial.println(peopleCount);

}
