# AvoidTheObstacle
A web-based obstacle avoidance game controlled by an Arduino Uno R3 with joystick. PHP fetches real-time joystick data via Python, with scores saved in a MySQL database. Features user authentication and a leaderboard. Ideal for those exploring Arduino, PHP, and web integration.


# 🎮 Setup Guide

This guide will help you set up and run the Avoid The Obstacle game using **XAMPP**, **Arduino Uno**, and a **joystick module**.

---

## 📦 1. Extract and Launch the Game in XAMPP

1. Unzip the downloaded project folder.
2. Move the `AvoidTheObstacle` folder into the `htdocs` directory inside your **XAMPP** installation folder.
3. Launch **XAMPP Control Panel** and start the **Apache** module.
4. Open a browser and go to: localhost/AvoidTheObstacle, you should now see the game

---

## 🛠️ 2. Set Up the MySQL Database

1. In the XAMPP Control Panel, start the **MySQL** module.
2. Click the **Admin** button to open **phpMyAdmin** in your browser.
3. In phpMyAdmin, click the **SQL** tab.
4. Open the `additional_info` folder and open `queries.txt`.
5. Copy all the SQL queries and paste them into the SQL editor in phpMyAdmin.
6. Press **Go** to create the database and tables.

---

## 🕹️ 3. Hardware Setup (Arduino + Joystick)

### Joystick to Arduino Wiring

| Joystick Pin | Arduino Pin |
|--------------|-------------|
| 5V           | 5V          |
| GND          | GND         |
| VRx          | A0          |
| VRy          | A1          |
| SW           | D2          |

---

## 🔌 4. Upload Arduino Code

1. Connect the Arduino UNO to your PC via USB.
2. Open the **Arduino IDE**.
3. Open the `.ino` file located in the `additional_info` folder.
4. Select the correct **board** and **COM port** under **Tools**.
5. Upload the code to the Arduino.

---

## 🧪 5. Test Python Serial Communication

1. Make sure you know your Arduino’s COM port (e.g., COM3, COM4, etc.).
2. Open `read_serial.py` and update the `COM` port in the code if needed.
3. Install the required Python module (if not yet installed):
```bash
pip install pyserial
```
4. Run the script:
```bash
python read_serial.py
```
5. You should see joystick coordinates printed in the terminal.

---

## ▶️ 6. Play the Game

1. Go to your browser and visit:
```bash
localhost/AvoidTheObstacle
```

---

## 💡 Troubleshooting

### If `read_serial.py` throws an error, double-check:
- ✅ Python is installed correctly.
- ✅ You’ve installed `pyserial`:
  ```bash
  pip install pyserial
  ```
- ✅ Python is installed correctly.
- ✅ The correct COM port is selected in read_serial.py.

### If you don’t see the game at `http://localhost/AvoidTheObstacle`, ensure:
- ✅ **Apache** is running in XAMPP.
- ✅ The `AvoidTheObstacle` folder is correctly placed inside the `htdocs` directory.


## thanks!


