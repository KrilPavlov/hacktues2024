
#include <ESP8266WiFi.h>
#include <ESP8266HTTPClient.h>

const int trigPin = 12;
const int echoPin = 14;

#define SOUND_VELOCITY 0.034
long duration, distance;
int counter = 0;

const char WIFI_SSID[] = "Nokia 6.1";
const char WIFI_PASSWORD[] = "ivo123456"; 

String HOST_NAME   = "http://192.168.43.10:8000"; 
String PATH_NAME   = "/post";
String queryString = "temperature=26&humidity=70";

void setup() {
  Serial.begin(9600);
  pinMode(trigPin, OUTPUT);
  pinMode(echoPin, INPUT);
  WiFi.begin(WIFI_SSID, WIFI_PASSWORD);
  Serial.println("Connecting");
  while (WiFi.status() != WL_CONNECTED) {
    delay(500);
    Serial.print(".");
  }
  Serial.println("");
  Serial.print("Connected to WiFi network with IP Address: ");
  Serial.println(WiFi.localIP());
}

void loop() {

  //trig a measurement
  digitalWrite(trigPin, LOW);
  delayMicroseconds(2);
  digitalWrite(trigPin, HIGH);
  delayMicroseconds(10);
  digitalWrite(trigPin, LOW);
  duration = pulseIn(echoPin, HIGH);

  distance = duration * 0.034 / 2;

  WiFiClient client;
  HTTPClient http;

  http.begin(client, HOST_NAME + PATH_NAME + "?" + queryString);
  http.addHeader("Content-Type", "application/x-www-form-urlencoded");
  
  int httpCode = http.GET();

  if (httpCode > 0) {

    if (httpCode == HTTP_CODE_OK) {
      String payload = http.getString();
      Serial.println(payload);
    } else {
      Serial.printf("[HTTP] POST... code: %d\n", httpCode);
    }
  } else {
    Serial.printf("[HTTP] POST... failed, error: %s\n", http.errorToString(httpCode).c_str());
  }

}
