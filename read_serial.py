import serial
import time

ser = serial.Serial('COM3', 9600)
time.sleep(2)  

# While loop to keep reading data
try:
    while True:
        if ser.in_waiting:
            line = ser.readline().decode('utf-8').strip()
            print("Received:", line)
            with open('joystick_data.txt', 'w') as f:
                f.write(line)

except serial.SerialException as e:
    print(f"Serial error occurred: {e}")
except KeyboardInterrupt:
    print("Program interrupted by user")

finally:
    ser.close()
    print("Serial connection closed")
