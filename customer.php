<style>
  .form-group {
    margin-bottom: 15px;
    text-align: center; /* Center-align the content */
  }
  <style>
    body {
      background: linear-gradient(to right, #ffecd2, #fcb69f);
      font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
      padding: 30px;
      display: flex;
      justify-content: center;
      align-items: center;
      min-height: 100vh;
      margin: 0;
    }

    .container {
      background-color: #fff;
      padding: 30px;
      border-radius: 15px;
      max-width: 600px;
      width: 100%;
      box-shadow: 0 10px 25px rgba(0, 0, 0, 0.2);
      text-align: center;
    }

    h1 {
      color: #ff5733;
      margin-bottom: 20px;
    }

    .form-group {
      margin-bottom: 15px;
      text-align: left;
    }

    label {
      display: block;
      margin-bottom: 6px;
      font-weight: bold;
      color: #333;
    }

    input, select {
      width: 100%;
      padding: 10px;
      border: 2px solid #fcb69f;
      border-radius: 8px;
      font-size: 16px;
    }

    button {
      width: 100%;
      padding: 12px;
      font-size: 18px;
      background-color: #ff5733;
      color: white;
      border: none;
      border-radius: 8px;
      cursor: pointer;
      transition: background 0.3s;
    }

    button:hover {
      background-color: #c70039;
    }

    .qr-section {
      margin-top: 20px;
      display: flex;
      flex-direction: column;
      align-items: center;
    }

    #qrScanner {
      border: 2px dashed #ff5733;
      padding: 40px;
      border-radius: 10px;
      background-color: #fff0e1;
      display: flex;
      justify-content: center;
      align-items: center;
      flex-direction: column;
    }

    img {
      max-width: 100px;
      margin-top: 10px;
    }
  </style>
</head>
<body>
  <center>
  <div class="container">
    <h1>🚌 Bus Reservation Portal</h1>
    
    <form id="reservationForm">
      <div class="form-group">
        <label for="name">👤 Customer Name:</label>
        <input type="text" id="name" placeholder="Enter your name" required>
      </div>

      <div class="form-group">
        <label for="address">🏠 Customer Address:</label>
        <input type="text" id="address" placeholder="Enter your address" required>
      </div>
      <div class="form-group">
        
  <label for="from">📍 From:</label>
  <select id="from" required>
    <option value="" disabled selected>Select departure</option>
    <option value="Chennai">Chennai</option>
    <option value="Bangalore">Bangalore</option>
    <option value="Hyderabad">Hyderabad</option>
    <option value="Mumbai">Mumbai</option>
    <option value="Delhi">Delhi</option>
  </select>
</div>

<div class="form-group">
  <label for="to">🎯 To:</label>
  <select id="to" required>
    <option value="" disabled selected>Select destination</option>
    <option value="Chennai">Chennai</option>
    <option value="Bangalore">Bangalore</option>
    <option value="Hyderabad">Hyderabad</option>
    <option value="Mumbai">Mumbai</option>
    <option value="Delhi">Delhi</option>
  </select>
</div>

      <div class="form-group">
        <label for="payment">💳 Payment Method:</label>
        <select id="payment" required>
          <option value="">--Select Payment--</option>
          <option value="upi">UPI</option>
          <option value="card">Credit/Debit Card</option>
        </select>
      </div>

      <div class="form-group qr-section">
        <label>📷 Scan QR to Pay:</label>
        <div id="qrScanner">
          <img src="assets/img/upi.png" alt="Logo" class="nav-item nav-image" style="display: block; margin: 0 auto; max-width: 300px;" />
        </div>
      </div>

      <button type="submit">Reserve Now</button>
    </form>
  </div>
  </center>

  <script>
    document.getElementById('reservationForm').addEventListener('submit', function(e) {
      e.preventDefault();
      alert("Reservation submitted successfully!");
    });

    console.log("QR Scanner initialized.");
  </script>
</body>
</html>
