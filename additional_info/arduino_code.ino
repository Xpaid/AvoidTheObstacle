const int xPin = A0;
const int yPin = A1;
const int buttonPin = 2;

void setup() {
  pinMode(buttonPin, INPUT_PULLUP); // internal pull-up resistor
  Serial.begin(9600);
}

void loop() {
  int xVal = analogRead(xPin);
  int yVal = analogRead(yPin);
  int buttonState = digitalRead(buttonPin); // LOW = pressed

  // Print as a string like "X:512,Y:490,B:1"
  Serial.print("X:");
  Serial.print(xVal);
  Serial.print(",Y:");
  Serial.print(yVal);
  Serial.print(",B:");
  Serial.println(buttonState == LOW ? "Pressed" : "Released");

  delay(10); // Reduce delay for faster updates
}
