const int trigPin = 9;
const int echoPin = 10;
float duration, distance;
unsigned long lastDetectionTime = 0; // Time of the last detection
const unsigned long detectionTimeout = 1000; // Timeout in milliseconds to end tracking
bool isTracking = false; // Are we currently tracking?
float initialDistance, finalDistance; // To determine direction
unsigned long initialTime, finalTime; // To calculate speed
int peopleCount = 0; // Count of people detected

void setup() {
  pinMode(trigPin, OUTPUT);
  pinMode(echoPin, INPUT);
  Serial.begin(9600);
  Serial.println("Started");
}

void loop() {
  digitalWrite(trigPin, LOW);
  delayMicroseconds(2);
  digitalWrite(trigPin, HIGH);
  delayMicroseconds(10);
  digitalWrite(trigPin, LOW);
  duration = pulseIn(echoPin, HIGH);
  distance = (duration*.0343)/2;

  if(distance <= 250 && distance >= 10){
    //Serial.println(distance);
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
    String direction = distanceDiff < 0 ? "1" : "0";
    peopleCount++; // Increment people count
    String querystring;
    querystring.concat("speed=");
    querystring.concat(speed);
    querystring.concat("&");
    querystring.concat("direction=");
    querystring.concat(direction);
    querystring.concat("&");
    querystring.concat("peopleCount=");
    querystring.concat("1");

    Serial.println(querystring);
    // Reset tracking flags
    isTracking = false;
  }

  delay(50); // Small delay to prevent spamming
  while (Serial.available()){
  Serial.read();
 }

}

void displayPeopleCount() {
  Serial.print("Total people counted: ");
  Serial.println(peopleCount);
}